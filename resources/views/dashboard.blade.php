<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Payment Requests API</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 text-sm text-white bg-white/10 rounded-lg transition-all duration-200">Dashboard</a>
                <a href="{{ url('/users') }}" class="px-3 py-1.5 text-sm text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-200">Users</a>
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

    <main class="max-w-5xl mx-auto p-6 space-y-6">

        <div class="bg-gradient-to-r from-violet-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl">
            <h1 class="text-3xl font-bold">Welcome, {{ Auth::user()->name }}!</h1>
            <p class="text-white/80 mt-2">You are logged in as <strong class="text-white">{{ Auth::user()->role }}</strong> &mdash; {{ Auth::user()->country }}</p>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200/60">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <h3 class="text-lg font-semibold text-slate-800">Currency Converter</h3>
            </div>

            <form id="convertForm" class="flex flex-wrap items-end gap-4">
                @csrf
                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">From</label>
                    <select name="from" id="from" class="w-full px-3.5 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-700 outline-none transition-all duration-200 focus:border-violet-500 focus:ring-3 focus:ring-violet-500/15 cursor-pointer">
                        @foreach ($currencies as $c)
                            <option value="{{ $c }}" {{ $c === Auth::user()->currency ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" id="swapBtn"
                    class="mb-0.5 w-11 h-11 flex items-center justify-center border border-slate-200 rounded-full text-slate-400 hover:border-violet-400 hover:text-violet-600 hover:bg-violet-50 transition-all duration-200 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </button>

                <div class="flex-1 min-w-[160px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">To</label>
                    <select name="to" id="to" class="w-full px-3.5 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-700 outline-none transition-all duration-200 focus:border-violet-500 focus:ring-3 focus:ring-violet-500/15 cursor-pointer">
                        @foreach ($currencies as $c)
                            <option value="{{ $c }}" {{ $c === 'EUR' ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" id="convertBtn"
                    class="px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-semibold text-sm rounded-xl transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-violet-500/25 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 whitespace-nowrap">
                    Convert
                </button>
            </form>

            <div id="result" class="hidden mt-6 p-5 bg-gradient-to-r from-violet-50 to-purple-50 rounded-xl border border-violet-100">
                <div class="text-2xl font-bold text-slate-800">
                    1 <span id="fromLabel" class="text-violet-600"></span> = <span id="rateValue" class="text-violet-600"></span> <span id="toLabel" class="text-violet-600"></span>
                </div>
                <div class="text-sm text-slate-500 mt-1">
                    1 <span id="toLabel2"></span> = <span id="inverseValue"></span> <span id="fromLabel2"></span>
                </div>
                <div class="text-xs text-slate-400 mt-2" id="updatedDisplay"></div>
            </div>

            <div id="error" class="hidden mt-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm"></div>
        </div>

        <div class="text-center text-xs text-slate-400 pt-4 border-t border-slate-200">
            &copy; {{ date('Y') }} - Powered by Henrique Marcandier Marques Gonçalves
        </div>
    </main>

    <script>
        document.getElementById('swapBtn').addEventListener('click', function () {
            const from = document.getElementById('from');
            const to = document.getElementById('to');
            const tmp = from.value;
            from.value = to.value;
            to.value = tmp;
            document.getElementById('result').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');
        });

        document.getElementById('convertForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById('convertBtn');
            btn.disabled = true;
            btn.textContent = 'Converting...';

            document.getElementById('result').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');

            const from = document.getElementById('from').value;
            const to = document.getElementById('to').value;

            fetch('{{ url('/convert') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ from, to }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('fromLabel').textContent = data.from;
                    document.getElementById('toLabel').textContent = data.to;
                    document.getElementById('rateValue').textContent = data.rate;
                    document.getElementById('toLabel2').textContent = data.to;
                    document.getElementById('fromLabel2').textContent = data.from;
                    document.getElementById('inverseValue').textContent = data.rate > 0 ? (1 / data.rate).toFixed(6) : '0';
                    document.getElementById('updatedDisplay').textContent = 'Updated just now';
                    document.getElementById('result').classList.remove('hidden');
                } else {
                    document.getElementById('error').textContent = data.message || 'Conversion failed.';
                    document.getElementById('error').classList.remove('hidden');
                }
            })
            .catch(() => {
                document.getElementById('error').textContent = 'Network error. Please try again.';
                document.getElementById('error').classList.remove('hidden');
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Convert';
            });
        });
    </script>
</body>
</html>
