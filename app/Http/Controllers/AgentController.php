<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AgentController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->whereNot('id', Auth::id())
            ->whereHas('roles', function($query) {
                $query->where('name', 'Agent');
            })
            ->get();
        return view('pages.agent.index', compact('users'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->hasRole('Super Admin')) {
            $user->assignRole('Agent');
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'data' => $user->load('roles')
        ]);
    }

    public function show($user): JsonResponse
    {
        $user = User::findOrFail($user);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $user): JsonResponse
    {
        $user = User::findOrFail($user);
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $request->validate($rules);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->assignRole('Agent');
        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $user->fresh()
        ]);
    }

    public function destroy($user): JsonResponse
    {
        $user = User::findOrFail($user);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }
}
