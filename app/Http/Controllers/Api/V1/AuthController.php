<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::with('role')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid email or password credentials.',
                'errors'  => ['email' => ['Invalid email or password.']]
            ], 422);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Your account is suspended or inactive. Please contact administration.',
            ], 403);
        }

        $token = $user->createToken('api_auth_token')->plainTextToken;
        $roleName = strtolower($user->role?->name ?? 'patient');

        $redirectMap = [
            'admin'        => 'index.html',
            'super_admin'  => 'super-admin-dashboard.html',
            'doctor'       => 'doctor-dashboard.html',
            'patient'      => 'patient-dashboard.html',
            'nurse'        => 'nurse-dashboard.html',
            'receptionist' => 'receptionist-dashboard.html',
            'pharmacist'   => 'pharmacist-dashboard.html',
        ];

        $redirectUrl = $redirectMap[$roleName] ?? 'index.html';

        return response()->json([
            'status'       => 'success',
            'message'      => 'Login successful!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'role'     => $roleName,
                'avatar'   => $user->avatar ?: 'assets/img/profiles/avatar-01.jpg',
            ],
            'redirect'     => $redirectUrl,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'nullable|string',
        ]);

        $requestedRole = strtolower($request->role ?? 'patient');
        $roleObj = Role::where('name', $requestedRole)->first() ?? Role::where('name', 'patient')->first();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $roleObj?->id,
            'status'   => 'active',
            'phone'    => $request->phone ?? null,
        ]);

        $token = $user->createToken('api_auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Registration successful! Welcome to NepXMedica SaaS.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'role'     => $requestedRole,
            ],
            'redirect'     => ($requestedRole === 'patient') ? 'patient-dashboard.html' : 'index.html',
        ], 201);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out.',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'We could not find a user account with that email address.',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Password reset verification code (123456) sent to ' . $request->email,
            'code'    => '123456',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'code'     => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password has been successfully updated. Please login with your new password.',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'status' => 'success',
            'user'   => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'role'   => strtolower($user->role?->name ?? 'user'),
                'avatar' => $user->avatar,
                'phone'  => $user->phone,
            ],
        ]);
    }
}
