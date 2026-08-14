/**
 * NepXMedica Offcanvas Form Helper — generic open/close + POST/PUT (Phase 24 / Item 24.07)
 *
 * Usage — open for CREATE:
 *   PcOffcanvas.open('patientOffcanvas', {
 *     title:    'Add New Patient',
 *     action:   'POST',
 *     endpoint: '/api/v1/patients',
 *     onSuccess: (data) => { PcToast.success('Patient created!'); myTable.reload(); },
 *   });
 *
 * Usage — open for EDIT (pre-fills form from object):
 *   PcOffcanvas.open('patientOffcanvas', {
 *     title:    'Edit Patient',
 *     action:   'PUT',
 *     endpoint: `/api/v1/patients/${id}`,
 *     prefill:  patientObject,   // key → value → sets matching [name] input
 *     onSuccess: (data) => { PcToast.success('Patient updated!'); },
 *   });
 *
 * Expects offcanvas HTML to have:
 *   - <div id="patientOffcanvas" class="offcanvas offcanvas-end"> … </div>
 *   - A <form id="patientOffcanvasForm"> inside it  (any id, detected automatically)
 *   - A <h5 class="offcanvas-title"> for the dynamic title
 *   - A submit button inside the form
 */
(function (window) {
    'use strict';

    // Active instances: offcanvasId → { bsInstance, opts }
    const _active = {};

    /**
     * Open an offcanvas and configure it for a form action.
     * @param {string} offcanvasId  — id of the .offcanvas element
     * @param {object} opts
     */
    function open(offcanvasId, opts = {}) {
        const panelEl = document.getElementById(offcanvasId);
        if (!panelEl) { console.warn('PcOffcanvas: element not found:', offcanvasId); return; }

        // Set title
        const titleEl = panelEl.querySelector('.offcanvas-title');
        if (titleEl && opts.title) titleEl.textContent = opts.title;

        // Find form (first <form> inside the panel)
        const formEl = panelEl.querySelector('form');
        if (!formEl) { console.warn('PcOffcanvas: no <form> found inside', offcanvasId); return; }

        // Reset form and clear previous errors
        formEl.reset();
        clearErrors(formEl);

        // Pre-fill for edit
        if (opts.prefill && typeof opts.prefill === 'object') {
            Object.entries(opts.prefill).forEach(([key, val]) => {
                const f = formEl.querySelector(`[name="${key}"]`);
                if (!f) return;
                if (f.type === 'checkbox') { f.checked = !!val; }
                else if (f.tagName === 'SELECT' && window.$ && window.$.fn.select2) {
                    window.$(f).val(val).trigger('change');
                } else {
                    f.value = val ?? '';
                }
            });
        }

        // Remove any previous submit listener to avoid stacking
        const newForm = formEl.cloneNode(true);
        formEl.replaceWith(newForm);

        newForm.addEventListener('submit', e => _handleSubmit(e, newForm, opts, offcanvasId));

        // Open via Bootstrap Offcanvas API
        let bsInst = _active[offcanvasId]?.bsInstance;
        if (!bsInst && window.bootstrap?.Offcanvas) {
            bsInst = new window.bootstrap.Offcanvas(panelEl);
        }
        if (bsInst) {
            _active[offcanvasId] = { bsInstance: bsInst, opts };
            bsInst.show();
        } else {
            // Fallback: toggle Bootstrap classes manually
            panelEl.classList.add('show');
            panelEl.style.visibility = 'visible';
        }
    }

    /**
     * Close an offcanvas programmatically.
     */
    function close(offcanvasId) {
        const panelEl = document.getElementById(offcanvasId);
        if (!panelEl) return;
        const inst = _active[offcanvasId]?.bsInstance;
        if (inst) { inst.hide(); }
        else {
            panelEl.classList.remove('show');
            panelEl.style.visibility = 'hidden';
        }
    }

    /* ----------------------------------------------------------------
       Internal submit handler
    ---------------------------------------------------------------- */
    async function _handleSubmit(e, formEl, opts, offcanvasId) {
        e.preventDefault();
        clearErrors(formEl);

        const submitBtn = formEl.querySelector('[type="submit"]');
        const origText  = submitBtn?.innerHTML || 'Save';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';
        }

        // Collect form data
        let payload;
        const hasFile = [...formEl.elements].some(f => f.type === 'file' && f.files?.length);
        if (hasFile) {
            payload = new FormData(formEl);
            if (opts.action === 'PUT') payload.append('_method', 'PUT');
        } else {
            payload = Object.fromEntries(new FormData(formEl).entries());
        }

        const method   = opts.action || 'POST';
        const endpoint = opts.endpoint || '';

        try {
            let result;
            if (window.PcApi) {
                result = method === 'PUT'
                    ? await window.PcApi.put(endpoint, payload)
                    : await window.PcApi.post(endpoint, payload);
            } else {
                // Bare fetch fallback
                const token = localStorage.getItem('auth_token') || '';
                const headers = { Accept: 'application/json', Authorization: 'Bearer ' + token };
                let body;
                if (hasFile) { body = payload; }
                else { headers['Content-Type'] = 'application/json'; body = JSON.stringify(payload); }

                const res  = await fetch((window.API_BASE || '/api/v1') + endpoint, { method, headers, body });
                const json = await res.json();
                if (!res.ok) {
                    if (json.errors) { showErrors(formEl, json.errors); }
                    throw new Error(json.message || 'Request failed');
                }
                result = json;
            }

            close(offcanvasId);
            if (opts.onSuccess) opts.onSuccess(result?.data || result);

        } catch (err) {
            if (err.errors) showErrors(formEl, err.errors);
            if (window.PcToast) window.PcToast.error(err.message || 'An error occurred. Please try again.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origText;
            }
        }
    }

    /* ----------------------------------------------------------------
       Inline validation error helpers
    ---------------------------------------------------------------- */
    function showErrors(formEl, errors) {
        Object.entries(errors).forEach(([field, messages]) => {
            const input = formEl.querySelector(`[name="${field}"]`);
            if (!input) return;
            input.classList.add('is-invalid');
            let fb = input.nextElementSibling;
            if (!fb || !fb.classList.contains('invalid-feedback')) {
                fb = document.createElement('div');
                fb.className = 'invalid-feedback';
                input.after(fb);
            }
            fb.textContent = Array.isArray(messages) ? messages[0] : messages;
        });
    }

    function clearErrors(formEl) {
        formEl.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        formEl.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    window.PcOffcanvas = { open, close, showErrors, clearErrors };

}(window));
