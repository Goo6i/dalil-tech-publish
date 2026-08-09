<?php

return [
    'title' => 'Insights',
    'description' => 'Rendimiento de vídeos y cuentas del recopilador de analíticas.',
    'no_accounts' => 'No hay cuentas conectadas con datos de insights.',

    'tabs' => [
        'nav_label' => 'Vista de analíticas',
        'live' => 'En vivo',
        'insights' => 'Insights',
        'best_times' => 'Mejores horarios',
    ],

    'filters' => [
        'all_accounts' => 'Todas las cuentas',
    ],

    'data_quality' => [
        'title' => 'Advertencia de calidad de datos',
    ],

    'stats' => [
        'followers' => 'Seguidores',
        'net_7d' => 'Seguidores netos (7 d)',
        'received_views_7d' => 'Vistas recibidas (7 d)',
        'received_views_7d_scope' => 'En todas las cuentas conectadas',
    ],

    'chart' => [
        'title' => 'Crecimiento de seguidores',
        'no_data' => 'Aún no hay datos suficientes.',
        'followers' => 'Seguidores',
        'posts_that_day' => 'Posts publicados',
    ],

    'scorecard' => [
        'title' => 'Marcador de vídeos',
        'no_data' => 'Aún no se ha rastreado ningún vídeo.',
        'class_experimental_tooltip' => 'EXPERIMENTAL: este clasificador aún no está validado frente a resultados reales. Tómalo como una pista, no como un veredicto.',
        'columns' => [
            'title' => 'Vídeo',
            'posted_at' => 'Publicado',
            'views' => 'Vistas',
            'views_24h' => 'Vistas (24h)',
            'er_views' => 'ER (vistas)',
            'share_rate' => 'Tasa de compartidos',
            'rank_24h' => 'Puesto (24h)',
            'trajectory' => 'Trayectoria',
            'class' => 'Clase',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Activo',
            'fading' => 'En descenso',
            'dead' => 'Agotado',
        ],
        'class' => [
            'spike' => 'Pico',
            'word_of_mouth' => 'Boca a boca',
            'mixed' => 'Mixto',
        ],
    ],

    'alerts' => [
        'title' => 'Alertas recientes',
        'empty' => 'Aún no se ha enviado ninguna alerta.',
        'types' => [
            'breakout' => 'Despegue',
            'flop' => 'Fracaso',
            'resurgence' => 'Repunte',
        ],
    ],

    'video' => [
        'posted_label' => 'Publicado',
        'experimental_badge' => 'EXPERIMENTAL',

        'trajectory' => [
            'title' => 'Vistas por hora',
            'no_data' => 'Aún no hay suficientes capturas.',
            'views_per_hour' => 'Vistas/hora',
            'peak_marker' => 'Pico',
            'per_hour' => 'vistas/h',
        ],

        'milestones' => [
            'title' => 'Hitos',
            'no_data' => 'Aún no hay capturas ancladas por antigüedad.',
            'measured_at_label' => 'Medido el',
        ],

        'expectation' => [
            'title' => 'Vistas esperadas al día 7',
            'band_label' => 'Rango esperado',
            'actual_label' => 'Real hasta ahora',
            'training_n_label' => 'Entrenado con vídeos',
            'explanation' => 'El rango proviene de cómo crecieron los propios vídeos maduros de esta cuenta entre el día 1 y el día 7. Es una banda, no una promesa.',
            'insufficient_data' => 'Aún no hay suficientes vídeos maduros para predecir un rango.',
        ],

        'class' => [
            'title' => 'Forma del tráfico',
            'peak_share_label' => 'Cuota del día pico',
            'explanation' => 'Pico significa que la mayoría de las vistas llegaron en un solo día; boca a boca significa que las vistas siguieron llegando con el tiempo.',
            'insufficient_data' => 'El vídeo es anterior al inicio de la recopilación, por lo que su etapa inicial nunca se observó. No se puede leer una forma honesta a partir de una serie parcial.',
        ],

        'mix' => [
            'title' => 'Mezcla de interacción',
            'like_rate' => 'Me gusta por vista',
            'comment_rate' => 'Comentarios por vista',
            'share_rate' => 'Compartidos por vista',
        ],

        'attribution' => [
            'title' => 'Atribución de seguidores',
            'followers_earned' => 'Seguidores ganados',
            'followers_per_1k_views' => 'Seguidores por cada 1000 vistas',
            'avg_confidence' => 'Confianza promedio',
            'disclaimer' => 'ESTIMACIÓN: asume una conversión uniforme de vista a seguimiento, lo que sobrevalora sistemáticamente los vídeos de alcance amplio y subestima a los conversores de nicho. Nunca es una calificación de aprobado o suspenso para el contenido. Los deltas negativos se conservan (dejar de seguir es real).',
        ],

        'tiktok_studio_link' => 'La retención y las fuentes de tráfico están disponibles en TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Mejores horarios para publicar',
        'description' => 'Las celdas clasificadas provienen de tu propio historial de publicaciones y se puntúan 24 horas después de cada post. Una celda solo obtiene puesto una vez que tiene al menos 3 posts puntuados; por debajo de eso, muestra cuántos posts tiene en lugar de un puesto.',
        'columns' => [
            'weekday' => 'Entre semana',
            'weekend' => 'Fin de semana',
        ],
        'dayparts' => [
            'morning' => 'Mañana',
            'afternoon' => 'Tarde',
            'evening' => 'Noche',
            'night' => 'Madrugada',
        ],
        'cell' => [
            'median_views_24h' => 'Vistas medianas (24h)',
            'n' => 'n=:n',
            'best' => 'Mejor',
            'empty' => 'Sin posts aún',
        ],
    ],

    'hint' => [
        'best_slot' => 'Mejor franja hasta ahora: :daypart :daytype (n=:n)',
        'daypart' => [
            'morning' => 'mañana',
            'afternoon' => 'tarde',
            'evening' => 'noche',
            'night' => 'madrugada',
        ],
        'daytype' => [
            'weekday' => 'entre semana',
            'weekend' => 'fin de semana',
        ],
    ],
];
