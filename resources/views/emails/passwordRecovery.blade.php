<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prioriza ERP</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: sans-serif;
        }

        h2 {
            color: #f4f4f4;
            margin: 5px;
            padding: 5px;
        }

        .container {
            max-width: 600px;
            padding: 20px;
            background-color: #D9D9D9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0.1, 0.1);
            text-align: center;
            word-wrap: break-word;
        }

        .header {
            background-color: #0D65D6;
            color: #373736;
            padding: 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .footer {
            background-color: #0D65D6;
            color: #D9D9D9;
            padding: 10px;
            font-size: 12px;
            border-top: 1px #555 solid;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            text-align: center;
        }

        .footer img {
            width: 70px;
            display: inline-block;
            margin-right: 10px;
        }

        p {
            color: #373736;
            margin: 10px;
            padding: 10px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #0D65D6;
            color: #D9D9D9;
            text-decoration: none;
            border-radius: 5px;
            width: 250px;
            display: inline-block;
            margin: 5px;
        }

        .token_link {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .token_link a {
            font-size: 12px;
            text-decoration: none;
            color: #2986FF;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <h2>Redefinição de Senha | SolusJur</h2>
    </div>
    <p>Olá,</p>
    <p>Recebemos sua solicitação para redefinir a senha de acesso. Para continuar clique no botão abaixo:</p>
    <a href="{{ env('URL_PASSWORD_RESET') }}?token={{$token}}&email={{urlencode($user->email)}}" class="btn">Redefinir Senha</a>
    <p><br>Caso você não tenha solicitado a redefinição de senha, ignore esta mensagem</p>

    <div class="footer">
        <img src="{{ asset('images/soluct-logo.png') }}" alt="Logo">
        <br>
        &copy;Soluct soluções em sistemas
    </div>
</div>
</body>

</html>
