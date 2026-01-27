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
use Symfony\Component\Mime\Address;


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

    private function getMailType($expiryDate, $notificationPeriods)
    {
        $days = Carbon::today()->diffInDays(Carbon::parse($expiryDate), false);

        Log::info('Days until expiry: ' . $days);

        // Check against configured notification periods
        if (in_array($days, $notificationPeriods)) {
            return (string) $days;
        }

        return null;
    }


    private function processNotificationBody($body, $record, $daysLeft)
    {
        $expiryDate = $record->expiration_date ?? ($record->expiry_date ?? null);
        $formattedExpiry = $expiryDate ? Carbon::parse($expiryDate)->format('d F Y') : '';

        $replacements = [
            '{company_name}' => $record->company->name ?? '',
            '{days_left}' => $daysLeft,
            '{course_name}' => $record->name ?? ($record->companyCourseType->name ?? ''),
            '{worker_first_name}' => $record->worker->first_name ?? '',
            '{worker_last_name}' => $record->worker->surname ?? '',
            '{expiry_date}' => $formattedExpiry,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $body);
    }

    private function sendMailAndLog($module, $record, $expiryDate)
    {
        Log::info('Attempting to send mail for module: ' . $module . ', record_id: ' . json_encode($record) . ', expiryDate: ' . $expiryDate);

        $company = Company::find($record->company_id);
        if (!$company) {
            Log::warning('Company not found for record: ' . $record->id);
            return;
        }

        // Fetch Settings first to get notification periods
        $setting = Setting::where('company_id', $company->company_id)->first();
        if (!$setting) {
            Log::warning('Settings missing for company: ' . $company->id);
            return;
        }

        $notificationPeriods = $setting->notification_periods ?? [90, 30]; // Default fallback

        $mailType = $this->getMailType($expiryDate, $notificationPeriods);
        if (!$mailType) return;

        if (!$this->shouldSendMail($module, $record->id, $mailType)) {
            Log::warning('Skipping mail for module: ' . $module . ', record_id: ' . $record->id . ', type: ' . $mailType . '. Already sent.');
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

        // Initialize SMTP settings with default global settings
        $smtpHost = $setting->smtp_host;
        $smtpPort = $setting->smtp_port;
        $smtpUsername = $setting->smtp_username;
        $smtpPassword = $setting->smtp_password;
        $fromAddress = $setting->smtp_address ?? $setting->smtp_username;
        $fromName = config('app.name');

        // Check for Operating Location Override
        if (isset($record->worker) && $record->worker && $record->worker->operatingLocation) {
            $opLocation = $record->worker->operatingLocation;
            // Check if SMTP is configured for this location
            if (!empty($opLocation->smtp_host) && !empty($opLocation->smtp_username) && !empty($opLocation->smtp_password)) {
                $smtpHost = $opLocation->smtp_host;
                $smtpPort = $opLocation->smtp_port;
                $smtpUsername = $opLocation->smtp_username;
                $smtpPassword = $opLocation->smtp_password;
                $fromAddress = $opLocation->smtp_from_address ?? $opLocation->smtp_username;
                if (!empty($opLocation->smtp_from_name)) {
                    $fromName = $opLocation->smtp_from_name;
                }

                Log::info('Using Operating Location SMTP for record: ' . $record->id . ' (Location: ' . $opLocation->name . ')');
            }
        }

        if (!$smtpHost || !$smtpUsername || !$smtpPassword) {
            Log::warning('SMTP settings missing for company: ' . $company->id);
            return;
        }

        $scheme = $smtpPort == 465 ? 'smtps' : 'smtp';
        $dsn = sprintf(
            '%s://%s:%s@%s:%s',
            $scheme,
            urlencode($smtpUsername),
            urlencode($smtpPassword),
            $smtpHost,
            $smtpPort
        );

        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer(transport: $transport);

        // Process notification body if available
        $customBody = null;
        if (!empty($setting->notification_body)) {
            $customBody = $this->processNotificationBody($setting->notification_body, $record, $mailType);
        }

        // Render Blade directly
        $html = View::make('emails.expiry-reminder', [
            'record'   => $record,
            'module'   => ucfirst(str_replace('_', ' ', $module)),
            'mailType' => $mailType,
            'customBody' => $customBody
        ])->render();

        $subject = $setting->notification_subject ?? (ucfirst(str_replace('_', ' ', $module)) . " Expiry Reminder – " . $mailType . " Days Remaining");

        // $fromAddress = $setting->smtp_address ?? $setting->smtp_username;
        // $fromName = config('app.name');

        // for ($i = 0; $i < count($contacts); $i++) {

            $email = (new Email())
                ->from(new Address($fromAddress, $fromName))
                ->to($recieverCompany->main_email) // <-- send to all company contacts
                ->subject($subject)
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
        $plans = TrainingPlanRecord::with(['company', 'worker.operatingLocation', 'companyCourseType'])->whereNotNull('expiration_date')->get();
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
