<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('users', compact('users'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:employee,finance',
            'country'  => 'nullable|string|max:100',
            'currency' => 'nullable|string|size:3',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'country'  => $validated['country'] ?? null,
            'currency' => $validated['currency'] ?? null,
        ]);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role !== 'finance' && $request->user()->id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:employee,finance',
            'country'  => 'nullable|string|max:100',
            'currency' => 'nullable|string|size:3',
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'country'  => $validated['country'] ?? null,
            'currency' => $validated['currency'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if ($request->user()->role !== 'finance' && $request->user()->id !== $user->id) {
            abort(403);
        }

        $user->delete();
        return response()->json(['success' => true]);
    }
}
