<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    public function create()
    {
        return view('playlists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Playlist::create([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        return redirect('/tracks')->with('success', 'Playlist créée avec succès !');
    }

    public function addTrack(Request $request)
    {
        $request->validate([
            'playlist_id' => 'required|exists:playlists,id',
            'track_id' => 'required|exists:tracks,id'
        ]);

        $playlist = Playlist::find($request->playlist_id);

        // évite les doublons
        if (!$playlist->tracks->contains($request->track_id)) {
            $playlist->tracks()->attach($request->track_id);
        }

        return back()->with('success', 'Musique ajoutée à la playlist !');
    }

    public function index()
    {
        $playlists = Playlist::with('tracks')->where('user_id', Auth::id())->get();

        return view('playlists.index', compact('playlists'));
    }

    public function removeTrack(Request $request)
    {
        $playlist = Playlist::find($request->playlist_id);
        $playlist->tracks()->detach($request->track_id);

        return back()->with('success', 'Musique supprimée de la playlist !');
    }
}
