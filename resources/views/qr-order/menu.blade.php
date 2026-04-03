<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $account->name }} — Order</title>
    <meta name="description" content="Order from {{ $account->name }} — browse the menu and place your order.">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#5b21b6">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#5b21b6', light: '#7c3aed', dark: '#4c1d95' },
                        accent: '#0d9488',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased pb-36">
@if(!$defaultOutlet)
    <div class="min-h-screen flex items-center justify-center p-6">
        <p class="text-red-600 text-center font-medium">No outlet is configured for this restaurant. Please contact the staff.</p>
    </div>
@else
    <div id="app" class="min-h-screen max-w-lg mx-auto sm:max-w-2xl lg:max-w-4xl">
        <!-- Header -->
        <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200 shadow-sm">
            <div class="px-4 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <h1 class="text-lg sm:text-xl font-bold text-brand truncate">{{ $account->name }}</h1>
                    <p class="text-xs text-slate-500 truncate">
                        @if($fromTableQr ?? false)
                            Table <span class="font-semibold text-slate-700">{{ $tableNoParam }}</span>
                            · {{ $defaultOutlet->name }}
                        @else
                            {{ $defaultOutlet->name }}
                        @endif
                    </p>
                </div>
                @if(!($orderTypes->count()))
                    <span class="text-xs text-amber-600 shrink-0">Setup order types in admin</span>
                @endif
            </div>
        </header>

        <!-- Browse: full menu -->
        <div id="step-browse" class="px-4 pt-4">
            <div class="mb-4">
                <input type="search" id="menuSearch" placeholder="Search menu…" autocomplete="off"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus:ring-2 focus:ring-brand focus:border-transparent outline-none">
            </div>

            @forelse($categories as $category)
                @continue($category->items->isEmpty())
                <section id="category-{{ $category->id }}" class="mb-8 scroll-mt-24">
                    <h2 class="text-sm font-bold uppercase tracking-wide text-slate-500 mb-3 px-1">{{ $category->name }}</h2>
                    <div class="space-y-2">
                        @foreach($category->items as $item)
                            <div class="menu-item-row flex items-center gap-3 rounded-2xl bg-white border border-slate-100 p-3 shadow-sm hover:shadow-md transition-shadow"
                                 data-search="{{ strtolower($item->name.' '.$category->name) }}"
                                 id="item-{{ $item->id }}">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 leading-tight">{{ $item->name }}</p>
                                    @if($item->description)
                                        <p class="text-xs text-slate-500 line-clamp-2 mt-0.5">{{ $item->description }}</p>
                                    @endif
                                    <p class="text-accent font-bold mt-1">₹{{ number_format((float) $item->price, 2) }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <button type="button" class="qty-btn w-9 h-9 rounded-full bg-slate-100 text-slate-700 font-bold text-lg leading-none hover:bg-slate-200"
                                            onclick="updateQty({{ $item->id }}, -1)">−</button>
                                    <span class="w-8 text-center font-semibold text-sm" id="qty-{{ $item->id }}">0</span>
                                    <button type="button" class="qty-btn w-9 h-9 rounded-full bg-brand text-white font-bold text-lg leading-none hover:bg-brand-dark"
                                            onclick="updateQty({{ $item->id }}, 1)">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @empty
                <p class="text-center text-slate-500 py-12">No menu items available yet.</p>
            @endforelse
        </div>

        <!-- Checkout panel (slides over) -->
        <div id="step-checkout" class="hidden fixed inset-0 z-40 bg-white overflow-y-auto">
            <div class="max-w-lg mx-auto px-4 py-4 pb-28">
                <div class="flex items-center gap-2 mb-6">
                    <button type="button" onclick="goBrowse()" class="text-brand font-medium text-sm">← Menu</button>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-1">Almost there</h2>
                <p class="text-sm text-slate-500 mb-6">Order type, your details, and any comments for the kitchen.</p>

                <form id="checkoutForm" class="space-y-5" onsubmit="return false;">
                    <input type="hidden" name="outlet_id" value="{{ $defaultOutlet->id }}">
                    <input type="hidden" name="payment_method" value="cash">
                    <input type="hidden" name="mode" id="input_mode" value="DINE_IN">
                    @if(!empty($fromTableQr) && !empty($tableNoParam))
                        <input type="hidden" name="table_no" id="input_table_no" value="{{ $tableNoParam }}">
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Order type <span class="text-red-500">*</span></label>
                        <select name="order_type_id" id="order_type_id" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-brand outline-none">
                            <option value="">Choose…</option>
                            @foreach($orderTypes as $ot)
                                <option value="{{ $ot->id }}" data-qr-mode="{{ $ot->qr_mode }}">{{ $ot->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1.5" id="mode-hint"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Your name</label>
                        <input type="text" name="customer_name" id="customer_name" placeholder="Name"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-2 focus:ring-brand outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Phone number <span class="text-red-500">*</span></label>
                        <input type="tel" name="customer_phone" id="customer_phone" required placeholder="Mobile number"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-2 focus:ring-brand outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Comments</label>
                        <textarea name="special_instructions" id="special_instructions" rows="3" placeholder="Allergies, spice level, notes…"
                                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-2 focus:ring-brand outline-none resize-none"></textarea>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sticky cart bar -->
        <div id="cart-bar" class="fixed bottom-0 left-0 right-0 z-50 hidden safe-pb">
            <div class="max-w-lg mx-auto sm:max-w-2xl lg:max-w-4xl px-3 pb-3 pt-2 bg-gradient-to-t from-slate-100 to-transparent">
                <div class="rounded-2xl bg-slate-900 text-white shadow-xl px-4 py-3 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs text-slate-400"><span id="cart-count">0</span> items</p>
                        <p class="text-lg font-bold">₹<span id="cart-total">0</span></p>
                    </div>
                    <button type="button" id="btn-continue" onclick="goCheckout()"
                            class="shrink-0 rounded-xl bg-accent px-5 py-3 text-sm font-bold text-white hover:opacity-95 active:scale-[0.98] transition">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const tenantSlug = @json($account->slug);
        const itemsMeta = {
            @foreach($categories as $cat)
                @foreach($cat->items as $item)
                    {{ $item->id }}: { name: @json($item->name), price: {{ (float) $item->price }} },
                @endforeach
            @endforeach
        };

        let quantities = {};

        function getCartLines() {
            const lines = [];
            for (const id of Object.keys(quantities)) {
                const q = quantities[id];
                if (q > 0 && itemsMeta[id]) {
                    lines.push({ item_id: parseInt(id, 10), qty: q, price: itemsMeta[id].price, name: itemsMeta[id].name });
                }
            }
            return lines;
        }

        function updateQty(itemId, delta) {
            const cur = quantities[itemId] || 0;
            const next = Math.max(0, cur + delta);
            if (next === 0) {
                delete quantities[itemId];
            } else {
                quantities[itemId] = next;
            }
            const el = document.getElementById('qty-' + itemId);
            if (el) el.textContent = quantities[itemId] || 0;
            syncCartBar();
        }

        function syncCartBar() {
            const lines = getCartLines();
            const bar = document.getElementById('cart-bar');
            const countEl = document.getElementById('cart-count');
            const totalEl = document.getElementById('cart-total');
            if (lines.length === 0) {
                bar.classList.add('hidden');
                return;
            }
            bar.classList.remove('hidden');
            let total = 0;
            let n = 0;
            lines.forEach(l => {
                total += l.price * l.qty;
                n += l.qty;
            });
            countEl.textContent = n;
            totalEl.textContent = total.toFixed(0);
        }

        function goCheckout() {
            @if(($orderTypes->count() ?? 0) < 1)
            alert('Order types are not set up yet. Please ask staff for help.');
            return;
            @endif
            const lines = getCartLines();
            if (lines.length === 0) return;
            document.getElementById('step-browse').classList.add('hidden');
            document.getElementById('cart-bar').classList.add('hidden');
            document.getElementById('step-checkout').classList.remove('hidden');
            window.scrollTo(0, 0);
            syncModeFromOrderType();
        }

        function goBrowse() {
            document.getElementById('step-checkout').classList.add('hidden');
            document.getElementById('step-browse').classList.remove('hidden');
            syncCartBar();
        }

        function syncModeFromOrderType() {
            const sel = document.getElementById('order_type_id');
            const opt = sel.options[sel.selectedIndex];
            const mode = opt && opt.getAttribute('data-qr-mode');
            const inputMode = document.getElementById('input_mode');
            const hint = document.getElementById('mode-hint');
            if (mode && inputMode) {
                inputMode.value = mode === 'PICKUP' ? 'TAKEAWAY' : mode;
            }
            if (hint) {
                if (inputMode.value === 'DINE_IN') {
                    hint.textContent = 'A staff member will assign your table and confirm the order before it goes to the kitchen.';
                } else if (inputMode.value === 'DELIVERY') {
                    hint.textContent = 'Staff will confirm your order and send it to the kitchen — no table needed.';
                } else {
                    hint.textContent = 'Staff will confirm your order and send it to the kitchen — no table needed.';
                }
            }
        }

        document.getElementById('order_type_id')?.addEventListener('change', syncModeFromOrderType);

        document.getElementById('menuSearch')?.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('.menu-item-row').forEach(row => {
                const hay = row.getAttribute('data-search') || '';
                row.classList.toggle('hidden', q !== '' && !hay.includes(q));
            });
            document.querySelectorAll('#step-browse section').forEach(sec => {
                const any = sec.querySelector('.menu-item-row:not(.hidden)');
                sec.classList.toggle('hidden', !any);
            });
        });

        async function submitOrder() {
            const lines = getCartLines();
            if (lines.length === 0) {
                alert('Add items to your cart first.');
                return;
            }
            const orderTypeId = document.getElementById('order_type_id').value;
            const phone = document.getElementById('customer_phone').value.trim();
            if (!orderTypeId) {
                alert('Please select an order type.');
                return;
            }
            if (!phone) {
                alert('Please enter your phone number.');
                return;
            }

            const modeInput = document.getElementById('input_mode');
            const orderData = {
                items: lines.map(l => ({ item_id: l.item_id, qty: l.qty })),
                outlet_id: {{ (int) $defaultOutlet->id }},
                order_type_id: parseInt(orderTypeId, 10),
                payment_method: 'cash',
                customer_name: document.getElementById('customer_name').value.trim() || null,
                customer_phone: phone,
                special_instructions: document.getElementById('special_instructions').value.trim() || null,
                mode: modeInput ? modeInput.value : 'DINE_IN',
            };
            const tn = document.getElementById('input_table_no');
            if (tn && tn.value) orderData.table_no = tn.value;

            const endpoints = [
                `/api/${tenantSlug}/public/qr-order/create`,
                `/api/${tenantSlug}/public/simple-order`
            ];

            let lastError = null;
            for (const endpoint of endpoints) {
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(orderData)
                    });
                    const ct = response.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        lastError = 'Invalid response';
                        continue;
                    }
                    const result = await response.json();
                    if (response.ok && (result.success || result.order_id)) {
                        alert('Order received! Our team will confirm it shortly. Order #' + (result.order_id || result.order?.id || '—'));
                        quantities = {};
                        Object.keys(itemsMeta).forEach(id => {
                            const el = document.getElementById('qty-' + id);
                            if (el) el.textContent = '0';
                        });
                        document.getElementById('checkoutForm')?.reset();
                        document.getElementById('input_mode').value = 'DINE_IN';
                        goBrowse();
                        syncCartBar();
                        return;
                    }
                    lastError = result.message || result.error || 'Failed';
                } catch (e) {
                    lastError = e.message;
                }
            }
            alert('Could not place order: ' + (lastError || 'Try again'));
        }

        // Continue button on checkout = submit (second bar)
        (function addCheckoutActions() {
            const panel = document.getElementById('step-checkout');
            if (!panel) return;
            const wrap = document.createElement('div');
            wrap.className = 'fixed bottom-0 left-0 right-0 z-50 px-3 pb-4 pt-2 bg-white border-t border-slate-200';
            wrap.innerHTML = `
                <div class="max-w-lg mx-auto">
                    <button type="button" id="btn-place" class="w-full rounded-xl bg-brand py-4 text-white font-bold text-base shadow-lg hover:opacity-95">
                        Place order
                    </button>
                </div>
            `;
            panel.appendChild(wrap);
            document.getElementById('btn-place')?.addEventListener('click', submitOrder);
        })();

        window.addEventListener('load', () => {
            const h = window.location.hash;
            if (h && h.length > 1) {
                const el = document.querySelector(h);
                if (el) setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300);
            }
        });
    </script>
@endif
</body>
</html>
