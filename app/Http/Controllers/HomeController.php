<?php

namespace App\Http\Controllers;

use App\Services\DatabaseQuizRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $repository;

    public function __construct(DatabaseQuizRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('home');
    }

    public function checkTeam(Request $request)
    {
        $teamName = $request->input('team_name');
        $team = $this->repository->getTeamByName($teamName);

        if ($team) {
            // Equipa existe
            return response()->json([
                'exists' => true,
                'team_name' => $team['name']
            ]);
        } else {
            // Equipa não existe
            return response()->json([
                'exists' => false
            ]);
        }
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string',
            'team_password' => 'required|string',
        ]);

        $teamName = $request->input('team_name');
        $teamPassword = $request->input('team_password');

        if ($this->repository->verifyTeamPassword($teamName, $teamPassword)) {
            return response()->json([
                'success' => true,
                'message' => 'Password correta!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password incorreta!'
            ], 401);
        }
    }

    public function joinTeam(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'team_password' => 'nullable|string',
        ]);

        $teamName = $request->input('team_name');
        $teamPassword = $request->input('team_password');

        // Verifica se equipa já existe
        $existingTeam = $this->repository->getTeamByName($teamName);

        if ($existingTeam) {
            // Equipa existe - verifica password (dobra verificação por segurança)
            if (!$this->repository->verifyTeamPassword($teamName, $teamPassword)) {
                return back()->withErrors(['team_password' => 'Password incorreta!']);
            }
            $team = $existingTeam;
        } else {
            // Equipa nova - cria com password aleatória ou custom
            $team = $this->repository->createTeam(
                $teamName,
                $teamPassword ? strtoupper($teamPassword) : null
            );
        }

        session(['team_id' => $team['id'], 'team_name' => $team['name'], 'team_code' => $team['code']]);

        return redirect()->route('quiz.index');
    }
}
