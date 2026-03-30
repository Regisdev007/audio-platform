<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>

    <style>
        body {
            background: linear-gradient(135deg, #121212, #1DB954);
            font-family: Arial;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            margin:0;
        }

        .card {
            background:#1e1e1e;
            padding:30px;
            border-radius:15px;
            width:350px;
            box-shadow:0 0 20px rgba(0,0,0,0.6);
            text-align:center;
        }

        h2 {
            margin-bottom:20px;
            color:#1DB954;
        }

        input {
            width:100%;
            padding:10px;
            margin-bottom:15px;
            border:none;
            border-radius:5px;
            background:#2a2a2a;
            color:white;
        }

        input:focus {
            outline:none;
            border:1px solid #1DB954;
        }

        button {
            width:100%;
            padding:10px;
            background:#1DB954;
            border:none;
            border-radius:5px;
            color:white;
            font-weight:bold;
            cursor:pointer;
        }

        button:hover {
            background:#17a74a;
        }

        .link {
            margin-top:15px;
            display:block;
            color:#ccc;
            text-decoration:none;
        }

        .link:hover {
            color:#1DB954;
        }

        .error {
            background:#ff4d4d;
            padding:10px;
            border-radius:5px;
            margin-bottom:10px;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>🎧 Connexion</h2>

    {{-- ERREURS --}}
    @if($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit">Se connecter</button>
    </form>

    <a href="/register" class="link">Pas de compte ? S'inscrire</a>
</div>

</body>
</html>
