<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Laravel\Sanctum\HasApiTokens;
use Monolog\Processor\UidProcessor;

class UserController extends Controller
{
    use HasApiTokens;
    
    public function store(Request $request){

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

            //coleção de itens q vou usar para gerar um random de ints para o pin..
            $ints = collect([0,1,2,3,4,5,6,7,8,9]);

            // Verifica se o usuário já existe com base no email, telefone e IP
            $existing_user = \App\Models\User::where('email', $request->email)->first();

            if ($existing_user) {

                //procurando outros tokens desse usuário..
                $latest_tokens = \App\Models\AcessCode::where('id_user', $existing_user->id);

                //excluindo eles..
                $latest_tokens->delete();

                // O usuário já existe, vai ser criado outro token de entrada na conta dele mesmo...
                $access_token = new \App\Models\AcessCode();

                $access_token->name = 'existing_user';
                $access_token->pin = implode("",$ints->random(5)->toArray());//pin de 5 caracteres
                $access_token->token = md5(date('YmdHis') . '' . (new UidProcessor)->getUid());
                $access_token->id_user = $existing_user->id;
                $access_token->expires_at = now()->addHour();
                
                //salvando token..
                $access_token->save();

                // Enviando e-mail com token gerado em personal_access_tokens para acesso do usuário
                \Illuminate\Support\Facades\Mail::to($request->input('email'))->send(new \App\Mail\LoginMail($existing_user, $access_token->pin));

                // Retornando uma resposta de sucesso
                return ['existing_user' => $existing_user->created_at];
            } 
            
            //recuprando info para salvar user..
            $user = new \App\Models\User;
            $user->email = $request->email;
            $user->ip = $request->ip;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->local = $request->location;
            $user->secret_pass = \Illuminate\Support\Str::random(10);

            //salvando user..
            $user->save(); 

            //procurando outros tokens desse usuário..
            $latest_tokens = \App\Models\AcessCode::where('id_user', $user->id);

            //excluindo eles..
            $latest_tokens->delete();

            //gerando um token em personal_access_tokens com expiração de uma hora..
            $access_token = new \App\Models\AcessCode();

            $access_token->name = 'new_user';
            $access_token->pin = implode("",$ints->random(5)->toArray());//pin de 5 caracteres
            $access_token->token = md5(date('YmdHis') . '' . (new UidProcessor)->getUid());
            $access_token->id_user = $user->id;
            $access_token->expires_at = now()->addHour();

            //salvando token..
            $access_token->save();

            // Enviando e-mail com token gerado em personal_access_tokens para acesso do usuário
            \Illuminate\Support\Facades\Mail::to($request->input('email'))->send(new \App\Mail\LoginMail($user, $access_token->pin));

            // Retornando uma resposta de sucesso
            return ['new_user' => $user->created_at];


        } catch (\Exception | \PDOException $th) {
            
            //retornando erro em caso de erros..
            return ['error' => $th->getMessage()];


        }

    }

    public function authenticate(Request $request) {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), 
            [
                'pin' => 'required'
            ],

            [
                'required' => 'Preencha o campo.'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()], 400);
        }
    
        $access = \App\Models\AcessCode::where('pin', $request->input('pin'))->first();
    
        if ($access) {
            if (strtotime($access->expires_at) > strtotime(now()->toDateTimeString())) {
                $user = \App\Models\User::find($access->id_user);
                return response()->json(['user' => $user->secret_pass], 200);
            } else {
                return response()->json(['error' => 'expired_token'], 400);
            }
        } else {
            return response()->json(['error' => 'absent_token'], 400);
        }
    }
    

    public function show(Request $request){

        return \App\Models\User::find($request->id);

    }

    public function user_data(Request $request){

        $user = \App\Models\User::where('secret_pass', $request->input('token'))->first();
    
        if (!$user) {
            return ['error' => 'user_not_exists'];
        }
        
        return $user;
    }


}
