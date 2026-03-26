<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuizApiController extends Controller
{
    public function getQuestions()
    {
        $questions = Question::orderBy('question_number')->get();

        return response()->json([
            'success' => true,
            'questions' => $questions,
        ]);
    }
}
