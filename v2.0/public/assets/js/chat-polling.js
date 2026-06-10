(function () {
  const panel = document.querySelector('[data-chat]');
  if (!panel) {
    return;
  }

  const messagesBox = panel.querySelector('[data-chat-messages]');
  const emptyState = panel.querySelector('[data-chat-empty]');
  const form = panel.querySelector('[data-chat-form]');
  const textarea = form ? form.querySelector('textarea[name="message"]') : null;
  const statusText = panel.querySelector('[data-chat-status]');
  const messagesUrl = panel.dataset.messagesUrl;
  const csrfToken = panel.dataset.csrfToken;
  const currentUserId = Number(panel.dataset.currentUserId || 0);
  let canSend = panel.dataset.canSend === '1';
  let lastMessageId = 0;
  let pollingId = null;

  function setStatus(message) {
    if (statusText) {
      statusText.textContent = message || '';
    }
  }

  function appendMessage(message) {
    if (emptyState) {
      emptyState.remove();
    }

    const item = document.createElement('article');
    const isOwnMessage = Number(message.sender_id) === currentUserId;
    item.className = `chat-message ${isOwnMessage ? 'is-own' : 'is-other'}`;

    const meta = document.createElement('div');
    meta.className = 'chat-message-meta';
    meta.textContent = `${message.sender_name} - ${message.created_at}`;

    const body = document.createElement('p');
    body.className = 'chat-message-body';
    body.textContent = message.message;

    item.appendChild(meta);
    item.appendChild(body);
    messagesBox.appendChild(item);
    messagesBox.scrollTop = messagesBox.scrollHeight;
  }

  async function fetchMessages() {
    const separator = messagesUrl.includes('?') ? '&' : '?';
    const response = await fetch(`${messagesUrl}${separator}after_id=${lastMessageId}`, {
      headers: { Accept: 'application/json' }
    });

    if (response.status === 401 || response.status === 403 || response.status === 423) {
      stopPolling();
    }

    const payload = await response.json();
    if (!payload.success) {
      setStatus(payload.message || 'Chat belum tersedia.');
      return;
    }

    payload.data.messages.forEach(appendMessage);
    lastMessageId = payload.data.last_message_id || lastMessageId;
    canSend = Boolean(payload.data.can_send);

    if (!canSend && payload.data.consultation_status !== 'active') {
      stopPolling();
    }
  }

  async function sendMessage(event) {
    event.preventDefault();
    if (!canSend || !textarea) {
      return;
    }

    const message = textarea.value.trim();
    if (message === '') {
      setStatus('Pesan wajib diisi.');
      return;
    }

    if (message.length > 3000) {
      setStatus('Pesan maksimal 3000 karakter.');
      return;
    }

    const response = await fetch(messagesUrl, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({ message })
    });
    const payload = await response.json();

    if (!payload.success) {
      setStatus(payload.message || 'Pesan gagal dikirim.');
      return;
    }

    textarea.value = '';
    setStatus('');
    await fetchMessages();
  }

  function stopPolling() {
    if (pollingId !== null) {
      window.clearInterval(pollingId);
      pollingId = null;
    }
  }

  if (form) {
    form.addEventListener('submit', sendMessage);
  }

  fetchMessages().catch(function () {
    setStatus('Gagal memuat pesan.');
  });
  pollingId = window.setInterval(function () {
    fetchMessages().catch(function () {
      setStatus('Gagal memperbarui pesan.');
    });
  }, 5000);
  window.addEventListener('beforeunload', stopPolling);
})();
