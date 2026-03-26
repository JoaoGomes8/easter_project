<?php

namespace App\Http\Controllers;

use App\Services\QuizRepository;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    private $repository;

    public function __construct(QuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $questions = $this->repository->getAllQuestions();
        $teams = $this->repository->getAllTeams();
        $answers = $this->repository->getAllAnswers();

        return view('monitor', [
            'questions' => $questions,
            'teams' => $teams,
            'answers' => $answers,
        ]);
    }

    public function getAnswers()
    {
        $teams = $this->repository->getAllTeams();
        $answers = $this->repository->getAllAnswers();
        $questions = $this->repository->getAllQuestions();

        $data = [];
        foreach ($teams as $team) {
            $teamAnswers = [];
            foreach ($questions as $question) {
                $answer = collect($answers)
                    ->where('team_id', $team['id'])
                    ->where('question_id', $question['id'])
                    ->first();

                $teamAnswers[] = [
                    'question_id' => $question['id'],
                    'question_number' => $question['question_number'],
                    'is_answered' => !is_null($answer),
                    'is_correct' => $answer ? $answer['is_correct'] : false,
                    'user_answer' => $answer ? $answer['user_answer'] : '',
                ];
            }

            $data[] = [
                'team_id' => $team['id'],
                'team_name' => $team['name'],
                'team_code' => $team['code'],
                'team_color' => $team['color'],
                'answers' => $teamAnswers,
                'correct_count' => collect($teamAnswers)->where('is_correct', true)->count(),
            ];
        }

        return response()->json([
            'success' => true,
            'teams' => $data,
        ]);
    }
}
