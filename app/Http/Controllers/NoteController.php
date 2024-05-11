<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::get();
        $message = [
            'message' => 'Get All Notes Successfully',
            'data' => $notes
        ];
        $response = Response::HTTP_OK;

        return response()->json($message, $response);
    }

    public function show($id)
    {
        $note = Note::where('id', $id)->first();

        if(!$note) {
            return response()->json([
                'message' => 'Note Not Found'
            ], Response::HTTP_BAD_REQUEST);
        }

        $message = [
            'message' => 'Get ' . $note->note . ' Successfully',
            'data' => $note
        ];
        $response = Response::HTTP_OK;

        return response()->json($message, $response);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'note' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), Response::HTTP_BAD_REQUEST);
        }

        $note = Note::create($request->all());

        $message = [
            'message' => 'Note Stored Successfully',
            'data' => $note
        ];
        $response = Response::HTTP_OK;

        return response()->json($message, $response);
    }

    public function update(Request $request, $id)
    {
        $note = Note::where('id', $id)->first();

        if(!$note) {
            return response()->json([
                'message' => 'Note Not Found'
            ], Response::HTTP_BAD_REQUEST);
        }

        $validated = Validator::make($request->all(), [
            'note' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json($validated->fails(), Response::HTTP_BAD_REQUEST);
        }

        $note->update($request->all());

        $message = [
            'message' => 'Note Updated Successfully',
            'data' => $note
        ];
        $response = Response::HTTP_OK;

        return response()->json($message, $response);
    }

    public function destroy($id)
    {
        $note = Note::where('id', $id)->first();

        if(!$note) {
            return response()->json([
                'message' => 'Note Not Found'
            ], Response::HTTP_BAD_REQUEST);
        }   

        $note->delete();

        $message = [
            'message' => 'Note Deleted Successfully'
        ];
        $response = Response::HTTP_OK;

        return response()->json($message, $response);
    }
}
