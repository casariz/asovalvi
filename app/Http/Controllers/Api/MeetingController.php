<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingAttendee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Meeting::with(['organizer:id,name', 'attendees.user:id,name']);

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }

        $meetings = $query->latest('scheduled_at')->paginate($request->get('per_page', 15));

        return response()->json($meetings);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'type' => 'required|in:general_assembly,board_meeting,committee,workshop,other',
            'agenda' => 'nullable|string',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
        ]);

        return DB::transaction(function () use ($request) {
            $meeting = Meeting::create([
                'title' => $request->title,
                'description' => $request->description,
                'scheduled_at' => $request->scheduled_at,
                'location' => $request->location,
                'duration_minutes' => $request->duration_minutes,
                'type' => $request->type,
                'organizer_id' => auth()->id(),
                'agenda' => $request->agenda,
            ]);

            // Agregar asistentes
            if ($request->has('attendees')) {
                foreach ($request->attendees as $userId) {
                    MeetingAttendee::create([
                        'meeting_id' => $meeting->id,
                        'user_id' => $userId,
                        'status' => 'invited',
                        'is_required' => true,
                    ]);
                }
            }

            $meeting->load(['organizer:id,name', 'attendees.user:id,name']);

            return response()->json([
                'message' => 'Reunión creada exitosamente',
                'meeting' => $meeting
            ], 201);
        });
    }

    public function show(Meeting $meeting): JsonResponse
    {
        $meeting->load(['organizer:id,name,email', 'attendees.user:id,name,email']);
        return response()->json($meeting);
    }

    public function update(Request $request, Meeting $meeting): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'type' => 'required|in:general_assembly,board_meeting,committee,workshop,other',
            'status' => 'in:scheduled,in_progress,completed,cancelled,postponed',
            'agenda' => 'nullable|string',
            'minutes' => 'nullable|string',
        ]);

        $meeting->update($request->validated());
        $meeting->load(['organizer:id,name', 'attendees.user:id,name']);

        return response()->json([
            'message' => 'Reunión actualizada exitosamente',
            'meeting' => $meeting
        ]);
    }

    public function markAttendance(Request $request, Meeting $meeting): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:attended,absent',
        ]);

        $attendee = MeetingAttendee::where('meeting_id', $meeting->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$attendee) {
            return response()->json(['message' => 'No estás invitado a esta reunión'], 422);
        }

        $attendee->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Asistencia registrada',
            'attendee' => $attendee
        ]);
    }

    public function start(Meeting $meeting): JsonResponse
    {
        if ($meeting->status !== 'scheduled') {
            return response()->json(['message' => 'La reunión no puede iniciarse'], 422);
        }

        $meeting->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json([
            'message' => 'Reunión iniciada',
            'meeting' => $meeting
        ]);
    }

    public function end(Meeting $meeting): JsonResponse
    {
        if ($meeting->status !== 'in_progress') {
            return response()->json(['message' => 'La reunión no está en progreso'], 422);
        }

        $meeting->update([
            'status' => 'completed',
            'ended_at' => now()
        ]);

        return response()->json([
            'message' => 'Reunión finalizada',
            'meeting' => $meeting
        ]);
    }

    public function attendees(Meeting $meeting): JsonResponse
    {
        $attendees = $meeting->attendees()->with('user:id,name,email')->get();
        return response()->json($attendees);
    }

    public function destroy(Meeting $meeting): JsonResponse
    {
        $meeting->delete();

        return response()->json([
            'message' => 'Reunión eliminada exitosamente'
        ]);
    }
}