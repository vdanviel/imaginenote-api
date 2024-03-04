<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
    use HasApiTokens;
    
    public function store(Request $request){

        try {
            
            //validando dados enviados
            $request->validate([
                'email' => 'required',
                'phone' => 'required',
                'ip' => 'required',
                'address' => 'required',
                'country' => 'required',
                'location' => 'required'
            ]);     

            //salvando o user do request com email_verified_at null para saber se user tem acesso a conta ou não e verificando se conta já existe..
            $user = new \App\Models\User;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->ip = $request->ip;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->local = $request->location;
            
            // Verifica se o usuário já existe com base no email, telefone e IP
            $existing_user = \App\Models\User::where('email', $request->email)->orWhere('phone',$request->phone)->orWhere('ip',$request->ip)->first();


            if ($existing_user) {
                // O usuário já existe, vai ser criado outro token de entrada na conta dele mesmo...
                $access_token = $existing_user->createToken('existing_account', ['read'], now()->addHours((1)));

                // Enviando e-mail com token gerado em personal_access_tokens para acesso do usuário
                \Illuminate\Support\Facades\Mail::to($existing_user->email)->send(new \App\Mail\LoginMail($existing_user, $access_token->plainTextToken));

                // Retornando uma resposta de sucesso
                return ['existing_user' => $existing_user->created_at];
            } 
            
            // O usuário não existe, então você pode salvá-lo no banco de dados
            $user->save(); 

            //gerando um token em personal_access_tokens com expiração de uma hora..
            $access_token = $user->createToken('create_account', ['read'], now()->addHours(1));

            // Enviando e-mail com token gerado em personal_access_tokens para acesso do usuário
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\LoginMail($user, $access_token->plainTextToken));

            // Retornando uma resposta de sucesso
            return ['new_user' => $user->created_at];


        } catch (\Exception | \PDOException $th) {
            
            //retornando erro em caso de erros..
            return ['error' => $th->getMessage()];

        }

    }

    public function authenticate($token){
    
        //achando o objeto token do usuário pelo findToken() que acha o objeto token pelo token criptografado que o sanctum dá..
        $access = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        
        if ($access) {        

            //se o token não está expirado..
            if (strtotime($access->expires_at) > strtotime(now()->toDateTimeString())) {

                // Token válido
                $user = \App\Models\User::find($access->tokenable_id);

                //retornando dados do usuário
                return $user;
            }else{
                //token expirado..
                return false;
            }

        }else {
            return false;
        }

    }

    public function show(Request $request){

        return \App\Models\User::find($request->id);

    }




}
