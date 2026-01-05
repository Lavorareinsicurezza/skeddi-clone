<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\ExpiryReminderMail;
use App\Models\Company;
use App\Models\TrainingPlanRecord;
use App\Models\CompanyCourseType;
use App\Models\Document;
use App\Models\CompanyVisitType;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;


class CheckExpiryAndSendEmails extends Command
{
    protected $signature = 'expiry:check-and-mail';
    protected $description = 'Check expiries and send reminder emails';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Expiry check started...');

        $this->checkTrainingPlans();
        // $this->checkCourses();
        // $this->checkDocuments();
        // $this->checkVisits();

        $this->info('Expiry check completed.');
    }

    private function shouldSendMail($module, $recordId, $type)
    {
        $shouldSend = !DB::table('expiry_mail_logs')
            ->where('module', $module)
            ->where('record_id', $recordId)
            ->where('mail_type', $type)
            ->exists();

        Log::info('Checking if mail should be sent for module: ' . $module . ', record_id: ' . $recordId . ', type: ' . $type . '. Result: ' . $shouldSend);

        return $shouldSend;
    }

    private function getMailType($expiryDate)
    {
        $days = Carbon::today()->diffInDays(Carbon::parse($expiryDate), false);

        Log::info('Days until expiry: ' . $days);

        // 3 months = approx 90 days
        if ($days == 90) return 'three_months';
        if ($days == 30) return 'one_month';

        return null;
    }


    private function sendMailAndLog($module, $record, $expiryDate)
    {
        Log::info('Attempting to send mail for module: ' . $module . ', record_id: ' . json_encode($record) . ', expiryDate: ' . $expiryDate);

        $mailType = $this->getMailType($expiryDate);
        if (!$mailType) return;

        if (!$this->shouldSendMail($module, $record->id, $mailType)) {
            Log::warning('Skipping mail for module: ' . $module . ', record_id: ' . $record->id . ', type: ' . $mailType . '. Already sent.');
            return;
        }

        $company = Company::find($record->company->company_id);
        if (!$company) {
            Log::warning('Company not found for record: ' . $record->id);
            return;
        }

        // Decode contacts JSON
        Log::info('company Contacts ------> ' . $record->company);
        $recieverCompany = Company::find($record->company_id);

        $contacts = $recieverCompany->contacts ?? '[]';
        Log::info('reciever company emails ' . json_encode($contacts) . ' and type ' . gettype($contacts));
        if (empty($contacts)) {
            Log::warning('No contacts found for company: ' . $company->id);
            return;
        }

        // Fetch SMTP settings
        $setting = Setting::where('company_id', $company->id)->first();
        if (!$setting || !$setting->smtp_host || !$setting->smtp_username || !$setting->smtp_password) {
            Log::warning('SMTP settings missing for company: ' . $company->id);
            return;
        }

        $scheme = $setting->smtp_port == 465 ? 'smtps' : 'smtp';
        $dsn = sprintf(
            '%s://%s:%s@%s:%s',
            $scheme,
            urlencode($setting->smtp_username),
            urlencode($setting->smtp_password),
            $setting->smtp_host,
            $setting->smtp_port
        );

        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer(transport: $transport);

        // Render Blade directly
        $html = View::make('emails.expiry-reminder', [
            'record'   => $record,
            'module'   => ucfirst(str_replace('_', ' ', $module)),
            'mailType' => $mailType
        ])->render();

        $subjectMap = [
            'three_months' => ucfirst(str_replace('_', ' ', $module)) . " Expiry Reminder – 3 Months Remaining",
            'one_month'    => ucfirst(str_replace('_', ' ', $module)) . " Expiry Reminder – 1 Month Remaining",
        ];

        // for ($i = 0; $i < count($contacts); $i++) {

            $email = (new Email())
                ->from($setting->smtp_address)
                ->to($recieverCompany->main_email) // <-- send to all company contacts
                ->subject($subjectMap[$mailType] ?? 'Expiry Reminder')
                ->html($html);

            if ($setting->smtp_reply_to) {
                $email->replyTo($setting->smtp_reply_to);
            }

            // Send email
            try {
                $mailer->send($email);
                Log::info('Email sent for module: ' . $module . ', record_id: ' . $record->id . ', type: ' . $mailType . ', to: ' . implode(',', $contacts));
            } catch (\Exception $e) {
                Log::error('Email sending failed for record: ' . $record->id . '. Error: ' . $e->getMessage());
                return;
            }
        // }
        // Log entry to prevent duplicate mails
        DB::table('expiry_mail_logs')->insert([
            'module'     => $module,
            'record_id'  => $record->id,
            'company_id' => $record->company_id ?? null,
            'mail_type'  => $mailType,
            'sent_at'    => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }



    // private function sendMailAndLog($module, $record, $expiryDate)
    // {
    //     $mailType = $this->getMailType($expiryDate);
    //     if (!$mailType) return;

    //     if (!$this->shouldSendMail($module, $record->id, $mailType)) {
    //         return;
    //     }

    //     // Send Email
    //     if(isset($record->company->email) && $record->company->email){
    //         Mail::to($record->company->email)
    //             ->send(new ExpiryReminderMail($record, ucfirst(str_replace('_',' ',$module)), $mailType));
    //     }

    //     // Log entry
    //     DB::table('expiry_mail_logs')->insert([
    //         'module'     => $module,
    //         'record_id'  => $record->id,
    //         'company_id' => $record->company_id ?? null,
    //         'mail_type'  => $mailType,
    //         'sent_at'    => now(),
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    // }

    private function checkTrainingPlans()
    {
        $plans = TrainingPlanRecord::with('company')->whereNotNull('expiration_date')->get();
        foreach ($plans as $plan) {
            $this->sendMailAndLog('training_plan', $plan, $plan->expiration_date);
        }
    }

    private function checkCourses()
    {
        $courses = CompanyCourseType::with('company')->get();
        foreach ($courses as $course) {
            // $expiryDate = Carbon::now()->addYears($course->validity_years); // as per your dashboard code
            $this->sendMailAndLog('course', $course, $course->expiration_date);
        }
    }

    private function checkDocuments()
    {
        $documents = Document::with('company')->whereNotNull('expiration_date')->get();
        foreach ($documents as $doc) {
            $this->sendMailAndLog('document', $doc, $doc->expiration_date);
        }
    }

    private function checkVisits()
    {
        $visits = CompanyVisitType::with('company')->whereNotNull('expiry_date')->get();
        foreach ($visits as $visit) {
            $this->sendMailAndLog('visit', $visit, $visit->expiry_date);
        }
    }
}
