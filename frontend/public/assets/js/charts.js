/**
 * NepXMedica Charts Factory — ApexCharts reusable helpers (Phase 24 / Item 24.03)
 * All functions accept an elementId string (or DOM element) + data object + optional overrides.
 *
 * Data shapes:
 *   Line / Bar / Area : { labels: [...], series: [...] }
 *     OR multi-series : { labels: [...], series: [{ name, data }] }
 *   Donut / Pie       : { labels: [...], series: [...numbers] }
 *   Radial (gauge)    : { value: 0-100, label: 'Risk Score' }
 *   Sparkline         : { series: [...numbers] }
 */
(function (window) {
    'use strict';

    const BRAND_COLORS = ['#2962ff', '#00897b', '#e53935', '#fb8c00', '#8e24aa', '#00acc1', '#43a047', '#fdd835'];

    function el(id) {
        return typeof id === 'string' ? document.getElementById(id) : id;
    }

    function destroyIfExists(id) {
        const target = el(id);
        if (target && target._apexChartInstance) {
            target._apexChartInstance.destroy();
        }
    }

    function store(id, chart) {
        const target = el(id);
        if (target) target._apexChartInstance = chart;
        return chart;
    }

    /* ------------------------------------------------------------------
       LINE CHART
    ------------------------------------------------------------------ */
    function createLineChart(elementId, data, options = {}) {
        destroyIfExists(elementId);
        const target = el(elementId);
        if (!target || !window.ApexCharts) return null;

        const isMulti = Array.isArray(data.series) && typeof data.series[0] === 'object';
        const series  = isMulti ? data.series : [{ name: options.seriesName || 'Value', data: data.series }];

        const chart = new ApexCharts(target, {
            chart:    { type: 'line', height: options.height || 300, toolbar: { show: false }, zoom: { enabled: false } },
            series,
            xaxis:    { categories: data.labels || [], labels: { style: { fontSize: '12px' } } },
            yaxis:    { labels: { formatter: options.yFormatter || (v => v) } },
            stroke:   { curve: 'smooth', width: options.strokeWidth || 2 },
            colors:   options.colors || BRAND_COLORS,
            markers:  { size: options.markerSize ?? 4 },
            tooltip:  { y: { formatter: options.tooltipFormatter || (v => v) } },
            legend:   { position: 'top' },
            grid:     { borderColor: '#f1f1f1' },
            noData:   { text: 'No data available' },
            ...options.apex,
        });
        chart.render();
        return store(elementId, chart);
    }

    /* ------------------------------------------------------------------
       BAR CHART
    ------------------------------------------------------------------ */
    function createBarChart(elementId, data, options = {}) {
        destroyIfExists(elementId);
        const target = el(elementId);
        if (!target || !window.ApexCharts) return null;

        const isMulti = Array.isArray(data.series) && typeof data.series[0] === 'object';
        const series  = isMulti ? data.series : [{ name: options.seriesName || 'Count', data: data.series }];

        const chart = new ApexCharts(target, {
            chart:    { type: options.horizontal ? 'bar' : 'bar', height: options.height || 300, toolbar: { show: false } },
            plotOptions: { bar: { horizontal: !!options.horizontal, borderRadius: options.borderRadius || 4, columnWidth: '55%' } },
            series,
            xaxis:    { categories: data.labels || [] },
            yaxis:    { labels: { formatter: options.yFormatter || (v => v) } },
            colors:   options.colors || BRAND_COLORS,
            dataLabels: { enabled: !!options.dataLabels },
            tooltip:  { y: { formatter: options.tooltipFormatter || (v => v) } },
            grid:     { borderColor: '#f1f1f1' },
            noData:   { text: 'No data available' },
            ...options.apex,
        });
        chart.render();
        return store(elementId, chart);
    }

    /* ------------------------------------------------------------------
       AREA CHART
    ------------------------------------------------------------------ */
    function createAreaChart(elementId, data, options = {}) {
        destroyIfExists(elementId);
        const target = el(elementId);
        if (!target || !window.ApexCharts) return null;

        const isMulti = Array.isArray(data.series) && typeof data.series[0] === 'object';
        const series  = isMulti ? data.series : [{ name: options.seriesName || 'Value', data: data.series }];

        const chart = new ApexCharts(target, {
            chart:    { type: 'area', height: options.height || 300, toolbar: { show: false } },
            series,
            xaxis:    { categories: data.labels || [] },
            stroke:   { curve: 'smooth', width: 2 },
            fill:     { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
            colors:   options.colors || BRAND_COLORS,
            tooltip:  { y: { formatter: options.tooltipFormatter || (v => v) } },
            noData:   { text: 'No data available' },
            ...options.apex,
        });
        chart.render();
        return store(elementId, chart);
    }

    /* ------------------------------------------------------------------
       DONUT / PIE CHART
    ------------------------------------------------------------------ */
    function createDonutChart(elementId, data, options = {}) {
        destroyIfExists(elementId);
        const target = el(elementId);
        if (!target || !window.ApexCharts) return null;

        const chart = new ApexCharts(target, {
            chart:   { type: options.pie ? 'pie' : 'donut', height: options.height || 300, toolbar: { show: false } },
            series:  data.series || [],
            labels:  data.labels || [],
            colors:  options.colors || BRAND_COLORS,
            legend:  { position: options.legendPosition || 'bottom' },
            plotOptions: { pie: { donut: { size: options.donutSize || '65%', labels: { show: true, total: { show: true, label: options.totalLabel || 'Total' } } } } },
            tooltip: { y: { formatter: options.tooltipFormatter || (v => v) } },
            noData:  { text: 'No data available' },
            ...options.apex,
        });
        chart.render();
        return store(elementId, chart);
    }

    /* ------------------------------------------------------------------
       RADIAL BAR (gauge / risk score)
    ------------------------------------------------------------------ */
    function createRadialChart(elementId, value, options = {}) {
        destroyIfExists(elementId);
        const target = el(elementId);
        if (!target || !window.ApexCharts) return null;

        const chart = new ApexCharts(target, {
            chart:   { type: 'radialBar', height: options.height || 280, toolbar: { show: false } },
            series:  [value],
            plotOptions: {
                radialBar: {
                    startAngle: -135, endAngle: 135,
                    hollow: { size: '65%' },
                    dataLabels: {
                        name:  { show: true, offsetY: -10, fontSize: '14px', color: '#888' },
                        value: { show: true, fontSize: '28px', fontWeight: 700,
                                 formatter: options.valueFormatter || (v => v + '%') },
                    },
                },
            },
            labels:  [options.label || 'Score'],
            colors:  options.colors || ['#2962ff'],
            noData:  { text: 'No data available' },
            ...options.apex,
        });
        chart.render();
        return store(elementId, chart);
    }

    /* ------------------------------------------------------------------
       SPARKLINE (mini trend line, no axes)
    ------------------------------------------------------------------ */
    function createSparkline(elementId, data, options = {}) {
        destroyIfExists(elementId);
        const target = el(elementId);
        if (!target || !window.ApexCharts) return null;

        const series = Array.isArray(data.series) ? data.series : data;

        const chart = new ApexCharts(target, {
            chart:    { type: 'line', height: options.height || 50, sparkline: { enabled: true } },
            series:   [{ data: series }],
            stroke:   { curve: 'smooth', width: 2 },
            colors:   options.colors || ['#2962ff'],
            tooltip:  { fixed: { enabled: false }, x: { show: false }, y: { title: { formatter: () => '' } } },
            ...options.apex,
        });
        chart.render();
        return store(elementId, chart);
    }

    /* ------------------------------------------------------------------
       Update helper — update any chart's series without full re-render
    ------------------------------------------------------------------ */
    function updateChart(elementId, newSeries, newLabels) {
        const target = el(elementId);
        if (!target || !target._apexChartInstance) return;
        const c = target._apexChartInstance;
        c.updateSeries(Array.isArray(newSeries[0]) || typeof newSeries[0] === 'object' ? newSeries : [{ data: newSeries }]);
        if (newLabels) c.updateOptions({ xaxis: { categories: newLabels } });
    }

    window.PcCharts = { createLineChart, createBarChart, createAreaChart, createDonutChart, createRadialChart, createSparkline, updateChart };

}(window));
