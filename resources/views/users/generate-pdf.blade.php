<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCO</title>
</head>
<body>
    <h2>
        Usuário
    </h2>
    ID: {{ $user->id }} <br>
    Nome: {{ $user->name }} <br>
    E-mail: {{ $user->email }} <br>
    Data de cadastro: {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s') }} <br>
</body>
</html>