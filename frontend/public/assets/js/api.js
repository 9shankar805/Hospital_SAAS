/**
 * NepXMedica API Layer — Base fetch wrapper (Phase 24 / Item 24.01)
 * Handles: auth token injection, unified error toasts, loading states,
 * response unwrapping.  All pages import this before their own scripts.
 */
(function (window) {
    'use strict';

    const API_BASE = window.API_BASE || '/api/v1';

    /* ---------------------------------------------------------------
       Token helpers
    --------------------------------------------------------------- */
    function getToken() {
        return localStorage.getItem('auth_token') || '';
    }

    function setToken(token) {
        localStorage.setItem('auth_token', token);
    }

    function clearToken() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user_role');
        localStorage.removeItem('user_name');
        localStorage.removeItem('user_avatar');
    }

    /* ---------------------------------------------------------------
       Loading spinner
    --------------------------------------------------------------- */
    function showSpinner() {
        let el = document.getElementById('pc-global-spinner');
        if (!el) {
            el = document.createElement('div');
            el.id = 'pc-global-spinner';
            el.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading…</span></div>';
            Object.assign(el.style, {
                position: 'fixed', top: '50%', left: '50%',
                transform: 'translate(-50%,-50%)', zIndex: 9999,
                background: 'rgba(255,255,255,0.8)', padding: '1.5rem',
                borderRadius: '0.5rem', display: 'none',
            });
            document.body.appendChild(el);
        }
        el.style.display = 'block';
    }

    function hideSpinner() {
        const el = document.getElementById('pc-global-spinner');
        if (el) el.style.display = 'none';
    }

    /* ---------------------------------------------------------------
       Core request function
    --------------------------------------------------------------- */
    async function request(method, path, body, opts = {}) {
        const url = path.startsWith('http') ? path : API_BASE + path;
        const token = getToken();

        const headers = { 'Accept': 'application/json' };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        let fetchOpts = { method, headers };

        if (body && !(body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
            fetchOpts.body = JSON.stringify(body);
        } else if (body instanceof FormData) {
            fetchOpts.body = body; // let browser set multipart boundary
        }

        if (!opts.silent) showSpinner();

        let response;
        try {
            response = await fetch(url, fetchOpts);
        } catch (networkErr) {
            hideSpinner();
            if (window.PcToast) window.PcToast.error('Network error — please check your connection.');
            throw networkErr;
        }

        hideSpinner();

        // Handle 401 — force logout
        if (response.status === 401) {
            clearToken();
            window.location.href = 'login.html';
            return;
        }

        const data = await response.json().catch(() => ({}));

        // Handle 422 validation errors
        if (response.status === 422) {
            const msg = data.message || 'Validation failed.';
            if (window.PcToast) window.PcToast.error(msg);
            const err = new Error(msg);
            err.errors = data.errors || {};
            err.status = 422;
            throw err;
        }

        // Handle 4xx / 5xx
        if (!response.ok) {
            const msg = data.message || `Server error (${response.status})`;
            if (!opts.silent && window.PcToast) window.PcToast.error(msg);
            const err = new Error(msg);
            err.status = response.status;
            throw err;
        }

        // Unwrap: return data.data if present, else full response object
        return data.data !== undefined ? data : data;
    }

    /* ---------------------------------------------------------------
       Public API
    --------------------------------------------------------------- */
    const PcApi = {
        get:    (path, opts)         => request('GET',    path, null, opts),
        post:   (path, body, opts)   => request('POST',   path, body, opts),
        put:    (path, body, opts)   => request('PUT',    path, body, opts),
        patch:  (path, body, opts)   => request('PATCH',  path, body, opts),
        delete: (path, opts)         => request('DELETE', path, null, opts),

        // Auth helpers
        setToken,
        getToken,
        clearToken,

        // NPR currency formatter
        formatNPR(amount) {
            const num = parseFloat(amount) || 0;
            return 'NPR ' + num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        // Boot: populate topbar user info from /me
        async bootUser() {
            const token = getToken();
            if (!token) return;
            try {
                const res = await this.get('/me', { silent: true });
                const u = res.user || res;
                if (u && u.name) {
                    document.querySelectorAll('.user-name, .header-user-name').forEach(el => { el.textContent = u.name; });
                    document.querySelectorAll('.user-role, .header-user-role').forEach(el => { el.textContent = u.role || ''; });
                    document.querySelectorAll('.user-avatar, .header-user-img').forEach(el => { if (u.avatar) el.src = u.avatar; });
                    localStorage.setItem('user_name',   u.name);
                    localStorage.setItem('user_role',   u.role || '');
                    localStorage.setItem('user_avatar', u.avatar || '');
                }
            } catch (_) { /* silent */ }
        },

        // Boot: populate topbar notification bell
        async bootNotifications() {
            const token = getToken();
            if (!token) return;
            try {
                const res = await this.get('/notifications', { silent: true });
                const count = res.unread_count ?? 0;
                document.querySelectorAll('.notification-badge, .noti-count').forEach(el => {
                    el.textContent = count;
                    el.style.display = count > 0 ? 'inline-block' : 'none';
                });
            } catch (_) { /* silent */ }
        },
    };

    window.PcApi = PcApi;

    // Auto-boot on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        PcApi.bootUser();
        PcApi.bootNotifications();
    });

}(window));
