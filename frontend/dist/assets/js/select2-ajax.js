/**
 * NepXMedica Select2 AJAX Helper — reusable search dropdowns (Phase 24 / Item 24.06)
 *
 * Usage:
 *   // Patient search dropdown
 *   PcSelect2.init('#patientSelect', {
 *     url:         '/api/v1/patients',
 *     queryParam:  'q',          // URL param for search term
 *     valueKey:    'id',         // property used as option value
 *     labelKey:    'name',       // property used as option label
 *     placeholder: 'Search patient…',
 *     extraLabel:  row => `${row.name} (${row.patient_code})`,  // optional richer label
 *   });
 *
 *   // Doctor dropdown (no AJAX — small static list)
 *   PcSelect2.initStatic('#doctorSelect', doctors, {
 *     valueKey: 'id', labelKey: 'name', placeholder: 'Select doctor'
 *   });
 *
 *   // Get selected value
 *   PcSelect2.getValue('#patientSelect');  // → '42'
 */
(function (window) {
    'use strict';

    function getToken() {
        return (window.PcApi && window.PcApi.getToken())
            || localStorage.getItem('auth_token') || '';
    }

    /**
     * Initialise Select2 with AJAX source backed by a NepXMedica API endpoint.
     */
    function init(selector, opts = {}) {
        const el = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!el) return;

        // If jQuery + Select2 are available use them (preferred)
        if (window.$ && window.$.fn && window.$.fn.select2) {
            window.$(el).select2({
                placeholder:    opts.placeholder || 'Search…',
                allowClear:     opts.allowClear !== false,
                minimumInputLength: opts.minLength ?? 0,
                width:          opts.width || '100%',
                ajax: {
                    url:   (window.API_BASE || '/api/v1') + (opts.url || ''),
                    type:  'GET',
                    delay: opts.delay || 250,
                    headers: { Authorization: 'Bearer ' + getToken() },
                    data: params => ({ [opts.queryParam || 'q']: params.term, per_page: opts.perPage || 15 }),
                    processResults: response => {
                        const items = response.data?.data || response.data || [];
                        return {
                            results: items.map(row => ({
                                id:   row[opts.valueKey || 'id'],
                                text: opts.extraLabel ? opts.extraLabel(row) : row[opts.labelKey || 'name'],
                                _raw: row,
                            })),
                        };
                    },
                },
                templateResult:    opts.templateResult    || null,
                templateSelection: opts.templateSelection || null,
            });

            if (opts.onChange) {
                window.$(el).on('select2:select', e => opts.onChange(e.params.data._raw, e.params.data.id));
            }
            return;
        }

        // Fallback: plain native <datalist> + <input> if Select2 unavailable
        console.warn('PcSelect2: Select2/jQuery not loaded. Using native datalist fallback for', selector);
        _nativeFallback(el, opts);
    }

    /**
     * Initialise Select2 from a static array (no AJAX).
     */
    function initStatic(selector, items, opts = {}) {
        const el = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!el) return;

        if (window.$ && window.$.fn && window.$.fn.select2) {
            const data = items.map(row => ({
                id:   row[opts.valueKey || 'id'],
                text: opts.extraLabel ? opts.extraLabel(row) : row[opts.labelKey || 'name'],
                _raw: row,
            }));

            window.$(el).empty().select2({
                placeholder: opts.placeholder || 'Select…',
                allowClear:  opts.allowClear !== false,
                width:       opts.width || '100%',
                data,
            });

            if (opts.onChange) {
                window.$(el).on('select2:select', e => opts.onChange(e.params.data._raw, e.params.data.id));
            }
            return;
        }

        // Native fallback
        el.innerHTML = `<option value="">-- ${opts.placeholder || 'Select'} --</option>` +
            items.map(row => `<option value="${row[opts.valueKey || 'id']}">${opts.extraLabel ? opts.extraLabel(row) : row[opts.labelKey || 'name']}</option>`).join('');
    }

    /**
     * Get the currently selected value (works with both Select2 and native).
     */
    function getValue(selector) {
        const el = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!el) return null;
        if (window.$ && window.$.fn && window.$.fn.select2) {
            return window.$(el).val();
        }
        return el.value || null;
    }

    /**
     * Set a value programmatically.
     */
    function setValue(selector, value, label) {
        const el = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!el) return;
        if (window.$ && window.$.fn && window.$.fn.select2) {
            const $el = window.$(el);
            // If option doesn't exist yet, create it
            if (!$el.find(`option[value="${value}"]`).length && label) {
                $el.append(new Option(label, value, true, true));
            }
            $el.val(value).trigger('change');
            return;
        }
        el.value = value;
    }

    /**
     * Destroy a Select2 instance.
     */
    function destroy(selector) {
        const el = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (!el) return;
        if (window.$ && window.$.fn && window.$.fn.select2) {
            try { window.$(el).select2('destroy'); } catch (_) {}
        }
    }

    // ---- internal ----
    function _nativeFallback(el, opts) {
        const listId = 'pc-dl-' + Math.random().toString(36).slice(2);
        const dl = document.createElement('datalist');
        dl.id = listId;
        el.setAttribute('list', listId);
        el.setAttribute('placeholder', opts.placeholder || 'Search…');
        el.after(dl);

        let timer;
        el.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(async () => {
                if (el.value.length < (opts.minLength ?? 2)) return;
                const token = getToken();
                const url = (window.API_BASE || '/api/v1') + (opts.url || '') + '?' + (opts.queryParam || 'q') + '=' + encodeURIComponent(el.value);
                const res  = await fetch(url, { headers: { Authorization: 'Bearer ' + token, Accept: 'application/json' } });
                const json = await res.json();
                const items = json.data?.data || json.data || [];
                dl.innerHTML = items.map(row =>
                    `<option value="${opts.extraLabel ? opts.extraLabel(row) : row[opts.labelKey || 'name']}" data-id="${row[opts.valueKey || 'id']}"></option>`
                ).join('');
            }, opts.delay || 300);
        });
    }

    window.PcSelect2 = { init, initStatic, getValue, setValue, destroy };

}(window));
