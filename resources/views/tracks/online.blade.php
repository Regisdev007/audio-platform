<!DOCTYPE html>
<html>
<head>
    <title>Musique en ligne</title>

    <style>
        body {
            font-family: Arial;
            background: #121212;
            color: white;
            padding: 20px;
        }

        .navbar {
            display: flex;
            gap: 20px;
            background: #1e1e1e;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .navbar a {
            color: #1DB954;
            text-decoration: none;
            font-weight: bold;
        }

        h2 {
            color: #1DB954;
        }

        .track {
            background: #1e1e1e;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        button {
            background: #1DB954;
            border: none;
            color: white;
            padding: 6px;
            border-radius: 5px;
            cursor: pointer;
        }

        #player {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #000;
            padding: 10px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="/tracks">🎵 Musiques</a>
    <a href="/playlists">🎶 Mes playlists</a>
    <a href="/tracks/create">➕ Ajouter</a>
</div>

<h2>🌍 Musique en ligne</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Recherche..." value="{{ request('search') }}">
    <button>Rechercher</button>
</form>

<br>

{{-- Playlist JS --}}
<script>
let playlist = [
@foreach($results as $music)
{
    file: "{{ $music['preview'] }}",
    title: "{{ $music['title'] }}"
},
@endforeach
];

let currentIndex = 0;
</script>

{{-- RESULTS --}}
@foreach($results as $music)
    <div class="track">

        <strong>{{ $music['title'] }}</strong><br>
        Artiste : {{ $music['artist']['name'] }}<br><br>

        <button onclick="playMusic('{{ $music['preview'] }}', '{{ $music['title'] }}', {{ $loop->index }})">
            ▶️ Lire
        </button>

    </div>
@endforeach


{{-- PLAYER GLOBAL --}}
<div id="player">

    <button onclick="prevMusic()">⏮️</button>
    <button onclick="nextMusic()">⏭️</button>

    <strong id="now-playing">Aucune musique</strong>
    <audio id="audio-player" controls style="width:100%;"></audio>

</div>

<script>
function playMusic(file, title, index) {
    let player = document.getElementById('audio-player');
    let now = document.getElementById('now-playing');

    currentIndex = index;

    player.src = file;
    player.play();

    now.innerText = "🌍 " + title;
}

function nextMusic() {
    currentIndex++;
    if (currentIndex >= playlist.length) currentIndex = 0;

    let track = playlist[currentIndex];
    playMusic(track.file, track.title, currentIndex);
}

function prevMusic() {
    currentIndex--;
    if (currentIndex < 0) currentIndex = playlist.length - 1;

    let track = playlist[currentIndex];
    playMusic(track.file, track.title, currentIndex);
}
</script>

</body>
</html>
