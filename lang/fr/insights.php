<?php

return [
    'title' => 'Aperçus',
    'description' => 'Performance des vidéos et des comptes issue du collecteur de statistiques.',
    'no_accounts' => 'Aucun compte connecté avec des données d\'aperçus.',

    'tabs' => [
        'nav_label' => 'Vue des statistiques',
        'live' => 'En direct',
        'insights' => 'Aperçus',
        'best_times' => 'Meilleurs horaires',
    ],

    'filters' => [
        'all_accounts' => 'Tous les comptes',
    ],

    'data_quality' => [
        'title' => 'Avertissement sur la qualité des données',
    ],

    'stats' => [
        'followers' => 'Abonnés',
        'net_7d' => 'Abonnés nets (7 j)',
        'received_views_7d' => 'Vues reçues (7 j)',
        'received_views_7d_scope' => 'Sur tous les comptes connectés',
    ],

    'chart' => [
        'title' => 'Croissance des abonnés',
        'no_data' => 'Pas encore assez de données.',
        'followers' => 'Abonnés',
        'posts_that_day' => 'Publications publiées',
    ],

    'scorecard' => [
        'title' => 'Tableau de performance vidéo',
        'no_data' => 'Aucune vidéo suivie pour le moment.',
        'class_experimental_tooltip' => 'EXPÉRIMENTAL : ce classificateur n\'est pas encore validé par rapport aux résultats réels. Considérez-le comme un indice, pas un verdict.',
        'columns' => [
            'title' => 'Vidéo',
            'posted_at' => 'Publiée',
            'views' => 'Vues',
            'views_24h' => 'Vues (24h)',
            'er_views' => 'ER (vues)',
            'share_rate' => 'Taux de partage',
            'rank_24h' => 'Rang (24h)',
            'trajectory' => 'Trajectoire',
            'class' => 'Classe',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Active',
            'fading' => 'En baisse',
            'dead' => 'Éteinte',
        ],
        'class' => [
            'spike' => 'Pic',
            'word_of_mouth' => 'Bouche à oreille',
            'mixed' => 'Mixte',
        ],
    ],

    'alerts' => [
        'title' => 'Alertes récentes',
        'empty' => 'Aucune alerte envoyée pour le moment.',
        'types' => [
            'breakout' => 'Envol',
            'flop' => 'Échec',
            'resurgence' => 'Regain',
        ],
    ],

    'video' => [
        'posted_label' => 'Publiée',
        'experimental_badge' => 'EXPÉRIMENTAL',

        'trajectory' => [
            'title' => 'Vues par heure',
            'no_data' => 'Pas encore assez de relevés.',
            'views_per_hour' => 'Vues/heure',
            'peak_marker' => 'Pic',
            'per_hour' => 'vues/h',
        ],

        'milestones' => [
            'title' => 'Étapes clés',
            'no_data' => 'Aucun relevé ancré sur l\'âge pour le moment.',
            'measured_at_label' => 'Mesuré le',
        ],

        'expectation' => [
            'title' => 'Vues attendues au jour 7',
            'band_label' => 'Plage attendue',
            'actual_label' => 'Réel à ce jour',
            'training_n_label' => 'Entraîné sur des vidéos',
            'explanation' => 'La plage provient de la façon dont les propres vidéos matures de ce compte ont évolué entre le jour 1 et le jour 7. C\'est une fourchette, pas une promesse.',
            'insufficient_data' => 'Pas encore assez de vidéos matures pour prédire une plage.',
        ],

        'class' => [
            'title' => 'Forme du trafic',
            'peak_share_label' => 'Part du jour de pic',
            'explanation' => 'Pic signifie que la majorité des vues sont arrivées en une seule journée ; bouche à oreille signifie que les vues ont continué d\'arriver au fil du temps.',
            'insufficient_data' => 'La vidéo est antérieure au début de la collecte, donc ses débuts n\'ont jamais été observés. Aucune forme fiable ne peut être lue à partir d\'une série partielle.',
        ],

        'mix' => [
            'title' => 'Répartition de l\'engagement',
            'like_rate' => 'J\'aime par vue',
            'comment_rate' => 'Commentaires par vue',
            'share_rate' => 'Partages par vue',
        ],

        'attribution' => [
            'title' => 'Attribution des abonnés',
            'followers_earned' => 'Abonnés gagnés',
            'followers_per_1k_views' => 'Abonnés pour 1 000 vues',
            'avg_confidence' => 'Confiance moyenne',
            'disclaimer' => 'ESTIMATION : suppose une conversion uniforme de la vue vers l\'abonnement, ce qui surestime systématiquement les vidéos à large audience et sous-estime les convertisseurs de niche. Ne constitue jamais une note de réussite ou d\'échec pour le contenu. Les variations négatives sont conservées (les désabonnements sont réels).',
        ],

        'tiktok_studio_link' => 'La rétention et les sources de trafic sont disponibles dans TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Meilleurs horaires de publication',
        'description' => 'Les cellules classées proviennent de votre propre historique de publication et sont évaluées 24 heures après chaque publication. Une cellule n\'est classée qu\'à partir de 3 publications évaluées ; en dessous, elle affiche le nombre de publications au lieu d\'un rang.',
        'columns' => [
            'weekday' => 'Semaine',
            'weekend' => 'Week-end',
        ],
        'dayparts' => [
            'morning' => 'Matin',
            'afternoon' => 'Après-midi',
            'evening' => 'Soir',
            'night' => 'Nuit',
        ],
        'cell' => [
            'median_views_24h' => 'Vues médianes (24h)',
            'n' => 'n=:n',
            'best' => 'Meilleur',
            'empty' => 'Aucune publication pour le moment',
        ],
    ],

    'hint' => [
        'best_slot' => 'Meilleur créneau à ce jour : :daypart, :daytype (n=:n)',
        'daypart' => [
            'morning' => 'matin',
            'afternoon' => 'après-midi',
            'evening' => 'soir',
            'night' => 'nuit',
        ],
        'daytype' => [
            'weekday' => 'en semaine',
            'weekend' => 'le week-end',
        ],
    ],
];
