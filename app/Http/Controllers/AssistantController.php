<?php

namespace App\Http\Controllers;

use App\Models\MeetingAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssistantController extends Controller
{
    public function list() {
        $assistants = MeetingAssistant::all();
        return response()->json([ 'assistants' => $assistants]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'assistant_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $assistant = new MeetingAssistant();
            $assistant->assistant_name = $request->assistant_name;
            $assistant->status = 2;

            $assistant->save();

            return response()->json(['message' => 'Assistant creado correctamente.', 'assistant' => $assistant], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al intentar guardar assistant.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function storeAssistants(Request $request) {
    $validator = Validator::make($request->all(), [
        'assistant_names' => 'required|array',
        'assistant_names.*' => 'required|string',
        'meeting_id' => 'required|integer|exists:meetings,meeting_id'
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    try {
        $meetingId = $request->meeting_id;
        $assistantNames = $request->assistant_names;

        $assistants = [];
        foreach ($assistantNames as $assistantName) {
            $assistant = new MeetingAssistant();
            $assistant->assistant_name = $assistantName;
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
        $assistants = MeetingAssistant::where('meeting_id', $meeting_id)->get()->toArray();
        return response()->json(['assistants' => $assistants]);
    }

    public function delete($meeting_id, $assistant_id) {
        $updated = MeetingAssistant::where('meeting_id', $meeting_id)
            ->where('id', $assistant_id)
            ->delete();

        if ($updated) {
            return response()->json(['message' => 'Assistant eliminado correctamente.']);
        } else {
            return response()->json(['message' => 'Asistente no encontrado o no se pudo actualizar.'], 404);
        }
    }
}