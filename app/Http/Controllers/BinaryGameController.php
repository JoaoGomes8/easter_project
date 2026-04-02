<?php

namespace App\Http\Controllers;

use App\Services\DatabaseQuizRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

        $team = \App\Models\Team::find($teamId);
        $guessedQuestionIds = $team->guessed_question_ids ?? [];

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
                        'question_id' => $answer['question_id'],
                        'is_guessed' => in_array($answer['question_id'], $guessedQuestionIds)
                    ];
                }
            }
        }

        // Calcular posições reveladas baseado nos question_ids acertados
        $revealedPositions = $this->calculateRevealedPositions($unlockedBinaries, $guessedQuestionIds);

        return view('binary-game', [
            'teamName' => $teamName,
            'unlockedBinaries' => $unlockedBinaries,
            'totalQuestions' => count(array_filter($teamAnswers, fn($a) => $a['is_correct'])),
            'revealedPositions' => $revealedPositions,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $teamId = session('team_id');
        if (!$teamId) {
            return response()->json([
                'success' => false,
                'message' => 'Equipa não encontrada.'
            ], 403);
        }

        $questions = collect($this->repository->getAllQuestions());
        $teamAnswers = collect($this->repository->getTeamAnswers($teamId));

        if (!$this->hasCompletedQuizPerfectly($questions, $teamAnswers)) {
            return response()->json([
                'success' => false,
                'message' => 'Só podes vencer depois de responderes corretamente a todas as perguntas e completares o jogo da frase.'
            ], 400);
        }

        $answer = strtoupper(trim($request->input('answer')));
        $correctAnswer = 'PASCOA CESAE';

        if ($answer === $correctAnswer) {
            $result = $this->repository->markPhraseGameCompleted($teamId);

            $message = $result['is_winner']
                ? '🎉 Parabéns! Desbloqueaste a frase secreta!\n\n🏆 A tua equipa cumpre as condições de vitória neste momento: todas as perguntas certas, jogo da frase completo e maior pontuação. 🏆'
                : '🎉 Parabéns! Desbloqueaste a frase secreta!\n\nA tua equipa concluiu o jogo da frase. Para ganhar, também precisa de terminar com a maior pontuação.';

            return response()->json([
                'success' => true,
                'message' => $message,
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
            'question_id' => 'required|integer',
        ]);

        $binary = $request->input('binary');
        $guess = strtoupper($request->input('guess'));
        $questionId = $request->input('question_id');

        $correctLetter = $this->binaryToUpperLetter($binary);

        if ($correctLetter === null) {
            return response()->json([
                'success' => false,
                'message' => '❌ Binário inválido!'
            ], 400);
        }

        if ($guess === $correctLetter) {
            // Guardar o question_id na lista de acertados
            $teamId = session('team_id');
            if ($teamId) {
                $team = \App\Models\Team::find($teamId);
                if ($team) {
                    $guessedIds = $team->guessed_question_ids ?? [];
                    if (!in_array($questionId, $guessedIds)) {
                        $guessedIds[] = $questionId;
                        $team->update(['guessed_question_ids' => $guessedIds]);
                    }
                }
            }

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

    private function hasCompletedQuizPerfectly(Collection $questions, Collection $teamAnswers): bool
    {
        if ($questions->isEmpty()) {
            return false;
        }

        $correctQuestionIds = $teamAnswers
            ->where('is_correct', true)
            ->pluck('question_id')
            ->unique();

        return $correctQuestionIds->count() === $questions->count();
    }

    private function calculateRevealedPositions(array $unlockedBinaries, array $guessedQuestionIds): array
    {
        $PHRASE = 'PASCOA CESAE';
        $PHRASE_POSITIONS = [
            'P' => [0],
            'A' => [1, 5, 9],
            'S' => [2, 10],
            'C' => [3, 7],
            'O' => [4],
            ' ' => [6],
            'E' => [8, 11]
        ];

        $revealedPositions = [];
        $letterCountByPosition = [];

        // Contar quantos binários de cada letra foram acertados
        $letterCounts = [];
        foreach ($unlockedBinaries as $binary) {
            if (in_array($binary['question_id'], $guessedQuestionIds)) {
                $letter = $binary['letter'];
                $letterCounts[$letter] = ($letterCounts[$letter] ?? 0) + 1;
            }
        }

        // Para cada letra com count, revelar as primeiras N posições
        foreach ($letterCounts as $letter => $count) {
            if (isset($PHRASE_POSITIONS[$letter])) {
                $positions = $PHRASE_POSITIONS[$letter];
                // Revelar as primeiras $count posições dessa letra
                for ($i = 0; $i < min($count, count($positions)); $i++) {
                    $revealedPositions[] = $positions[$i];
                }
            }
        }

        return $revealedPositions;
    }

    public function getGuessedIds()
    {
        if (!session('team_id')) {
            return response()->json([
                'success' => false,
                'guessed_ids' => []
            ], 403);
        }

        $team = \App\Models\Team::find(session('team_id'));

        return response()->json([
            'success' => true,
            'guessed_ids' => $team->guessed_question_ids ?? []
        ]);
    }
}


