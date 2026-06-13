<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExchangeRateService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = env('EXCHANGE_RATE_API_KEY', 'ba0f7ae2e285c305b038a4fd');
        $this->baseUrl = 'https://v6.exchangerate-api.com/v6';
    }

    public function getRates(string $baseCurrency = 'EUR'): array
    {
        $url = "{$this->baseUrl}/{$this->apiKey}/latest/" . strtoupper($baseCurrency);

        $response = Http::get($url);

        if ($response->failed()) {
            throw new RuntimeException('Failed to fetch exchange rates from API.');
        }

        $data = $response->json();

        if (!isset($data['result']) || $data['result'] !== 'success') {
            $error = $data['error-type'] ?? 'unknown';
            throw new RuntimeException("API error: {$error}");
        }

        return $data;
    }

    public function getRate(string $currency): array
    {
        $data = $this->getRates('EUR');

        if (!isset($data['conversion_rates'][$currency])) {
            throw new RuntimeException("Currency {$currency} not supported.");
        }

        return [
            'rate'       => $data['conversion_rates'][$currency],
            'source'     => "{$this->baseUrl}/{$this->apiKey}/latest/EUR",
            'fetched_at' => isset($data['time_last_update_utc'])
                ? Carbon::parse($data['time_last_update_utc'])
                : now(),
        ];
    }

    public function convertToEur(float $amount, string $currency): array
    {
        $rateData = $this->getRate($currency);
        $amountEur = $amount / $rateData['rate'];

        return [
            'amount_eur'    => round($amountEur, 2),
            'exchange_rate' => $rateData['rate'],
            'source'        => $rateData['source'],
            'fetched_at'    => $rateData['fetched_at'],
        ];
    }
}
