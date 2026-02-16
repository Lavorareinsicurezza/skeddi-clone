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
     * Format phone number to international format
     * Removes all non-numeric characters and adds country code if missing
     *
     * @param string $phone Raw phone number
     * @return string Formatted phone number (e.g., 393331234567)
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // If phone is empty after cleaning, return as-is
        if (empty($cleaned)) {
            return $phone;
        }

        // Add Italy country code (39) if not present
        // Handles various formats: +39, 0039, 39, or local numbers
        if (str_starts_with($cleaned, '0039')) {
            $cleaned = substr($cleaned, 2); // Remove leading 00
        } elseif (str_starts_with($cleaned, '39')) {
            // Already has country code, keep as-is
        } elseif (str_starts_with($cleaned, '0')) {
            // Italian local number starting with 0, remove 0 and add country code
            $cleaned = '39' . substr($cleaned, 1);
        } else {
            // No country code detected, assume Italian and add 39
            $cleaned = '39' . $cleaned;
        }

        return $cleaned;
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
     * @param mixed $record The record (training plan, document, etc.)
     * @param string $module Module type
     * @param string $daysLeft Days until expiry
     * @return array Template parameters
     */
    public function buildTemplateParams($record, string $module, string $daysLeft): array
    {
        $params = [];

        // Company name
        $params[] = [
            'type' => 'text',
            'text' => $record->company->name ?? 'N/A'
        ];

        // Module type (translated)
        $moduleTranslations = [
            'training_plan' => 'Piano di Formazione',
            'course' => 'Corso',
            'document' => 'Documento',
            'visit' => 'Visita',
        ];
        $params[] = [
            'type' => 'text',
            'text' => $moduleTranslations[$module] ?? ucfirst(str_replace('_', ' ', $module))
        ];

        // Days left
        $params[] = [
            'type' => 'text',
            'text' => $daysLeft
        ];

        // Item name
        $params[] = [
            'type' => 'text',
            'text' => $record->name ?? ($record->companyCourseType->name ?? 'N/A')
        ];

        // Expiry date
        $expiryDate = $record->expiration_date ?? ($record->expiry_date ?? null);
        $params[] = [
            'type' => 'text',
            'text' => $expiryDate ? \Carbon\Carbon::parse($expiryDate)->format('d/m/Y') : 'N/A'
        ];

        return $params;
    }
}
