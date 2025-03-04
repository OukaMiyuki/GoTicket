<?php

namespace App\Services\Payment\Qris;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class QrisPaymentService {
    private $client;
    private $storeIdentifier;
    private $apiKey;
    private $visiposUrl;

    public function __construct() {
        $this->client = new Client(['verify' => false]);
        $this->storeIdentifier = env('VISIPOS_STORE_IDENTIFIER');
        $this->apiKey = env('VISIPOS_API_KEY');
        $this->visiposUrl = env('VISIPOS_BASE_URL');
    }

    public function generateQRIS(array $postData): array {
        $postData = array_merge($postData, [
            'store_identifier' => $this->storeIdentifier,
            'secret_key' => $this->apiKey
        ]);
        
        return $this->makeRequest(
            "{$this->visiposUrl}/v1.2/qr/qr-mpm-generate/",
            $postData,
        );
    }

    private function makeRequest(string $url, array $postData): array {

        try {
            $response = $this->client->post($url, [
                'json' => $postData,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $transactionNo = $postData['partnerTransactionNo'] ?? 'Unknown';
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            // $responseCodeContent = $errorResponse['responseCode'] ?? null;
            // $responseMessageContent = $errorResponse['responseMessage'] ?? null;

            Log::error('Error in Snap QRIS Request', [
                'invoice_number' => $transactionNo,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'response' => $errorResponse,
            ]);
            return [
                'responseCode' => $errorResponse['responseCode'] ?? 'Unknown',
                'responseMessage' => $errorResponse['responseMessage'] ?? 'Unknown error',
            ];
        } catch (Exception $e) {
            $transactionNo = $postData['partnerReferenceNo'] ?? 'Unknown';
            // $body = $this->formatErrorNotification(
            //     'Unexpected Error in Snap QRIS Request',
            //     $transactionNo,
            //     $e->getMessage(),
            //     json_encode(array_slice($e->getTrace(), 0, 5), JSON_PRETTY_PRINT),
            //     null
            // );
            Log::error('Unexpected Error in Snap QRIS Request', [
                'invoice_number' => $transactionNo,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            //$this->devNotification($body);
            return [
                'responseCode' => '500',
                'responseMessage' => 'An unexpected error occurred: ' . $e->getMessage(),
            ];
        }
    }
}