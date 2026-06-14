<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Payment Requests API</title>
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
                <a href="{{ url('/users') }}" class="px-3 py-1.5 text-sm text-white bg-white/10 rounded-lg transition-all duration-200">Users</a>
                <a href="{{ url('/payment') }}" class="px-3 py-1.5 text-sm text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200">Payment</a>
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

    <main class="max-w-6xl mx-auto p-6 space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-800">Users</h1>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-400">{{ $users->total() }} registered</span>
                @if (Auth::user()->role === 'finance')
                    <button onclick="openAddUser()" class="px-4 py-2 text-xs font-semibold rounded-lg bg-violet-600 text-white hover:bg-violet-700 transition-colors">+ Add User</button>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Country</th>
                        <th class="px-6 py-4">Currency</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors" id="user-row-{{ $user->id }}">
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->role === 'finance' ? 'bg-amber-400/20 text-amber-600' : 'bg-blue-400/20 text-blue-600' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $user->country }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $user->currency }}</td>
                            @if (Auth::user()->role === 'finance' || $user->id === Auth::id())
                                <td class="px-6 py-4 text-center">
                                    <button data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}" data-country="{{ $user->country }}" data-currency="{{ $user->currency }}" onclick="openEditUser(this)" class="tooltip-btn text-violet-600 hover:text-violet-800 transition-colors" data-title="Edit">
                                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button data-id="{{ $user->id }}" data-name="{{ $user->name }}" onclick="openDeleteUser(this)" class="tooltip-btn text-red-500 hover:text-red-700 transition-colors ml-3" data-title="Delete">
                                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            @else
                                <td class="px-6 py-4"></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </main>

    <footer class="text-center text-xs text-slate-400 pb-6">
        &copy; {{ date('Y') }} - Powered by Henrique Marcandier Marques Gonçalves
    </footer>

    @if (Auth::user()->role === 'finance')
    {{-- Add User Modal --}}
    <div id="addUserModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Add User</h3>
                <button onclick="closeModal('addUserModal')" class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <form id="addUserForm" class="space-y-4">
                <input type="text" name="name" placeholder="Full name" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <input type="password" name="password" placeholder="Password (min 6 chars)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <select name="role" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                    <option value="">Select role</option>
                    <option value="employee">Employee</option>
                    <option value="finance">Finance</option>
                </select>
                <input type="text" name="country" placeholder="Country" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                <input type="text" name="currency" placeholder="Currency (e.g. USD, BRL)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                <div id="addUserError" class="hidden text-red-500 text-xs"></div>
                <button type="submit" class="w-full py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-lg hover:bg-violet-700 transition-colors">Create User</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Edit User Modal --}}
    <div id="editUserModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Edit User</h3>
                <button onclick="closeModal('editUserModal')" class="text-slate-400 hover:text-slate-600 text-xl leading-none">&times;</button>
            </div>
            <form id="editUserForm" class="space-y-4">
                <input type="hidden" name="id" id="editUserId">
                <input type="text" name="name" id="editUserName" placeholder="Full name" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <input type="email" name="email" id="editUserEmail" placeholder="Email" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                <input type="password" name="password" placeholder="New password (leave blank to keep)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                <select name="role" id="editUserRole" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none" required>
                    <option value="employee">Employee</option>
                    <option value="finance">Finance</option>
                </select>
                <input type="text" name="country" id="editUserCountry" placeholder="Country" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                <input type="text" name="currency" id="editUserCurrency" placeholder="Currency (e.g. USD, BRL)" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none">
                <div id="editUserError" class="hidden text-red-500 text-xs"></div>
                <button type="submit" class="w-full py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-lg hover:bg-violet-700 transition-colors">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Delete User Modal --}}
    <div id="deleteUserModal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-2">Delete User</h3>
            <p class="text-sm text-slate-500 mb-6">Are you sure you want to delete <strong id="deleteUserName" class="text-slate-700"></strong>?</p>
            <input type="hidden" id="deleteUserId">
            <div id="deleteUserError" class="hidden text-red-500 text-xs mb-3"></div>
            <div class="flex gap-3 justify-end">
                <button onclick="closeModal('deleteUserModal')" class="px-4 py-2 text-sm font-semibold rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">Cancel</button>
                <button onclick="deleteUser()" class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors">Delete</button>
            </div>
        </div>
    </div>

    <script>
        const csrf = '{{ csrf_token() }}';

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function openAddUser() {
            document.getElementById('addUserForm').reset();
            document.getElementById('addUserError').classList.add('hidden');
            document.getElementById('addUserModal').classList.remove('hidden');
        }

        document.getElementById('addUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Creating...';
            const err = document.getElementById('addUserError');
            try {
                const r = await fetch('{{ url("/users") }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ name: this.name.value, email: this.email.value, password: this.password.value, role: this.role.value, country: this.country.value, currency: this.currency.value }) });
                const d = await r.json();
                if (!r.ok) { err.textContent = d.message || 'Error creating user'; err.classList.remove('hidden'); return; }
                location.reload();
            } catch(e) { err.textContent = 'Network error'; err.classList.remove('hidden'); }
            finally { btn.disabled = false; btn.textContent = 'Create User'; }
        });

        function openEditUser(btn) {
            const id = btn.dataset.id;
            document.getElementById('editUserId').value = id;
            document.getElementById('editUserName').value = btn.dataset.name;
            document.getElementById('editUserEmail').value = btn.dataset.email;
            document.getElementById('editUserRole').value = btn.dataset.role;
            document.getElementById('editUserCountry').value = btn.dataset.country || '';
            document.getElementById('editUserCurrency').value = btn.dataset.currency || '';
            document.getElementById('editUserError').classList.add('hidden');
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        document.getElementById('editUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true; btn.textContent = 'Saving...';
            const err = document.getElementById('editUserError');
            const id = document.getElementById('editUserId').value;
            try {
                const r = await fetch('{{ url("/users") }}/' + id, { method: 'PUT', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ name: this.name.value, email: this.email.value, password: this.password.value, role: this.role.value, country: this.country.value, currency: this.currency.value }) });
                const d = await r.json();
                if (!r.ok) { err.textContent = d.message || 'Error updating user'; err.classList.remove('hidden'); return; }
                location.reload();
            } catch(e) { err.textContent = 'Network error'; err.classList.remove('hidden'); }
            finally { btn.disabled = false; btn.textContent = 'Save Changes'; }
        });

        function openDeleteUser(btn) {
            document.getElementById('deleteUserId').value = btn.dataset.id;
            document.getElementById('deleteUserName').textContent = btn.dataset.name;
            document.getElementById('deleteUserError').classList.add('hidden');
            document.getElementById('deleteUserModal').classList.remove('hidden');
        }

        async function deleteUser() {
            const btn = event.target;
            btn.disabled = true; btn.textContent = 'Deleting...';
            const err = document.getElementById('deleteUserError');
            const id = document.getElementById('deleteUserId').value;
            try {
                const r = await fetch('{{ url("/users") }}/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } });
                if (!r.ok) { const d = await r.json(); err.textContent = d.message || 'Error deleting user'; err.classList.remove('hidden'); return; }
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
