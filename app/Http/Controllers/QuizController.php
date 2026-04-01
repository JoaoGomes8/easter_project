<?php

namespace App\Http\Controllers;

use App\Services\DatabaseQuizRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    private $repository;

    public function __construct(DatabaseQuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        if (!session('team_id')) {
            return redirect()->route('home');
        }

        $questions = $this->repository->getAllQuestions();
        $teamId = session('team_id');
        $teamName = session('team_name');
        $teamAnswers = $this->repository->getTeamAnswers($teamId);
        $team = $this->repository->getTeamById($teamId);

        return view('quiz-new', [
            'questions' => $questions,
            'teamId' => $teamId,
            'teamName' => $teamName,
            'teamAnswers' => $teamAnswers,
            'teamScore' => $team['score'] ?? 0,
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|numeric',
            'answer' => 'required|string',
        ]);

        $teamId = session('team_id');
        $questionId = $request->input('question_id');
        $rawUserAnswer = trim($request->input('answer'));

        $question = $this->repository->getQuestion($questionId);
        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Pergunta não encontrada'
            ], 404);
        }

        $normalize = function (string $value): string {
            $value = Str::ascii(mb_strtolower(trim($value)));
            $value = preg_replace('/[^a-z0-9\s]/', '', $value);
            $value = preg_replace('/\s+/', ' ', $value);
            return trim($value);
        };

        $userAnswer = $normalize($rawUserAnswer);
        $correctAnswer = $normalize((string) $question['correct_answer']);
        $isCorrect = $userAnswer === $correctAnswer;

        // Respostas alternativas para perguntas específicas
        if (!$isCorrect && (int) ($question['question_number'] ?? 0) === 5) {
            // Pergunta 5 aceita "8" ou "oito"
            if ($correctAnswer === '8' && in_array($userAnswer, ['8', 'oito'], true)) {
                $isCorrect = true;
            }
        }

        $saveResult = $this->repository->saveAnswer($teamId, $questionId, $rawUserAnswer, $isCorrect, $question);

        $response = [
            'success' => true,
            'is_correct' => $isCorrect,
            'hint' => $question['hint'],
            'explanation' => $question['explanation'],
            'score_delta' => $saveResult['score_delta'] ?? 0,
            'current_score' => $saveResult['score'] ?? 0,
            'already_solved' => $saveResult['already_solved'] ?? false,
        ];

        // Adicionar binário se a resposta está correta
        if ($isCorrect && !empty($question['binary_code'])) {
            $response['binary_code'] = $question['binary_code'];
        }

        return response()->json($response);
    }
}
