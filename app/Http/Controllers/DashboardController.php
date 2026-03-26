<?php

namespace App\Http\Controllers;

use App\Services\DatabaseQuizRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $repository;

    public function __construct(DatabaseQuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('dashboard');
    }

    public function getProgress()
    {
        $teams = $this->repository->getTeamsProgress();

        return response()->json([
            'success' => true,
            'teams' => $teams,
        ]);
    }
}
