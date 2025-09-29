@extends('layouts.app')

@section('content')
<div class="section-block">
    <div class="page-hero">
        <div>
            <h1>Juegos educativos</h1>
            <p>Diseña experiencias gamificadas y reta a toda la comunidad.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('juegos.create') }}" class="btn-neo">Crear juego</a>
        </div>
    </div>

    <section class="glass-panel">
        <div class="section-heading">
            <h2>Catálogo de juegos</h2>
            <span class="tag">{{ $games->total() }} activos</span>
        </div>
        <div class="filter-bar">
            <form method="GET" action="{{ route('juegos.index') }}">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar juego..." class="input search-input">
            </form>
            <a href="{{ route('juegos.create') }}" class="btn-ghost btn-compact">Nuevo juego</a>
        </div>

        <div class="resource-grid">
                    @forelse($games as $g)
                        @php
                            $questionCount = $g->questions_count ?? optional($g->questions)->count() ?? 0;
                        @endphp
                <article class="resource-card">
                    <div class="resource-media">
                        @if($g->cover_path)
                            <img src="{{ asset('storage/'.$g->cover_path) }}" alt="Portada del juego">
                        @else
                            <span>Play</span>
                        @endif
                    </div>
                    <div class="resource-body">
                        <h3>{{ $g->title }}</h3>
                        @php($plays = $g->plays_count ?? 0)
                        <div class="collection-tags" style="margin: 0.6rem 0 0.8rem;">
                            <span class="pill pill--accent">{{ $g->type_label }}</span>
                            <span class="pill pill--outline">{{ $questionCount }} preguntas</span>
                            <span class="pill pill--muted">{{ $g->points_per_question }} pts</span>
                            <span class="pill">{{ $plays }} partidas</span>
                        </div>
                        <p>{{ Str::limit($g->description, 110) }}</p>
                        <div class="resource-actions">
                            <a href="{{ route('juegos.show',$g) }}" class="btn-neo btn-compact">Ver</a>
                            @can('update', $g)
                                <a href="{{ route('juegos.edit',$g) }}" class="btn-ghost btn-compact">Editar</a>
                            @endcan
                            @if($questionCount > 0)
                                <a href="{{ route('juegos.play',$g) }}" class="btn-neo btn-compact">Jugar</a>
                            @else
                                <button type="button" class="btn-neo btn-compact" disabled title="Agrega preguntas para activar el juego">Jugar</button>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="resource-empty">Aún no hay juegos disponibles.</div>
            @endforelse
        </div>

        <div class="pagination-shell">
            {{ $games->links() }}
        </div>
    </section>

    <section class="glass-panel alt-panel">
        <div class="section-heading">
            <h2>Juegos interactivos express</h2>
            <span class="tag">3 dinámicas</span>
        </div>

        <div class="external-games-grid">
            <article class="external-game-card">
                <div class="game-embed local-game" id="lightning-quiz">
                    <div class="flash-quiz" data-state="intro">
                        <div class="flash-status">
                            <span class="card-eyebrow">Relámpago</span>
                            <span data-counter>0/0</span>
                        </div>
                        <h3 data-question>¿Listo para retarte?</h3>
                        <p data-description>Click en "Comenzar" y responde rápidamente sobre historia y cultura nicaragüense.</p>
                        <div class="flash-options" data-options></div>
                        <div class="flash-feedback" data-feedback></div>
                        <div class="resource-actions">
                            <button type="button" class="btn-neo btn-compact" data-action="start">Comenzar</button>
                            <button type="button" class="btn-ghost btn-compact" data-action="restart" hidden>Reiniciar</button>
                        </div>
                    </div>
                </div>
                <div class="external-card-body">
                    <span class="card-eyebrow">Desafío propio</span>
                    <h3>Trivia Flash Nicaragua</h3>
                    <p>Un minijuego exclusivo de Redline que mezcla independencia, patrimonio y gastronomía.</p>
                    <p class="muted">Perfecto para proyectar en clase o animar ferias educativas.</p>
                </div>
            </article>

            <article class="external-game-card">
                <div class="game-embed local-game" id="memory-match">
                    <div class="memory-shell" data-state="intro">
                        <div class="memory-status">
                            <span class="card-eyebrow">Memoria</span>
                            <span data-memory-progress>0 pares</span>
                        </div>
                        <div class="memory-grid" data-memory-grid></div>
                        <div class="flash-feedback" data-memory-feedback>Presiona "Iniciar" para barajar las cartas.</div>
                        <div class="resource-actions">
                            <button type="button" class="btn-neo btn-compact" data-action="memory-start">Iniciar</button>
                            <button type="button" class="btn-ghost btn-compact" data-action="memory-restart" hidden>Reiniciar</button>
                        </div>
                    </div>
                </div>
                <div class="external-card-body">
                    <span class="card-eyebrow">Memoria cultural</span>
                    <h3>Empareja héroes y símbolos</h3>
                    <p>Descubre parejas de próceres, manifestaciones artísticas y símbolos patrios para reforzar la identidad nacional.</p>
                    <p class="muted">Ideal para estaciones rápidas en actividades presenciales.</p>
                </div>
            </article>

            <article class="external-game-card">
                <div class="game-embed local-game" id="timeline-challenge">
                    <div class="timeline-shell" data-state="intro">
                        <div class="timeline-status">
                            <span class="card-eyebrow">Línea de tiempo</span>
                            <span data-timeline-progress>0 de 5</span>
                        </div>
                        <h3 data-timeline-heading>Ordena los hitos históricos</h3>
                        <p data-timeline-description>Selecciona los eventos en orden cronológico, comenzando por el más antiguo.</p>
                        <div class="timeline-options" data-timeline-options></div>
                        <div class="flash-feedback" data-timeline-feedback>Haz clic en "Comenzar" para mezclar los eventos.</div>
                        <div class="resource-actions">
                            <button type="button" class="btn-neo btn-compact" data-action="timeline-start">Comenzar</button>
                            <button type="button" class="btn-ghost btn-compact" data-action="timeline-restart" hidden>Reintentar</button>
                        </div>
                    </div>
                </div>
                <div class="external-card-body">
                    <span class="card-eyebrow">Secuencia histórica</span>
                    <h3>Ruta de la independencia</h3>
                    <p>Practica la cronología de acontecimientos clave: independencia, resistencia y cultura.</p>
                    <p class="muted">Perfecto como rompehielo para iniciar debates o foros.</p>
                </div>
            </article>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quizRoot = document.querySelector('#lightning-quiz .flash-quiz');
        if (!quizRoot) {
            return;
        }

        const questionEl = quizRoot.querySelector('[data-question]');
        const descriptionEl = quizRoot.querySelector('[data-description]');
        const optionsEl = quizRoot.querySelector('[data-options]');
        const feedbackEl = quizRoot.querySelector('[data-feedback]');
        const counterEl = quizRoot.querySelector('[data-counter]');
        const startBtn = quizRoot.querySelector('[data-action="start"]');
        const restartBtn = quizRoot.querySelector('[data-action="restart"]');

        const questions = [
            {
                prompt: '¿Qué héroe firmó el Acta de Independencia como representante de Nicaragua?',
                options: [
                    { label: 'Miguel Larreynaga', correct: true },
                    { label: 'José Dolores Estrada', correct: false },
                    { label: 'Tomás Martínez', correct: false }
                ],
                hint: 'Figura clave y diplomático originario de León.'
            },
            {
                prompt: 'La ofensiva final de julio de 1979 se bautizó como:',
                options: [
                    { label: 'Insurrección de Monimbó', correct: false },
                    { label: 'Ofensiva Final Carlos Fonseca', correct: true },
                    { label: 'Operación Managua Libre', correct: false }
                ],
                hint: 'Honra al fundador del FSLN.'
            },
            {
                prompt: '¿Cuál es la bebida tradicional que acompaña la sopa de queso en Semana Santa?',
                options: [
                    { label: 'Chicha de maíz morado', correct: false },
                    { label: 'Pinolillo', correct: true },
                    { label: 'Tiste de cacao', correct: false }
                ],
                hint: 'Se prepara con maíz tostado y cacao.'
            },
            {
                prompt: '¿Qué sitio nicaragüense es Reserva de Biosfera de la UNESCO?',
                options: [
                    { label: 'Volcán Momotombo', correct: false },
                    { label: 'Bosawás', correct: true },
                    { label: 'Isletas de Granada', correct: false }
                ],
                hint: 'Es la segunda selva tropical más grande del hemisferio occidental.'
            },
            {
                prompt: 'Rubén Darío fue nombrado cónsul de Nicaragua en:',
                options: [
                    { label: 'París y Madrid', correct: true },
                    { label: 'Roma y Lisboa', correct: false },
                    { label: 'Buenos Aires y México', correct: false }
                ],
                hint: 'Representó al país en dos capitales europeas.'
            }
        ];

        let position = 0;
        let score = 0;

        const setState = (state) => {
            quizRoot.dataset.state = state;
        };

        const updateCounter = () => {
            counterEl.textContent = `${Math.min(position + 1, questions.length)}/${questions.length}`;
        };

        const resetQuiz = () => {
            position = 0;
            score = 0;
            feedbackEl.textContent = '';
            feedbackEl.classList.remove('is-correct', 'is-wrong');
            renderQuestion();
            setState('running');
            restartBtn.hidden = true;
            startBtn.hidden = true;
        };

        const renderQuestion = () => {
            const question = questions[position];
            if (!question) {
                showResults();
                return;
            }

            questionEl.textContent = question.prompt;
            descriptionEl.textContent = question.hint;
            optionsEl.innerHTML = '';

            question.options.forEach((option) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn-ghost btn-compact flash-option';
                button.textContent = option.label;
                button.addEventListener('click', () => handleAnswer(option, button));
                optionsEl.appendChild(button);
            });

            feedbackEl.textContent = 'Selecciona la alternativa correcta';
            feedbackEl.classList.remove('is-correct', 'is-wrong');
            updateCounter();
        };

        const handleAnswer = (option, button) => {
            const buttons = optionsEl.querySelectorAll('button');
            buttons.forEach((btn) => {
                btn.disabled = true;
                btn.classList.add('is-locked');
            });

            if (option.correct) {
                score += 1;
                feedbackEl.textContent = '¡Correcto! Sigue así.';
                feedbackEl.classList.add('is-correct');
            } else {
                feedbackEl.textContent = 'Respuesta incorrecta, repasa tu Redline.';
                feedbackEl.classList.add('is-wrong');
            }

            button.classList.add(option.correct ? 'is-correct' : 'is-wrong');

            setTimeout(() => {
                position += 1;
                buttons.forEach((btn) => {
                    btn.disabled = false;
                    btn.classList.remove('is-locked', 'is-correct', 'is-wrong');
                });
                renderQuestion();
            }, 1200);
        };

        const showResults = () => {
            setState('finished');
            questionEl.textContent = 'Resultado final';
            descriptionEl.textContent = 'Puntaje obtenido en este relámpago cultural';
            optionsEl.innerHTML = '';
            feedbackEl.textContent = `Acertaste ${score} de ${questions.length} preguntas.`;
            feedbackEl.classList.remove('is-correct', 'is-wrong');
            restartBtn.hidden = false;
            startBtn.hidden = true;
            updateCounter();
        };

        startBtn.addEventListener('click', resetQuiz);
        restartBtn.addEventListener('click', resetQuiz);

        setState('intro');
        counterEl.textContent = `0/${questions.length}`;
    });

    document.addEventListener('DOMContentLoaded', () => {
        const memoryRoot = document.querySelector('#memory-match .memory-shell');
        if (!memoryRoot) {
            return;
        }

        const gridEl = memoryRoot.querySelector('[data-memory-grid]');
        const feedbackEl = memoryRoot.querySelector('[data-memory-feedback]');
        const progressEl = memoryRoot.querySelector('[data-memory-progress]');
        const startBtn = memoryRoot.querySelector('[data-action="memory-start"]');
        const restartBtn = memoryRoot.querySelector('[data-action="memory-restart"]');

        const pairs = [
            { id: 'larreynaga', label: 'Miguel Larreynaga', group: 'Prócer de la Independencia' },
            { id: 'gueguense', label: 'El Güegüense', group: 'Patrimonio Inmaterial' },
            { id: 'bosawas', label: 'Reserva Bosawás', group: 'Biodiversidad' },
            { id: 'andrescastro', label: 'Andrés Castro', group: 'Heroísmo Nacional' },
            { id: 'pinolillo', label: 'Pinolillo', group: 'Gastronomía tradicional' },
            { id: 'dario', label: 'Rubén Darío', group: 'Modernismo' }
        ];

        let revealed = [];
        let matched = new Set();
        let lockBoard = false;

        const shuffle = (array) => [...array].sort(() => Math.random() - 0.5);

        const renderGrid = () => {
            const cards = shuffle([...pairs, ...pairs]);
            gridEl.innerHTML = '';
            cards.forEach((card) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'memory-card btn-ghost btn-compact';
                button.dataset.cardId = card.id;
                button.dataset.group = card.group;
                button.dataset.revealed = 'false';
                button.innerHTML = `<span class="memory-front">?</span><span class="memory-back"><strong>${card.label}</strong><small>${card.group}</small></span>`;
                button.addEventListener('click', () => revealCard(button));
                gridEl.appendChild(button);
            });
        };

        const updateProgress = () => {
            progressEl.textContent = `${matched.size} pares`;
        };

        const revealCard = (button) => {
            if (lockBoard || matched.has(button.dataset.cardId) || button.dataset.revealed === 'true') {
                return;
            }

            button.dataset.revealed = 'true';
            button.classList.add('is-flipped');
            revealed.push(button);

            if (revealed.length === 2) {
                checkMatch();
            }
        };

        const checkMatch = () => {
            const [first, second] = revealed;
            if (!first || !second) {
                return;
            }

            lockBoard = true;

            if (first.dataset.cardId === second.dataset.cardId && first !== second) {
                matched.add(first.dataset.cardId);
                feedbackEl.textContent = '¡Excelente! Encontraste una pareja.';
                feedbackEl.classList.add('is-correct');
                feedbackEl.classList.remove('is-wrong');
                updateProgress();
                resetReveal();

                if (matched.size === pairs.length) {
                    feedbackEl.textContent = '¡Completaste todas las parejas!';
                    restartBtn.hidden = false;
                }
            } else {
                feedbackEl.textContent = 'No coinciden, inténtalo de nuevo.';
                feedbackEl.classList.add('is-wrong');
                feedbackEl.classList.remove('is-correct');
                setTimeout(() => {
                    revealed.forEach((card) => {
                        card.dataset.revealed = 'false';
                        card.classList.remove('is-flipped');
                    });
                    resetReveal();
                }, 900);
            }
        };

        const resetReveal = () => {
            revealed = [];
            lockBoard = false;
        };

        const resetGame = () => {
            matched = new Set();
            feedbackEl.textContent = 'Presiona "Iniciar" para barajar las cartas.';
            feedbackEl.classList.remove('is-correct', 'is-wrong');
            updateProgress();
            renderGrid();
            restartBtn.hidden = true;
            memoryRoot.dataset.state = 'running';
        };

        startBtn.addEventListener('click', () => {
            resetGame();
            startBtn.hidden = true;
        });

        restartBtn.addEventListener('click', resetGame);

        memoryRoot.dataset.state = 'intro';
        updateProgress();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const timelineRoot = document.querySelector('#timeline-challenge .timeline-shell');
        if (!timelineRoot) {
            return;
        }

        const optionsEl = timelineRoot.querySelector('[data-timeline-options]');
        const feedbackEl = timelineRoot.querySelector('[data-timeline-feedback]');
        const progressEl = timelineRoot.querySelector('[data-timeline-progress]');
        const startBtn = timelineRoot.querySelector('[data-action="timeline-start"]');
        const restartBtn = timelineRoot.querySelector('[data-action="timeline-restart"]');

        const events = [
            { label: 'Acta de Independencia de Centroamérica', year: 1821 },
            { label: 'Batalla de San Jacinto', year: 1856 },
            { label: 'Obra "Azul..." de Rubén Darío', year: 1888 },
            { label: 'Triunfo de la Revolución Sandinista', year: 1979 },
            { label: 'UNESCO reconoce El Güegüense', year: 2005 }
        ];

        let attempt = [];

        const shuffle = (array) => [...array].sort(() => Math.random() - 0.5);

        const renderTimeline = () => {
            const shuffled = shuffle(events);
            optionsEl.innerHTML = '';
            shuffled.forEach((event) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'btn-ghost btn-compact timeline-option';
                button.textContent = `${event.label}`;
                button.dataset.year = event.year;
                button.addEventListener('click', () => handleSelection(button));
                optionsEl.appendChild(button);
            });
            progressEl.textContent = '0 de 5';
        };

        const handleSelection = (button) => {
            if (attempt.includes(button)) {
                return;
            }

            button.disabled = true;
            button.classList.add('is-active');
            attempt.push(button);
            progressEl.textContent = `${attempt.length} de ${events.length}`;

            if (attempt.length === events.length) {
                verifyOrder();
            }
        };

        const verifyOrder = () => {
            const years = attempt.map((button) => parseInt(button.dataset.year, 10));
            const isCorrect = years.every((year, index, arr) => index === 0 || arr[index - 1] <= year);

            if (isCorrect) {
                feedbackEl.textContent = '¡Secuencia correcta! Dominás la historia patria.';
                feedbackEl.classList.add('is-correct');
                feedbackEl.classList.remove('is-wrong');
                restartBtn.hidden = false;
            } else {
                feedbackEl.textContent = 'Orden incorrecto. Revisa los hitos y vuelve a intentarlo.';
                feedbackEl.classList.add('is-wrong');
                feedbackEl.classList.remove('is-correct');
                attempt.forEach((button) => {
                    button.disabled = false;
                    button.classList.remove('is-active');
                });
                attempt = [];
                progressEl.textContent = '0 de 5';
            }
        };

        const resetTimeline = () => {
            attempt = [];
            feedbackEl.textContent = 'Haz clic en "Comenzar" para mezclar los eventos.';
            feedbackEl.classList.remove('is-correct', 'is-wrong');
            renderTimeline();
            restartBtn.hidden = true;
        };

        startBtn.addEventListener('click', () => {
            timelineRoot.dataset.state = 'running';
            startBtn.hidden = true;
            resetTimeline();
        });

        restartBtn.addEventListener('click', resetTimeline);

        timelineRoot.dataset.state = 'intro';
    });
</script>
@endsection