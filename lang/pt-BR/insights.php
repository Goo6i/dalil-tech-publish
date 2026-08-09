<?php

return [
    'title' => 'Insights',
    'description' => 'Desempenho de vídeos e contas do coletor de analytics.',
    'no_accounts' => 'Nenhuma conta conectada com dados de insights.',

    'tabs' => [
        'nav_label' => 'Visualização de analytics',
        'live' => 'Ao vivo',
        'insights' => 'Insights',
        'best_times' => 'Melhores horários',
    ],

    'filters' => [
        'all_accounts' => 'Todas as contas',
    ],

    'data_quality' => [
        'title' => 'Aviso de qualidade dos dados',
    ],

    'stats' => [
        'followers' => 'Seguidores',
        'net_7d' => 'Seguidores líquidos (7d)',
        'received_views_7d' => 'Visualizações recebidas (7d)',
        'received_views_7d_scope' => 'Em todas as contas conectadas',
    ],

    'chart' => [
        'title' => 'Crescimento de seguidores',
        'no_data' => 'Ainda não há dados suficientes.',
        'followers' => 'Seguidores',
        'posts_that_day' => 'Posts publicados',
    ],

    'scorecard' => [
        'title' => 'Placar de vídeos',
        'no_data' => 'Nenhum vídeo rastreado ainda.',
        'class_experimental_tooltip' => 'EXPERIMENTAL: esse classificador ainda não foi validado com base em resultados reais. Encare como uma pista, não um veredito.',
        'columns' => [
            'title' => 'Vídeo',
            'posted_at' => 'Publicado',
            'views' => 'Visualizações',
            'views_24h' => 'Visualizações (24h)',
            'er_views' => 'ER (visualizações)',
            'share_rate' => 'Taxa de compartilhamento',
            'rank_24h' => 'Posição (24h)',
            'trajectory' => 'Trajetória',
            'class' => 'Classe',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Ativo',
            'fading' => 'Em queda',
            'dead' => 'Estagnado',
        ],
        'class' => [
            'spike' => 'Pico',
            'word_of_mouth' => 'Boca a boca',
            'mixed' => 'Misto',
        ],
    ],

    'alerts' => [
        'title' => 'Alertas recentes',
        'empty' => 'Nenhum alerta enviado ainda.',
        'types' => [
            'breakout' => 'Decolagem',
            'flop' => 'Fracasso',
            'resurgence' => 'Retomada',
        ],
    ],

    'video' => [
        'posted_label' => 'Publicado',
        'experimental_badge' => 'EXPERIMENTAL',

        'trajectory' => [
            'title' => 'Visualizações por hora',
            'no_data' => 'Ainda não há capturas suficientes.',
            'views_per_hour' => 'Visualizações/hora',
            'peak_marker' => 'Pico',
            'per_hour' => 'visualizações/h',
        ],

        'milestones' => [
            'title' => 'Marcos',
            'no_data' => 'Ainda não há capturas ancoradas por idade.',
            'measured_at_label' => 'Medido em',
        ],

        'expectation' => [
            'title' => 'Visualizações esperadas no dia 7',
            'band_label' => 'Faixa esperada',
            'actual_label' => 'Real até agora',
            'training_n_label' => 'Treinado com vídeos',
            'explanation' => 'A faixa vem de como os próprios vídeos maduros desta conta cresceram entre o dia 1 e o dia 7. É uma faixa, não uma promessa.',
            'insufficient_data' => 'Ainda não há vídeos maduros suficientes para prever uma faixa.',
        ],

        'class' => [
            'title' => 'Formato de tráfego',
            'peak_share_label' => 'Participação do dia de pico',
            'explanation' => 'Pico significa que a maior parte das visualizações chegou em um único dia; boca a boca significa que as visualizações continuaram chegando ao longo do tempo.',
            'insufficient_data' => 'O vídeo é anterior ao início da coleta, então sua fase inicial nunca foi observada. Não é possível ler um formato confiável a partir de uma série parcial.',
        ],

        'mix' => [
            'title' => 'Mix de engajamento',
            'like_rate' => 'Curtidas por visualização',
            'comment_rate' => 'Comentários por visualização',
            'share_rate' => 'Compartilhamentos por visualização',
        ],

        'attribution' => [
            'title' => 'Atribuição de seguidores',
            'followers_earned' => 'Seguidores conquistados',
            'followers_per_1k_views' => 'Seguidores por 1 mil visualizações',
            'avg_confidence' => 'Confiança média',
            'disclaimer' => 'ESTIMATIVA: pressupõe uma conversão uniforme de visualização para seguidor, o que superestima sistematicamente vídeos de alcance amplo e subestima conversores de nicho. Nunca é uma nota de aprovação ou reprovação para o conteúdo. Variações negativas são mantidas (deixar de seguir é real).',
        ],

        'tiktok_studio_link' => 'Retenção e origens de tráfego estão disponíveis no TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Melhores horários para publicar',
        'description' => 'As células classificadas vêm do seu próprio histórico de publicações e são pontuadas 24 horas após cada post. Uma célula só recebe posição quando tem pelo menos 3 posts pontuados; abaixo disso, mostra quantos posts tem em vez de uma posição.',
        'columns' => [
            'weekday' => 'Dia útil',
            'weekend' => 'Fim de semana',
        ],
        'dayparts' => [
            'morning' => 'Manhã',
            'afternoon' => 'Tarde',
            'evening' => 'Noite',
            'night' => 'Madrugada',
        ],
        'cell' => [
            'median_views_24h' => 'Visualizações medianas (24h)',
            'n' => 'n=:n',
            'best' => 'Melhor',
            'empty' => 'Nenhum post ainda',
        ],
    ],

    'hint' => [
        'best_slot' => 'Melhor horário até agora: :daypart, :daytype (n=:n)',
        'daypart' => [
            'morning' => 'manhã',
            'afternoon' => 'tarde',
            'evening' => 'noite',
            'night' => 'madrugada',
        ],
        'daytype' => [
            'weekday' => 'dia útil',
            'weekend' => 'fim de semana',
        ],
    ],
];
