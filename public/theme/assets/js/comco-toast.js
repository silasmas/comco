
/**
 * Affiche une notification toast visible, colorée et fermable.
 *
 * @param {string} message Texte du message
 * @param {string} type success|danger|info|warning
 */
window.showComcoToast = function (message, type) {
  const container = document.getElementById('comco-toast-container');

  if (!container || !message) {
    return;
  }

  const typeConfig = {
    success: {
      title: 'Succès',
      icon: 'check-circle',
      className: 'comco-toast--success',
      delay: 8000,
    },
    danger: {
      title: 'Erreur',
      icon: 'exclamation-circle',
      className: 'comco-toast--danger',
      delay: 10000,
    },
    info: {
      title: 'Information',
      icon: 'info-circle',
      className: 'comco-toast--info',
      delay: 9000,
    },
    warning: {
      title: 'Attention',
      icon: 'exclamation-triangle',
      className: 'comco-toast--warning',
      delay: 9000,
    },
  };
  const config = typeConfig[type] || {
    title: 'Notification',
    icon: 'bell',
    className: 'comco-toast--info',
    delay: 8000,
  };

  const toastEl = document.createElement('div');
  toastEl.className = 'toast comco-toast ' + config.className;
  toastEl.setAttribute('role', 'alert');
  toastEl.setAttribute('aria-live', 'assertive');
  toastEl.setAttribute('aria-atomic', 'true');

  const header = document.createElement('div');
  header.className = 'toast-header comco-toast__header';

  const icon = document.createElement('span');
  icon.className = 'fas fa-' + config.icon + ' comco-toast__icon me-2';
  icon.setAttribute('aria-hidden', 'true');

  const title = document.createElement('strong');
  title.className = 'me-auto comco-toast__title';
  title.textContent = config.title;

  const closeBtn = document.createElement('button');
  closeBtn.type = 'button';
  closeBtn.className = 'btn-close comco-toast__close';
  closeBtn.setAttribute('data-bs-dismiss', 'toast');
  closeBtn.setAttribute('aria-label', 'Fermer la notification');

  header.appendChild(icon);
  header.appendChild(title);
  header.appendChild(closeBtn);

  const body = document.createElement('div');
  body.className = 'toast-body comco-toast__body';
  body.textContent = message;

  toastEl.appendChild(header);
  toastEl.appendChild(body);
  container.appendChild(toastEl);

  const toast = new window.bootstrap.Toast(toastEl, {
    delay: config.delay,
    autohide: true,
  });

  toastEl.addEventListener('hidden.bs.toast', function () {
    toastEl.remove();
  });

  toast.show();
};

document.addEventListener('livewire:init', function () {
  Livewire.on('show-toast', function (event) {
    const payload = Array.isArray(event) ? event[0] : event;
    const message = payload?.message ?? payload?.[0]?.message ?? '';
    const type = payload?.type ?? payload?.[0]?.type ?? 'info';
    window.showComcoToast(message, type);
  });

  Livewire.hook('message.processed', function (message) {
    const errors = message.response?.effects?.errors;

    if (!errors) {
      return;
    }

    const firstField = Object.keys(errors)[0];
    const firstMessage = firstField ? errors[firstField]?.[0] : null;

    if (firstMessage) {
      window.showComcoToast(firstMessage, 'danger');
    }
  });
});
