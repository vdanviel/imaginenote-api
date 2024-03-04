<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function store(Request $request){

        try {

            $request->validate(
                [
                    'name' => 'required',
                    'id_user' => 'required',
                ]
            );
    
            $note = new \App\Models\Note;
    
            $note->name = $request->name;
            $note->id_user = $request->id_user;
    
            return $note->save();

        } catch (\Exception | \PDOException $th) {
            
            return $th;

        }

    }

    public function show($id_user){

        $note = \App\Models\Note::where('id_user', $id_user)->get();

        return $note;

    }
}
