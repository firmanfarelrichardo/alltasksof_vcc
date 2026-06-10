<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Services\PaymentService;
use RuntimeException;
use Throwable;

final class MidtransWebhookController
{
    public function __construct(
        private readonly PaymentService $payments = new PaymentService()
    ) {
    }

    public function notification(): void
    {
        $rawPayload = file_get_contents('php://input') ?: '';
        $payload = json_decode($rawPayload, true);

        if (!is_array($payload)) {
            Response::json(['success' => false], 400);
            return;
        }

        try {
            $payment = $this->payments->applyWebhookPayload($payload, true);
        } catch (RuntimeException) {
            Response::json(['success' => false], 403);
            return;
        } catch (Throwable) {
            Response::json(['success' => false], 500);
            return;
        }

        if ($payment === null) {
            Response::json(['success' => false], 404);
            return;
        }

        Response::json(['success' => true]);
    }
}
