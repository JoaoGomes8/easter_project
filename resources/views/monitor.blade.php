<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor - Respostas em Tempo Real</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .monitor-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .team-card {
            transition: all 0.3s ease;
        }
        .question-box {
            transition: all 0.2s ease;
            min-height: 80px;
        }
        .correct {
            background-color: #10b981;
            color: white;
        }
        .incorrect {
            background-color: #ef4444;
            color: white;
        }
        .pending {
            background-color: #e5e7eb;
            color: #6b7280;
        }
    </style>
</head>
<body class="monitor-bg min-h-screen p-6">
    <div class="max-w-full">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">📊 Monitor de Respostas em Tempo Real</h1>
            <p class="text-blue-100">Atualiza a cada 1 segundo</p>
        </div>

        <!-- Teams Container -->
        <div id="teams-container" class="space-y-6">
            <!-- Teams will be loaded here -->
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a
                href="{{ route('home') }}"
                class="inline-block px-6 py-3 bg-white text-purple-600 rounded-lg hover:bg-gray-100 transition font-bold"
            >
                ← Voltar
            </a>
        </div>
    </div>

    <script>
        function formatAnswer(text) {
            return text.length > 15 ? text.substring(0, 12) + '...' : text;
        }

        function updateMonitor() {
            fetch('{{ route("monitor.answers") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderTeams(data.teams);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function renderTeams(teams) {
            const container = document.getElementById('teams-container');
            container.innerHTML = '';

            if (teams.length === 0) {
                container.innerHTML = '<div class="bg-white rounded-xl shadow-lg p-8 text-center text-gray-500"><p>Nenhuma equipa registada ainda...</p></div>';
                return;
            }

            teams.forEach(team => {
                const teamCard = document.createElement('div');
                teamCard.className = 'team-card bg-white rounded-xl shadow-lg overflow-hidden';

                const headerColor = team.team_color || '#3b82f6';

                teamCard.innerHTML = `
                    <div style="background-color: ${headerColor};" class="p-4 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-2xl font-bold">${team.team_name}</h3>
                                <p class="text-sm opacity-90">Código: ${team.team_code}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold">${team.correct_count}/11</p>
                                <p class="text-sm opacity-90">Corretas</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-5 max-md:grid-cols-4 gap-3">
                            ${team.answers.map(answer => `
                                <div class="question-box rounded-lg p-3 flex flex-col items-center justify-center text-center ${
                                    answer.is_correct ? 'correct' :
                                    answer.is_answered ? 'incorrect' :
                                    'pending'
                                }">
                                    <p class="text-xs font-bold">Q${answer.question_number}</p>
                                    <p class="text-2xl mt-1">
                                        ${answer.is_correct ? '✅' : answer.is_answered ? '❌' : '❓'}
                                    </p>
                                    <p class="text-xs mt-1 max-w-full">${answer.user_answer ? formatAnswer(answer.user_answer) : '---'}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;

                container.appendChild(teamCard);
            });
        }

        // Load monitor on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateMonitor();
            // Refresh every 1 second
            setInterval(updateMonitor, 1000);
        });
    </script>
</body>
</html>
