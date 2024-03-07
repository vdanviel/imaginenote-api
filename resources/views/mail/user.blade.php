@extends('layout');

@section('content')

    @push('css')
        
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f2f2f2;
                margin: 0;
                padding: 0;
            }

            .font-sans {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: #ffffff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            h1 {
                color: #333333;
                font-size: 24px;
                margin-bottom: 20px;
            }

            div {
                color: #666666;
                font-size: 16px;
                line-height: 1.5;
            }

            b {
                color: #009688;
            }
        </style>

    @endpush

    <section>
        <h1>Olá {{$email}}!</h1>

        <div>
            Para acessar a sua conta clique <a href="{{'http://localhost:5173/verify' . "/" . $secret_pass}}">aqui</a>. Obrigado por usar o ImagineNote, anote tudo o que você pensa!
        </div>

        <div>
            <a href="{{'http://localhost:5173/verify' . "/" . $secret_pass}}">{{'http://localhost:5173/verify' . "/" . $secret_pass}}</a>
        </div>
    </section>
    
    
@endsection