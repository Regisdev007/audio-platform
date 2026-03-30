<!DOCTYPE html>
<html>
<head>
    <title>Audio Platform</title>

    <script src="https://www.youtube.com/iframe_api"></script>

    <style>
        body {
            font-family: Arial;
            background: #121212;
            color: white;
            margin: 0;
            padding: 20px;
        }

        h1 { text-align:center; }

        /* 🔥 NAVBAR PRO */
        .navbar {
            display:flex;
            justify-content:space-between;
            align-items:center;
            background:#1e1e1e;
            padding:15px 20px;
            border-radius:10px;
            margin-bottom:20px;
        }

        .nav-left a {
            margin-right:15px;
            color:#1DB954;
            text-decoration:none;
            font-weight:bold;
        }

        .nav-left a:hover {
            color:white;
        }

        .nav-right {
            display:flex;
            align-items:center;
            gap:15px;
        }

        .logout-btn {
            background:red;
            border:none;
            padding:6px 10px;
            color:white;
            border-radius:5px;
            cursor:pointer;
        }

        .logout-btn:hover {
            background:darkred;
        }

        .auth-link {
            color:#1DB954;
            text-decoration:none;
            font-weight:bold;
        }

        .auth-link:hover {
            color:white;
        }

        /* 🎧 TRACK */
        .track {
            background:#1e1e1e;
            padding:15px;
            margin-bottom:15px;
            border-radius:10px;
            transition:0.2s;
        }

        .track:hover {
            transform:scale(1.02);
        }

        button {
            background:#1DB954;
            border:none;
            padding:6px 10px;
            color:white;
            cursor:pointer;
            border-radius:5px;
        }

        button:hover {
            background:#17a74a;
        }

        /* 🎧 PLAYER */
        #player {
            position:fixed;
            bottom:0;
            left:0;
            width:100%;
            background:black;
            padding:10px;
        }
    </style>
</head>

<body>

<!-- 🔥 NAVBAR -->
<div class="navbar">
    <div class="nav-left">
        <a href="/tracks">🎵 Musiques</a>
        <a href="/playlists">🎶 Playlists</a>
        <a href="/tracks/create">➕ Ajouter</a>
        <a href="/tracks/online">🌍 En ligne</a>
        <a href="/tracks/youtube">🔥 YouTube</a>
    </div>

    <div class="nav-right">

        @if(auth()->check())
            <span>👤 {{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Déconnexion</button>
            </form>
        @else
            <a href="/login" class="auth-link">Connexion</a>
            <a href="/register" class="auth-link">Inscription</a>
        @endif

    </div>
</div>

<h1>🎧 Audio Streaming Platform</h1>

{{-- 🔥 PLAYLIST MIXTE --}}
<script>
let playlist = [
@foreach($tracks as $track)
{
    type: "local",
    file: "{{ asset($track->file_path) }}",
    title: "{{ $track->title }}"
},
@endforeach

{
    type: "youtube",
    id: "3tmd-ClpJxA",
    title: "Naza - Loin de moi"
},
{
    type: "youtube",
    id: "JGwWNGJdvx8",
    title: "Ed Sheeran"
}
];

let currentIndex = 0;
let ytPlayer;
</script>

{{-- 🎵 TRACKS --}}
@foreach($tracks as $track)
<div class="track">
    <h3>{{ $track->title }}</h3>
    <p>Artiste : {{ $track->artist->name }}</p>

    <button onclick="playMusicIndex({{ $loop->index }})">
        ▶️ Lire
    </button>
</div>
@endforeach

{{-- 🎧 PLAYER --}}
<div id="player">

    <button onclick="prevMusic()">⏮️</button>
    <button onclick="nextMusic()">⏭️</button>

    <strong id="now-playing">Aucune musique</strong>

    <audio id="audio-player" controls style="width:100%;"></audio>

</div>

<div id="youtube-player" style="display:none;"></div>

<script>
function onYouTubeIframeAPIReady() {
    ytPlayer = new YT.Player('youtube-player', {
        height: '0',
        width: '0'
    });
}

function playMusicIndex(index) {
    currentIndex = index;
    playCurrent();
}

function playCurrent() {
    let track = playlist[currentIndex];
    let audio = document.getElementById('audio-player');
    let now = document.getElementById('now-playing');

    if (track.type === "local") {
        audio.src = track.file;
        audio.play();
        if (ytPlayer) ytPlayer.stopVideo();
    }

    if (track.type === "youtube") {
        audio.pause();
        if (ytPlayer && track.id) {
            ytPlayer.loadVideoById(track.id);
        }
    }

    now.innerText = "🎧 " + track.title;
}

function nextMusic() {
    currentIndex++;
    if (currentIndex >= playlist.length) currentIndex = 0;
    playCurrent();
}

function prevMusic() {
    currentIndex--;
    if (currentIndex < 0) currentIndex = playlist.length - 1;
    playCurrent();
}

document.addEventListener("DOMContentLoaded", () => {
    let audio = document.getElementById('audio-player');
    audio.addEventListener('ended', nextMusic);
});
</script>

</body>
</html>
