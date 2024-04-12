<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Note as Note;

class NoteController extends Controller
{

    public function index($id){

        $notes = Note::where('id_user',$id)->select(['id','name', 'created_at', 'id_user'])->orderBy('updated_at', 'desc')->get();

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

            return ['new_note' => $note->created_at, 'id' => $note->id];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function show($id){

        $note = Note::where('id', $id)->first();

        return $note;

    }

    public function save_midia(Request $request){
        
        //$gname,$appname,$type,$date
        $request->validate(
            [
                'id' => 'required',
                'gname' => 'required',
                'appname' => 'required',
                'type' => 'required',
                'date' => 'required'
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $note = new Note;

            switch ($request->type) {
                case 'image':
                    
                    $note->images = [
                        [

                        ]
                    ];

                    break;

                case 'video':
                    # code...
                    break;

                case 'audio':
                    # code...
                    break;
                
                default:
                    # code...
                    break;
            }

            $note->save();

            return ['new_note' => $note->created_at, 'id' => $note->id];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function save_text(Request $request){

        $request->validate(
            [
                'id' => 'required'
            ],
            [
                'required' => 'Identificador necessário.',
            ]
        );

        try {
            
            $note = Note::find($request->id);

            if ($note) {
                
                
                $note->text = $request->text;

                $note->save();

                return ['note_text_saved' => $note->updated_at];

            }else{

                return ['error' => 'Nota inexistente no banco de dados.'];

            }


        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function change_name(Request $request){
            
        $request->validate(
            [
                'id' => 'required',
                'name' => 'required'
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {
            
            $note = Note::find($request->id);

            if ($note) {
                
                
                $note->name = $request->name;

                $note->save();

                return ['note_name_saved' => $note->updated_at];

            }else{

                return ['error' => 'Nota inexistente no banco de dados.'];

            }

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }
}
