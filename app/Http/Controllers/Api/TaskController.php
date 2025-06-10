<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::with(['assignedTo:id,name', 'createdBy:id,name']);

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->boolean('my_tasks')) {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        $tasks = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        $task->load(['assignedTo:id,name', 'createdBy:id,name']);

        return response()->json([
            'message' => 'Tarea creada exitosamente',
            'task' => $task
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $task->load(['assignedTo:id,name,email', 'createdBy:id,name,email']);
        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $task->update($request->validated());
        $task->load(['assignedTo:id,name', 'createdBy:id,name']);

        return response()->json([
            'message' => 'Tarea actualizada exitosamente',
            'task' => $task
        ]);
    }

    public function complete(Task $task): JsonResponse
    {
        if ($task->status === 'completed') {
            return response()->json(['message' => 'La tarea ya está completada'], 422);
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json([
            'message' => 'Tarea marcada como completada',
            'task' => $task
        ]);
    }

    public function assign(Request $request, Task $task): JsonResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task->update(['assigned_to' => $request->assigned_to]);
        $task->load('assignedTo:id,name');

        return response()->json([
            'message' => 'Tarea asignada exitosamente',
            'task' => $task
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }
}