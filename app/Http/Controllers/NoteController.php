<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request){

        $request->validate(
            [
                'name' => 'required',
                'id_user' => 'required'
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {
   
            $note = new \App\Models\Note;
    
            $note->name = $request->name;
            $note->id_user = $request->id_user;
    
            $note->save();

            return ['new_note' => $note->created_at];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function show($id_user){

        $note = \App\Models\Note::where('id_user', $id_user)->get();

        return $note;

    }
}
