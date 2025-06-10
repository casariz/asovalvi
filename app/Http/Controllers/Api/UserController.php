<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->boolean('only_active')) {
            $query->active();
        }

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'nursery_name' => 'nullable|string|max:255',
            'role' => 'required|in:admin,member,president,secretary,treasurer',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),            'phone' => $request->phone,
            'address' => $request->address,
            'nursery_name' => $request->nursery_name,
            'role' => $request->role,
            'is_active' => $request->get('is_active', true),
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);
    }    public function show(User $user): JsonResponse
    {
        $user->loadCount(['assignedTasks', 'createdTasks', 'organizedMeetings', 'memberFees']);
        
        return response()->json($user);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,member,president,secretary,treasurer',
            'is_active' => 'boolean',
        ]);

        $updateData = $request->except(['password']);
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'user' => $user
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        // No permitir eliminar al usuario autenticado
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'No puedes eliminar tu propia cuenta'
            ], 422);
        }        // Verificar si tiene tareas, reuniones o transacciones
        if ($user->assignedTasks()->count() > 0 || $user->organizedMeetings()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el usuario porque tiene tareas o reuniones asociadas'
            ], 422);
        }

        $user->delete();        return response()->json([
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }

    public function activate(User $user): JsonResponse
    {
        $user->update(['is_active' => true]);

        return response()->json([
            'message' => 'Usuario activado exitosamente',
            'user' => $user
        ]);
    }

    public function deactivate(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'No puedes desactivar tu propia cuenta'
            ], 422);
        }

        $user->update(['is_active' => false]);

        return response()->json([
            'message' => 'Usuario desactivado exitosamente',
            'user' => $user
        ]);
    }
}