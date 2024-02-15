<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuitarController extends Controller
{
    
    public function store(Request $request){

        try {

            $request->validate(
                [
                    'name' => 'required',
                    'id_user' => 'required',
                ]
            );
    
            $guitar = new \App\Models\Guitar;
    
            $guitar->name = $request->name;
            $guitar->id_user = $request->id_user;
    
            return $guitar->save();

        } catch (\Exception | \PDOException $th) {
            
            return $th;

        }

    }

    public function show($id_user){

        $guitar = \App\Models\Guitar::where('id_user', $id_user)->get();

        return $guitar;

    }

}
