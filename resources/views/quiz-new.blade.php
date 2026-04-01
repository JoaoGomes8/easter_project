<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - Páscoa & Programação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .quiz-bg {
            background: linear-gradient(135deg, #f8d7da 0%, #fff9e6 50%, #e8f4ff 100%);
        }

        .question-number {
            font-size: 0.8rem;
            font-weight: bold;
            color: #666;
        }

        .question-box {
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 120px;
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }

        .question-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .question-box.locked {
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            opacity: 0.8;
        }

        .question-box.unlocked {
            background: #ecfdf5;
            border: 2px solid #10b981;
        }

        .question-box .icon {
            font-size: 2rem;
            text-align: center;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px rgba(0,0,0,0.3);
            position: relative;
        }

        .hint-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            display: none;
        }

        .explanation-box {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            display: none;
        }

        .status-message {
            font-weight: bold;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            display: none;
        }

        .status-message.correct {
            background: #ecfdf5;
            color: #059669;
            display: block;
        }

        .status-message.incorrect {
            background: #fee2e2;
            color: #dc2626;
            display: block;
        }

        .status-message.neutral {
            background: #eff6ff;
            color: #1d4ed8;
            display: block;
        }

        .score-pill {
            min-width: 140px;
            border-radius: 1rem;
            padding: 0.9rem 1.2rem;
            background: linear-gradient(135deg, #eef2ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            text-align: center;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.12);
        }

        .score-feedback {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 1200;
            padding: 0.9rem 1.1rem;
            border-radius: 0.9rem;
            color: white;
            font-weight: 700;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.22);
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .score-feedback.show {
            opacity: 1;
            transform: translateY(0);
        }

        .score-feedback.positive {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        }

        .score-feedback.negative {
            background: linear-gradient(135deg, #dc2626 0%, #f97316 100%);
        }

        .score-feedback.neutral {
            background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
        }
    </style>
</head>
<body class="quiz-bg min-h-screen p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-purple-600">🎯 Páscoa & Programação Quiz</h1>
                    <p class="text-gray-600 mt-1">Equipa: <span class="font-bold text-purple-500">{{ $teamName }}</span></p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="score-pill">
                        <div id="score" class="text-3xl md:text-4xl font-bold text-blue-600">{{ $teamScore }}</div>
                        <p class="text-gray-600 text-sm">Pontos</p>
                    </div>
                    <div class="score-pill">
                        <div id="progress" class="text-3xl md:text-4xl font-bold text-green-600">0/11</div>
                        <p class="text-gray-600 text-sm">Respostas Corretas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg mb-6">
            <p class="text-blue-800"><strong>Como jogar:</strong> Clique em cada caixa para responder à pergunta. Quando acertar, a caixa fica verde e desbloqueada! ✅</p>
        </div>

        <!-- Question Boxes Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            @foreach ($questions as $question)
                @php
                    $teamAnswer = collect($teamAnswers)->firstWhere('question_id', $question['id']);
                    $isCorrect = $teamAnswer && $teamAnswer['is_correct'];
                @endphp

                <div
                    onclick="openQuestion({{ $question['id'] }})"
                    class="question-box rounded-xl shadow-md p-4 {{ $isCorrect ? 'unlocked' : 'locked' }}"
                    id="box-{{ $question['id'] }}"
                    data-question-number="{{ $question['question_number'] }}"
                >
                    <div class="question-number">Pergunta {{ $question['question_number'] }}</div>
                    <div class="icon">
                        @if ($isCorrect)
                            ✅
                        @else
                            ❓
                        @endif
                    </div>
                    <div class="text-center text-sm font-semibold text-gray-700">
                        @if ($isCorrect)
                            Desbloqueado
                        @else
                            Bloqueado
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Navigation -->
        <div class="flex gap-4 justify-center mb-4 flex-wrap">
            <a
                href="{{ route('home') }}"
                class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-bold"
            >
                ← Voltar
            </a>
            <a
                href="{{ route('game.show') }}"
                class="px-6 py-3 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-bold"
            >
                🎮 Jogo da Frase
            </a>
            <a
                href="{{ route('dashboard') }}"
                class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-bold"
            >
                📊 Pontuações
            </a>
            <form action="{{ route('home.logout') }}" method="POST" class="inline-block">
                @csrf
                <button
                    type="submit"
                    class="px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-bold"
                >
                    Sair
                </button>
            </form>
        </div>
    </div>

    <!-- Hidden data for team identification -->
    <script>
        const CURRENT_TEAM_ID = {{ $teamId }};
        const TEAM_ANSWERS = @json($teamAnswers);
        let TEAM_SCORE = {{ $teamScore }};
    </script>
    <div id="scoreFeedback" class="score-feedback"></div>
    <div id="passwordModal" class="modal-overlay">
        <div class="modal-content">
            <button
                onclick="closePassword()"
                class="sm:hidden absolute top-3 right-3 w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition font-bold text-xl leading-none"
                aria-label="Fechar"
            >
                ×
            </button>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">🔒 Desbloquear Pergunta</h2>
            <p class="text-gray-600 mb-6">Insira a password para desbloquear esta pergunta:</p>

            <div class="flex flex-col sm:flex-row gap-2 mb-4">
                <input
                    type="password"
                    id="passwordInput"
                    class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                    placeholder="Password..."
                    onkeypress="if(event.key === 'Enter') verifyPassword()"
                    autocomplete="off"
                >
                <button
                    onclick="verifyPassword()"
                    class="w-full sm:w-auto px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-bold"
                >
                    Confirmar
                </button>
            </div>

            <div id="passwordMessage" class="status-message"></div>

            <div class="flex gap-3 mt-6">
                <button
                    onclick="closePassword()"
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-bold"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para responder -->
    <div id="questionModal" class="modal-overlay">
        <div class="modal-content">
            <button
                onclick="closeQuestion()"
                class="sm:hidden absolute top-3 right-3 w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition font-bold text-xl leading-none"
                aria-label="Fechar"
            >
                ×
            </button>
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-4"></h2>
            <p id="modalQuestion" class="text-gray-700 mb-4 text-lg"></p>

            <!-- Opções para múltipla escolha -->
            <div id="multipleChoiceOptions" class="space-y-2 mb-4"></div>

            <!-- Botão confirmar para múltipla escolha -->
            <button
                id="submitMultipleChoiceBtn"
                onclick="submitCurrentAnswer()"
                class="w-full px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-bold mb-4"
                style="display: none;"
            >
                Confirmar
            </button>

            <!-- Input de texto para perguntas abertas -->
            <div id="modalAnswer" class="flex flex-col sm:flex-row gap-2 mb-4">
                <input
                    type="text"
                    id="answerInput"
                    class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                    placeholder="Insira sua resposta..."
                    onkeypress="if(event.key === 'Enter') submitCurrentAnswer()"
                >
                <button
                    onclick="submitCurrentAnswer()"
                    class="w-full sm:w-auto px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-bold"
                >
                    Confirmar
                </button>
            </div>

            <div id="statusMessage" class="status-message"></div>

            <div id="hintBox" class="hint-box">
                <strong>💡 Dica:</strong> <span id="hintText"></span>
            </div>

            <button
                onclick="showHint()"
                class="text-blue-500 hover:text-blue-700 font-semibold text-sm mb-4"
            >
                Mostrar Dica
            </button>

            <div id="explanationBox" class="explanation-box">
                <strong>📖 Explicação:</strong> <span id="explanationText"></span>
            </div>

            <div class="flex gap-3 mt-6">
                <button
                    onclick="closeQuestion()"
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-bold"
                >
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para exibir o binário -->
    <div id="binaryModal" class="modal-overlay">
        <div class="modal-content">
            <button
                onclick="closeBinaryModal()"
                class="sm:hidden absolute top-3 right-3 w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition font-bold text-xl leading-none"
                aria-label="Fechar"
            >
                ×
            </button>
            <div class="text-center">
                <h2 class="text-3xl font-bold text-purple-600 mb-4">🔓 Código Binário Desbloqueado!</h2>
                <p class="text-gray-600 mb-6">Guarda este código para descodificar mais tarde:</p>

                <div class="bg-gradient-to-r from-purple-100 to-pink-100 p-8 rounded-lg mb-6 border-2 border-purple-300">
                    <p id="binaryCode" class="font-mono text-4xl font-bold text-purple-600 tracking-widest select-all">000000</p>
                </div>

                <p class="text-sm text-gray-500 mb-6">💡 Tip: Clica no código para copiar</p>

                <button
                    onclick="closeBinaryModal()"
                    class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg transform hover:scale-105 transition font-bold text-lg"
                >
                    Continuar com Quiz 🎮
                </button>
            </div>
        </div>
    </div>

    <script>
        // Copiar binário ao clicar
        document.addEventListener('DOMContentLoaded', function() {
            const binaryCodeEl = document.getElementById('binaryCode');
            if (binaryCodeEl) {
                binaryCodeEl.addEventListener('click', function() {
                    navigator.clipboard.writeText(this.textContent.trim());
                    const original = this.textContent;
                    this.textContent = '✓ Copiado!';
                    setTimeout(() => {
                        this.textContent = original;
                    }, 1500);
                });
            }
        });

        // Close modal when clicking outside
        document.getElementById('binaryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBinaryModal();
            }
        });

        let currentQuestionId = null;

        function openQuestion(questionId) {
            currentQuestionId = questionId;

            // Verifica se a pergunta já foi respondida corretamente
            const isUnlocked = TEAM_ANSWERS.some(a => a.question_id === questionId && a.is_correct);

            if (isUnlocked) {
                // Pergunta já desbloqueada - abre direto sem pedir password
                openQuestionModal(questionId);
            } else {
                // Pergunta bloqueada - pede password
                document.getElementById('passwordInput').value = '';
                document.getElementById('passwordMessage').className = 'status-message';
                document.getElementById('passwordMessage').textContent = '';
                document.getElementById('passwordModal').classList.add('active');
                document.getElementById('passwordInput').focus();
            }
        }

        function closePassword() {
            document.getElementById('passwordModal').classList.remove('active');
            currentQuestionId = null;
        }

        function verifyPassword() {
            if (!currentQuestionId) return;

            const questions = {
                @foreach ($questions as $question)
                    {{ $question['id'] }}: {
                        number: {{ $question['question_number'] }},
                        text: "{{ $question['question'] }}",
                        hint: "{{ $question['hint'] }}",
                        explanation: "{{ $question['explanation'] }}",
                        password: "{{ $question['password'] }}"
                    },
                @endforeach
            };

            const password = document.getElementById('passwordInput').value.trim().toLowerCase();
            const correct_password = questions[currentQuestionId].password.toLowerCase();

            if (password === correct_password) {
                // Password correta, abre a pergunta
                document.getElementById('passwordModal').classList.remove('active');
                openQuestionModal(currentQuestionId);
            } else {
                // Password incorreta
                const msg = document.getElementById('passwordMessage');
                msg.className = 'status-message incorrect';
                msg.textContent = '❌ Password incorreta! Tente novamente.';
                document.getElementById('passwordInput').value = '';
                document.getElementById('passwordInput').focus();
            }
        }

        function openQuestionModal(questionId) {
            const questions = {
                @foreach ($questions as $question)
                    {{ $question['id'] }}: {
                        number: {{ $question['question_number'] }},
                        text: "{{ $question['question'] }}",
                        hint: "{{ $question['hint'] }}",
                        explanation: "{{ $question['explanation'] }}",
                        password: "{{ $question['password'] }}",
                        type: "{{ $question['question_type'] ?? 'text' }}",
                        options: @json($question['options'] ?? null)
                    },
                @endforeach
            };

            const question = questions[questionId];
            document.getElementById('modalTitle').textContent = `Pergunta ${question.number}`;
            document.getElementById('modalQuestion').textContent = question.text;
            document.getElementById('hintText').textContent = question.hint;
            document.getElementById('explanationText').textContent = question.explanation;
            document.getElementById('answerInput').value = '';
            document.getElementById('answerInput').disabled = false;
            document.getElementById('statusMessage').className = 'status-message';
            document.getElementById('hintBox').style.display = 'none';
            document.getElementById('explanationBox').style.display = 'none';

            // Limpar seleção anterior
            document.querySelectorAll('input[name="questionOption"]').forEach(el => el.checked = false);

            // Mostrar opções de múltipla escolha ou input de texto
            const multipleChoiceDiv = document.getElementById('multipleChoiceOptions');
            const answerInputDiv = document.getElementById('modalAnswer');
            const submitMultipleChoiceBtn = document.getElementById('submitMultipleChoiceBtn');

            if (question.type === 'multiple_choice' && question.options) {
                multipleChoiceDiv.innerHTML = '';
                answerInputDiv.style.display = 'none';
                submitMultipleChoiceBtn.style.display = 'block';

                for (const [key, optionText] of Object.entries(question.options)) {
                    const label = document.createElement('label');
                    label.className = 'flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition';
                    label.innerHTML = `
                        <input type="radio" name="questionOption" value="${optionText}" class="mr-3 w-4 h-4">
                        <span class="text-gray-700"><strong>${key})</strong> ${optionText}</span>
                    `;
                    multipleChoiceDiv.appendChild(label);
                }
            } else {
                multipleChoiceDiv.innerHTML = '';
                answerInputDiv.style.display = 'flex';
                submitMultipleChoiceBtn.style.display = 'none';
                document.getElementById('answerInput').focus();
            }

            // Re-enable the submit button
            const buttons = document.getElementById('questionModal').querySelectorAll('button');
            buttons.forEach(btn => {
                if (btn.textContent.includes('Confirmar')) {
                    btn.disabled = false;
                }
            });

            document.getElementById('questionModal').classList.add('active');
        }

        function closeQuestion() {
            document.getElementById('questionModal').classList.remove('active');
            currentQuestionId = null;
        }

        function showHint() {
            document.getElementById('hintBox').style.display = 'block';
        }

        function submitCurrentAnswer() {
            if (!currentQuestionId) return;

            // Obter resposta (seja de texto ou múltipla escolha)
            let answer = '';
            const selectedOption = document.querySelector('input[name="questionOption"]:checked');

            if (selectedOption) {
                // Múltipla escolha
                answer = selectedOption.value;
            } else {
                // Texto livre
                answer = document.getElementById('answerInput').value.trim();
            }

            if (!answer) {
                alert('Por favor, insira uma resposta!');
                return;
            }

            fetch('{{ route("quiz.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: currentQuestionId,
                    answer: answer
                })
            })
            .then(response => response.json())
            .then(data => {
                const statusMsg = document.getElementById('statusMessage');
                updateScore(data.current_score ?? TEAM_SCORE);

                if (data.already_solved) {
                    statusMsg.className = 'status-message neutral';
                    statusMsg.textContent = `✅ Esta pergunta já estava certa. Pontuação atual: ${TEAM_SCORE} pontos.`;
                    showScoreFeedback(0, TEAM_SCORE, 'Pergunta já resolvida');
                    document.getElementById('explanationBox').style.display = 'block';
                    return;
                }

                if (data.is_correct) {
                    statusMsg.className = 'status-message correct';
                    statusMsg.textContent = `✅ Correto! Ganhaste ${formatScoreDelta(data.score_delta)}. Total: ${TEAM_SCORE} pontos.`;
                    document.getElementById('answerInput').disabled = true;

                    // Desabilitar radio buttons de múltipla escolha
                    document.querySelectorAll('input[name="questionOption"]').forEach(el => el.disabled = true);

                    // Adiciona a resposta ao array TEAM_ANSWERS para sincronização
                    const existingAnswerIndex = TEAM_ANSWERS.findIndex(a => a.question_id === currentQuestionId);
                    if (existingAnswerIndex >= 0) {
                        TEAM_ANSWERS[existingAnswerIndex].is_correct = true;
                    } else {
                        TEAM_ANSWERS.push({
                            team_id: CURRENT_TEAM_ID,
                            question_id: currentQuestionId,
                            is_correct: true
                        });
                    }

                    // Update box display with correct styling
                    const box = document.getElementById(`box-${currentQuestionId}`);
                    const questionNumber = box?.dataset?.questionNumber || currentQuestionId;
                    box.classList.remove('locked');
                    box.classList.add('unlocked');
                    box.innerHTML = `
                        <div class="question-number">Pergunta ${questionNumber}</div>
                        <div class="icon">✅</div>
                        <div class="text-center text-sm font-semibold text-gray-700">Desbloqueado</div>
                    `;

                    updateProgress();
                    showScoreFeedback(data.score_delta, TEAM_SCORE, 'Resposta correta');

                    // Mostrar binário se existir
                    if (data.binary_code) {
                        setTimeout(() => {
                            showBinaryModal(data.binary_code);
                        }, 500);
                    }

                    // Disable the submit button
                    const buttons = document.getElementById('questionModal').querySelectorAll('button');
                    buttons.forEach(btn => {
                        if (btn.textContent.includes('Confirmar')) {
                            btn.disabled = true;
                        }
                    });
                } else {
                    statusMsg.className = 'status-message incorrect';
                    statusMsg.textContent = `❌ Incorreto! Perdeste ${formatScoreDelta(data.score_delta)}. Total: ${TEAM_SCORE} pontos.`;
                    showScoreFeedback(data.score_delta, TEAM_SCORE, 'Resposta incorreta');
                }

                document.getElementById('explanationBox').style.display = 'block';
            })
            .catch(error => console.error('Error:', error));
        }

        function showBinaryModal(binaryCode) {
            document.getElementById('binaryCode').textContent = binaryCode;
            document.getElementById('binaryModal').classList.add('active');
        }

        function closeBinaryModal() {
            document.getElementById('binaryModal').classList.remove('active');
        }

        function updateProgress() {
            // Usa os dados do servidor em vez de contar elementos do DOM
            // Garante que cada equipa vê apenas suas respostas
            const correct = TEAM_ANSWERS.filter(a => a.is_correct).length;
            document.getElementById('progress').textContent = `${correct}/11`;
        }

        function updateScore(score) {
            TEAM_SCORE = score;
            document.getElementById('score').textContent = score;
        }

        function formatScoreDelta(delta) {
            if (delta > 0) {
                return `+${delta} pontos`;
            }

            if (delta < 0) {
                return `${Math.abs(delta)} ${Math.abs(delta) === 1 ? 'ponto' : 'pontos'}`;
            }

            return '0 pontos';
        }

        function showScoreFeedback(delta, totalScore, label) {
            const feedback = document.getElementById('scoreFeedback');
            const tone = delta > 0 ? 'positive' : delta < 0 ? 'negative' : 'neutral';
            const signal = delta > 0 ? `+${delta}` : delta < 0 ? `${delta}` : '0';

            feedback.className = `score-feedback ${tone} show`;
            feedback.textContent = `${label}: ${signal} pontos | Total ${totalScore}`;

            clearTimeout(showScoreFeedback.timeoutId);
            showScoreFeedback.timeoutId = setTimeout(() => {
                feedback.classList.remove('show');
            }, 2200);
        }

        // Close modal when clicking outside
        document.getElementById('questionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuestion();
            }
        });

        // Close password modal when clicking outside
        document.getElementById('passwordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePassword();
            }
        });

        // Update progress on load
        document.addEventListener('DOMContentLoaded', function() {
            // Sincroniza o estado de todas as caixas com os dados do servidor
            syncBoxesWithServerData();
            updateProgress();
        });

        function syncBoxesWithServerData() {
            // Para cada resposta correta do servidor, marca a caixa como desbloqueada
            TEAM_ANSWERS.forEach(answer => {
                if (answer.is_correct) {
                    const box = document.getElementById(`box-${answer.question_id}`);
                    if (box) {
                        const questionNumber = box.dataset.questionNumber || answer.question_id;
                        box.classList.remove('locked');
                        box.classList.add('unlocked');
                        box.innerHTML = `
                            <div class="question-number">Pergunta ${questionNumber}</div>
                            <div class="icon">✅</div>
                            <div class="text-center text-sm font-semibold text-gray-700">Desbloqueado</div>
                        `;
                    }
                }
            });
        }
    </script>
</body>
</html>
