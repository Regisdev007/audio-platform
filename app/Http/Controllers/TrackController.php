<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Album;
use Illuminate\Support\Facades\Http;

class TrackController extends Controller
{
    public function index()
    {
        // 🔍 recherche
        $query = request('search');

        // 🎵 tracks avec filtre
        $tracks = Track::with('artist', 'album')
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                  ->orWhereHas('artist', function ($q2) use ($query) {
                      $q2->where('name', 'like', "%$query%");
                  });
            })
            ->get();

        // 📂 playlists utilisateur
        $playlists = [];
        if (Auth::check()) {
            $playlists = Playlist::where('user_id', Auth::id())->get();
        }

        // 🔥 recommandations
        $recommended = collect();

        if ($tracks->count() > 0) {
            $artistId = $tracks->first()->artist_id;

            $recommended = Track::with('artist')
                ->where('artist_id', $artistId)
                ->whereNotIn('id', $tracks->pluck('id')) // éviter doublons
                ->take(3)
                ->get();
        }

        return view('tracks.index', compact('tracks', 'playlists', 'recommended'));
    }

    public function create()
    {
        $artists = Artist::all();
        $albums = Album::all();

        return view('tracks.create', compact('artists', 'albums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|mimes:mp3,wav',
            'artist_id' => 'required|exists:artists,id',
            'album_id' => 'required|exists:albums,id'
        ]);

        // 📁 upload fichier
        $file = $request->file('file');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('music'), $filename);

        // 💾 sauvegarde DB
        Track::create([
            'title' => $request->title,
            'file_path' => 'music/' . $filename,
            'artist_id' => $request->artist_id,
            'album_id' => $request->album_id
        ]);

        return redirect('/tracks')->with('success', 'Musique ajoutée !');
    }

    public function searchOnline(Request $request)
    {
        $query = $request->search;

        $response = Http::get("https://api.deezer.com/search?q=" . $query);

        $data = $response->json();

        return view('tracks.online', [
            'results' => $data['data'] ?? []
        ]);
    }

    public function youtube(Request $request)
    {
        $query = $request->search;
        $results = [];

        if ($query) {

            $apiKey = "AIzaSyCZ3Rb7IZKlsjD5OCKA1_hDleMXa5Y0KKk"; // ⚠️ remplace par ta clé

            $response = Http::get("https://www.googleapis.com/youtube/v3/search", [
                'part' => 'snippet',
                'q' => $query,
                'type' => 'video',
                'maxResults' => 10,
                'key' => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $results = collect($data['items'] ?? [])
                    ->filter(function ($item) {
                        return isset($item['id']['videoId']); // 🔥 évite crash
                    })
                    ->map(function ($item) {
                        return [
                            'id' => $item['id']['videoId'],
                            'title' => $item['snippet']['title'] ?? 'Sans titre'
                        ];
                    })
                    ->values()
                    ->toArray();
            }
        }

        return view('tracks.youtube', compact('results'));
    }
}
