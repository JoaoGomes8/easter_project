<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Frase Binária - CESAE DIGITAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .game-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .binary-item {
            background: white;
            border: 2px solid #667eea;
            border-radius: 0.75rem;
            padding: 1rem;
            margin: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .binary-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .binary-item.solved {
            background: #ecfdf5;
            border-color: #10b981;
        }

        .binary-code {
            font-family: 'Courier New', monospace;
            font-size: 1rem;
            font-weight: bold;
            padding: 0.75rem 1rem;
            background: #f3f4f6;
            border-radius: 0.5rem;
            cursor: pointer;
            user-select: all;
            transition: all 0.2s;
        }

        .binary-code:hover {
            background: #667eea;
            color: white;
        }

        .binary-code.copied {
            background: #10b981;
            color: white;
        }

        .input-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .letter-input {
            width: 60px;
            padding: 0.75rem;
            font-size: 1.25rem;
            text-align: center;
            font-weight: bold;
            border: 2px solid #667eea;
            border-radius: 0.5rem;
            text-transform: uppercase;
            font-family: 'Courier New', monospace;
        }

        .letter-input:focus {
            outline: none;
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-letter-btn {
            padding: 0.75rem 1.25rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .submit-letter-btn:hover {
            background: #764ba2;
            transform: scale(1.05);
        }

        .submit-letter-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .guessed-letter {
            font-size: 2rem;
            font-weight: bold;
            color: #10b981;
            min-width: 50px;
            text-align: center;
        }

        .hint-text {
            font-size: 0.85rem;
            color: #999;
            font-style: italic;
        }

        .phrase-display {
            font-family: 'Courier New', monospace;
            font-size: 2.5rem;
            letter-spacing: 0.5rem;
            font-weight: bold;
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: #667eea;
            min-height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .letter-box {
            background: #f0f0f0;
            padding: 0.5rem 0.75rem;
            border: 2px solid #667eea;
            border-radius: 0.5rem;
            min-width: 50px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .letter-box.empty {
            color: #ccc;
            font-size: 1.5rem;
        }

        .letter-box.filled {
            background: #ecfdf5;
            border-color: #10b981;
            color: #059669;
            font-weight: bold;
        }

        .success-message {
            background: #ecfdf5;
            border: 2px solid #10b981;
            color: #059669;
            padding: 1.5rem;
            border-radius: 1rem;
            display: none;
            text-align: center;
        }

        .error-message {
            background: #fee2e2;
            border: 2px solid #dc2626;
            color: #dc2626;
            padding: 1rem;
            border-radius: 0.5rem;
            display: none;
            margin-top: 1rem;
            text-align: center;
        }

        .info-box {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            text-sm text-green-800;
        }

        .reveal-btn {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
        }

        .reveal-btn:hover {
            box-shadow: 0 8px 12px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .error-inline {
            background: #fee2e2;
            border: 2px solid #dc2626;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
            font-weight: bold;
            animation: shake 0.5s ease-in-out;
        }

        @media (max-width: 640px) {
            .binary-item {
                flex-direction: column;
                align-items: stretch;
            }

            .input-group {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .letter-input {
                width: 100%;
                font-size: 1.1rem;
            }

            .submit-letter-btn {
                width: 100%;
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body class="game-bg min-h-screen p-4 md:p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-purple-600">🎮 Desafio Binário</h1>
                    <p class="text-gray-600 mt-1">Equipa: <span class="font-bold text-purple-500">{{ $teamName }}</span></p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $totalQuestions }}/13</div>
                    <p class="text-gray-600 text-sm">Binários Desbloqueados</p>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="info-box">
            <p><strong>🔐 Como jogar:</strong> Tens {{ $totalQuestions }} binários desbloqueados! 🎯 Converte cada binário em ASCII para adivinhar a letra. Depois completa a frase!</p>
        </div>

        <!-- Binary Codes Display -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📟 Códigos Binários Desbloqueados:</h2>
            <div class="p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 min-h-16">
                @if (count($unlockedBinaries) > 0)
                    @foreach ($unlockedBinaries as $item)
                        <div class="binary-item" id="binary-item-{{ $item['question_id'] }}">
                            <div class="flex-1">
                                <div class="binary-code" onclick="copyBinary(this, '{{ $item['binary'] }}')">
                                    {{ $item['binary'] }}
                                </div>
                                <p class="hint-text mt-1">Clica para copiar</p>
                            </div>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="letter-input"
                                    maxlength="1"
                                    id="input-{{ $item['question_id'] }}"
                                    placeholder="?"
                                    data-binary="{{ $item['binary'] }}"
                                    data-question-id="{{ $item['question_id'] }}"
                                    inputmode="text"
                                    autocomplete="off"
                                    autocorrect="off"
                                    autocapitalize="characters"
                                    spellcheck="false"
                                    @if ($item['is_guessed']) disabled @endif
                                />
                                <button
                                    class="submit-letter-btn"
                                    onclick="checkLetter({{ $item['question_id'] }})"
                                    @if ($item['is_guessed']) disabled @endif
                                >
                                    ✓
                                </button>
                            </div>
                            @if ($item['is_guessed'])
                                <div class="guessed-letter" style="display: block;">
                                    <span id="letter-{{ $item['question_id'] }}">{{ $item['letter'] }}</span>
                                </div>
                            @else
                                <div class="guessed-letter" id="guessed-{{ $item['question_id'] }}" style="display: none;">
                                    <span id="letter-{{ $item['question_id'] }}"></span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 w-full text-center">Nenhum código desbloqueado ainda. Continua respondendo as perguntas!</p>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-4">💡 Dica: Converte cada binário de 8 bits em decimal e depois em ASCII!</p>
        </div>

        <!-- Phrase Display -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-6 text-center">🎯 Frase Secreta (completa-a!):</h2>
            <div class="phrase-display" id="phraseDisplay">
                <div class="letter-box empty" id="pos-0">_</div>
                <div class="letter-box empty" id="pos-1">_</div>
                <div class="letter-box empty" id="pos-2">_</div>
                <div class="letter-box empty" id="pos-3">_</div>
                <div class="letter-box empty" id="pos-4">_</div>
                <div class="letter-box empty" id="pos-5">_</div>
                <div class="letter-box empty" id="pos-6">_</div>
                <div class="letter-box empty" id="pos-7">_</div>
                <div class="letter-box empty" id="pos-8">_</div>
                <div class="letter-box empty" id="pos-9">_</div>
                <div class="letter-box empty" id="pos-10">_</div>
                <div class="letter-box empty" id="pos-11">_</div>
            </div>
            <p class="text-center text-sm text-gray-500 mt-3">12 caracteres (inclui espaço)</p>
        </div>

        <!-- Answer Input (apareceApenas quando completo) -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6" id="answerSection" style="display: none;">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🔓 Desvendar Frase Secreta:</h2>
            <button
                onclick="submitAnswer()"
                class="reveal-btn"
            >
                🎯 Desvendar a Frase!
            </button>
            <div class="error-message" id="errorMessage"></div>
            <div class="success-message" id="successMessage"></div>
        </div>

        <!-- Mensagen de erro para input errado -->
        <div id="binaryErrorContainer" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1000; display: none;"></div>

        <!-- Navigation -->
        <div class="flex gap-4 justify-center mb-4 flex-wrap">
            <a
                href="{{ route('quiz.index') }}"
                class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-bold"
            >
                ← Voltar ao Quiz
            </a>
            <a
                href="{{ route('dashboard') }}"
                class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-bold"
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

    <script>
        // Dados conhecidos: PASCOA CESAE
        const PHRASE = 'PASCOA CESAE';
        const PHRASE_POSITIONS = {
            'P': [0],
            'A': [1, 5, 9],
            'S': [2, 10],
            'C': [3, 7],
            'O': [4],
            ' ': [6],
            'E': [8, 11]
        };

        // Matriz de binários desbloqueados (enviados do backend)
        const unlockedBinaries = @json($unlockedBinaries);

        // Inicializar guessedQuestionIds a partir dos dados do backend
        let guessedQuestionIds = unlockedBinaries
            .filter(binary => binary.is_guessed)
            .map(binary => binary.question_id);
        let revealedPositions = new Set();

        function copyBinary(element, binary) {
            navigator.clipboard.writeText(binary);
            const originalText = element.textContent;
            element.textContent = '✓ Copiado!';
            element.classList.add('copied');
            setTimeout(() => {
                element.textContent = originalText;
                element.classList.remove('copied');
            }, 1500);
        }

        function calculateRevealedPositions() {
            revealedPositions.clear();

            // Contar quantos binários de cada letra foram acertados
            const letterCounts = {};
            unlockedBinaries.forEach(binary => {
                if (guessedQuestionIds.includes(binary.question_id)) {
                    const letter = binary.letter;
                    letterCounts[letter] = (letterCounts[letter] || 0) + 1;
                }
            });

            // Para cada letra com count, revelar as primeiras N posições
            Object.entries(letterCounts).forEach(([letter, count]) => {
                if (PHRASE_POSITIONS[letter]) {
                    for (let i = 0; i < Math.min(count, PHRASE_POSITIONS[letter].length); i++) {
                        revealedPositions.add(PHRASE_POSITIONS[letter][i]);
                    }
                }
            });

            // Espaço sempre revelado
            if (PHRASE_POSITIONS[' ']) {
                PHRASE_POSITIONS[' '].forEach(pos => revealedPositions.add(pos));
            }
        }

        function checkLetter(questionId) {
            const input = document.getElementById(`input-${questionId}`);
            const binary = input.dataset.binary;
            const guess = input.value.toUpperCase().trim();
            questionId = parseInt(questionId); // Converter para número

            if (!guess) {
                showError('Por favor, insere uma letra!');
                return;
            }

            fetch('{{ route("game.validate-binary") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    binary: binary,
                    guess: guess,
                    question_id: questionId
                })
            })
            .then(response => response.json().then(data => ({ status: response.ok, data: data })))
            .then(({ status, data }) => {
                if (status && data.success) {
                    // Letra correta! Sincronizar com o backend
                    fetch('{{ route("game.guessed") }}')
                        .then(res => res.json())
                        .then(result => {
                            // Atualizar guessedQuestionIds com dados do backend
                            guessedQuestionIds = result.guessed_ids || [];

                            // Recalcular posições reveladas
                            calculateRevealedPositions();

                            // Marcar item como resolvido
                            const item = document.getElementById(`binary-item-${questionId}`);
                            item.classList.add('solved');
                            input.disabled = true;
                            document.querySelector(`#binary-item-${questionId} .submit-letter-btn`).disabled = true;

                            // Mostrar a letra
                            const guessedDiv = document.getElementById(`guessed-${questionId}`);
                            const letter = data.letter;
                            document.getElementById(`letter-${questionId}`).textContent = letter;
                            guessedDiv.style.display = 'block';

                            // Atualizar frase
                            updatePhrase();

                            // Verificar se completou
                            setTimeout(() => checkIfCompleted(), 500);

                            showSuccess('✅ ' + data.message);

                            // Focar no próximo input
                            const nextInput = input.closest('div').nextElementSibling?.querySelector('.letter-input');
                            if (nextInput && !nextInput.disabled) {
                                nextInput.focus();
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao sincronizar:', error);
                            showError('Erro ao atualizar dados!');
                        });
                } else {
                    showError(data.message || '❌ Incorreto!');
                    input.value = '';
                    input.focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Erro ao validar. Tenta novamente!');
            });
        }

        function updatePhrase() {
            PHRASE.split('').forEach((char, index) => {
                const box = document.getElementById(`pos-${index}`);
                if (char === ' ') {
                    box.textContent = ' ';
                    box.classList.remove('empty');
                    box.classList.add('filled');
                } else if (revealedPositions.has(index)) {
                    box.textContent = char;
                    box.classList.remove('empty');
                    box.classList.add('filled');
                } else {
                    box.textContent = '_';
                    box.classList.remove('filled');
                    box.classList.add('empty');
                }
            });
        }

        function checkIfCompleted() {
            const totalLetterPositions = PHRASE.split('').filter(l => l !== ' ').length;

            if (totalLetterPositions > 0 && revealedPositions.size >= totalLetterPositions) {
                // Todas as letras foram adivinhas!
                document.getElementById('answerSection').style.display = 'block';
                showSuccess('🎉 Conseguiste todas as letras! Clica em "Desvendar a Frase!"');
                setTimeout(() => {
                    document.querySelector('.reveal-btn').focus();
                }, 500);
                return true;
            }
            return false;
        }

        function submitAnswer() {
            const answer = PHRASE; // Use a frase conhecida
            const errorMsg = document.getElementById('errorMessage');
            const successMsg = document.getElementById('successMessage');

            errorMsg.style.display = 'none';
            successMsg.style.display = 'none';

            fetch('{{ route("game.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ answer: answer })
            })
            .then(response => response.json().then(data => ({ status: response.ok, data: data })))
            .then(({ status, data }) => {
                if (status && data.success) {
                    successMsg.innerHTML = '<h3 class="text-2xl font-bold mb-2">🎉 PARABÉNS! 🎉</h3>' +
                                          '<p class="whitespace-pre-line text-lg font-semibold mt-4">' + data.message + '</p>' +
                                          '<a href="{{ route("home") }}" class="inline-block mt-6 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold">🏠 Voltar ao Início</a>';
                    successMsg.style.display = 'block';
                } else {
                    errorMsg.textContent = data.message || '❌ Erro ao desvendar!';
                    errorMsg.style.display = 'block';
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function showSuccess(message) {
            const msg = document.getElementById('errorMessage');
            msg.textContent = message;
            msg.style.display = 'block';
            msg.style.background = '#ecfdf5';
            msg.style.borderColor = '#10b981';
            msg.style.color = '#059669';
            setTimeout(() => msg.style.display = 'none', 3000);
        }

        function showError(message) {
            const container = document.getElementById('binaryErrorContainer');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-inline';
            errorDiv.textContent = message;

            // Limpar erros anteriores
            container.innerHTML = '';
            container.style.display = 'block';
            container.appendChild(errorDiv);

            // Auto-hide após 3 segundos
            setTimeout(() => {
                container.style.display = 'none';
                errorDiv.remove();
            }, 3000);
        }

        // Allow Enter key to submit
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const activeInput = document.activeElement;
                if (activeInput && activeInput.classList.contains('letter-input')) {
                    const questionId = activeInput.id.replace('input-', '');
                    checkLetter(questionId);
                } else {
                    submitAnswer();
                }
            }
        });

        // Focus no primeiro input ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar guessed_question_ids com as que já estão desabilitadas (da BD)
            document.querySelectorAll('.letter-input:disabled').forEach(input => {
                const questionId = parseInt(input.id.replace('input-', ''));
                if (!guessedQuestionIds.includes(questionId)) {
                    guessedQuestionIds.push(questionId);
                }
            });

            // Calcular posições reveladas baseado nos question_ids já acertados
            calculateRevealedPositions();

            // Atualizar frase com letras já acertadas
            updatePhrase();

            // Marcar itens já resolvidos como "solved"
            document.querySelectorAll('.letter-input:disabled').forEach(input => {
                const questionId = input.id.replace('input-', '');
                const item = document.getElementById(`binary-item-${questionId}`);
                if (item) {
                    item.classList.add('solved');
                }
            });

            // Verificar se já está completo
            checkIfCompleted();

            // Focar no primeiro input não-desabilitado
            const inputs = document.querySelectorAll('.letter-input');
            for (let input of inputs) {
                if (!input.disabled) {
                    input.focus();
                    break;
                }
            }
        });
    </script>
</body>
</html>
