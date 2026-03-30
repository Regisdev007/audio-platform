<!DOCTYPE html>
<html>
<head>
    <title>Créer Playlist</title>
</head>
<body>

<h2>Créer une playlist 🎶</h2>

<form method="POST" action="/playlists">
    @csrf
    <input type="text" name="name" placeholder="Nom de la playlist" required>
    <button type="submit">Créer</button>
</form>

</body>
</html>
