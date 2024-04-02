<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Note as Note;

class NoteController extends Controller
{

    public function index($id){

        $notes = Note::where('id_user',$id)->select(['id','name', 'created_at', 'id_user'])->get();

        return $notes;

    }

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
   
            $note = new Note;
    
            $note->name = $request->name;
            $note->id_user = $request->id_user;
    
            $note->save();

            return ['new_note' => $note->created_at, 'id' => $note->id_user];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function show($id_user){

        $note = Note::where('id_user', $id_user)->get();

        return $note;

    }
}
