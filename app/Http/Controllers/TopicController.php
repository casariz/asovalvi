<?php

namespace App\Http\Controllers;

use App\Models\MeetingTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{

    public function list() {
        $topics = MeetingTopic::all();
        return response()->json($topics);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'meeting_id' => 'required|integer',
            'type' => 'required|string',
            'topic' => 'required|string',
            'creation_date' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $topic = new MeetingTopic();
            $topic->meeting_id = $request->meeting_id;
            $topic->type = $request->type;
            $topic->topic = $request->topic;
            $topic->created_by = 1; // Sin autenticación
            $topic->creation_date = \Carbon\Carbon::now();
            $topic->status = 2;

            $topic->save();

            return response()->json(['message' => 'topic creado correctamente.', 'topic' => $topic], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al intentar guardar topic.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function view($meeting_id) {
        $topics = MeetingTopic::where('meeting_id', $meeting_id)->get()->toArray();
        return response()->json(['topics' => $topics]);
    }

    public function delete($topic_id) {
        $topic = MeetingTopic::findORfail($topic_id);
        $topic->update([
            'status' => 1
        ]);
        return response()->json(['message' => 'Topic eliminado correctamente.']);
    }
}