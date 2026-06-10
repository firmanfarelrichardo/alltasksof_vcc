<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use RuntimeException;

final class MidtransService
{
    private array $config;

    public function __construct()
    {
        $this->config = require BASE_PATH . '/config/payment.php';
        $this->configure();
    }

    public function createSnapToken(array $payment, array $user, array $subService): string
    {
        $this->ensureConfigured();

        $params = [
            'transaction_details' => [
                'order_id' => $payment['order_id'],
                'gross_amount' => (int) round((float) $payment['amount']),
            ],
            'customer_details' => [
                'first_name' => $user['name'],
                'email' => $user['email'],
            ],
            'item_details' => [
                [
                    'id' => 'SUBSERVICE-' . $subService['id'],
                    'price' => (int) round((float) $payment['amount']),
                    'quantity' => 1,
                    'name' => $subService['name'],
                ],
            ],
        ];

        $callbacks = [
            'finish' => $this->midtrans('finish_redirect_url'),
            'unfinish' => $this->midtrans('unfinish_redirect_url'),
            'error' => $this->midtrans('error_redirect_url'),
        ];

        foreach ($callbacks as $key => $url) {
            if ($url !== '') {
                $params['callbacks'][$key] = $url;
            }
        }

        return Snap::getSnapToken($params);
    }

    public function status(string $orderId): array
    {
        $this->ensureConfigured();
        $status = Transaction::status($orderId);

        return json_decode(json_encode($status, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
    }

    public function verifySignature(array $payload): bool
    {
        foreach (['order_id', 'status_code', 'gross_amount', 'signature_key'] as $key) {
            if (!isset($payload[$key])) {
                return false;
            }
        }

        $expected = hash(
            'sha512',
            (string) $payload['order_id']
            . (string) $payload['status_code']
            . (string) $payload['gross_amount']
            . $this->midtrans('server_key')
        );

        return hash_equals($expected, (string) $payload['signature_key']);
    }

    public function mapInternalStatus(array $payload): string
    {
        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');

        return match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'pending',
            'cancel' => 'cancelled',
            'deny' => 'failed',
            'expire' => 'expired',
            'refund', 'partial_refund' => 'refunded',
            default => 'pending',
        };
    }

    public function snapScriptUrl(): string
    {
        return $this->midtrans('is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    public function clientKey(): string
    {
        return $this->midtrans('client_key');
    }

    private function configure(): void
    {
        if (!class_exists(Config::class)) {
            throw new RuntimeException('Dependency Midtrans belum tersedia. Jalankan composer install.');
        }

        Config::$serverKey = $this->midtrans('server_key');
        Config::$isProduction = (bool) $this->midtrans('is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    private function ensureConfigured(): void
    {
        if ($this->midtrans('server_key') === '') {
            throw new InvalidArgumentException('MIDTRANS_SERVER_KEY belum dikonfigurasi.');
        }
    }

    private function midtrans(string $key): mixed
    {
        return $this->config['midtrans'][$key] ?? '';
    }
}
