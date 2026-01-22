<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Exception;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        // Get the first setting record for this company or create one if it doesn't exist
        $setting = Setting::where('company_id', auth()->user()->company_id)->first();

        if (!$setting) {
            $setting = Setting::create([
                'company_id' => auth()->user()->company_id,
                'notification_periods' => [90, 30], // Default values
            ]);
        }

        // Ensure notification_periods is an array if it's null (for existing records migrated)
        if (is_null($setting->notification_periods)) {
            $setting->notification_periods = [90, 30];
        }

        // Get UI version (timestamp)
        $uiVersion = Carbon::now()->format('Y-m-d\TH:i:s.u\Z');

        return view('admin.settings.index', compact('setting', 'uiVersion'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'notification_periods' => 'required|array',
            'notification_periods.*' => 'required|integer|min:1',
            'smtp_address' => 'nullable|string|max:255',
            'smtp_alias' => 'nullable|string|max:255',
            'smtp_reply_to' => 'nullable|string|max:255',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|string|max:10',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'email_auto_generated' => 'nullable|boolean',
            'whatsapp_notification' => 'nullable|boolean',
            'email_template' => 'nullable|string',
            'notification_subject' => 'nullable|string|max:255',
            'notification_body' => 'nullable|string',
            'whatsapp_smtp_address' => 'nullable|string|max:255',
            'whatsapp_smtp_alias' => 'nullable|string|max:255',
            'whatsapp_smtp_reply_to' => 'nullable|string|max:255',
        ]);

       $validated['notification_periods'] = array_map('intval', $validated['notification_periods']);
        // Convert checkboxes to boolean
        $validated['email_auto_generated'] = $request->has('email_auto_generated');
        $validated['whatsapp_notification'] = $request->has('whatsapp_notification');

        $setting = Setting::where('company_id', auth()->user()->company_id)->first();

        if ($setting) {
            $setting->update($validated);
        } else {
            $validated['company_id'] = auth()->user()->company_id;
            Setting::create($validated);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully');
    }

    /**
     * Test SMTP connection.
     */
    public function testSmtp(Request $request)
    {
        try {
            // Get current settings from database for this company
            $setting = Setting::where('company_id', auth()->user()->company_id)->first();

            if (!$setting || !$setting->smtp_host) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP configuration not found. Please configure SMTP settings first.'
                ], 400);
            }

            // Validate required fields
            if (!$setting->smtp_username || !$setting->smtp_password) {
                return response()->json([
                    'success' => false,
                    'message' => 'SMTP username and password are required for testing.'
                ], 400);
            }

            // Determine encryption type and create DSN
            $scheme = 'smtp';
            if ($setting->smtp_port == 465) {
                $scheme = 'smtps'; // Use smtps for SSL
            }

            // Create DSN string for Symfony Mailer
            $dsn = sprintf(
                '%s://%s:%s@%s:%s',
                $scheme,
                urlencode($setting->smtp_username),
                urlencode($setting->smtp_password),
                $setting->smtp_host,
                $setting->smtp_port
            );

            // Create transport using Transport factory
            $transport = Transport::fromDsn($dsn);

            // Create mailer
            $mailer = new Mailer($transport);

            // Create email message
            $email = (new Email())
                ->from($setting->smtp_address)
                ->to($setting->smtp_address)
                ->subject('SMTP Configuration Test')
                ->html(
                    '<html><body>' .
                    '<h2>SMTP Test Successful!</h2>' .
                    '<p>This is a test email from your SMTP configuration.</p>' .
                    '<p><strong>Configuration Details:</strong></p>' .
                    '<ul>' .
                    '<li>Host: ' . htmlspecialchars($setting->smtp_host) . '</li>' .
                    '<li>Port: ' . htmlspecialchars($setting->smtp_port) . '</li>' .
                    '<li>Encryption: ' . htmlspecialchars($scheme === 'smtps' ? 'SSL' : 'TLS') . '</li>' .
                    '<li>Username: ' . htmlspecialchars($setting->smtp_username) . '</li>' .
                    '</ul>' .
                    '<p>If you received this email, your SMTP configuration is working correctly!</p>' .
                    '</body></html>'
                );

            // Add reply-to if configured
            if ($setting->smtp_reply_to) {
                $email->replyTo($setting->smtp_reply_to);
            }

            // Send email
            $mailer->send($email);

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully! Please check your inbox at ' . $setting->smtp_address
            ]);

        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP Connection Error: ' . $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SMTP test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
