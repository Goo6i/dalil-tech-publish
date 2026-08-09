<?php

return [
    'title' => 'Insights',
    'description' => 'Video- und Kontoleistung aus dem Analytics-Collector.',
    'no_accounts' => 'Keine verbundenen Konten mit Insights-Daten.',

    'tabs' => [
        'nav_label' => 'Analytics-Ansicht',
        'live' => 'Live',
        'insights' => 'Insights',
        'best_times' => 'Beste Zeiten',
    ],

    'filters' => [
        'all_accounts' => 'Alle Konten',
    ],

    'data_quality' => [
        'title' => 'Warnung zur Datenqualität',
    ],

    'stats' => [
        'followers' => 'Follower',
        'net_7d' => 'Netto-Follower (7 Tage)',
        'received_views_7d' => 'Erhaltene Aufrufe (7 Tage)',
        'received_views_7d_scope' => 'Über alle verbundenen Konten hinweg',
    ],

    'chart' => [
        'title' => 'Follower-Wachstum',
        'no_data' => 'Noch nicht genug Daten.',
        'followers' => 'Follower',
        'posts_that_day' => 'Veröffentlichte Beiträge',
    ],

    'scorecard' => [
        'title' => 'Video-Scorecard',
        'no_data' => 'Noch keine Videos erfasst.',
        'class_experimental_tooltip' => 'EXPERIMENTELL: Dieser Klassifikator ist noch nicht anhand tatsächlicher Ergebnisse validiert. Verstehe ihn als Hinweis, nicht als Urteil.',
        'columns' => [
            'title' => 'Video',
            'posted_at' => 'Veröffentlicht',
            'views' => 'Aufrufe',
            'views_24h' => 'Aufrufe (24 Std.)',
            'er_views' => 'ER (Aufrufe)',
            'share_rate' => 'Share-Rate',
            'rank_24h' => 'Rang (24 Std.)',
            'trajectory' => 'Verlauf',
            'class' => 'Klasse',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Aktiv',
            'fading' => 'Abklingend',
            'dead' => 'Beendet',
        ],
        'class' => [
            'spike' => 'Spike',
            'word_of_mouth' => 'Mundpropaganda',
            'mixed' => 'Gemischt',
        ],
    ],

    'alerts' => [
        'title' => 'Neueste Warnungen',
        'empty' => 'Noch keine Warnungen gesendet.',
        'types' => [
            'breakout' => 'Durchbruch',
            'flop' => 'Flop',
            'resurgence' => 'Wiederaufleben',
        ],
    ],

    'video' => [
        'posted_label' => 'Veröffentlicht',
        'experimental_badge' => 'EXPERIMENTELL',

        'trajectory' => [
            'title' => 'Aufrufe pro Stunde',
            'no_data' => 'Noch nicht genug Snapshots.',
            'views_per_hour' => 'Aufrufe/Stunde',
            'peak_marker' => 'Höhepunkt',
            'per_hour' => 'Aufrufe/Std.',
        ],

        'milestones' => [
            'title' => 'Meilensteine',
            'no_data' => 'Noch keine altersbezogenen Snapshots.',
            'measured_at_label' => 'Gemessen am',
        ],

        'expectation' => [
            'title' => 'Erwartete Aufrufe an Tag 7',
            'band_label' => 'Erwarteter Bereich',
            'actual_label' => 'Bisher tatsächlich',
            'training_n_label' => 'Trainiert an Videos',
            'explanation' => 'Der Bereich ergibt sich daraus, wie die eigenen ausgereiften Videos dieses Kontos zwischen Tag 1 und Tag 7 gewachsen sind. Es ist eine Bandbreite, kein Versprechen.',
            'insufficient_data' => 'Noch nicht genug ausgereifte Videos, um einen Bereich vorherzusagen.',
        ],

        'class' => [
            'title' => 'Verlaufsform',
            'peak_share_label' => 'Anteil am Spitzentag',
            'explanation' => 'Spike bedeutet, dass die meisten Aufrufe an einem einzigen Tag entstanden; Mundpropaganda bedeutet, dass Aufrufe über die Zeit hinweg weiter eintrafen.',
            'insufficient_data' => 'Das Video wurde vor Beginn der Erfassung veröffentlicht, seine frühe Phase wurde also nie beobachtet. Aus einer unvollständigen Reihe lässt sich keine ehrliche Form ablesen.',
        ],

        'mix' => [
            'title' => 'Engagement-Mix',
            'like_rate' => 'Likes pro Aufruf',
            'comment_rate' => 'Kommentare pro Aufruf',
            'share_rate' => 'Shares pro Aufruf',
        ],

        'attribution' => [
            'title' => 'Follower-Zuordnung',
            'followers_earned' => 'Gewonnene Follower',
            'followers_per_1k_views' => 'Follower pro 1.000 Aufrufe',
            'avg_confidence' => 'Durchschnittliche Konfidenz',
            'disclaimer' => 'NÄHERUNGSWERT: geht von einer gleichmäßigen Umwandlung von Aufruf zu Follow aus, was breit angelegte Videos systematisch überbewertet und Nischen-Konverter unterbewertet. Nie eine Bestehen-oder-Durchfallen-Bewertung für Inhalte. Negative Veränderungen bleiben erhalten (Entfolgen sind real).',
        ],

        'tiktok_studio_link' => 'Verweildauer und Traffic-Quellen findest du in TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Beste Zeiten zum Posten',
        'description' => 'Bewertete Zellen stammen aus deiner eigenen Posting-Historie und werden 24 Stunden nach jedem Beitrag bewertet. Eine Zelle erhält erst dann einen Rang, wenn sie mindestens 3 bewertete Beiträge hat; darunter zeigt sie stattdessen an, wie viele Beiträge sie hat.',
        'columns' => [
            'weekday' => 'Wochentag',
            'weekend' => 'Wochenende',
        ],
        'dayparts' => [
            'morning' => 'Morgen',
            'afternoon' => 'Nachmittag',
            'evening' => 'Abend',
            'night' => 'Nacht',
        ],
        'cell' => [
            'median_views_24h' => 'Median-Aufrufe (24 Std.)',
            'n' => 'n=:n',
            'best' => 'Beste',
            'empty' => 'Noch keine Beiträge',
        ],
    ],

    'hint' => [
        'best_slot' => 'Bester Slot bisher: :daypart, :daytype (n=:n)',
        'daypart' => [
            'morning' => 'morgens',
            'afternoon' => 'nachmittags',
            'evening' => 'abends',
            'night' => 'nachts',
        ],
        'daytype' => [
            'weekday' => 'werktags',
            'weekend' => 'am Wochenende',
        ],
    ],
];
