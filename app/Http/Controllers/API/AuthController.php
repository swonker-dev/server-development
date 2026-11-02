<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => 'Введите имя.',
            'name.min' => 'Имя должно содержать не менее 2 символов.',
            'email.required' => 'Введите email.',
            'email.email' => 'Введите корректный email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'password.required' => 'Введите пароль.',
            'password.min' => 'Пароль должен содержать не менее 6 символов.',
        ]);

        $readerRole = Role::where('name', 'reader')->firstOrFail();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $readerRole->id,
        ]);

        return response()->json([
            'message' => 'Регистрация прошла успешно. Теперь войдите.',
            'user' => $user->load('role'),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Введите email.',
            'email.email' => 'Введите корректный email.',
            'password.required' => 'Введите пароль.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Неверный email или пароль.'], 401);
        }

        $token = $user->createToken('api-auth')->plainTextToken;

        return response()->json([
            'message' => 'Вход выполнен.',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('role'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Вы вышли из аккаунта.']);
    }
}
