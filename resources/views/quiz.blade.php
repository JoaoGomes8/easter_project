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
        .correct-answer {
            animation: correctPulse 0.6s ease-in-out;
        }
        .wrong-answer {
            animation: wrongShake 0.4s ease-in-out;
        }
        @keyframes correctPulse {
            0%, 100% { background-color: inherit; }
            50% { background-color: #d4edda; }
        }
        @keyframes wrongShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .question-card {
            transition: all 0.3s ease;
        }
        .question-card.answered {
            opacity: 0.7;
        }
        .modal {
            display: none;
        }
        .modal.show {
            display: flex;
        }
    </style>
</head>
<body class="quiz-bg min-h-screen p-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-purple-600">📝 Quiz - Páscoa & Programação</h1>
                    <p class="text-gray-600">Equipa: <span class="font-bold text-purple-500">{{ $teamName }}</span></p>
                </div>
                <div class="text-right">
                    <div id="progress" class="text-2xl font-bold text-green-500">0/11</div>
                    <p class="text-gray-600">Respostas Corretas</p>
                </div>
            </div>
        </div>

        <!-- Questions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($questions as $question)
                @php
                    $isAnswered = collect($teamAnswers)->firstWhere('question_id', $question['id']) && collect($teamAnswers)->firstWhere('question_id', $question['id'])['is_correct'] == true;
                @endphp
                <div class="question-card bg-white rounded-xl shadow-md hover:shadow-lg p-6 {{ $isAnswered ? 'answered' : '' }}">
                    <!-- Question Number and Title -->
                    <div class="mb-4">
                        <span class="inline-block bg-purple-500 text-white rounded-full w-8 h-8 text-center leading-8 font-bold">
                            {{ $question['question_number'] }}
                        </span>
                        <h3 class="text-lg font-semibold text-gray-700 mt-2">{{ $question['question'] }}</h3>
                    </div>

                    <!-- Input Field -->
                    <div class="flex gap-2 mb-3">
                        <input
                            type="text"
                            class="input-answer-{{ $question['id'] }} flex-1 px-3 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                            placeholder="Sua resposta..."
                            data-question-id="{{ $question['id'] }}"
                        >
                        <button
                            onclick="submitAnswer({{ $question['id'] }})"
                            class="btn-submit-{{ $question['id'] }} px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-bold"
                        >
                            ✓
                        </button>
                    </div>

                    <!-- Hint Button -->
                    <button
                        onclick="showHint({{ $question['id'] }})"
                        class="text-sm text-blue-500 hover:text-blue-700 font-semibold mb-2 block"
                    >
                        💡 Dica
                    </button>

                    <!-- Hint Display -->
                    <div id="hint-{{ $question['id'] }}" class="hint-box hidden bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3 rounded text-sm text-gray-700">
                        <strong>Dica:</strong> {{ $question['hint'] }}
                    </div>

                    <!-- Explanation Display -->
                    <div id="explanation-{{ $question['id'] }}" class="explanation-box hidden bg-green-50 border-l-4 border-green-400 p-3 rounded text-sm text-gray-700">
                        <strong>Explicação:</strong> {{ $question['explanation'] }}
                    </div>

                    <!-- Answer Status -->
                    <div id="status-{{ $question['id'] }}" class="status-box hidden mt-3 p-3 rounded text-sm font-bold">
            @endforeach
        </div>

        <!-- Dashboard Link -->
        <div class="flex gap-4 mt-8">
            <a
                href="{{ route('home') }}"
                class="flex-1 px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-center font-bold"
            >
                ← Voltar
            </a>
            <a
                href="{{ route('dashboard') }}"
                class="flex-1 px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-center font-bold"
            >
                📊 Ver Dashboard
            </a>
        </div>
    </div>

    <script>
        function submitAnswer(questionId) {
            const input = document.querySelector(`.input-answer-${questionId}`);
            const answer = input.value.trim();

            if (!answer) {
                alert('Por favor, insira uma resposta!');
                return;
            }

            // Send to server
            fetch('{{ route("quiz.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer: answer
                })
            })
            .then(response => response.json())
            .then(data => {
                const statusBox = document.getElementById(`status-${questionId}`);
                const explanationBox = document.getElementById(`explanation-${questionId}`);
                const card = input.closest('.question-card');

                if (data.is_correct) {
                    statusBox.className = 'status-box mt-3 p-3 rounded text-sm font-bold bg-green-100 text-green-700';
                    statusBox.textContent = '✅ Correto!';
                    card.classList.add('answered');
                    input.disabled = true;
                    document.querySelector(`.btn-submit-${questionId}`).disabled = true;
                } else {
                    statusBox.className = 'status-box mt-3 p-3 rounded text-sm font-bold bg-red-100 text-red-700';
                    statusBox.textContent = '❌ Incorreto! Tente novamente.';
                    card.classList.add('wrong-answer');
                }

                explanationBox.classList.remove('hidden');
                updateProgress();
            })
            .catch(error => console.error('Error:', error));
        }

        function showHint(questionId) {
            const hintBox = document.getElementById(`hint-${questionId}`);
            hintBox.classList.toggle('hidden');
        }

        function updateProgress() {
            const total = document.querySelectorAll('.status-box:not(.hidden)').length;
            const correct = document.querySelectorAll('.status-box.bg-green-100:not(.hidden)').length;
            document.getElementById('progress').textContent = `${correct}/11`;
        }

        // Load answers on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
        });
    </script>
</body>
</html>
