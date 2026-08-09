<?php

return [
    'title' => 'Analiza',
    'description' => 'Wyniki wideo i konta z modułu zbierania danych analitycznych.',
    'no_accounts' => 'Brak połączonych kont z danymi analizy.',

    'tabs' => [
        'nav_label' => 'Widok analityki',
        'live' => 'Na żywo',
        'insights' => 'Analiza',
        'best_times' => 'Najlepsze godziny',
    ],

    'filters' => [
        'all_accounts' => 'Wszystkie konta',
    ],

    'data_quality' => [
        'title' => 'Ostrzeżenie o jakości danych',
    ],

    'stats' => [
        'followers' => 'Obserwujący',
        'net_7d' => 'Obserwujący netto (7 dni)',
        'received_views_7d' => 'Uzyskane wyświetlenia (7 dni)',
        'received_views_7d_scope' => 'We wszystkich połączonych kontach',
    ],

    'chart' => [
        'title' => 'Wzrost liczby obserwujących',
        'no_data' => 'Wciąż za mało danych.',
        'followers' => 'Obserwujący',
        'posts_that_day' => 'Opublikowane posty',
    ],

    'scorecard' => [
        'title' => 'Karta wyników wideo',
        'no_data' => 'Żadne wideo nie jest jeszcze śledzone.',
        'class_experimental_tooltip' => 'EKSPERYMENTALNE: ten klasyfikator nie został jeszcze zweryfikowany na podstawie rzeczywistych wyników. Traktuj to jako wskazówkę, a nie wyrok.',
        'columns' => [
            'title' => 'Wideo',
            'posted_at' => 'Opublikowano',
            'views' => 'Wyświetlenia',
            'views_24h' => 'Wyświetlenia (24h)',
            'er_views' => 'ER (wyświetlenia)',
            'share_rate' => 'Wskaźnik udostępnień',
            'rank_24h' => 'Pozycja (24h)',
            'trajectory' => 'Trajektoria',
            'class' => 'Klasa',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Aktywne',
            'fading' => 'Wygasające',
            'dead' => 'Wygasłe',
        ],
        'class' => [
            'spike' => 'Skok',
            'word_of_mouth' => 'Poczta pantoflowa',
            'mixed' => 'Mieszane',
        ],
    ],

    'alerts' => [
        'title' => 'Ostatnie alerty',
        'empty' => 'Nie wysłano jeszcze żadnych alertów.',
        'types' => [
            'breakout' => 'Przełom',
            'flop' => 'Klapa',
            'resurgence' => 'Odrodzenie',
        ],
    ],

    'video' => [
        'posted_label' => 'Opublikowano',
        'experimental_badge' => 'EKSPERYMENTALNE',

        'trajectory' => [
            'title' => 'Wyświetlenia na godzinę',
            'no_data' => 'Wciąż za mało migawek.',
            'views_per_hour' => 'Wyświetlenia/godz.',
            'peak_marker' => 'Szczyt',
            'per_hour' => 'wyśw./godz.',
        ],

        'milestones' => [
            'title' => 'Kamienie milowe',
            'no_data' => 'Brak jeszcze migawek powiązanych z wiekiem filmu.',
            'measured_at_label' => 'Zmierzono o',
        ],

        'expectation' => [
            'title' => 'Oczekiwane wyświetlenia w 7. dniu',
            'band_label' => 'Oczekiwany zakres',
            'actual_label' => 'Rzeczywiste dotychczas',
            'training_n_label' => 'Wytrenowano na filmach',
            'explanation' => 'Zakres wynika z tego, jak własne dojrzałe filmy tego konta rosły między 1. a 7. dniem. To przedział, a nie obietnica.',
            'insufficient_data' => 'Wciąż za mało dojrzałych filmów, aby przewidzieć zakres.',
        ],

        'class' => [
            'title' => 'Kształt ruchu',
            'peak_share_label' => 'Udział dnia szczytowego',
            'explanation' => 'Skok oznacza, że większość wyświetleń trafiła w jednym dniu; poczta pantoflowa oznacza, że wyświetlenia napływały stopniowo w czasie.',
            'insufficient_data' => 'Film powstał przed rozpoczęciem zbierania danych, więc jego wczesny etap nigdy nie został zaobserwowany. Z niepełnej serii nie da się odczytać rzetelnego kształtu.',
        ],

        'mix' => [
            'title' => 'Mieszanka zaangażowania',
            'like_rate' => 'Polubienia na wyświetlenie',
            'comment_rate' => 'Komentarze na wyświetlenie',
            'share_rate' => 'Udostępnienia na wyświetlenie',
        ],

        'attribution' => [
            'title' => 'Atrybucja obserwujących',
            'followers_earned' => 'Zdobyci obserwujący',
            'followers_per_1k_views' => 'Obserwujący na 1000 wyświetleń',
            'avg_confidence' => 'Średnia pewność',
            'disclaimer' => 'SZACUNEK: zakłada jednolitą konwersję z wyświetlenia na obserwację, co systematycznie zawyża wynik filmów o szerokim zasięgu i zaniża wynik konwersji niszowych. Nigdy nie jest oceną zaliczenia lub odrzucenia treści. Ujemne zmiany są zachowywane (rezygnacje z obserwowania są realne).',
        ],

        'tiktok_studio_link' => 'Retencję i źródła ruchu znajdziesz w TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Najlepsze godziny publikacji',
        'description' => 'Ocenione komórki pochodzą z Twojej własnej historii publikacji i są oceniane 24 godziny po każdym poście. Komórka otrzymuje pozycję dopiero po zebraniu co najmniej 3 ocenionych postów; poniżej tego progu pokazuje liczbę postów zamiast pozycji.',
        'columns' => [
            'weekday' => 'Dzień roboczy',
            'weekend' => 'Weekend',
        ],
        'dayparts' => [
            'morning' => 'Rano',
            'afternoon' => 'Popołudnie',
            'evening' => 'Wieczór',
            'night' => 'Noc',
        ],
        'cell' => [
            'median_views_24h' => 'Mediana wyświetleń (24h)',
            'n' => 'n=:n',
            'best' => 'Najlepszy',
            'empty' => 'Brak postów',
        ],
    ],

    'hint' => [
        'best_slot' => 'Najlepszy przedział jak dotąd: :daypart, :daytype (n=:n)',
        'daypart' => [
            'morning' => 'rano',
            'afternoon' => 'popołudnie',
            'evening' => 'wieczór',
            'night' => 'noc',
        ],
        'daytype' => [
            'weekday' => 'dzień roboczy',
            'weekend' => 'weekend',
        ],
    ],
];
