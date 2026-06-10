<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\Consultation;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Throwable;

final class PaymentController extends Controller
{
    public function __construct(
        private readonly Consultation $consultations = new Consultation(),
        private readonly Payment $payments = new Payment(),
        private readonly PaymentService $service = new PaymentService(),
        private readonly MidtransService $midtrans = new MidtransService()
    ) {
    }

    public function show(string $consultationId): void
    {
        $consultation = $this->consultations->findForUser((int) $consultationId, (int) Session::get('user_id'));

        if ($consultation === null) {
            Response::notFound();
            return;
        }

        $payment = $this->payments->latestForConsultationUser((int) $consultationId, (int) Session::get('user_id'));

        $this->view('user.payment', [
            'consultation' => $consultation,
            'payment' => $payment,
            'snapScriptUrl' => $this->midtrans->snapScriptUrl(),
            'midtransClientKey' => $this->midtrans->clientKey(),
        ]);
    }

    public function create(string $consultationId): void
    {
        try {
            $payment = $this->service->createSnapPayment((int) $consultationId, (int) Session::get('user_id'));
        } catch (Throwable) {
            Session::flash('error', 'Pembayaran belum dapat dibuat. Periksa konfigurasi Midtrans Sandbox.');
            Response::redirect('/user/consultations/' . $consultationId . '/payment');
            return;
        }

        if ($payment === null) {
            Response::notFound();
            return;
        }

        Session::flash('success', 'Transaksi Midtrans berhasil dibuat.');
        Response::redirect('/user/consultations/' . $consultationId . '/payment');
    }

    public function showStatus(string $paymentId): void
    {
        $payment = $this->payments->findForUser((int) $paymentId, (int) Session::get('user_id'));
        if ($payment === null) {
            Response::notFound();
            return;
        }

        Response::redirect('/user/consultations/' . $payment['consultation_id']);
    }

    public function statusApi(string $paymentId): void
    {
        $payment = $this->payments->findForUser((int) $paymentId, (int) Session::get('user_id'));
        if ($payment === null) {
            Response::notFound();
            return;
        }

        Response::json([
            'success' => true,
            'data' => [
                'payment_id' => (int) $payment['id'],
                'order_id' => $payment['order_id'],
                'payment_status' => $payment['internal_status'],
                'consultation_status' => $payment['consultation_status'],
            ],
        ]);
    }

    public function refreshStatus(string $paymentId): void
    {
        try {
            $payment = $this->service->refreshStatus((int) $paymentId, (int) Session::get('user_id'));
        } catch (Throwable) {
            Session::flash('error', 'Status pembayaran belum dapat disegarkan dari Midtrans.');
            Response::redirect('/user/payments/' . $paymentId);
            return;
        }

        if ($payment === null) {
            Response::notFound();
            return;
        }

        Session::flash('success', 'Status pembayaran berhasil disegarkan dari Midtrans.');
        Response::redirect('/user/consultations/' . $payment['consultation_id']);
    }

    public function finish(): void
    {
        Session::flash('success', 'Pembayaran selesai diproses Midtrans. Status final menunggu webhook atau refresh backend.');
        Response::redirect('/user/consultations');
    }

    public function unfinish(): void
    {
        Session::flash('error', 'Pembayaran belum selesai. Silakan cek ulang status pembayaran.');
        Response::redirect('/user/consultations');
    }

    public function error(): void
    {
        Session::flash('error', 'Pembayaran gagal atau dibatalkan di Midtrans. Status final tetap menunggu backend.');
        Response::redirect('/user/consultations');
    }
}
