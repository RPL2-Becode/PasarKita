<?php

class ApiHelper {
    // Base URLs untuk eksternal service
    // Ganti URL ini saat mendeploy ke production (misal Fly.io)
    private static $smartbank_url = 'http://pasarkitadummy.test/smartbank';
    private static $logistikita_url = 'http://pasarkitadummy.test/logistikita'; 
    // Menggunakan route .htaccess yang kita set sebelumnya

    /**
     * Memproses pembayaran ke SmartBank
     */
    public static function paySmartBank($account_number, $order_id, $amount) {
        $url = self::$smartbank_url . '/pembayaran_transaksi';
        
        $data = [
            'account_number' => $account_number,
            'order_id' => $order_id,
            'amount' => $amount
        ];

        return self::sendPostRequest($url, $data);
    }

    /**
     * Mengambil daftar ongkos kirim dari LogistikKita
     */
    public static function getShippingRates($amount) {
        $url = self::$logistikita_url . '/cek_ongkir?amount=' . urlencode($amount);
        
        return self::sendGetRequest($url);
    }

    /**
     * Membuat request pengiriman dan mendapatkan resi
     */
    public static function createShipment($order_id, $service_type, $shipping_cost) {
        $url = self::$logistikita_url . '/request_pengiriman';
        
        $data = [
            'order_id' => $order_id,
            'service_type' => $service_type,
            'shipping_cost' => $shipping_cost
        ];

        return self::sendPostRequest($url, $data);
    }

    /**
     * Melacak resi pengiriman
     */
    public static function trackResi($resi) {
        $url = self::$logistikita_url . '/lacak_resi?resi=' . urlencode($resi);
        
        return self::sendGetRequest($url);
    }

    /**
     * Internal Helper: Execute cURL POST
     */
    private static function sendPostRequest($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Timeout 15 detik

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpcode >= 400) {
            return [
                'status' => 'error',
                'message' => 'Layanan eksternal sedang sibuk atau tidak dapat diakses (HTTP Code: '.$httpcode.').'
            ];
        }

        return json_decode($response, true) ?: ['status' => 'error', 'message' => 'Respons tidak valid'];
    }

    /**
     * Internal Helper: Execute cURL GET
     */
    private static function sendGetRequest($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpcode >= 400) {
            return [
                'status' => 'error',
                'message' => 'Layanan eksternal sedang sibuk atau tidak dapat diakses (HTTP Code: '.$httpcode.').'
            ];
        }

        return json_decode($response, true) ?: ['status' => 'error', 'message' => 'Respons tidak valid'];
    }
}
