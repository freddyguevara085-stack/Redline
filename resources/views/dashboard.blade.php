<x-app-layout>
    @php
        $user = Auth::user()->loadMissing(['score', 'badges']);
        $score = optional($user->score);
        $badges = $user->badges;
        $rankingUsers = \App\Models\UserScore::with('user')->orderByDesc('score')->take(5)->get();
        $position = $score ? optional($rankingUsers->pluck('user_id'))->search($user->id) : false;
        if ($position === false) {
            $positionList = \App\Models\UserScore::orderByDesc('score')->pluck('user_id')->toArray();
            $position = array_search($user->id, $positionList);
        }
        $position = $position === false ? 'Sin ranking' : $position + 1;
        $recentNews = \App\Models\News::latest('published_at')->take(3)->get();
        $upcomingEvents = \App\Models\Event::where('start_at', '>=', now())->orderBy('start_at')->take(3)->get();
        $latestHistories = \App\Models\History::with('category')->latest('published_at')->take(3)->get();
        $scoreValue = $score->score ?? 0;
        $nextBadge = \App\Models\Badge::where('threshold', '>', $scoreValue)->orderBy('threshold')->first();
        $nextBadgeProgress = $nextBadge ? min(100, round(($scoreValue / $nextBadge->threshold) * 100)) : 100;
        $pointsToNextBadge = $nextBadge ? max(0, $nextBadge->threshold - $scoreValue) : 0;
        $latestNewsItem = $recentNews->first();
        $nextEvent = $upcomingEvents->first();
        $latestHistoryItem = $latestHistories->first();
        $actionChecklist = [
            [
                'label' => 'Completa un nuevo quiz',
                'done' => ($score->quizzes_taken ?? 0) > 0,
                'url' => route('quiz.index'),
                'hint' => 'Refuerza tus conocimientos con desafíos cortos.'
            ],
            [
                'label' => 'Comparte o revisa una noticia',
                'done' => $recentNews->isNotEmpty(),
                'url' => $latestNewsItem ? route('noticias.show', $latestNewsItem) : route('noticias.create'),
                'hint' => $recentNews->isNotEmpty() ? 'Revisa la última publicación de la comunidad.' : 'Comparte novedades históricas para todos.'
            ],
            [
                'label' => 'Organiza un evento en la agenda',
                'done' => $upcomingEvents->isNotEmpty(),
                'url' => $nextEvent ? route('calendario.show', $nextEvent) : route('calendario.create'),
                'hint' => $upcomingEvents->isNotEmpty() ? 'Tienes actividades programadas, confirma tu asistencia.' : 'Propón una nueva actividad para la comunidad.'
            ],
            [
                'label' => 'Publica una historia destacada',
                'done' => $latestHistories->isNotEmpty(),
                'url' => $latestHistoryItem ? route('historia.show', $latestHistoryItem) : route('historia.create'),
                'hint' => $latestHistories->isNotEmpty() ? 'Explora los aportes más recientes.' : 'Comparte tus hallazgos históricos.'
            ],
        ];
        $suggestions = [
            [
                'title' => $latestNewsItem?->title ?? 'Publica tu primera noticia',
                'description' => $latestNewsItem ? \Illuminate\Support\Str::limit($latestNewsItem->body, 110) : 'Comparte novedades históricas y mantén informada a la comunidad.',
                'cta_label' => $latestNewsItem ? 'Leer noticia' : 'Crear noticia',
                'cta_url' => $latestNewsItem ? route('noticias.show', $latestNewsItem) : route('noticias.create')
            ],
            [
                'title' => $nextEvent?->title ?? 'Agenda un nuevo evento',
                'description' => $nextEvent ? \Illuminate\Support\Str::limit($nextEvent->description, 110) : 'Coordina una reunión, clase o visita guiada para tu grupo.',
                'cta_label' => $nextEvent ? 'Ver detalles' : 'Crear evento',
                'cta_url' => $nextEvent ? route('calendario.show', $nextEvent) : route('calendario.create')
            ],
            [
                'title' => $latestHistoryItem?->title ?? 'Comparte una nueva historia',
                'description' => $latestHistoryItem ? \Illuminate\Support\Str::limit($latestHistoryItem->excerpt, 110) : 'Añade a la colección un relato que merezca ser recordado.',
                'cta_label' => $latestHistoryItem ? 'Leer historia' : 'Crear historia',
                'cta_url' => $latestHistoryItem ? route('historia.show', $latestHistoryItem) : route('historia.create')
            ],
            [
                'title' => 'Escala posiciones en el ranking',
                'description' => $position === 'Sin ranking'
                    ? 'Aún no apareces en el ranking. Completa quizzes para sumar puntos.'
                    : "Actualmente estás en la posición {$position}. Mantén el ritmo para llegar al top 3.",
                'cta_label' => 'Ver ranking',
                'cta_url' => route('ranking.index')
            ],
        ];
    @endphp

    <div class="section-block">
        <section class="hero-banner">
            <div class="hero-grid">
                <div class="hero-content">
                    <span class="tag">Redline</span>
                    <h1>Hola {{ $user->name }}, tu aventura histórica continúa</h1>
                    <p class="muted">Sigue acumulando conocimiento, desbloquea insignias y encuentra nuevos retos cada día.</p>
                    <div class="hero-actions">
                        <a href="{{ route('quiz.index') }}" class="btn-neo">Comenzar un quiz</a>
                        <a href="{{ route('ranking.index') }}" class="btn-ghost">Ver ranking semanal</a>
                    </div>
                </div>
                <div class="panel-solid" style="padding: 2rem;">
                    <div class="metric-title">Resumen rápido</div>
                    <div class="metric-stack" style="margin-top: 1.1rem;">
                        <div class="metric-stack-item">
                            <div class="metric-title">Puntaje total</div>
                            <div class="metric-value" style="font-size: 1.85rem;">{{ $score->score ?? 0 }}</div>
                        </div>
                        <div class="metric-stack-item">
                            <div class="metric-title">Quizzes completados</div>
                            <div class="metric-value" style="font-size: 1.85rem;">{{ $score->quizzes_taken ?? 0 }}</div>
                        </div>
                        <div class="metric-stack-item">
                            <div class="metric-title">Posición actual</div>
                            <div class="metric-value" style="font-size: 1.85rem;">{{ $position }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="glass-panel section-block">
            <div class="section-heading">
                <h2>Insignias desbloqueadas</h2>
                <a href="{{ route('profile.show') }}">Ver perfil completo</a>
            </div>

            @if($badges->count())
                <div class="badge-board">
                    @foreach($badges as $badge)
                        <div class="badge-card">
                            <img src="{{ $badge->icon }}" alt="{{ $badge->name }}">
                            <div>
                                <strong>{{ $badge->name }}</strong>
                                <span class="muted">Meta: {{ $badge->threshold }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="empty-state">Aún no tienes insignias. Completa quizzes y eventos para desbloquear tus primeras recompensas.</p>
            @endif
        </section>

        <section class="hero-grid">
            <div class="glass-panel section-block">
                <div class="section-heading">
                    <h2>Accesos rápidos</h2>
                </div>
                <div class="quick-links-grid">
                    <a href="{{ route('quiz.index') }}" class="quick-link-card">
                        <span class="quick-link-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/3874/3874303.png" alt="Quiz">
                        </span>
                        <div class="quick-link-text">
                            <strong>Quiz de Historia</strong>
                            <span>Pon a prueba tus conocimientos y suma puntos.</span>
                        </div>
                    </a>
                    <a href="{{ route('ranking.index') }}" class="quick-link-card">
                        <span class="quick-link-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/2761/2761315.png" alt="Ranking">
                        </span>
                        <div class="quick-link-text">
                            <strong>Ranking global</strong>
                            <span>Descubre quién lidera esta semana.</span>
                        </div>
                    </a>
                    <a href="{{ route('historia.index') }}" class="quick-link-card">
                        <span class="quick-link-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/4380/4380458.png" alt="Historias">
                        </span>
                        <div class="quick-link-text">
                            <strong>Historias destacadas</strong>
                            <span>Lee relatos y comparte descubrimientos.</span>
                        </div>
                    </a>
                    <a href="{{ route('biblioteca.index') }}" class="quick-link-card">
                        <span class="quick-link-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/2303/2303988.png" alt="Biblioteca">
                        </span>
                        <div class="quick-link-text">
                            <strong>Biblioteca digital</strong>
                            <span>Accede a documentos, imágenes y recursos.</span>
                        </div>
                    </a>
                    <a href="{{ route('noticias.index') }}" class="quick-link-card">
                        <span class="quick-link-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/2972/2972331.png" alt="Noticias">
                        </span>
                        <div class="quick-link-text">
                            <strong>Noticias de actualidad</strong>
                            <span>Mantente informado de todo lo nuevo.</span>
                        </div>
                    </a>
                    <a href="{{ route('calendario.index') }}" class="quick-link-card">
                        <span class="quick-link-icon">
                            <img src="https://cdn-icons-png.flaticon.com/512/3176/3176311.png" alt="Calendario">
                        </span>
                        <div class="quick-link-text">
                            <strong>Agenda histórica</strong>
                            <span>Consulta próximos eventos y actividades.</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="glass-panel section-block">
                <div class="section-heading">
                    <h2>Top 5 del ranking</h2>
                    <a href="{{ route('ranking.index') }}">Ver ranking</a>
                </div>
                <ul class="ranking-list">
                    @forelse($rankingUsers as $index => $item)
                        <li class="ranking-entry">
                            <div class="ranking-person">
                                <span class="ranking-rank">{{ $index + 1 }}</span>
                                <div>
                                    <strong>{{ $item->user->name ?? 'Usuario' }}</strong>
                                    <span>{{ $item->score }} pts acumulados</span>
                                </div>
                            </div>
                            <a href="{{ route('ranking.index') }}" class="ranking-action">Detalle</a>
                        </li>
                    @empty
                        <li class="empty-state">Todavía no hay usuarios en el ranking. Sé el primero en sumar puntos.</li>
                    @endforelse
                </ul>
            </div>
        </section>

        <section class="hero-grid">
            <div class="glass-panel section-block">
                <div class="section-heading">
                    <h2>Próximo logro</h2>
                </div>
                <div class="goal-card">
                    <div>
                        <p class="muted">Siguiente insignia</p>
                        <h3>{{ $nextBadge?->name ?? 'Máximo nivel alcanzado' }}</h3>
                        <p class="muted">
                            @if($nextBadge)
                                Necesitas {{ $pointsToNextBadge }} puntos adicionales para desbloquearla.
                            @else
                                ¡Has superado todas las metas disponibles por ahora!
                            @endif
                        </p>
                    </div>
                    <div class="progress-track">
                        <div class="progress-bar" style="width: {{ $nextBadgeProgress }}%;"></div>
                    </div>
                    <div class="goal-meta">
                        <span>{{ $scoreValue }} pts acumulados</span>
                        <span>{{ $nextBadge?->threshold ?? $scoreValue }} pts objetivo</span>
                    </div>
                    <a href="{{ route('quiz.index') }}" class="btn-neo">Sumar puntos</a>
                </div>
            </div>

            <div class="glass-panel section-block">
                <div class="section-heading">
                    <h2>Checklist rápida</h2>
                </div>
                <ul class="action-list">
                    @foreach($actionChecklist as $task)
                        <li class="action-item {{ $task['done'] ? 'is-done' : '' }}">
                            <div>
                                <strong class="action-label">{{ $task['label'] }}</strong>
                                <p class="muted">{{ $task['hint'] }}</p>
                            </div>
                            <a href="{{ $task['url'] }}" class="action-link">{{ $task['done'] ? 'Repasar' : 'Ir ahora' }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        <section class="glass-panel section-block">
            <div class="section-heading">
                <h2>Sugerencias personalizadas</h2>
            </div>
            <div class="suggestion-grid">
                @foreach($suggestions as $suggestion)
                    <article class="suggestion-card">
                        <strong>{{ $suggestion['title'] }}</strong>
                        <p class="muted">{{ $suggestion['description'] }}</p>
                        <a href="{{ $suggestion['cta_url'] }}" class="suggestion-link">{{ $suggestion['cta_label'] }}</a>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
