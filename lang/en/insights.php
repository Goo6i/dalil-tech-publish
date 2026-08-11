<?php

return [
    'title' => 'Insights',
    'description' => 'Video and account performance from the analytics collector.',
    'no_accounts' => 'No connected accounts with insights data.',

    'tabs' => [
        'nav_label' => 'Analytics view',
        'live' => 'Latest',
        'insights' => 'Insights',
        'best_times' => 'Best Times',
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
        'types' => [
            'breakout' => 'Breakout',
            'flop' => 'Flop',
            'resurgence' => 'Resurgence',
        ],
    ],

    'video' => [
        'posted_label' => 'Posted',
        'experimental_badge' => 'EXPERIMENTAL',

        'trajectory' => [
            'title' => 'Views per hour',
            'no_data' => 'Not enough snapshots yet.',
            'views_per_hour' => 'Views/hour',
            'peak_marker' => 'Peak',
            'per_hour' => 'views/h',
        ],

        'milestones' => [
            'title' => 'Milestones',
            'no_data' => 'No age-anchored snapshots yet.',
            'measured_at_label' => 'Measured at',
        ],

        'expectation' => [
            'title' => 'Expected day-7 views',
            'band_label' => 'Expected range',
            'actual_label' => 'Actual so far',
            'training_n_label' => 'Trained on videos',
            'explanation' => 'The range comes from how this account\'s own matured videos grew between day 1 and day 7. It is a band, not a promise.',
            'insufficient_data' => 'Not enough matured videos yet to predict a range.',
        ],

        'class' => [
            'title' => 'Traffic shape',
            'peak_share_label' => 'Peak-day share',
            'explanation' => 'Spike means most views landed in a single day; word of mouth means views kept coming over time.',
            'insufficient_data' => 'The video predates collection, so its early life was never observed. No honest shape can be read from a partial series.',
        ],

        'mix' => [
            'title' => 'Engagement mix',
            'like_rate' => 'Likes per view',
            'comment_rate' => 'Comments per view',
            'share_rate' => 'Shares per view',
        ],

        'attribution' => [
            'title' => 'Follower attribution',
            'followers_earned' => 'Followers earned',
            'followers_per_1k_views' => 'Followers per 1k views',
            'avg_confidence' => 'Average confidence',
            'disclaimer' => 'PROXY: assumes uniform view→follow conversion, which systematically over-credits broad videos and under-credits niche converters. Never a pass/fail grade for content. Negative deltas kept (unfollows are real).',
        ],

        'tiktok_studio_link' => 'Retention and traffic sources live in TikTok Studio',
    ],

    'best_times' => [
        'title' => 'Best times to post',
        'description' => 'Ranked cells come from your own posting history, scored 24 hours after each post. A cell only ranks once it has at least 3 scored posts; below that it shows how many posts it has instead of a rank.',
        'columns' => [
            'weekday' => 'Weekday',
            'weekend' => 'Weekend',
        ],
        'dayparts' => [
            'morning' => 'Morning',
            'afternoon' => 'Afternoon',
            'evening' => 'Evening',
            'night' => 'Night',
        ],
        'cell' => [
            'median_views_24h' => 'Median views (24h)',
            'n' => 'n=:n',
            'best' => 'Best',
            'empty' => 'No posts yet',
        ],
    ],

    'hint' => [
        'best_slot' => 'Best slot so far: :daypart :daytype (n=:n)',
        'daypart' => [
            'morning' => 'morning',
            'afternoon' => 'afternoon',
            'evening' => 'evening',
            'night' => 'night',
        ],
        'daytype' => [
            'weekday' => 'weekday',
            'weekend' => 'weekend',
        ],
    ],
];
