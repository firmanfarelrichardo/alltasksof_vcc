<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\Consultation;
use App\Models\Message;

final class ChatController extends Controller
{
    public function __construct(
        private readonly Consultation $consultations = new Consultation(),
        private readonly Message $messages = new Message()
    ) {
    }

    public function showForUser(string $consultationId): void
    {
        $consultation = $this->consultations->chatForUser((int) $consultationId, (int) Session::get('user_id'));
        if ($consultation === null) {
            Response::notFound();
            return;
        }

        $this->view('chat.show', [
            'consultation' => $consultation,
            'scope' => 'user',
            'messagesUrl' => '/api/user/consultations/' . $consultationId . '/messages',
            'canRead' => $this->canRead($consultation),
            'canSend' => $this->canSend($consultation),
        ]);
    }

    public function showForAdmin(string $consultationId): void
    {
        $consultation = $this->consultations->findForAdmin((int) $consultationId, (int) Session::get('user_id'));
        if ($consultation === null) {
            Response::forbidden();
            return;
        }

        $this->view('chat.show', [
            'consultation' => $consultation,
            'scope' => 'admin',
            'messagesUrl' => '/api/admin/consultations/' . $consultationId . '/messages',
            'canRead' => $this->canRead($consultation),
            'canSend' => $this->canSend($consultation),
        ]);
    }

    public function messagesForUser(string $consultationId): void
    {
        $consultation = $this->consultations->chatForUser((int) $consultationId, (int) Session::get('user_id'));
        $this->messagesResponse($consultation);
    }

    public function messagesForAdmin(string $consultationId): void
    {
        $consultation = $this->consultations->findForAdmin((int) $consultationId, (int) Session::get('user_id'));
        $this->messagesResponse($consultation);
    }

    public function sendByUserApi(string $consultationId): void
    {
        $consultation = $this->consultations->chatForUser((int) $consultationId, (int) Session::get('user_id'));
        $this->sendResponse($consultation);
    }

    public function sendByAdminApi(string $consultationId): void
    {
        $consultation = $this->consultations->findForAdmin((int) $consultationId, (int) Session::get('user_id'));
        $this->sendResponse($consultation);
    }

    private function messagesResponse(?array $consultation): void
    {
        if ($consultation === null) {
            Response::json(['success' => false, 'message' => 'Anda tidak memiliki akses ke konsultasi ini.'], 403);
            return;
        }

        if (!$this->canRead($consultation)) {
            Response::json(['success' => false, 'message' => 'Chat belum tersedia untuk konsultasi ini.'], 423);
            return;
        }

        $afterId = max(0, (int) ($_GET['after_id'] ?? 0));
        $messages = $afterId > 0
            ? $this->messages->afterId((int) $consultation['id'], $afterId)
            : $this->messages->recentForConsultation((int) $consultation['id']);

        Response::json([
            'success' => true,
            'data' => [
                'messages' => $messages,
                'last_message_id' => $this->lastMessageId($messages, $afterId),
                'consultation_status' => $consultation['status'],
                'can_send' => $this->canSend($consultation),
            ],
        ]);
    }

    private function sendResponse(?array $consultation): void
    {
        if ($consultation === null) {
            Response::json(['success' => false, 'message' => 'Anda tidak memiliki akses ke konsultasi ini.'], 403);
            return;
        }

        if (!$this->canSend($consultation)) {
            Response::json(['success' => false, 'message' => 'Chat hanya dapat digunakan saat konsultasi aktif dan sudah dibayar.'], 423);
            return;
        }

        $message = $this->messageFromRequest();
        if ($message === '') {
            Response::json(['success' => false, 'message' => 'Pesan wajib diisi.'], 422);
            return;
        }

        if (strlen($message) > 3000) {
            Response::json(['success' => false, 'message' => 'Pesan maksimal 3000 karakter.'], 422);
            return;
        }

        $messageId = $this->messages->create((int) $consultation['id'], (int) Session::get('user_id'), $message);

        Response::json([
            'success' => true,
            'data' => [
                'message_id' => $messageId,
            ],
        ], 201);
    }

    private function messageFromRequest(): string
    {
        $payload = json_decode(file_get_contents('php://input') ?: '', true);
        $message = is_array($payload) ? ($payload['message'] ?? '') : ($_POST['message'] ?? '');

        return trim(is_string($message) ? $message : '');
    }

    private function canRead(array $consultation): bool
    {
        return in_array($consultation['status'], ['active', 'closed'], true)
            && ($consultation['internal_status'] ?? null) === 'paid';
    }

    private function canSend(array $consultation): bool
    {
        return $consultation['status'] === 'active'
            && ($consultation['internal_status'] ?? null) === 'paid';
    }

    private function lastMessageId(array $messages, int $fallback): int
    {
        if ($messages === []) {
            return $fallback;
        }

        $last = end($messages);

        return (int) ($last['id'] ?? $fallback);
    }
}
