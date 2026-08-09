<?php

return [
    'title' => 'Inzichten',
    'description' => 'Video- en accountprestaties van de analytics-collector.',
    'no_accounts' => 'Geen gekoppelde accounts met inzichtgegevens.',

    'tabs' => [
        'nav_label' => 'Statistiekweergave',
        'live' => 'Live',
        'insights' => 'Inzichten',
        'best_times' => 'Beste tijden',
    ],

    'filters' => [
        'all_accounts' => 'Alle accounts',
    ],

    'data_quality' => [
        'title' => 'Waarschuwing over datakwaliteit',
    ],

    'stats' => [
        'followers' => 'Volgers',
        'net_7d' => 'Netto volgers (7 dgn)',
        'received_views_7d' => 'Ontvangen weergaven (7 dgn)',
        'received_views_7d_scope' => 'Over alle gekoppelde accounts',
    ],

    'chart' => [
        'title' => 'Volgersgroei',
        'no_data' => 'Nog niet genoeg gegevens.',
        'followers' => 'Volgers',
        'posts_that_day' => 'Gepubliceerde posts',
    ],

    'scorecard' => [
        'title' => 'Videoscorekaart',
        'no_data' => 'Nog geen video\'s gevolgd.',
        'class_experimental_tooltip' => 'EXPERIMENTEEL: deze classificator is nog niet gevalideerd aan de hand van werkelijke resultaten. Zie het als een aanwijzing, geen oordeel.',
        'columns' => [
            'title' => 'Video',
            'posted_at' => 'Geplaatst',
            'views' => 'Weergaven',
            'views_24h' => 'Weergaven (24u)',
            'er_views' => 'ER (weergaven)',
            'share_rate' => 'Deelpercentage',
            'rank_24h' => 'Positie (24u)',
            'trajectory' => 'Verloop',
            'class' => 'Klasse',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Actief',
            'fading' => 'Afnemend',
            'dead' => 'Gestopt',
        ],
        'class' => [
            'spike' => 'Piek',
            'word_of_mouth' => 'Mond-tot-mondreclame',
            'mixed' => 'Gemengd',
        ],
    ],

    'alerts' => [
        'title' => 'Recente meldingen',
        'empty' => 'Nog geen meldingen verzonden.',
        'types' => [
            'breakout' => 'Doorbraak',
            'flop' => 'Flop',
            'resurgence' => 'Heropleving',
        ],
    ],

    'video' => [
        'posted_label' => 'Geplaatst',
        'experimental_badge' => 'EXPERIMENTEEL',

        'trajectory' => [
            'title' => 'Weergaven per uur',
            'no_data' => 'Nog niet genoeg momentopnamen.',
            'views_per_hour' => 'Weergaven/uur',
            'peak_marker' => 'Piek',
            'per_hour' => 'weerg./u',
        ],

        'milestones' => [
            'title' => 'Mijlpalen',
            'no_data' => 'Nog geen momentopnamen op basis van leeftijd.',
            'measured_at_label' => 'Gemeten op',
        ],

        'expectation' => [
            'title' => 'Verwachte weergaven op dag 7',
            'band_label' => 'Verwachte bandbreedte',
            'actual_label' => 'Werkelijk tot nu toe',
            'training_n_label' => 'Getraind op video\'s',
            'explanation' => 'De bandbreedte komt voort uit hoe de eigen volgroeide video\'s van dit account groeiden tussen dag 1 en dag 7. Het is een marge, geen belofte.',
            'insufficient_data' => 'Nog niet genoeg volgroeide video\'s om een bandbreedte te voorspellen.',
        ],

        'class' => [
            'title' => 'Verkeersvorm',
            'peak_share_label' => 'Aandeel piekdag',
            'explanation' => 'Piek betekent dat de meeste weergaven op één dag binnenkwamen; mond-tot-mondreclame betekent dat weergaven na verloop van tijd bleven binnenkomen.',
            'insufficient_data' => 'De video dateert van vóór het begin van de dataverzameling, waardoor de beginfase nooit is waargenomen. Uit een onvolledige reeks kan geen eerlijke vorm worden afgeleid.',
        ],

        'mix' => [
            'title' => 'Betrokkenheidsmix',
            'like_rate' => 'Likes per weergave',
            'comment_rate' => 'Reacties per weergave',
            'share_rate' => 'Keer gedeeld per weergave',
        ],

        'attribution' => [
            'title' => 'Volgerstoewijzing',
            'followers_earned' => 'Verworven volgers',
            'followers_per_1k_views' => 'Volgers per 1000 weergaven',
            'avg_confidence' => 'Gemiddeld betrouwbaarheidsniveau',
            'disclaimer' => 'SCHATTING: gaat uit van een gelijkmatige omzetting van weergave naar volgen, waardoor breed bereikte video\'s systematisch worden overschat en nichegerichte video\'s worden onderschat. Nooit een go/no-go-beoordeling voor content. Negatieve schommelingen blijven behouden (ontvolgen is reëel).',
        ],

        'tiktok_studio_link' => 'Retentie en verkeersbronnen zijn te vinden in TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Beste tijden om te posten',
        'description' => 'Gerangschikte cellen komen uit je eigen postgeschiedenis en worden 24 uur na elke post gescoord. Een cel krijgt pas een rangorde zodra er minstens 3 gescoorde posts zijn; daaronder wordt in plaats daarvan het aantal posts getoond.',
        'columns' => [
            'weekday' => 'Doordeweeks',
            'weekend' => 'Weekend',
        ],
        'dayparts' => [
            'morning' => 'Ochtend',
            'afternoon' => 'Middag',
            'evening' => 'Avond',
            'night' => 'Nacht',
        ],
        'cell' => [
            'median_views_24h' => 'Mediane weergaven (24u)',
            'n' => 'n=:n',
            'best' => 'Beste',
            'empty' => 'Nog geen posts',
        ],
    ],

    'hint' => [
        'best_slot' => 'Beste tijdslot tot nu toe: :daypart :daytype (n=:n)',
        'daypart' => [
            'morning' => 'ochtend',
            'afternoon' => 'middag',
            'evening' => 'avond',
            'night' => 'nacht',
        ],
        'daytype' => [
            'weekday' => 'doordeweeks',
            'weekend' => 'weekend',
        ],
    ],
];
