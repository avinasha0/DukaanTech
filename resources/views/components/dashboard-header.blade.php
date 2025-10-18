{{-- resources/views/components/dashboard-header.blade.php --}}
<div class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-3">
            <img src="/images/logos/dukaantech-logo.png" alt="Dukaantech Logo" class="h-10 w-auto">
        </a>

        <nav class="hidden md:flex items-center gap-8">
            @php
                $tenant = app('tenant') ?? (isset($tenant) ? $tenant : null);
            @endphp
            @if($tenant)
                <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug]) }}" class="text-orange-600 font-semibold">Dashboard</a>
                <a href="{{ route('tenant.pos.terminal', ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">POS Terminal</a>
                <a href="{{ route('tenant.kot.public', ['tenant' => $tenant->slug]) }}" target="_blank" class="text-gray-700 hover:text-orange-600 transition-colors">KOT</a>
            @else
                <a href="/dashboard" class="text-orange-600 font-semibold">Dashboard</a>
                <a href="#" class="text-gray-700 hover:text-orange-600 transition-colors">POS Terminal</a>
                <a href="#" class="text-gray-700 hover:text-orange-600 transition-colors">KOT</a>
            @endif
        </nav>

        <div class="flex items-center gap-4">
            @php
                $tenant = app('tenant');
                $currentPlan = $tenant ? $tenant->plan : 'free';
                $planColors = [
                    'free' => 'bg-gray-100 text-gray-800 border-gray-200',
                    'professional' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'enterprise' => 'bg-purple-100 text-purple-800 border-purple-200'
                ];
                $planLabels = [
                    'free' => 'Free Plan',
                    'professional' => 'Professional',
                    'enterprise' => 'Enterprise'
                ];
            @endphp
            
            @if($tenant)
                <div class="px-3 py-1 rounded-full border text-xs font-medium {{ $planColors[$currentPlan] ?? $planColors['free'] }}">
                    {{ $planLabels[$currentPlan] ?? 'Free Plan' }}
                </div>
            @endif
            
            <div class="text-sm text-gray-600">
                <span class="font-medium">Login Details:</span> {{ Auth::user()->name }}
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-gray-700 hover:text-orange-600 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
