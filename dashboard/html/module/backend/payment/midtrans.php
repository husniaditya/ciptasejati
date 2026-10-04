<?php
/**
 * Small Midtrans REST client used by the payment endpoints.
 */
class MidtransClient
{
    private static function config($key, $default = null)
    {
        return isset($_ENV[$key]) && $_ENV[$key] !== '' ? $_ENV[$key] : $default;
    }

    public static function clientKey()
    {
        return self::config('MIDTRANS_CLIENT_KEY', '');
    }

    public static function snapScriptUrl()
    {
        return self::config(
            'MIDTRANS_SNAP_SCRIPT_URL',
            self::isProduction()
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js'
        );
    }

    public static function isProduction()
    {
        return filter_var(self::config('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
    }

    public static function createSnapToken(array $payload)
    {
        $finishUrl = self::config('MIDTRANS_FINISH_URL', '');
        if ($finishUrl !== '' && !isset($payload['callbacks'])) {
            $payload['callbacks'] = ['finish' => $finishUrl];
        }

        return self::request('POST', '/snap/v1/transactions', $payload);
    }

    public static function getTransactionStatus($orderId)
    {
        return self::request('GET', '/v2/' . rawurlencode($orderId) . '/status');
    }

    public static function verifySignature(array $notification)
    {
        $signature = $notification['signature_key'] ?? '';
        $raw = ($notification['order_id'] ?? '')
            . ($notification['status_code'] ?? '')
            . ($notification['gross_amount'] ?? '')
            . self::config('MIDTRANS_SERVER_KEY', '');

        return $signature !== '' && hash_equals(hash('sha512', $raw), $signature);
    }

    private static function request($method, $path, array $payload = null)
    {
        $baseUrl = rtrim(self::config(
            'MIDTRANS_API_BASE_URL',
            self::isProduction() ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com'
        ), '/');
        $serverKey = self::config('MIDTRANS_SERVER_KEY', '');

        if ($serverKey === '') {
            throw new RuntimeException('Midtrans server key is not configured');
        }

        $ch = curl_init($baseUrl . $path);
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($serverKey . ':')
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $body = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false || $curlError !== '') {
            throw new RuntimeException('Midtrans request failed: ' . ($curlError ?: 'empty response'));
        }

        $response = json_decode($body, true);
        if (!is_array($response)) {
            throw new RuntimeException('Invalid response from Midtrans');
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            $message = $response['status_message'] ?? 'Midtrans rejected the request';
            throw new RuntimeException($message, $httpCode);
        }

        return $response;
    }
}
