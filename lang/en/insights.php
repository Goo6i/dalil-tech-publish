<?php

return [
    'title' => 'Insights',
    'description' => 'Video and account performance from the analytics collector.',
    'no_accounts' => 'No connected accounts with insights data.',

    'tabs' => [
        'nav_label' => 'Analytics view',
        'live' => 'Live',
        'insights' => 'Insights',
    ],

    'filters' => [
        'all_accounts' => 'All accounts',
    ],

    'data_quality' => [
        'title' => 'Data quality warning',
    ],

    'stats' => [
        'followers' => 'Followers',
        'net_7d' => 'Net followers (7d)',
        'received_views_7d' => 'Received views (7d)',
        'received_views_7d_scope' => 'Across all connected accounts',
    ],

    'chart' => [
        'title' => 'Follower growth',
        'no_data' => 'Not enough data yet.',
        'followers' => 'Followers',
        'posts_that_day' => 'Posts published',
    ],

    'scorecard' => [
        'title' => 'Video scorecard',
        'no_data' => 'No videos tracked yet.',
        'class_experimental_tooltip' => 'EXPERIMENTAL: this classifier is not yet validated against outcomes. Read it as a hint, not a verdict.',
        'columns' => [
            'title' => 'Video',
            'posted_at' => 'Posted',
            'views' => 'Views',
            'views_24h' => 'Views (24h)',
            'er_views' => 'ER (views)',
            'share_rate' => 'Share rate',
            'rank_24h' => 'Rank (24h)',
            'trajectory' => 'Trajectory',
            'class' => 'Class',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Active',
            'fading' => 'Fading',
            'dead' => 'Dead',
        ],
        'class' => [
            'spike' => 'Spike',
            'word_of_mouth' => 'Word of mouth',
            'mixed' => 'Mixed',
        ],
    ],

    'alerts' => [
        'title' => 'Recent alerts',
        'empty' => 'No alerts sent yet.',
    ],
];
