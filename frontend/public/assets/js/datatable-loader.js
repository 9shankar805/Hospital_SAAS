/**
 * NepXMedica DataTable Loader — reusable DataTables.js helper (Phase 24 / Item 24.05)
 *
 * Usage:
 *   PcDataTable.load('myTableId', '/api/v1/patients', [
 *     { title: 'Name',   data: 'name' },
 *     { title: 'Email',  data: 'email' },
 *     { title: 'Status', data: 'status', render: (v) => `<span class="badge bg-success">${v}</span>` },
 *     { title: 'Action', data: null, render: (_, __, row) => `<a href="detail.html?id=${row.id}">View</a>` },
 *   ], options);
 *
 * options (all optional):
 *   pageLength  : rows per page (default 15)
 *   order       : [[colIndex, 'asc'|'desc']] (default [[0,'asc']])
 *   exportCsv   : true — adds CSV export button
 *   exportExcel : true — adds Excel export button
 *   serverSide  : true — enables DataTables server-side processing
 *   searchable  : false — hides the search box
 *   onRowClick  : function(rowData) — callback when a row is clicked
 *   rowCallback : function(row, data) — raw DataTables rowCallback
 */
(function (window) {
    'use strict';

    // Registry of initialised instances keyed by tableId
    const _instances = {};

    /**
     * Fetch data from a NepXMedica paginated API endpoint and return flat array.
     * Handles both { data: [...] } and { data: { data: [...] } } shapes.
     */
    async function fetchAll(apiUrl) {
        const token = window.PcApi ? window.PcApi.getToken() : localStorage.getItem('auth_token');
        const headers = { Accept: 'application/json' };
        if (token) headers['Authorization'] = 'Bearer ' + token;

        const res  = await fetch(apiUrl, { headers });
        const json = await res.json();

        // Paginated response: { data: { data: [...], total: N } }
        if (json.data && json.data.data) return json.data.data;
        // Flat list: { data: [...] }
        if (Array.isArray(json.data)) return json.data;
        return [];
    }

    /**
     * Initialise (or re-initialise) a DataTable on the given element.
     * Returns the DataTables API instance.
     */
    async function load(tableId, apiUrl, columns, options = {}) {
        const tableEl = document.getElementById(tableId);
        if (!tableEl) { console.warn(`PcDataTable: #${tableId} not found`); return null; }

        // Destroy existing instance to allow re-init
        if (_instances[tableId]) {
            _instances[tableId].destroy();
            delete _instances[tableId];
        }

        // Show inline skeleton loader
        const tbody = tableEl.querySelector('tbody') || tableEl.createTBody();
        tbody.innerHTML = `<tr><td colspan="${columns.length}" class="text-center py-4">
            <span class="spinner-border spinner-border-sm me-2"></span>Loading data…</td></tr>`;

        let rows = [];
        try {
            rows = await fetchAll(apiUrl);
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="${columns.length}" class="text-center text-danger py-4">
                Failed to load data. <a href="#" onclick="location.reload()">Retry</a></td></tr>`;
            console.error('PcDataTable fetch error:', err);
            return null;
        }

        // Build export buttons config
        const exportButtons = [];
        if (options.exportCsv !== false) {
            exportButtons.push({ extend: 'csv', text: '<i class="ti ti-file-text me-1"></i>CSV', className: 'btn btn-sm btn-outline-secondary' });
        }
        if (options.exportExcel) {
            exportButtons.push({ extend: 'excel', text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel', className: 'btn btn-sm btn-outline-success' });
        }

        const dtOptions = {
            data:        rows,
            columns:     columns.map(c => ({
                title:   c.title,
                data:    c.data,
                render:  c.render || null,
                orderable: c.orderable !== false,
                searchable: c.searchable !== false,
            })),
            pageLength:  options.pageLength || 15,
            lengthMenu:  [[10, 15, 25, 50, -1], [10, 15, 25, 50, 'All']],
            order:       options.order || [[0, 'asc']],
            responsive:  true,
            language: {
                search:           '<i class="ti ti-search"></i>',
                searchPlaceholder: 'Search…',
                emptyTable:        'No records found',
                zeroRecords:       'No matching records',
                paginate: { previous: '‹', next: '›' },
            },
            dom: exportButtons.length
                ? '<"d-flex justify-content-between align-items-center mb-3"B<"ms-auto"f>>rtip'
                : '<"d-flex justify-content-between align-items-center mb-3"<"ms-auto"f>>rtip',
            buttons: exportButtons,
            searching:   options.searchable !== false,
            rowCallback: function (row, data) {
                if (options.onRowClick) {
                    row.style.cursor = 'pointer';
                    row.addEventListener('click', () => options.onRowClick(data));
                }
                if (options.rowCallback) options.rowCallback(row, data);
            },
        };

        // Only add DataTables.Buttons if library present
        if (!exportButtons.length) delete dtOptions.buttons;

        const dt = window.$ && window.$.fn && window.$.fn.DataTable
            ? window.$(tableEl).DataTable(dtOptions)
            : null;

        if (!dt) {
            console.warn('PcDataTable: jQuery DataTables not loaded. Falling back to plain table render.');
            _renderPlain(tableEl, columns, rows);
            return null;
        }

        _instances[tableId] = dt;
        return dt;
    }

    /**
     * Reload a table's data from its original API URL.
     * You must have initialised it with load() first.
     */
    async function reload(tableId, apiUrl, options = {}) {
        const dt = _instances[tableId];
        if (!dt) return;
        const rows = await fetchAll(apiUrl).catch(() => []);
        dt.clear();
        dt.rows.add(rows);
        dt.draw();
    }

    /**
     * Plain-HTML fallback when DataTables JS is absent (e.g. in unit tests).
     */
    function _renderPlain(tableEl, columns, rows) {
        let thead = tableEl.querySelector('thead');
        if (!thead) { thead = tableEl.createTHead(); }
        thead.innerHTML = '<tr>' + columns.map(c => `<th>${c.title}</th>`).join('') + '</tr>';

        const tbody = tableEl.querySelector('tbody') || tableEl.createTBody();
        if (!rows.length) {
            tbody.innerHTML = `<tr><td colspan="${columns.length}" class="text-center py-3">No records found</td></tr>`;
            return;
        }
        tbody.innerHTML = rows.map(row =>
            '<tr>' + columns.map(c => {
                const val = c.data ? row[c.data] : '';
                return '<td>' + (c.render ? c.render(val, 'display', row) : (val ?? '')) + '</td>';
            }).join('') + '</tr>'
        ).join('');
    }

    window.PcDataTable = { load, reload };

}(window));
