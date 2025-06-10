<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'document_number' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar usuario por document_number
        $user = DB::table('users')
            ->where('document_number', $request->document_number)
            ->where('status', 'ACTIVE')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'document_number' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Crear token de acceso (simulado, ya que no tienes Sanctum configurado)
        $token = base64_encode($user->id . ':' . time());

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'document_number' => $user->document_number,
                'user_type' => $user->user_type,
                'status' => $user->status,
            ],
            'token' => $token,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'client', // Default role
            'is_active' => true,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registro exitoso'
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        // Obtener el token del header Authorization
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['message' => 'Token no proporcionado'], 401);
        }

        // Decodificar el token (básico, sin seguridad real)
        $decoded = base64_decode($token);
        $userId = explode(':', $decoded)[0];

        $user = DB::table('users')
            ->where('id', $userId)
            ->where('status', 'ACTIVE')
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'document_number' => $user->document_number,
                'user_type' => $user->user_type,
                'status' => $user->status,
            ]
        ]);
    }
}