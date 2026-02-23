<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

class QuizGenerator
{
    public function extractText($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // PDF
        if ($extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
            return $pdf->getText();
        }

        // DOCX (Word)
        if ($extension === 'docx' || $extension === 'doc') {
            try {
                $phpWord = WordIOFactory::load($file->getPathname());
                $fullText = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $fullText .= $element->getText() . " ";
                        }
                    }
                }
                return $fullText;
            } catch (\Exception $e) {
                return ''; // Log error if needed
            }
        }

        // EXCEL (XLSX, XLS)
        if ($extension === 'xlsx' || $extension === 'xls') {
            try {
                $spreadsheet = SpreadsheetIOFactory::load($file->getPathname());
                $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                $fullText = '';
                foreach ($sheetData as $row) {
                    $fullText .= implode(" ", array_filter($row)) . ". ";
                }
                return $fullText;
            } catch (\Exception $e) {
                return '';
            }
        }

        // Default to text file
        return file_get_contents($file->getPathname());
    }

    public function generateQuestions($text)
    {
        // STEP 3: Clean the extracted text
        // $text = strtolower($text); // Keep case for better sentence splitting, lower later in logic
        $cleanText = preg_replace('/[^a-zA-Z0-9.\s]/', '', $text);
        $cleanText = preg_replace('/\s+/', ' ', $cleanText);

        // Split into sentences
        $sentences = explode('.', $cleanText);
        $questions = [];

        foreach ($sentences as $s) {
            $s = trim($s);
            if (strlen($s) < 20)
                continue; // Skip short sentences

            $question = null;
            $answer = null;
            $options = [];

            // STEP 4: Rule-based question generation
            $lowerS = strtolower($s);

            // Rule 1: Definition -> MCQ
            if (str_contains($lowerS, ' is ') || str_contains($lowerS, ' means ')) {
                $subject = $this->extractSubject($s);
                if ($subject) {
                    $question = "What best describes " . $subject . "?";
                    $answer = $s; // The full sentence is the definition
                }
            }
            // Rule 2: Focus / Emphasizes -> MCQ
            elseif (str_contains($lowerS, 'focuses on') || str_contains($lowerS, 'emphasizes')) {
                $subject = $this->extractSubject($s);
                if ($subject) {
                    $question = "What does " . $subject . " focus on?";
                    $answer = $s;
                }
            }
            // Rule 3: True / False (Random 20% chance if no other rule matched)
            elseif (rand(1, 5) === 1) {
                $question = $s . " (True or False)";
                $answer = "True";
                $options = ["True", "False"];
            }

            // Rule 4: Fill in the blanks (Fall back if no question yet)
            if (!$question && rand(1, 2) === 1) {
                $keywords = $this->extractKeywords($s);
                if (!empty($keywords)) {
                    $keyword = $keywords[0];
                    $question = str_replace($keyword, '_____', $s);
                    $answer = $keyword;

                    // Generate distractor options
                    $distractors = $this->getDistractors($text, $keyword);
                    $options = array_merge([$answer], $distractors);
                    shuffle($options);
                }
            }

            // Fallback for Rules 1 & 2 options
            if ($question && empty($options) && $answer) {
                // For definitions, distractors are hard. 
                // Simple logic: Use other sentences as fake answers? 
                // Or just make it True/False if definition?
                // Let's stick to user request: "Generate options"
                // Since extracting subjects from other sentences is hard, we'll convert Rules 1/2 to Fill-in-blanks style logic for simplicity in options
                // Actually, let's treat Rules 1 & 2 as special cases where the answer is the predicate.
                // For simplicity in this v1, I will convert Rules 1 & 2 to return the FULL SENTENCE as the answer, and random other sentences as options.
                $distractors = $this->getRandomSentences($sentences, $s, 3);
                $options = array_merge([$answer], $distractors);
                shuffle($options);
            }

            if ($question && $answer && !empty($options)) {
                $questions[] = [
                    'question' => $question,
                    'options' => $options,
                    'answer' => $answer
                ];
            }

            if (count($questions) >= 10)
                break; // Limit to 10 questions
        }

        return $questions;
    }

    private function extractSubject($sentence)
    {
        // Simple heuristic: Take first 3 words or words before 'is'
        $parts = explode(' ', $sentence);
        return implode(' ', array_slice($parts, 0, 3));
    }

    private function extractKeywords($sentence)
    {
        // Find longest words (> 5 chars)
        $words = explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $sentence));
        $longWords = array_filter($words, fn($w) => strlen($w) > 5);
        return array_values($longWords);
    }

    private function getDistractors($fullText, $correctWord)
    {
        // Get random words from full text
        $allWords = explode(' ', preg_replace('/[^a-zA-Z\s]/', '', $fullText));
        $validWords = array_filter($allWords, fn($w) => strlen($w) > 5 && strtolower($w) !== strtolower($correctWord));

        if (count($validWords) < 3)
            return ['Option A', 'Option B', 'Option C'];

        // Pick 3 random
        shuffle($validWords);
        return array_slice($validWords, 0, 3);
    }

    private function getRandomSentences($allSentences, $exclude, $count)
    {
        $others = array_filter($allSentences, fn($s) => trim($s) !== trim($exclude) && strlen($s) > 20);
        if (count($others) < $count)
            return array_pad([], $count, "N/A");

        // Pick random keys
        $keys = array_rand($others, min($count, count($others)));
        if (!is_array($keys))
            $keys = [$keys];

        $result = [];
        foreach ($keys as $k) {
            $result[] = trim($others[$k]);
        }
        return $result;
    }
}
