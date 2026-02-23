<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Services\QuizGenerator;

class QuizController extends Controller
{
    protected $generator;

    public function __construct(QuizGenerator $generator)
    {
        $this->generator = $generator;
    }

    public function generate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:txt,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        try {
            $text = $this->generator->extractText($file);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Extraction Error: ' . $e->getMessage()]);
        }

        if (strlen($text) < 50) {
            return response()->json(['success' => false, 'message' => 'Could not extract enough text. Extracted length: ' . strlen($text)]);
        }

        $questionsData = $this->generator->generateQuestions($text);

        if (empty($questionsData)) {
            return response()->json(['success' => false, 'message' => 'Could not generate questions. Try a clearer document.']);
        }

        // Save to DB
        $quiz = Quiz::create([
            'user_id' => $request->user()->id,
            'title' => $file->getClientOriginalName(),
        ]);

        // Save File as Note
        $path = $file->store('notes', 'public');
        \App\Models\Note::create([
            'user_id' => $request->user()->id,
            'title' => $file->getClientOriginalName(),
            'content' => $text, // Store extracted text as content for search/fallback
            'file_path' => $path,
            'folder_id' => null // Optional: Assign to a default folder if needed
        ]);

        foreach ($questionsData as $q) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'answer' => $q['answer']
            ]);
        }

        return response()->json([
            'success' => true,
            'quiz_id' => $quiz->id,
            'title' => $quiz->title,
            'questions' => $questionsData
        ]);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'quiz_id' => 'sometimes|exists:quizzes,id'
        ]);

        $user = $request->user();
        $score = $request->score;

        // Log the attempt
        $user->logQuizAttempt($score);

        // Award XP (Base 20 + 10% of score)
        $xpGained = 20 + round($score * 0.1);
        $leveledUp = $user->addXp($xpGained);

        // Update Quiz record if ID provided
        if ($request->quiz_id) {
            $quiz = Quiz::find($request->quiz_id);
            if ($quiz && $quiz->user_id == $user->id) {
                $quiz->update(['score' => $score]);
            }
        }

        return response()->json([
            'success' => true,
            'avg_score' => $user->average_quiz_score,
            'xp_gained' => $xpGained,
            'new_xp' => $user->xp,
            'leveled_up' => $leveledUp,
            'message' => "Quiz Complete! Score: $score% | +$xpGained XP"
        ]);
    }
}
