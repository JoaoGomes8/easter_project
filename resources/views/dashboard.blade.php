<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pontuações - Páscoa & Programação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .leaderboard-bg {
            background: linear-gradient(135deg, #f8d7da 0%, #fff9e6 50%, #e8f4ff 100%);
        }
        .team-row {
            transition: all 0.3s ease;
        }
        .team-row:hover {
            transform: translateX(10px);
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .team-row {
            animation: slideIn 0.5s ease-out;
        }

        .winner-row {
            position: relative;
            background: linear-gradient(90deg, rgba(255, 251, 235, 0.9) 0%, rgba(255, 247, 213, 0.9) 50%, rgba(255, 251, 235, 0.9) 100%);
            border-left: 6px solid #f59e0b;
            overflow: hidden;
        }

        .winner-row::after {
            content: "";
            position: absolute;
            top: -120%;
            left: -40%;
            width: 30%;
            height: 300%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.55), rgba(255, 255, 255, 0));
            transform: rotate(18deg);
            animation: winnerShine 2.8s linear infinite;
            pointer-events: none;
        }

        .winner-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.4rem;
            padding: 0.28rem 0.72rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: #78350f;
            border: 1px solid #f59e0b;
            background: linear-gradient(135deg, #fde68a 0%, #fcd34d 55%, #fbbf24 100%);
            box-shadow: 0 0 0 rgba(245, 158, 11, 0.55);
            animation: badgePulse 1.4s ease-in-out infinite;
        }

        .winner-crown {
            display: inline-block;
            animation: crownBounce 1.1s ease-in-out infinite;
            transform-origin: center;
        }

        .winner-medal {
            animation: medalSpin 3s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes winnerShine {
            0% { left: -40%; }
            100% { left: 130%; }
        }

        @keyframes badgePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.45); }
            50% { box-shadow: 0 0 0 8px rgba(245, 158, 11, 0); }
        }

        @keyframes crownBounce {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-2px) rotate(-7deg); }
        }

        @keyframes medalSpin {
            0%, 85%, 100% { transform: rotate(0deg) scale(1); }
            90% { transform: rotate(18deg) scale(1.08); }
            95% { transform: rotate(-12deg) scale(1.08); }
        }

        @media (prefers-reduced-motion: reduce) {
            .winner-row::after,
            .winner-badge,
            .winner-crown,
            .winner-medal,
            .team-row {
                animation: none !important;
            }
        }
    </style>
</head>
<body class="leaderboard-bg min-h-screen p-4 md:p-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">🏆 Pontuações 🏆</h1>
            <p class="text-gray-600 text-lg">Atualiza em tempo real</p>
        </div>

        <!-- Leaderboard -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div id="leaderboard-container" class="divide-y-2 divide-gray-200">
                <!-- Teams will be loaded here -->
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a
                href="{{ session('team_id') ? route('quiz.index') : route('home') }}"
                class="inline-block px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-bold"
            >
                ← Voltar
            </a>
            @if (session('team_id'))
                <form action="{{ route('home.logout') }}" method="POST" class="inline-block ml-3">
                    @csrf
                    <button
                        type="submit"
                        class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-bold"
                    >
                        Sair
                    </button>
                </form>
            @endif
        </div>
    </div>

    <script>
        let lastTeamsData = null;

        function updateLeaderboard() {
            fetch('{{ route("dashboard.progress") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Only render if data has changed
                        const currentData = JSON.stringify(data.teams);
                        if (currentData !== lastTeamsData) {
                            lastTeamsData = currentData;
                            renderLeaderboard(data.teams);
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function renderLeaderboard(teams) {
            const container = document.getElementById('leaderboard-container');

            if (teams.length === 0) {
                container.innerHTML = '<div class="p-8 text-center text-gray-500"><p>Nenhuma equipa registada ainda...</p></div>';
                return;
            }

            // Sort by score first and use correct answers as tie-breaker
            teams.sort((a, b) => (b.score - a.score) || (b.correct_answers - a.correct_answers));

            container.innerHTML = teams.map((team, index) => {
                const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '✨';
                const position = index + 1;
                const winnerClass = team.is_winner ? 'winner-row' : '';
                const winnerBadge = team.is_winner
                    ? '<span class="winner-badge"><span class="winner-crown">👑</span> WINNER</span>'
                    : '';
                const medalClass = team.is_winner ? 'winner-medal' : '';
                const phraseStatusClass = team.phrase_game_completed
                    ? 'bg-green-100 text-green-700'
                    : 'bg-gray-100 text-gray-700';
                const phraseStatusText = team.phrase_game_completed
                    ? 'Jogo da frase: completo'
                    : 'Jogo da frase: não completo';

                return `
                    <div class="team-row ${winnerClass} p-6 flex items-center justify-between hover:bg-purple-50 transition" style="animation-delay: ${index * 100}ms;">
                        <div class="flex items-center gap-6 flex-1">
                            <div class="text-4xl font-bold text-gray-400 w-12 text-center">#${position}</div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">${team.name}</h3>
                                ${winnerBadge}
                                <p class="mt-2 inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ${phraseStatusClass}">${phraseStatusText}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-8">
                            <div class="text-right">
                                <div class="text-3xl font-bold" style="color: ${team.color};">${team.score}</div>
                                <p class="text-sm text-gray-600">pontos</p>
                            </div>
                            <div class="text-4xl ${medalClass}">${medal}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Load leaderboard on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateLeaderboard();
            // Check for updates every 2 seconds (only updates visually if data changes)
            setInterval(updateLeaderboard, 2000);
        });
    </script>
</body>
</html>
