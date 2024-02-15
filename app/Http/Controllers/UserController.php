<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function authenticate(Request $request){

        $ip = $request->input('ip'); // Supondo que você esteja recebendo o IP como parâmetro

        // Verificar se o IP existe na tabela de usuários
        $user = \App\Models\User::where('ip', $ip)->first();

        if ($user) {
            // O IP existe na tabela de usuários
            return $user;
        } else {
            // O IP não existe na tabela de usuários
            return false;
        }

    }

    public function show(Request $request){

        return \App\Models\User::find($request->id);

    }

    public function store(Request $request){

        try {
            
            $request->validate([
                'ip' => 'required',
                'address' => 'required',
                'country' => 'required',
                'location' => 'required'
            ]);
    
            $request->headers->set('Access-Control-Allow-Origin', '*');
            $request->headers->set('Access-Control-Allow-Methods', '*');
            $request->headers->set('Access-Control-Allow-Headers', '*');
            
            $user_exists = \App\Models\User::where('ip', $request->ip)->get();

            //https://laravel.com/docs/10.x/collections#method-isnotempty
            if ($user_exists->isNotEmpty()) {
                
                return false;
    
            }

            $user = new \App\Models\User;
    
            $user->ip = $request->ip;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->local = $request->location;

            return $user->save();

        } catch (\Exception | \PDOException $th) {
            
            return $th;

        }

    }

}
