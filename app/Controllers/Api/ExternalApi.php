<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class ExternalApi extends BaseController
{
    public function activity()
    {
        try {
            $client = \Config\Services::curlrequest([
                'timeout' => 8,
                'connect_timeout' => 5,
            ]);

            $response = $client->get(
                'https://bored-api.appbrewery.com/random',
                [
                    'headers' => ['Accept' => 'application/json'],
                    'http_errors' => false,
                ]
            );

            if ($response->getStatusCode() !== 200) {
                return $this->response
                    ->setStatusCode(502)
                    ->setJSON([
                        'success' => false,
                        'message' => 'External service is temporarily unavailable.',
                    ]);
            }

            $activity = json_decode($response->getBody(), true);

            if (
                ! is_array($activity)
                || empty($activity['activity'])
            ) {
                return $this->response
                    ->setStatusCode(502)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Invalid response from external service.',
                    ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'activity' => $activity['activity'],
                    'type' => $activity['type'] ?? 'General',
                    'participants' => $activity['participants'] ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'External API error: {message}', [
                'message' => $e->getMessage(),
            ]);

            return $this->response
                ->setStatusCode(502)
                ->setJSON([
                    'success' => false,
                    'message' => 'Could not connect to the external service.',
                ]);
        }
    }
}
