@extends('layouts.tenant')

@section('title', 'Report Logs')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-dm">Report System Logs</h1>
                <p class="text-gray-600 mt-2">Monitor report generation and system errors</p>
            </div>
            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Log Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Log Level</label>
                <select id="logLevel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Levels</option>
                    <option value="ERROR">Error</option>
                    <option value="WARNING">Warning</option>
                    <option value="INFO">Info</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" id="dateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" id="dateTo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="flex items-end">
                <button onclick="loadLogs()" class="w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    Load Logs
                </button>
            </div>
        </div>
    </div>

    {{-- Log Display --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">System Logs</h3>
            <button onclick="clearLogs()" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors">
                Clear Display
            </button>
        </div>
        
        <div id="logsContainer" class="space-y-2 max-h-96 overflow-y-auto">
            <div class="text-center py-8 text-gray-500">
                Click "Load Logs" to view system logs
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Errors Today</p>
                    <p class="text-2xl font-semibold text-gray-900" id="errorCount">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Warnings Today</p>
                    <p class="text-2xl font-semibold text-gray-900" id="warningCount">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Reports Generated</p>
                    <p class="text-2xl font-semibold text-gray-900" id="reportCount">0</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">System Status</p>
                    <p class="text-2xl font-semibold text-green-600" id="systemStatus">Healthy</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);
    
    document.getElementById('dateFrom').value = yesterday.toISOString().split('T')[0];
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
    
    // Load initial logs
    loadLogs();
});

function loadLogs() {
    const level = document.getElementById('logLevel').value;
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;
    
    const logsContainer = document.getElementById('logsContainer');
    logsContainer.innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div></div>';
    
    fetch('/teabench1/reports/logs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            level: level,
            date_from: dateFrom,
            date_to: dateTo
        })
    })
    .then(response => response.json())
    .then(data => {
        displayLogs(data.logs);
        updateStats(data.stats);
    })
    .catch(error => {
        console.error('Error loading logs:', error);
        logsContainer.innerHTML = '<div class="text-red-600 text-center py-8">Error loading logs</div>';
    });
}

function displayLogs(logs) {
    const logsContainer = document.getElementById('logsContainer');
    
    if (logs.length === 0) {
        logsContainer.innerHTML = '<div class="text-center py-8 text-gray-500">No logs found for the selected criteria</div>';
        return;
    }
    
    let html = '';
    logs.forEach(log => {
        const levelColor = getLevelColor(log.level);
        const timestamp = new Date(log.timestamp).toLocaleString();
        
        html += `
            <div class="border border-gray-200 rounded-lg p-4 ${levelColor.bg}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full ${levelColor.text} ${levelColor.bg}">
                                ${log.level}
                            </span>
                            <span class="text-sm text-gray-500">${timestamp}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-900 mb-1">${log.message}</p>
                        ${log.data ? `<pre class="text-xs text-gray-600 bg-gray-50 p-2 rounded mt-2 overflow-x-auto">${JSON.stringify(log.data, null, 2)}</pre>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    logsContainer.innerHTML = html;
}

function getLevelColor(level) {
    switch (level) {
        case 'ERROR':
            return { bg: 'bg-red-50', text: 'text-red-800 bg-red-100' };
        case 'WARNING':
            return { bg: 'bg-yellow-50', text: 'text-yellow-800 bg-yellow-100' };
        case 'INFO':
            return { bg: 'bg-blue-50', text: 'text-blue-800 bg-blue-100' };
        default:
            return { bg: 'bg-gray-50', text: 'text-gray-800 bg-gray-100' };
    }
}

function updateStats(stats) {
    document.getElementById('errorCount').textContent = stats.errors || 0;
    document.getElementById('warningCount').textContent = stats.warnings || 0;
    document.getElementById('reportCount').textContent = stats.reports || 0;
    document.getElementById('systemStatus').textContent = stats.errors > 5 ? 'Issues' : 'Healthy';
    document.getElementById('systemStatus').className = stats.errors > 5 ? 'text-2xl font-semibold text-red-600' : 'text-2xl font-semibold text-green-600';
}

function clearLogs() {
    document.getElementById('logsContainer').innerHTML = '<div class="text-center py-8 text-gray-500">Click "Load Logs" to view system logs</div>';
}
</script>
@endsection
