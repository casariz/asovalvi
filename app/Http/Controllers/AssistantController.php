<?php

namespace App\Http\Controllers;

use App\Models\MeetingAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssistantController extends Controller
{
    public function list() {
        $assistants = MeetingAssistant::with(['user_id:id,first_name,last_name'])->get();
        return response()->json([ 'assistants' => $assistants]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $assistant = new MeetingAssistant();
            $assistant->user_id = $request->worker_id;
            $assistant->status = 2;

            $assistant->save();

            return response()->json(['message' => 'Assistant creado correctamente.', 'assistant' => $assistant], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al intentar guardar assistant.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function storeAssistants(Request $request) {
    $validator = Validator::make($request->all(), [
        'user_ids' => 'required|array',
        'user_ids.*' => 'required|integer|exists:users,id',
        'meeting_id' => 'required|integer|exists:meetings,meeting_id'
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        $meetingId = $request->meeting_id;
        $userIds = $request->user_ids;

        $assistants = [];
        foreach ($userIds as $userId) {
            $assistant = new MeetingAssistant();
            $assistant->user_id = $userId;
            $assistant->meeting_id = $meetingId;
            $assistant->status = 2;
            $assistant->save();
            $assistants[] = $assistant;
        }

        return response()->json(['message' => 'Assistants creados correctamente.', 'assistants' => $assistants], 201);
    }

    catch (\Exception $e) {
        return response()->json(['error' => 'Error al intentar guardar assistants.', 'exception' => $e->getMessage()], 500);
    }
}


    public function view($meeting_id) {
        $assistants = MeetingAssistant::with(['user_id:id,first_name,last_name'])->where('meeting_id', $meeting_id)->get()->toArray();
        return response()->json(['assistants' => $assistants]);
    }

    public function delete($meeting_id, $user_id) {
        $updated = MeetingAssistant::where('meeting_id', $meeting_id)
            ->where('user_id', $user_id)
            ->delete();

        if ($updated) {
            return response()->json(['message' => 'Assistant eliminado correctamente.']);
        } else {
            return response()->json(['message' => 'Asistente no encontrado o no se pudo actualizar.'], 404);
        }
    }
}