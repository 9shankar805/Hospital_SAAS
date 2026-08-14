/**
 * NepXMedica Toast Helper — Bootstrap 5 toast wrapper (Phase 24 / Item 24.04)
 * Usage:
 *   PcToast.success('Saved successfully');
 *   PcToast.error('Something went wrong');
 *   PcToast.warning('Check your input');
 *   PcToast.info('Processing request…');
 */
(function (window) {
    'use strict';

    /* Inject container once */
    function getContainer() {
        let c = document.getElementById('pc-toast-container');
        if (!c) {
            c = document.createElement('div');
            c.id = 'pc-toast-container';
            c.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            c.style.zIndex = '11000';
            document.body.appendChild(c);
        }
        return c;
    }

    const ICONS = {
        success: '<i class="ti ti-circle-check text-success me-2"></i>',
        error:   '<i class="ti ti-circle-x text-danger me-2"></i>',
        warning: '<i class="ti ti-alert-triangle text-warning me-2"></i>',
        info:    '<i class="ti ti-info-circle text-info me-2"></i>',
    };

    const HEADER_CLASSES = {
        success: 'bg-success text-white',
        error:   'bg-danger text-white',
        warning: 'bg-warning text-dark',
        info:    'bg-info text-white',
    };

    function show(message, type, duration) {
        type     = type || 'info';
        duration = duration || 4000;

        const container = getContainer();
        const id = 'toast-' + Date.now();

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div id="${id}" class="toast align-items-center border-0 shadow-sm" role="alert"
                 aria-live="assertive" aria-atomic="true" data-bs-delay="${duration}">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        ${ICONS[type] || ICONS.info}
                        <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>`;

        const toastEl = wrapper.firstElementChild;
        // Colour the left border to indicate type
        toastEl.style.borderLeft = `4px solid var(--bs-${type === 'error' ? 'danger' : type})`;
        container.appendChild(toastEl);

        if (window.bootstrap && window.bootstrap.Toast) {
            const bsToast = new window.bootstrap.Toast(toastEl, { delay: duration });
            bsToast.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        } else {
            // Fallback without Bootstrap JS
            toastEl.style.cssText += 'display:block;opacity:1;min-width:280px;background:#fff;padding:12px 16px;border-radius:6px;margin-bottom:8px;';
            setTimeout(() => toastEl.remove(), duration);
        }
    }

    window.PcToast = {
        show,
        success: (msg, ms) => show(msg, 'success', ms),
        error:   (msg, ms) => show(msg, 'error',   ms),
        warning: (msg, ms) => show(msg, 'warning',  ms),
        info:    (msg, ms) => show(msg, 'info',     ms),
    };

}(window));
