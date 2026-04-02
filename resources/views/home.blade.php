<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páscoa & Programação - Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .easter-bg {
            background: linear-gradient(135deg, #f8d7da 0%, #fff9e6 50%, #e8f4ff 100%);
        }
        .egg-animation {
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>
<body class="easter-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
            <!-- Emoji Easter Eggs Animation -->
            <div class="flex justify-center gap-3 mb-6">
                <span class="text-4xl egg-animation" style="animation-delay: 0s;">🥚</span>
                <span class="text-4xl egg-animation" style="animation-delay: 0.3s;">🐰</span>
                <span class="text-4xl egg-animation" style="animation-delay: 0.6s;">💻</span>
            </div>

            <h1 class="text-3xl font-bold text-purple-600 mb-2">🎉 Páscoa & Programação 🎉</h1>
            <p class="text-gray-600 mb-8">Quiz interativo com 10 perguntas sobre Páscoa e Programação</p>

            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded text-sm text-amber-900 text-left mb-6">
                <p><strong>Como ganhar:</strong> a equipa vencedora tem de terminar com mais pontos, acertar todas as perguntas e completar o jogo da frase.</p>
            </div>

            <!-- Step 1: Team Name -->
            <div id="step-1" class="space-y-4">
                <div>
                    <label for="team_name" class="block text-sm font-semibold text-gray-700 mb-2">Nome da Equipa</label>
                    <input
                        type="text"
                        id="team_name"
                        class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                        placeholder="Ex: Os Programadores"
                    >
                    <p id="team-error" class="text-red-500 text-sm mt-1" style="display:none;"></p>
                </div>

                <button
                    onclick="checkTeam()"
                    class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition duration-200"
                >
                    Verificar Equipa ✓
                </button>
            </div>

            <!-- Step 2: Password (for existing team) -->
            <div id="step-2" style="display:none;" class="space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded text-sm text-blue-800 text-left">
                    <p><strong id="new-team-name"></strong> - equipa já existe! 🎉</p>
                    <p class="mt-2">Insira a password para voltar a jogar com a mesma equipa e com as mesmas respostas certas!</p>
                </div>

                <div>
                    <label for="team_password" class="block text-sm font-semibold text-gray-700 mb-2">Password da Equipa</label>
                    <input
                        type="password"
                        id="team_password"
                        class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                        placeholder="Insira a password..."
                        onkeypress="if(event.key === 'Enter') submitWithPassword()"
                    >
                    <p id="password-error" class="text-red-500 text-sm mt-1" style="display:none;"></p>
                </div>

                <button
                    onclick="submitWithPassword()"
                    class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition duration-200"
                >
                    Entrar 🚀
                </button>

                <button
                    onclick="resetForm()"
                    class="w-full bg-gray-300 text-gray-800 font-bold py-2 rounded-lg hover:bg-gray-400 transition"
                >
                    ← Voltar
                </button>
            </div>

            <!-- Step 3: New Team Password (optional) -->
            <div id="step-3" style="display:none;" class="space-y-4">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded text-sm text-green-800 text-left">
                    <p>Bem-vindo! 🎊</p>
                    <p class="mt-2">Defina uma password para proteger a equipa:</p>
                </div>

                <form id="join-form" action="{{ route('home.join') }}" method="POST" onsubmit="copyPasswordBeforeSubmit()">
                    @csrf
                    <input type="hidden" id="hidden-team-name" name="team_name">
                    <input type="hidden" id="hidden-team-password" name="team_password">

                    <div>
                        <label for="new_team_password" class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <input
                            type="password"
                            id="new_team_password"
                            class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                            placeholder="Defina uma password para a equipa..."
                            required
                        >
                        <p id="password-create-error" class="text-red-500 text-sm mt-1" style="display:none;"></p>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-teal-500 text-white font-bold py-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition duration-200 mt-4"
                    >
                        Começar Quiz 🚀
                    </button>

                    <button
                        type="button"
                        onclick="resetForm()"
                        class="w-full bg-gray-300 text-gray-800 font-bold py-2 rounded-lg hover:bg-gray-400 transition mt-2"
                    >
                        ← Voltar
                    </button>
                </form>
            </div>

            <!-- Dashboard Link -->
            <div class="mt-8 pt-8 border-t border-gray-300">
                <p class="text-gray-600 text-sm mb-3">Quer ver as pontuações das equipas?</p>
                <a
                    href="{{ route('dashboard') }}"
                    class="w-full block px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-center font-bold"
                >
                    📊 Ver Pontuações
                </a>
            </div>
        </div>

        <!-- Fun Footer -->
        <p class="text-center text-gray-600 mt-8 text-sm">
            🥚 Divirte-te respondendo as perguntas! 💻
        </p>
    </div>

    <script>
        function checkTeam() {
            const teamName = document.getElementById('team_name').value.trim();

            if (!teamName) {
                showError('team-error', 'Por favor, insira o nome da equipa.');
                return;
            }

            fetch('{{ route("home.check-team") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ team_name: teamName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Equipa existe - pedir password
                    document.getElementById('new-team-name').textContent = teamName;
                    document.getElementById('step-1').style.display = 'none';
                    document.getElementById('step-2').style.display = 'block';
                    document.getElementById('team_password').focus();
                } else {
                    // Equipa nova
                    document.getElementById('hidden-team-name').value = teamName;
                    document.getElementById('step-1').style.display = 'none';
                    document.getElementById('step-3').style.display = 'block';
                    document.getElementById('new_team_password').focus();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showError('team-error', 'Erro ao verificar equipa. Tente novamente.');
            });
        }

        function submitWithPassword() {
            const password = document.getElementById('team_password').value.trim();
            const teamName = document.getElementById('team_name').value.trim();

            if (!password) {
                showError('password-error', 'Por favor, insira a password.');
                return;
            }

            // Valida password via fetch
            fetch('{{ route("home.verify-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    team_name: teamName,
                    team_password: password
                })
            })
            .then(response => response.json().then(data => ({ status: response.ok, data: data })))
            .then(({ status, data }) => {
                if (status && data.success) {
                    // Password correta - faz o submit
                    document.getElementById('hidden-team-name').value = teamName;
                    document.getElementById('hidden-team-password').value = password;
                    document.getElementById('join-form').submit();
                } else {
                    // Password incorreta
                    const message = data.message || 'Password incorreta!';
                    showError('password-error', '❌ ' + message);
                    document.getElementById('team_password').value = '';
                    document.getElementById('team_password').focus();
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showError('password-error', 'Erro ao verificar password. Tente novamente.');
            });
        }

        function resetForm() {
            document.getElementById('team_name').value = '';
            document.getElementById('team_password').value = '';
            document.getElementById('new_team_password').value = '';
            document.getElementById('team-error').style.display = 'none';
            document.getElementById('password-error').style.display = 'none';
            document.getElementById('step-1').style.display = 'block';
            document.getElementById('step-2').style.display = 'none';
            document.getElementById('step-3').style.display = 'none';
            document.getElementById('team_name').focus();
        }

        function showError(elementId, message) {
            const el = document.getElementById(elementId);
            el.textContent = message;
            el.style.display = 'block';
        }

        function copyPasswordBeforeSubmit() {
            const newPassword = document.getElementById('new_team_password').value.trim();

            if (!newPassword) {
                showError('password-create-error', 'Por favor, insira uma password obrigatória.');
                document.getElementById('new_team_password').focus();
                return false;
            }

            document.getElementById('hidden-team-password').value = newPassword;
            return true;
        }
    </script>
</body>
</html>

