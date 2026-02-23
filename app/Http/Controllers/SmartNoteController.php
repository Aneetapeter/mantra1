<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartNoteController extends Controller
{
    // Fetch all data for the Notes App (Folders, Tags, Initial Notes)
    public function index()
    {
        $user_id = Auth::id();

        $folders = Folder::where('user_id', $user_id)->withCount('notes')->get();
        $tags = Tag::where('user_id', $user_id)->get();
        // Fetch recent notes or all notes initially
        $notes = Note::where('user_id', $user_id)
            ->with('tags')
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->get();

        return response()->json([
            'folders' => $folders,
            'tags' => $tags,
            'notes' => $notes
        ]);
    }

    // Create a new Note
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title ?? 'Untitled Note',
            'content' => $request->input('content') ?? '',
            'folder_id' => $request->folder_id
        ]);

        return response()->json($note);
    }

    // Update Note (Auto-save)
    // Update Note (Auto-save)
    public function update(Request $request, $id)
    {
        try {
            $note = Note::where('user_id', Auth::id())->findOrFail($id);

            $data = $request->only(['title', 'content', 'is_pinned']);

            // Handle folder_id specifically to ensure null if empty
            if ($request->has('folder_id')) {
                $data['folder_id'] = $request->folder_id ? $request->folder_id : null;
            }

            $note->update($data);

            if ($request->has('tags')) {
                $note->tags()->sync($request->tags);
            }

            return response()->json(['success' => true, 'note' => $note]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Delete Note
    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->delete();
        return response()->json(['success' => true]);
    }

    // Create Folder
    public function storeFolder(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $folder = Folder::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);
        return response()->json($folder);
    }

    // Delete Folder
    public function destroyFolder($id)
    {
        $folder = Folder::where('user_id', Auth::id())->findOrFail($id);
        $folder->delete();
        return response()->json(['success' => true]);
    }

    // Create Tag
    public function storeTag(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $tag = Tag::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'color' => $request->color ?? '#6366f1'
        ]);
        return response()->json($tag);
    }

    // Extract text from file WITHOUT saving a note (for the "Save to Notes" modal flow)
    public function extract(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $content = '';

        try {
            $generator = app(\App\Services\QuizGenerator::class);
            $extracted = $generator->extractText($file);
            if (strlen($extracted) > 10) {
                $content = $extracted;
            }
        } catch (\Exception $e) {
            // Fallback: empty content
        }

        return response()->json([
            'success' => true,
            'title' => $title,
            'text' => $content,
        ]);
    }

    // Upload File for Smart Note
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');

        $path = $file->store('notes', 'public');
        $title = $file->getClientOriginalName();
        $content = "Uploaded File: " . $title;

        // Try to extract text if QuizGenerator is available
        try {
            $generator = app(\App\Services\QuizGenerator::class);
            $extracted = $generator->extractText($file);
            if (strlen($extracted) > 10)
                $content = $extracted;
        } catch (\Exception $e) {
            // Fallback
        }

        // Ensure content is in Editor.js format (JSON)
        $jsonContent = json_encode([
            'time' => time(),
            'blocks' => [
                [
                    'type' => 'header',
                    'data' => [
                        'text' => $title,
                        'level' => 2
                    ]
                ],
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => substr($content, 0, 2000) . (strlen($content) > 2000 ? '...' : '')
                    ]
                ]
            ]
        ]);

        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $jsonContent,
            'file_path' => $path,
            'is_pinned' => false
        ]);

        return response()->json([
            'success' => true,
            'note' => $note,
            'message' => 'File uploaded successfully'
        ]);
    }
}
