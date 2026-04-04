@extends('layouts.tenant')

@section('title', 'Shift logs')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 font-dm">Shift logs</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-2">Staff who opened each shift, login and logout times, and sales for that shift</p>
            </div>
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="shiftLogsOutlet" class="block text-sm font-medium text-gray-700 mb-2">Outlet</label>
                <select id="shiftLogsOutlet" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @forelse($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                    @empty
                        <option value="">No outlets</option>
                    @endforelse
                </select>
            </div>
            <div>
                <label for="shiftLogsDateFrom" class="block text-sm font-medium text-gray-700 mb-2">From</label>
                <input type="date" id="shiftLogsDateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label for="shiftLogsDateTo" class="block text-sm font-medium text-gray-700 mb-2">To</label>
                <input type="date" id="shiftLogsDateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="flex items-end">
                <button type="button" id="shiftLogsLoadBtn" class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Load shifts
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 overflow-x-auto">
        <div id="shiftLogsStatus" class="text-sm text-gray-500 mb-4 hidden"></div>
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Logged in user</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Logged in</th>
                    <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-700">Logged out</th>
                    <th scope="col" class="px-4 py-3 text-right font-semibold text-gray-700">Shift sales</th>
                </tr>
            </thead>
            <tbody id="shiftLogsBody" class="divide-y divide-gray-200 bg-white">
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Choose a range and click Load shifts</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function () {
    const reportsBaseUrl = @json(url($tenant->slug.'/reports'));
    const csrf = document.querySelector('meta[name="csrf-token"]');

    function fmtMoney(n) {
        const v = Number(n);
        if (Number.isNaN(v)) return '₹0';
        return '₹' + v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function fmtDt(iso) {
        if (!iso) return '—';
        const d = new Date(iso);
        if (Number.isNaN(d.getTime())) return String(iso);
        return d.toLocaleString();
    }

    function escapeHtml(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date();
        const weekAgo = new Date(today);
        weekAgo.setDate(weekAgo.getDate() - 7);
        document.getElementById('shiftLogsDateFrom').value = weekAgo.toISOString().split('T')[0];
        document.getElementById('shiftLogsDateTo').value = today.toISOString().split('T')[0];

        const btn = document.getElementById('shiftLogsLoadBtn');
        const outletSel = document.getElementById('shiftLogsOutlet');
        const body = document.getElementById('shiftLogsBody');
        const status = document.getElementById('shiftLogsStatus');

        btn.addEventListener('click', async function () {
            const outletId = outletSel.value;
            const dateFrom = document.getElementById('shiftLogsDateFrom').value;
            const dateTo = document.getElementById('shiftLogsDateTo').value;

            if (!outletId) {
                body.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-amber-700">Add an outlet in settings first.</td></tr>';
                return;
            }
            if (!dateFrom || !dateTo) {
                body.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-red-600">Please select from and to dates.</td></tr>';
                return;
            }

            btn.disabled = true;
            status.classList.remove('hidden');
            status.textContent = 'Loading…';
            body.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Loading…</td></tr>';

            try {
                const res = await fetch(reportsBaseUrl + '/shift', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ outlet_id: outletId, date_from: dateFrom, date_to: dateTo })
                });

                const data = await res.json();
                if (!res.ok) {
                    const msg = data.message || data.error || ('HTTP ' + res.status);
                    throw new Error(typeof msg === 'string' ? msg : JSON.stringify(msg));
                }

                if (!Array.isArray(data) || data.length === 0) {
                    body.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No shifts in this range.</td></tr>';
                    status.textContent = '';
                    return;
                }

                status.textContent = data.length + ' shift' + (data.length === 1 ? '' : 's') + ' found';
                body.innerHTML = data.map(function (row) {
                    const user = row.opened_by != null ? escapeHtml(row.opened_by) : '—';
                    const out = row.closed_at ? escapeHtml(fmtDt(row.closed_at)) : '<span class="text-green-700 font-medium">Open</span>';
                    return (
                        '<tr class="hover:bg-gray-50">' +
                        '<td class="px-4 py-3 text-gray-900">' + user + '</td>' +
                        '<td class="px-4 py-3 text-gray-700 whitespace-nowrap">' + escapeHtml(fmtDt(row.opened_at)) + '</td>' +
                        '<td class="px-4 py-3 text-gray-700 whitespace-nowrap">' + out + '</td>' +
                        '<td class="px-4 py-3 text-right font-medium text-gray-900 tabular-nums">' + fmtMoney(row.total_sales) + '</td>' +
                        '</tr>'
                    );
                }).join('');
            } catch (e) {
                console.error(e);
                body.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-red-600">Could not load shifts. ' + (e.message || '') + '</td></tr>';
                status.textContent = '';
            } finally {
                btn.disabled = false;
            }
        });
    });
})();
</script>
@endsection
