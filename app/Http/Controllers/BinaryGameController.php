<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\DatabaseQuizRepository;
use Illuminate\Http\Request;

class BinaryGameController extends Controller
{
    private $repository;

    public function __construct(DatabaseQuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show()
    {
        if (!session('team_id')) {
            return redirect()->route('home');
        }

        $teamId = session('team_id');
        $teamName = session('team_name');
        $teamAnswers = $this->repository->getTeamAnswers($teamId);

        // Coletar binários desbloqueados com informações da letra
        $unlockedBinaries = [];
        foreach ($teamAnswers as $answer) {
            if ($answer['is_correct']) {
                $question = $this->repository->getQuestion($answer['question_id']);
                if ($question && !empty($question['binary_code'])) {
                    $letter = $this->binaryToUpperLetter($question['binary_code']);
                    $unlockedBinaries[] = [
                        'binary' => $question['binary_code'],
                        'letter' => $letter,
                        'question_id' => $answer['question_id']
                    ];
                }
            }
        }

        return view('binary-game', [
            'teamName' => $teamName,
            'unlockedBinaries' => $unlockedBinaries,
            'totalQuestions' => count(array_filter($teamAnswers, fn($a) => $a['is_correct'])),
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $answer = strtoupper(trim($request->input('answer')));
        $correctAnswer = 'PASCOA CESAE';

        if ($answer === $correctAnswer) {
            $teamId = session('team_id');
            if ($teamId) {
                Team::where('id', $teamId)->update(['is_winner' => true]);
            }

            return response()->json([
                'success' => true,
                'message' => '🎉 Parabéns! Desbloqueaste a frase secreta!\n\n🏆 Conseguiste completar o desafio CESAE DIGITAL! 🏆'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => '❌ Frase incorreta! Continua a responder as perguntas para desbloquear mais letras.'
            ], 400);
        }
    }

    public function validateBinaryGuess(Request $request)
    {
        $request->validate([
            'binary' => 'required|string',
            'guess' => 'required|string|size:1',
        ]);

        $binary = $request->input('binary');
        $guess = strtoupper($request->input('guess'));

        $correctLetter = $this->binaryToUpperLetter($binary);

        if ($correctLetter === null) {
            return response()->json([
                'success' => false,
                'message' => '❌ Binário inválido!'
            ], 400);
        }

        if ($guess === $correctLetter) {
            return response()->json([
                'success' => true,
                'letter' => $correctLetter,
                'message' => '✅ Correto! Ganhaste a letra: ' . $correctLetter
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => '❌ Incorreto! Tenta novamente.',
                'hint' => 'A letra não é: ' . $guess
            ], 400);
        }
    }

    private function binaryToUpperLetter(string $binary): ?string
    {
        if (!preg_match('/^[01]{8}$/', $binary)) {
            return null;
        }

        $char = chr(bindec($binary));
        return strtoupper($char);
    }
}

