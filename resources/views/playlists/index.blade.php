<!DOCTYPE html>
<html>
<head>
    <title>Mes playlists</title>

    <style>
        body {
            background:#121212;
            color:white;
            padding:20px;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align:center;
            margin-bottom:30px;
        }

        .playlist {
            margin-bottom:20px;
            background:#1e1e1e;
            padding:15px;
            border-radius:10px;
        }

        .track {
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:10px;
        }

        button {
            padding:6px 10px;
            border:none;
            border-radius:5px;
            cursor:pointer;
        }

        .play-btn {
            background:#1DB954;
            color:white;
        }

        .delete-btn {
            background:red;
            color:white;
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

        .navbar a:hover {
            color: white;
        }

        #player {
            position:fixed;
            bottom:0;
            left:0;
            width:100%;
            background:#000;
            padding:10px;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<div class="navbar">
    <a href="/tracks">🎵 Musiques</a>
    <a href="/playlists">🎶 Mes playlists</a>
    <a href="/playlists/create">➕ Créer playlist</a>
</div>

<h1>🎶 Mes Playlists</h1>

@foreach($playlists as $playlist)
    <div class="playlist">
        <h3>{{ $playlist->name }}</h3>

        @foreach($playlist->tracks as $track)
            <div class="track">

                {{-- BOUTON LECTURE --}}
                <button class="play-btn"
                    onclick="playMusic('{{ asset($track->file_path) }}', '{{ $track->title }}')">
                    ▶️ {{ $track->title }}
                </button>

                {{-- SUPPRESSION --}}
                <form method="POST" action="/playlist/remove-track">
                    @csrf
                    <input type="hidden" name="playlist_id" value="{{ $playlist->id }}">
                    <input type="hidden" name="track_id" value="{{ $track->id }}">
                    <button class="delete-btn">❌</button>
                </form>

            </div>
        @endforeach

    </div>
@endforeach


{{-- 🎧 LECTEUR UNIQUE --}}
<div id="player">
    <strong id="now-playing">Aucune musique</strong>
    <audio id="audio-player" controls style="width:100%;"></audio>
</div>


{{-- SCRIPT --}}
<script>
function playMusic(file, title) {
    let player = document.getElementById('audio-player');
    let now = document.getElementById('now-playing');

    player.src = file;
    player.play();
    now.innerText = "🎧 " + title;
}
</script>

</body>
</html>
