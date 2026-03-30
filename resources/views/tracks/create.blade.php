<!DOCTYPE html>
<html>
<head>
    <title>Ajouter musique</title>

    <style>
        body {
            background:#121212;
            color:white;
            font-family: Arial;
            padding:20px;
        }

        .container {
            max-width:400px;
            margin:auto;
            background:#1e1e1e;
            padding:20px;
            border-radius:10px;
        }

        input, button {
            width:100%;
            padding:10px;
            margin-top:10px;
            border:none;
            border-radius:5px;
        }

        button {
            background:#1DB954;
            color:white;
            cursor:pointer;
        }

        .navbar {
            display: flex;
            gap: 20px;
            background: #1e1e1e;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .navbar a {
            color: #1DB954;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="/tracks">🎵 Musiques</a>
    <a href="/playlists">🎶 Mes playlists</a>
    <a href="/tracks/create">➕ Ajouter musique</a>
</div>

<div class="container">
    <h2>🎵 Ajouter une musique</h2>

    <form method="POST" action="/tracks" enctype="multipart/form-data">
        @csrf

        <input type="text" name="title" placeholder="Titre" required>

        {{-- ARTIST --}}
        <select name="artist_id" required>
            <option value="">Choisir un artiste</option>
            @foreach($artists as $artist)
                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
            @endforeach
        </select>

        {{-- ALBUM --}}
        <select name="album_id" required>
            <option value="">Choisir un album</option>
            @foreach($albums as $album)
                <option value="{{ $album->id }}">{{ $album->title }}</option>
            @endforeach
        </select>

        <input type="file" name="file" required>

        <button type="submit">Uploader</button>
    </form>
</div>

</body>
</html>
