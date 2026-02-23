<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->latest('updated_at')->get();

        // Data from Index Page
        $channels = [
            [
                'name' => 'Bro Code',
                // Using a known transparent PNG for Bro Code logo
                'image' => 'https://avatars.githubusercontent.com/u/111867189?s=200&v=4',
                'url' => 'https://www.youtube.com/@BroCodez',
                'desc' => 'Coding tutorials on Python, Java, C#, C++, JavaScript, HTML, CSS, React, and more.'
            ],
            [
                'name' => 'freeCodeCamp.org',
                'image' => 'https://raw.githubusercontent.com/freeCodeCamp/design-style-guide/main/downloads/fcc_primary_small.png',
                'url' => 'https://www.youtube.com/@freecodecamp',
                'desc' => 'Learn to code for free. Build projects. Earn certifications.'
            ],
            [
                'name' => 'CrashCourse',
                'image' => 'https://unavatar.io/youtube/crashcourse',
                'url' => 'https://www.youtube.com/@crashcourse',
                'desc' => 'Tons of awesome courses in one awesome channel!'
            ],
            [
                'name' => 'Khan Academy',
                'image' => 'https://unavatar.io/youtube/khanacademy',
                'url' => 'https://www.youtube.com/@khanacademy',
                'desc' => 'A nonprofit with the mission to provide a free, world-class education for anyone, anywhere.'
            ],
            [
                'name' => 'Traversy Media',
                'image' => 'https://unavatar.io/github/bradtraversy',
                'url' => 'https://www.youtube.com/@TraversyMedia',
                'desc' => 'Web development tutorials for all latest web technologies.'
            ],
            [
                'name' => 'CS50',
                'image' => 'https://unavatar.io/youtube/cs50',
                'url' => 'https://www.youtube.com/@cs50',
                'desc' => 'Harvard University\'s introduction to the intellectual enterprises of computer science.'
            ],
            [
                'name' => 'TED-Ed',
                'image' => 'https://unavatar.io/youtube/TEDEd',
                'url' => 'https://www.youtube.com/@TEDEd',
                'desc' => 'TED\'s youth and education initiative.'
            ],
            [
                'name' => 'Physics Wallah',
                'image' => 'https://unavatar.io/youtube/PhysicsWallah',
                'url' => 'https://www.youtube.com/@PhysicsWallah',
                'desc' => 'One of India\'s most loved education platforms.'
            ],
            [
                'name' => 'Unacademy',
                'image' => 'https://unavatar.io/youtube/unacademy',
                'url' => 'https://www.youtube.com/@unacademy',
                'desc' => 'Comprehensive exam preparation for CBSE, JEE, NEET, UPSC.'
            ]
        ];

        // Fetch Recent Visits (Unique by Channel Name)
        $recentChannels = \App\Models\ChannelVisit::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('channel_name')
            ->take(4);

        return view('library', compact('notes', 'channels', 'recentChannels'));
    }

    public function trackVisit(Request $request)
    {
        $request->validate([
            'channel_name' => 'required|string',
            'channel_url' => 'required|url',
            'channel_image' => 'nullable|string'
        ]);

        \App\Models\ChannelVisit::create([
            'user_id' => Auth::id(),
            'channel_name' => $request->channel_name,
            'channel_url' => $request->channel_url,
            'channel_image' => $request->channel_image
        ]);

        return response()->json(['success' => true]);
    }
}
