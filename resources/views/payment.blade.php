<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Payment Requests API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tooltip-btn { position: relative; }
        .tooltip-btn::after {
            content: attr(data-title);
            position: absolute; bottom: calc(100% + 8px); left: 50%; transform: translateX(-50%);
            white-space: nowrap; font-size: 11px; font-weight: 600;
            padding: 4px 10px; border-radius: 5px;
            background: #1e293b; color: #fff;
            opacity: 0; pointer-events: none; transition: opacity 0.15s ease;
        }
        .tooltip-btn::before {
            content: '';
            position: absolute; bottom: calc(100% + 2px); left: 50%; transform: translateX(-50%);
            border: 5px solid transparent; border-top-color: #1e293b;
            opacity: 0; pointer-events: none; transition: opacity 0.15s ease;
        }
        .tooltip-btn:hover::after, .tooltip-btn:hover::before { opacity: 1; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased">

    <nav class="bg-gradient-to-r from-slate-900 via-purple-900 to-slate-900 px-6 py-4 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg">Payment Requests</span>
            </div>
            <div class="flex gap-1">
                <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 text-sm text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200">Dashboard</a>
                <a href="{{ url('/users') }}" class="px-3 py-1.5 text-sm text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200">Users</a>
                <a href="{{ url('/payment') }}" class="px-3 py-1.5 text-sm text-white bg-white/10 rounded-lg transition-all duration-200">Payment</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-white/60 text-sm">
                <strong class="text-white">{{ Auth::user()->name }}</strong>
                <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ Auth::user()->role === 'finance' ? 'bg-amber-400/20 text-amber-300' : 'bg-blue-400/20 text-blue-300' }}">
                    {{ Auth::user()->role }}
                </span>
            </span>
            <a href="{{ url('/logout') }}" class="text-white/50 text-sm border border-white/10 px-3 py-1.5 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200">Sign Out</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">Payment Requests</h1>
            <div class="flex items-center gap-3">
                <div class="flex gap-2">
                    <a href="{{ url('/payment') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ !request('status') ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition-colors">All</a>
                    <a href="{{ url('/payment?status=pending') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'pending' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition-colors">Pending</a>
                    <a href="{{ url('/payment?status=approved') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'approved' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition-colors">Approved</a>
                    <a href="{{ url('/payment?status=rejected') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg {{ request('status') === 'rejected' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }} transition-colors">Rejected</a>
                </div>
                @if (Auth::user()->role === 'finance')
                    <button onclick="openAddPayment()" class="px-4 py-2 text-xs font-semibold rounded-lg bg-violet-600 text-white hover:bg-violet-700 transition-colors">+ Add Payment</button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Currency</th>
                        <th class="px-6 py-4">Amount (EUR)</th>
                        <th class="px-6 py-4">Exchange Rate</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-slate-50 transition-colors" id="payment-row-{{ $payment->id }}">
                            <td class="px-6 py-4 text-sm text-slate-500">#{{ $payment->id }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $payment->user?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ number_format($payment->amount_local, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $payment->currency_code }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ number_format($payment->amount_eur, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $payment->exchange_rate }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'approved' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        'expired' => 'bg-slate-100 text-slate-500',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClasses[$payment->status] ?? 'bg-slate-100 text-slate-500' }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            @if (Auth::user()->role === 'finance' || $payment->user_id === Auth::id())
                                <td class="px-6 py-4 text-center">
                                    @if ($payment->status === 'pending')
                                        <button data-id="{{ $payment->id }}" data-amount="{{ $payment->amount_local }}" data-currency="{{ $payment->currency_code }}" data-description="{{ $payment->description }}" onclick="openEditPayment(this)" class="tooltip-btn text-violet-600 hover:text-violet-800 transition-colors" data-title="Edit">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button data-id="{{ $payment->id }}" onclick="openDeletePayment(this)" class="tooltip-btn text-red-500 hover:text-red-700 transition-colors ml-3" data-title="Delete">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-300">--</span>
                                    @endif
                                </td>
                            @else
                                <td class="px-6 py-4"></td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-sm text-slate-400">No payment requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($payments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </main>

    @if (Auth::user()->role === 'finance')
    {{-- Add Payment Modal --}}
    <div id="addPaymentModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Add Payment Request</h3>
                <button onclick="closeModal('addPaymentModal')" class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <form id="addPaymentForm" class="space-y-4">
                <select name="user_id" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                    <option value="">Select user</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                <input type="number" name="amount_local" step="0.01" min="0.01" placeholder="Amount" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <input type="text" name="currency_code" placeholder="Currency (e.g. USD, BRL)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <textarea name="description" placeholder="Description (optional)" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none"></textarea>
                <div id="addPaymentError" class="hidden text-red-500 text-xs"></div>
                <button type="submit" class="w-full py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-lg hover:bg-violet-700 transition-colors">Create Payment Request</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit Payment Modal --}}
    <div id="editPaymentModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Edit Payment Request</h3>
                <button onclick="closeModal('editPaymentModal')" class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <form id="editPaymentForm" class="space-y-4">
                <input type="hidden" name="id" id="editPaymentId">
                <input type="number" name="amount_local" id="editPaymentAmount" step="0.01" min="0.01" placeholder="Amount" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <input type="text" name="currency_code" id="editPaymentCurrency" placeholder="Currency (e.g. USD, BRL)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <textarea name="description" id="editPaymentDesc" placeholder="Description (optional)" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none"></textarea>
                <div id="editPaymentError" class="hidden text-red-500 text-xs"></div>
                <button type="submit" class="w-full py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-lg hover:bg-violet-700 transition-colors">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Delete Payment Modal --}}
    <div id="deletePaymentModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete Payment Request</h3>
            <p class="text-sm text-slate-500 mb-6">Are you sure you want to delete payment request <strong id="deletePaymentId">#</strong>?</p>
            <input type="hidden" id="deletePaymentIdInput">
            <div id="deletePaymentError" class="hidden text-red-500 text-xs mb-3"></div>
            <div class="flex gap-3 justify-end">
                <button onclick="closeModal('deletePaymentModal')" class="px-4 py-2 text-sm font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">Cancel</button>
                <button onclick="deletePayment()" class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const csrf = '{{ csrf_token() }}';

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openAddPayment() {
            document.getElementById('addPaymentForm').reset();
            document.getElementById('addPaymentError').classList.add('hidden');
            document.getElementById('addPaymentModal').classList.remove('hidden');
        }

        document.getElementById('addPaymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Creating...';
            const err = document.getElementById('addPaymentError');
            try {
                const r = await fetch('{{ url("/payment") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ user_id: this.user_id.value, amount_local: this.amount_local.value, currency_code: this.currency_code.value, description: this.description.value }) });
                const d = await r.json();
                if (!r.ok || !d.success) { err.textContent = d.message || 'Error creating payment'; err.classList.remove('hidden'); return; }
                location.reload();
            } catch(e) { err.textContent = 'Network error'; err.classList.remove('hidden'); }
            finally { btn.disabled = false; btn.textContent = 'Create Payment Request'; }
        });

        function openEditPayment(btn) {
            document.getElementById('editPaymentId').value = btn.dataset.id;
            document.getElementById('editPaymentAmount').value = btn.dataset.amount;
            document.getElementById('editPaymentCurrency').value = btn.dataset.currency;
            document.getElementById('editPaymentDesc').value = btn.dataset.description || '';
            document.getElementById('editPaymentError').classList.add('hidden');
            document.getElementById('editPaymentModal').classList.remove('hidden');
        }

        document.getElementById('editPaymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Saving...';
            const err = document.getElementById('editPaymentError');
            const id = document.getElementById('editPaymentId').value;
            try {
                const r = await fetch('{{ url("/payment") }}/' + id, { method: 'PUT', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ amount_local: this.amount_local.value, currency_code: this.currency_code.value, description: this.description.value }) });
                const d = await r.json();
                if (!r.ok || !d.success) { err.textContent = d.message || 'Error updating payment'; err.classList.remove('hidden'); return; }
                location.reload();
            } catch(e) { err.textContent = 'Network error'; err.classList.remove('hidden'); }
            finally { btn.disabled = false; btn.textContent = 'Save Changes'; }
        });

        function openDeletePayment(btn) {
            const id = btn.dataset.id;
            document.getElementById('deletePaymentIdInput').value = id;
            document.getElementById('deletePaymentId').textContent = '#' + id;
            document.getElementById('deletePaymentError').classList.add('hidden');
            document.getElementById('deletePaymentModal').classList.remove('hidden');
        }

        async function deletePayment() {
            const btn = event.target;
            btn.disabled = true; btn.textContent = 'Deleting...';
            const err = document.getElementById('deletePaymentError');
            const id = document.getElementById('deletePaymentIdInput').value;
            try {
                const r = await fetch('{{ url("/payment") }}/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                if (!r.ok) { const d = await r.json(); err.textContent = d.message || 'Error deleting payment'; err.classList.remove('hidden'); return; }
                location.reload();
            } catch(e) { err.textContent = 'Network error'; err.classList.remove('hidden'); }
            finally { btn.disabled = false; btn.textContent = 'Delete'; }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed.inset-0.z-50').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>

</body>
</html>
