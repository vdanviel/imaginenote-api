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

    //MIDIA
    //image
    public function register_image(Request $request){

        $request->validate(
            [
                'id_note' => 'required',
                'gname' => 'required',
                'appname' => 'required',
                'size' => 'required|integer'
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $image = new \App\Models\Images;

            $image->id_note = $request->id_note;
            $image->gname = $request->gname;
            $image->appname = $request->appname;
            $image->size = $request->size;

            $image->save();

            return ['new_image' => $image->created_at, 'name' => $image->appname];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function save_image_name(Request $request){
        
        $request->validate(
            [
                'id_image' => 'required',
                'appname' => 'required',
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $image_existence = \App\Models\Images::find($request->id_image);

            if ($image_existence) {
                # code...
            }

            $image_existence->appname = $request->appname;

            return ['image_name_saved' => $image_existence->updated_at];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    //video
    public function register_video(Request $request){

        $request->validate(
            [
                'id_note' => 'required',
                'gname' => 'required',
                'appname' => 'required',
                'size' => 'required|integer',
                'duration' => 'required'
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $video = new \App\Models\Videos;

            $video->id_note = $request->id_note;
            $video->gname = $request->gname;
            $video->appname = $request->appname;
            $video->size = $request->size;
            $video->duration = $request->duration;

            $video->save();

            return ['new_video' => $video->created_at, 'name' => $video->appname];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function save_video_name(Request $request){
        
        $request->validate(
            [
                'id_video' => 'required',
                'appname' => 'required',
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $video_existence = \App\Models\Videos::find($request->id_video);

            $video_existence->appname = $request->appname;

            return ['video_name_saved' => $video_existence->updated_at];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    //audio
    public function register_audio(Request $request){

        $request->validate(
            [
                'id_note' => 'required',
                'gname' => 'required',
                'appname' => 'required',
                'size' => 'required|integer',
                'duration' => 'required'
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $audio = new \App\Models\Audios;

            $audio->id_note = $request->id_note;
            $audio->gname = $request->gname;
            $audio->appname = $request->appname;
            $audio->size = $request->size;
            $audio->duration = $request->duration;

            $audio->save();

            return ['new_audio' => $audio->created_at, 'name' => $audio->appname];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

    public function save_audio_name(Request $request){
        
        $request->validate(
            [
                'id_audio' => 'required',
                'appname' => 'required',
            ],
            [
                'required' => 'Um ou mais campos em falta.',
            ]
        );

        try {

            $audio_existence = \App\Models\Audios::find($request->id_audio);

            $audio_existence->appname = $request->appname;

            return ['audio_name_saved' => $audio_existence->updated_at];

        } catch (\Exception | \PDOException $th) {
            
            return ['error' => $th->getMessage()];

        }

    }

}
