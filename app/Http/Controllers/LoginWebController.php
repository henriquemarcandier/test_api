<?php

namespace App\Http\Controllers;

use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginWebController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function dashboard()
    {
        $currencies = ['EUR', 'USD', 'GBP', 'JPY', 'BRL', 'MXN', 'CAD', 'AUD', 'CHF', 'CNY', 'INR', 'KRW'];
        return view('dashboard', compact('currencies'));
    }

    public function convert(Request $request, ExchangeRateService $exchangeRateService)
    {
        $request->validate([
            'from' => 'required|string|size:3',
            'to'   => 'required|string|size:3|different:from',
        ]);

        try {
            $result = $exchangeRateService->getRates($request->from);

            if (!isset($result['conversion_rates'][$request->to])) {
                return response()->json(['success' => false, 'message' => 'Currency not supported.'], 422);
            }

            $rate = $result['conversion_rates'][$request->to];

            return response()->json([
                'success' => true,
                'from'    => strtoupper($request->from),
                'to'      => strtoupper($request->to),
                'rate'    => round($rate, 6),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
