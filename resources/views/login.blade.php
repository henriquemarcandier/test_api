<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Requests API</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center p-4 font-sans antialiased">
    <div class="w-full max-w-md">
        <div class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-[0_25px_60px_rgba(0,0,0,0.5)]">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-lg shadow-purple-500/20">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Payment Requests</h1>
                <p class="text-sm text-white/50 mt-1">Sign in to manage your requests</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-300 text-sm px-4 py-3 rounded-xl mb-6 text-center">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="login" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-white/70 mb-2 tracking-wide">Email</label>
                    <input type="email" name="email" id="email" placeholder="your@email.com" required autofocus
                        class="w-full px-4 py-3.5 bg-white/8 border border-white/10 rounded-xl text-white text-sm placeholder-white/30 outline-none transition-all duration-300 focus:border-violet-500 focus:bg-white/10 focus:ring-4 focus:ring-violet-500/15">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-white/70 mb-2 tracking-wide">Password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" required
                        class="w-full px-4 py-3.5 bg-white/8 border border-white/10 rounded-xl text-white text-sm placeholder-white/30 outline-none transition-all duration-300 focus:border-violet-500 focus:bg-white/10 focus:ring-4 focus:ring-violet-500/15">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-white/50 cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-violet-500 w-4 h-4 rounded border-white/20 bg-white/10">
                        Remember me
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3.5 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-semibold text-sm rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-violet-500/30 active:translate-y-0 tracking-wide">
                    Sign In
                </button>
            </form>

            <details class="mt-6 pt-6 border-t border-white/8">
                <summary class="text-xs text-white/40 cursor-pointer text-center hover:text-white/60 transition-colors">Test users</summary>
                <table class="w-full mt-4 text-xs text-white/50">
                    <tr><td class="py-1.5">joao@empresa.com</td><td class="text-right">employee</td><td class="text-right">BRL</td></tr>
                    <tr><td class="py-1.5">john@empresa.com</td><td class="text-right">employee</td><td class="text-right">USD</td></tr>
                    <tr><td class="py-1.5">pierre@empresa.com</td><td class="text-right">employee</td><td class="text-right">EUR</td></tr>
                    <tr><td class="py-1.5">akira@empresa.com</td><td class="text-right">employee</td><td class="text-right">JPY</td></tr>
                    <tr><td class="py-1.5">carlos@empresa.com</td><td class="text-right">employee</td><td class="text-right">MXN</td></tr>
                    <tr><td class="py-1.5">sarah@empresa.com</td><td class="text-right">employee</td><td class="text-right">GBP</td></tr>
                    <tr><td class="py-1.5">finance@empresa.com</td><td class="text-right">finance</td><td class="text-right">EUR</td></tr>
                    <tr><td colspan="3" class="text-center pt-3 text-white/30">Password: <strong class="text-white/50">password</strong></td></tr>
                </table>
            </details>

            <div class="text-center mt-6 text-xs text-white/30">
                &copy; {{ date('Y') }} - Powered by Henrique Marcandier Marques Gonçalves
            </div>
        </div>
    </div>

</body>
</html>
