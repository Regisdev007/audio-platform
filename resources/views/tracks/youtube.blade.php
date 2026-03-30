<!DOCTYPE html>
<html>
<head>
    <title>YouTube Music</title>

    <script src="https://www.youtube.com/iframe_api"></script>

    <style>
        body {
            background:#121212;
            color:white;
            padding:20px;
            font-family:Arial;
        }

        .track {
            background:#1e1e1e;
            padding:15px;
            margin-bottom:15px;
            border-radius:10px;
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

        #youtube-player {
            margin-top:20px;
        }

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

<h2>🔥 YouTube Audio</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
    <button type="submit">Rechercher</button>
</form>

<br>

{{-- 🎧 PLAYER YOUTUBE --}}
<div id="youtube-player"></div>

<script>
let playlist = [];
let currentIndex = 0;
let ytPlayer = null;
let playerReady = false;

// 🔥 INIT PLAYER
function onYouTubeIframeAPIReady() {
    ytPlayer = new YT.Player('youtube-player', {
        height: '200',
        width: '300',
        playerVars: {
            autoplay: 0
        },
        events: {
            'onReady': function () {
                playerReady = true;
                console.log("Player prêt");
            }
        }
    });
}

// ▶️ LECTURE
function playYT(index) {

    if (!playerReady) {
        alert("⏳ Player pas encore prêt");
        return;
    }

    currentIndex = index;

    let track = playlist[index];

    if (!track || !track.id) {
        alert("❌ Erreur lecture");
        return;
    }

    // 🔥 SOLUTION QUI MARCHE
    ytPlayer.cueVideoById(track.id);
    ytPlayer.playVideo();

    document.getElementById('now').innerText = "🎧 " + track.title;
}

// ⏭️ NEXT
function next() {
    if (playlist.length === 0) return;

    currentIndex++;
    if (currentIndex >= playlist.length) currentIndex = 0;

    playYT(currentIndex);
}

// ⏮️ PREV
function prev() {
    if (playlist.length === 0) return;

    currentIndex--;
    if (currentIndex < 0) currentIndex = playlist.length - 1;

    playYT(currentIndex);
}
</script>

{{-- 🔥 RESULTS --}}
@if(!empty($results))
    @foreach($results as $index => $video)

        <script>
        playlist.push({
            id: "{{ $video['id'] }}",
            title: "{{ $video['title'] }}"
        });
        </script>

        <div class="track">
            <strong>{{ $video['title'] }}</strong><br><br>

            <button onclick="playYT({{ $index }})">▶️ Lire</button>
        </div>

    @endforeach
@else
    <p style="color:red;">❌ Aucun résultat trouvé</p>
@endif


{{-- 🎧 PLAYER GLOBAL --}}
<div id="player">
    <button onclick="prev()">⏮️</button>
    <button onclick="next()">⏭️</button>

    <strong id="now">Aucune musique</strong>
</div>

</body>
</html>
