<?php

return [
    'title' => 'Approfondimenti',
    'description' => 'Performance di video e account dal collettore di statistiche.',
    'no_accounts' => 'Nessun account collegato con dati di approfondimento.',

    'tabs' => [
        'nav_label' => 'Vista statistiche',
        'live' => 'Live',
        'insights' => 'Approfondimenti',
        'best_times' => 'Orari migliori',
    ],

    'filters' => [
        'all_accounts' => 'Tutti gli account',
    ],

    'data_quality' => [
        'title' => 'Avviso sulla qualità dei dati',
    ],

    'stats' => [
        'followers' => 'Follower',
        'net_7d' => 'Follower netti (7 gg)',
        'received_views_7d' => 'Visualizzazioni ricevute (7 gg)',
        'received_views_7d_scope' => 'Su tutti gli account collegati',
    ],

    'chart' => [
        'title' => 'Crescita dei follower',
        'no_data' => 'Non ci sono ancora abbastanza dati.',
        'followers' => 'Follower',
        'posts_that_day' => 'Post pubblicati',
    ],

    'scorecard' => [
        'title' => 'Scheda di valutazione video',
        'no_data' => 'Nessun video ancora monitorato.',
        'class_experimental_tooltip' => 'SPERIMENTALE: questo classificatore non è ancora stato validato rispetto ai risultati reali. Consideralo un indizio, non un verdetto.',
        'columns' => [
            'title' => 'Video',
            'posted_at' => 'Pubblicato',
            'views' => 'Visualizzazioni',
            'views_24h' => 'Visualizzazioni (24h)',
            'er_views' => 'ER (visualizzazioni)',
            'share_rate' => 'Tasso di condivisione',
            'rank_24h' => 'Posizione (24h)',
            'trajectory' => 'Traiettoria',
            'class' => 'Classe',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Attivo',
            'fading' => 'In calo',
            'dead' => 'Esaurito',
        ],
        'class' => [
            'spike' => 'Picco',
            'word_of_mouth' => 'Passaparola',
            'mixed' => 'Misto',
        ],
    ],

    'alerts' => [
        'title' => 'Avvisi recenti',
        'empty' => 'Nessun avviso inviato finora.',
        'types' => [
            'breakout' => 'Decollo',
            'flop' => 'Flop',
            'resurgence' => 'Ripresa',
        ],
    ],

    'video' => [
        'posted_label' => 'Pubblicato',
        'experimental_badge' => 'SPERIMENTALE',

        'trajectory' => [
            'title' => 'Visualizzazioni orarie',
            'no_data' => 'Non ci sono ancora abbastanza rilevazioni.',
            'views_per_hour' => 'Visualizzazioni/ora',
            'peak_marker' => 'Picco',
            'per_hour' => 'visualizzazioni/h',
        ],

        'milestones' => [
            'title' => 'Traguardi',
            'no_data' => 'Nessuna rilevazione ancorata all\'età ancora disponibile.',
            'measured_at_label' => 'Misurato il',
        ],

        'expectation' => [
            'title' => 'Visualizzazioni previste al giorno 7',
            'band_label' => 'Intervallo previsto',
            'actual_label' => 'Effettivo finora',
            'training_n_label' => 'Addestrato su video',
            'explanation' => 'L\'intervallo deriva da come sono cresciuti i video maturi di questo account tra il giorno 1 e il giorno 7. È una fascia, non una promessa.',
            'insufficient_data' => 'Non ci sono ancora abbastanza video maturi per prevedere un intervallo.',
        ],

        'class' => [
            'title' => 'Forma del traffico',
            'peak_share_label' => 'Quota del giorno di picco',
            'explanation' => 'Picco significa che la maggior parte delle visualizzazioni è arrivata in un solo giorno; passaparola significa che le visualizzazioni hanno continuato ad arrivare nel tempo.',
            'insufficient_data' => 'Il video è precedente all\'inizio della raccolta dati, quindi la sua fase iniziale non è mai stata osservata. Da una serie parziale non si può leggere una forma attendibile.',
        ],

        'mix' => [
            'title' => 'Mix di coinvolgimento',
            'like_rate' => 'Mi piace per visualizzazione',
            'comment_rate' => 'Commenti per visualizzazione',
            'share_rate' => 'Condivisioni per visualizzazione',
        ],

        'attribution' => [
            'title' => 'Attribuzione dei follower',
            'followers_earned' => 'Follower ottenuti',
            'followers_per_1k_views' => 'Follower ogni 1.000 visualizzazioni',
            'avg_confidence' => 'Affidabilità media',
            'disclaimer' => 'STIMA: presuppone una conversione uniforme da visualizzazione a follow, il che sovrastima sistematicamente i video ad ampio pubblico e sottostima i convertitori di nicchia. Non è mai un giudizio di promozione o bocciatura per i contenuti. Le variazioni negative vengono mantenute (i "non seguo più" sono reali).',
        ],

        'tiktok_studio_link' => 'Ritenzione e fonti di traffico sono disponibili in TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Orari migliori per pubblicare',
        'description' => 'Le celle classificate provengono dalla tua cronologia di pubblicazione e vengono valutate 24 ore dopo ogni post. Una cella ottiene una posizione solo quando ha almeno 3 post valutati; sotto questa soglia mostra invece quanti post ha.',
        'columns' => [
            'weekday' => 'Feriale',
            'weekend' => 'Weekend',
        ],
        'dayparts' => [
            'morning' => 'Mattina',
            'afternoon' => 'Pomeriggio',
            'evening' => 'Sera',
            'night' => 'Notte',
        ],
        'cell' => [
            'median_views_24h' => 'Visualizzazioni mediane (24h)',
            'n' => 'n=:n',
            'best' => 'Migliore',
            'empty' => 'Nessun post ancora',
        ],
    ],

    'hint' => [
        'best_slot' => 'Miglior fascia finora: :daypart :daytype (n=:n)',
        'daypart' => [
            'morning' => 'mattina',
            'afternoon' => 'pomeriggio',
            'evening' => 'sera',
            'night' => 'notte',
        ],
        'daytype' => [
            'weekday' => 'feriale',
            'weekend' => 'weekend',
        ],
    ],
];
