<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp message using WhatsApp Business API
     *
     * @param string $apiUrl WhatsApp API base URL (e.g., https://graph.facebook.com/v21.0)
     * @param string $apiKey Access token for authentication
     * @param string $phoneNumberId WhatsApp Phone Number ID
     * @param string $recipientPhone Recipient phone number (will be formatted)
     * @param string $templateName Name of approved WhatsApp template
     * @param array $templateParams Template parameters for dynamic content
     * @return bool True if message sent successfully, false otherwise
     */
    public function sendMessage(
        string $apiUrl,
        string $apiKey,
        string $phoneNumberId,
        string $recipientPhone,
        string $templateName,
        array $templateParams = []
    ): bool {
        try {
            // Format phone number to international format
            $formattedPhone = $this->formatPhoneNumber($recipientPhone);

            Log::info('Attempting to send WhatsApp message', [
                'recipient' => $formattedPhone,
                'template' => $templateName,
                'phone_number_id' => $phoneNumberId,
            ]);

            // Build API endpoint
            $endpoint = rtrim($apiUrl, '/') . '/' . $phoneNumberId . '/messages';

            // Prepare message payload
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $formattedPhone,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => 'it' // Italian language code
                    ]
                ]
            ];

            // Add template parameters if provided
            if (!empty($templateParams)) {
                $payload['template']['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => $templateParams
                    ]
                ];
            }

            // Send API request
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post($endpoint, $payload);

            // Check if request was successful
            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('WhatsApp message sent successfully', [
                    'recipient' => $formattedPhone,
                    'template' => $templateName,
                    'message_id' => $responseData['messages'][0]['id'] ?? null,
                ]);
                return true;
            }

            // Log failure
            Log::error('WhatsApp message failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'recipient' => $formattedPhone,
                'template' => $templateName,
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('WhatsApp sending exception', [
                'error' => $e->getMessage(),
                'recipient' => $recipientPhone,
                'template' => $templateName,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Format phone number to international format for WhatsApp Business API
     * Expected output: country code + number (no +, spaces, or dashes)
     *
     * Logic:
     * - If number has + or 00 prefix → treat as international, respect existing country code
     * - If number is 10+ digits → assume it already has country code, keep as-is
     * - If number is shorter or starts with 0 → assume Italian local, add +39
     *
     * Examples:
     *   +39 333 1234567 → 393331234567
     *   0039 333 1234567 → 393331234567
     *   +1 555 194 1356 → 15551941356 (US preserved)
     *   +44 20 1234 5678 → 442012345678 (UK preserved)
     *   +91 98765 43210 → 919876543210 (India preserved)
     *   333 1234567 → 393331234567 (Italian local, adds +39)
     *   0333 1234567 → 393331234567 (Italian local with 0, adds +39)
     *
     * @param string $phone Raw phone number
     * @return string Formatted phone number (country code + number, digits only)
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Trim whitespace
        $original = trim($phone);

        // Check if number has international prefix indicators
        $hasInternationalPrefix = str_starts_with($original, '+') || str_starts_with($original, '00');

        // Remove all non-numeric characters except leading +
        $cleaned = preg_replace('/[^0-9+]/', '', $original);

        // If phone is empty after cleaning, return original
        if (empty($cleaned)) {
            Log::warning('Empty phone number after cleaning', ['original' => $original]);
            return $original;
        }

        // Remove + sign and convert 00 prefix to nothing (we have the info we need)
        $cleaned = ltrim($cleaned, '+');
        if (str_starts_with($cleaned, '00')) {
            $cleaned = substr($cleaned, 2);
        }

        // If original had + or 00 prefix, trust it as international format
        if ($hasInternationalPrefix) {
            // Validate international format (typically 10-15 digits with country code)
            if (strlen($cleaned) >= 10 && strlen($cleaned) <= 15) {
                Log::info('International number detected and preserved', [
                    'original' => $original,
                    'formatted' => $cleaned
                ]);
                return $cleaned;
            } else {
                Log::warning('International number has unusual length', [
                    'original' => $original,
                    'cleaned' => $cleaned,
                    'length' => strlen($cleaned)
                ]);
                return $cleaned; // Return anyway, let API validate
            }
        }

        // No international prefix detected
        // If number is already 10+ digits, assume it has country code
        if (strlen($cleaned) >= 10) {
            Log::info('Long number without prefix, assuming international', [
                'original' => $original,
                'formatted' => $cleaned,
                'length' => strlen($cleaned)
            ]);
            return $cleaned;
        }

        // Short number without prefix - assume Italian local format
        // Remove leading 0 if present (Italian local convention)
        if (str_starts_with($cleaned, '0')) {
            $cleaned = substr($cleaned, 1);
        }

        // Validate Italian mobile/landline length after removing leading 0
        // Mobile: 3xx xxxxxxx (9 digits)
        // Landline: varies, typically 8-10 digits
        if (strlen($cleaned) < 7 || strlen($cleaned) > 11) {
            Log::warning('Unusual Italian local number length', [
                'original' => $original,
                'cleaned' => $cleaned,
                'length' => strlen($cleaned)
            ]);
        }

        // Add Italy country code
        $formatted = '39' . $cleaned;
        Log::info('Italian local number formatted', [
            'original' => $original,
            'formatted' => $formatted
        ]);

        return $formatted;
    }

    /**
     * Test WhatsApp API connection
     *
     * @param string $apiUrl WhatsApp API base URL
     * @param string $apiKey Access token
     * @param string $phoneNumberId WhatsApp Phone Number ID
     * @return array Result with success status and message
     */
    public function testConnection(
        string $apiUrl,
        string $apiKey,
        string $phoneNumberId
    ): array {
        try {
            // Try to get phone number info
            $endpoint = rtrim($apiUrl, '/') . '/' . $phoneNumberId;

            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->get($endpoint);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'WhatsApp API connection successful!',
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'WhatsApp API connection failed: ' . $response->body(),
                'status' => $response->status(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'WhatsApp API connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build template parameters from record data
     *
     * Template "Avviso_scadenza_corso" (MARKETING category) expects 3 parameters:
     * {{1}} = Training course name (Nome del corso di formazione)
     * {{2}} = Course expiration date (Data di scadenza del corso)
     * {{3}} = Participant first and last name (Nome e cognome del partecipante/dipendente)
     *
     * @param mixed $record The record (training plan, document, etc.)
     * @param string $module Module type (unused, kept for compatibility)
     * @param string $daysLeft Days until expiry (unused, kept for compatibility)
     * @return array Template parameters (3 params to match template)
     */
    public function buildTemplateParams($record, string $module, string $daysLeft): array
    {
        // {{1}} - Training course name
        $courseName = $record->name ?? ($record->companyCourseType->name ?? 'N/A');

        // {{2}} - Course expiration date (formatted as DD/MM/YYYY)
        $expiryDate = $record->expiration_date ?? ($record->expiry_date ?? null);
        $formattedDate = $expiryDate ? \Carbon\Carbon::parse($expiryDate)->format('d/m/Y') : 'N/A';

        // {{3}} - Participant/employee full name (first name + surname)
        $workerFirstName = $record->worker->first_name ?? '';
        $workerSurname = $record->worker->surname ?? '';
        $workerFullName = trim($workerFirstName . ' ' . $workerSurname) ?: 'N/A';

        // Build 3 parameters to match WhatsApp template
        $params = [
            // {{1}} - Nome del corso di formazione
            [
                'type' => 'text',
                'text' => $courseName
            ],
            // {{2}} - Data di scadenza del corso
            [
                'type' => 'text',
                'text' => $formattedDate
            ],
            // {{3}} - Nome e cognome del partecipante/dipendente
            [
                'type' => 'text',
                'text' => $workerFullName
            ]
        ];

        Log::info('Built WhatsApp template parameters', [
            'param_count' => count($params),
            'course_name' => $courseName,
            'expiry_date' => $formattedDate,
            'worker_name' => $workerFullName
        ]);

        return $params;
    }
}
