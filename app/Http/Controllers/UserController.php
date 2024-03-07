<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
    use HasApiTokens;
    
    public function store(Request $request, Response $response){

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        try {
            
            //validando dados enviados
            $request->validate(
                [
                    'email' => 'required|email',
                    'address' => 'required|string',
                    'country' => 'required|string',
                    'location' => 'required|string'
                ],
                [
                    'email.required' => 'Digite seu email.',
                    'email' => 'Email inválido.'
                ]
            );

            //salvando o user do request com email_verified_at null para saber se user tem acesso a conta ou não e verificando se conta já existe..
            $user = new \App\Models\User;
            $user->email = $request->input('email');
            $user->ip = $request->ip;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->local = $request->location;

            // Verifica se o usuário já existe com base no email, telefone e IP
            $existing_user = \App\Models\User::where('email', $request->input('email'))->first();


            if ($existing_user) {
                // O usuário já existe, vai ser criado outro token de entrada na conta dele mesmo...
                $access_token = $existing_user->createToken('existing_account', ['read'], now()->addHours((1)));

                // Enviando e-mail com token gerado em personal_access_tokens para acesso do usuário
                \Illuminate\Support\Facades\Mail::to($request->input('email'))->send(new \App\Mail\LoginMail($existing_user, $access_token->plainTextToken));

                // Retornando uma resposta de sucesso
                return ['existing_user' => $existing_user->created_at];
            } 
            
            // O usuário não existe, então você pode salvá-lo no banco de dados
            $user->save(); 

            //gerando um token em personal_access_tokens com expiração de uma hora..
            $access_token = $user->createToken('create_account', ['read'], now()->addHours(1));

            // Enviando e-mail com token gerado em personal_access_tokens para acesso do usuário
            \Illuminate\Support\Facades\Mail::to($request->input('email'))->send(new \App\Mail\LoginMail($user, $access_token->plainTextToken));

            // Retornando uma resposta de sucesso
            return ['new_user' => $user->created_at];


        } catch (\Exception | \PDOException $th) {
            
            //retornando erro em caso de erros..
            return ['error' => $th];

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
