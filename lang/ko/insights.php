<?php

return [
    'title' => '인사이트',
    'description' => '분석 수집기에서 가져온 동영상 및 계정 성과입니다.',
    'no_accounts' => '인사이트 데이터가 있는 연결된 계정이 없습니다.',

    'tabs' => [
        'nav_label' => '분석 보기',
        'live' => '라이브',
        'insights' => '인사이트',
        'best_times' => '최적 시간대',
    ],

    'filters' => [
        'all_accounts' => '모든 계정',
    ],

    'data_quality' => [
        'title' => '데이터 품질 경고',
    ],

    'stats' => [
        'followers' => '팔로워',
        'net_7d' => '순증 팔로워 (7일)',
        'received_views_7d' => '획득 조회수 (7일)',
        'received_views_7d_scope' => '연결된 모든 계정 합산',
    ],

    'chart' => [
        'title' => '팔로워 증가',
        'no_data' => '아직 데이터가 충분하지 않습니다.',
        'followers' => '팔로워',
        'posts_that_day' => '게시된 게시물',
    ],

    'scorecard' => [
        'title' => '동영상 스코어카드',
        'no_data' => '아직 추적된 동영상이 없습니다.',
        'class_experimental_tooltip' => '실험적 기능: 이 분류기는 아직 실제 결과로 검증되지 않았습니다. 판정이 아닌 참고용 힌트로 받아들이세요.',
        'columns' => [
            'title' => '동영상',
            'posted_at' => '게시일',
            'views' => '조회수',
            'views_24h' => '조회수 (24시간)',
            'er_views' => 'ER (조회수)',
            'share_rate' => '공유율',
            'rank_24h' => '순위 (24시간)',
            'trajectory' => '추세',
            'class' => '분류',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => '활발함',
            'fading' => '둔화 중',
            'dead' => '정체',
        ],
        'class' => [
            'spike' => '급상승',
            'word_of_mouth' => '입소문',
            'mixed' => '혼합',
        ],
    ],

    'alerts' => [
        'title' => '최근 알림',
        'empty' => '아직 전송된 알림이 없습니다.',
        'types' => [
            'breakout' => '급부상',
            'flop' => '부진',
            'resurgence' => '재상승',
        ],
    ],

    'video' => [
        'posted_label' => '게시일',
        'experimental_badge' => '실험적 기능',

        'trajectory' => [
            'title' => '시간당 조회수',
            'no_data' => '아직 스냅샷이 충분하지 않습니다.',
            'views_per_hour' => '조회수/시간',
            'peak_marker' => '피크',
            'per_hour' => '조회/시간',
        ],

        'milestones' => [
            'title' => '마일스톤',
            'no_data' => '아직 경과 시간 기준 스냅샷이 없습니다.',
            'measured_at_label' => '측정 시각',
        ],

        'expectation' => [
            'title' => '7일 차 예상 조회수',
            'band_label' => '예상 범위',
            'actual_label' => '현재까지 실제값',
            'training_n_label' => '학습에 사용된 동영상 수',
            'explanation' => '이 범위는 이 계정의 성숙된 동영상들이 1일 차부터 7일 차까지 어떻게 성장했는지를 바탕으로 산출됩니다. 이는 확정된 약속이 아니라 하나의 범위입니다.',
            'insufficient_data' => '범위를 예측하기에 충분한 성숙된 동영상이 아직 없습니다.',
        ],

        'class' => [
            'title' => '트래픽 형태',
            'peak_share_label' => '피크일 비중',
            'explanation' => '급상승은 조회수 대부분이 하루에 몰린 것을 의미하고, 입소문은 시간이 지나며 조회수가 꾸준히 유입된 것을 의미합니다.',
            'insufficient_data' => '이 동영상은 데이터 수집 시작 이전에 게시되어 초반 추이가 관측되지 않았습니다. 불완전한 데이터로는 정확한 형태를 파악할 수 없습니다.',
        ],

        'mix' => [
            'title' => '참여 구성',
            'like_rate' => '조회당 좋아요 수',
            'comment_rate' => '조회당 댓글 수',
            'share_rate' => '조회당 공유 수',
        ],

        'attribution' => [
            'title' => '팔로워 기여도',
            'followers_earned' => '획득한 팔로워 수',
            'followers_per_1k_views' => '조회수 1,000회당 팔로워 수',
            'avg_confidence' => '평균 신뢰도',
            'disclaimer' => '추정값: 조회에서 팔로우로의 전환율이 균일하다고 가정하므로, 넓은 대상에게 도달하는 동영상은 과대평가되고 니치한 전환자는 과소평가되는 경향이 구조적으로 발생합니다. 콘텐츠의 합격/불합격 기준으로 사용해서는 안 됩니다. 음수 변동값도 그대로 유지됩니다(언팔로우는 실제 현상입니다).',
        ],

        'tiktok_studio_link' => '유지율과 유입 경로는 TikTok Studio에서 확인할 수 있습니다',
    ],

    'best_times' => [
        'title' => '게시하기 좋은 시간대',
        'description' => '순위가 매겨진 셀은 회원님의 게시 이력을 기반으로 하며, 각 게시물이 게시된 지 24시간 후에 점수가 매겨집니다. 셀은 점수가 매겨진 게시물이 3개 이상일 때만 순위가 표시되며, 그 이하일 경우 순위 대신 게시물 수가 표시됩니다.',
        'columns' => [
            'weekday' => '평일',
            'weekend' => '주말',
        ],
        'dayparts' => [
            'morning' => '아침',
            'afternoon' => '오후',
            'evening' => '저녁',
            'night' => '밤',
        ],
        'cell' => [
            'median_views_24h' => '조회수 중앙값 (24시간)',
            'n' => 'n=:n',
            'best' => '최적',
            'empty' => '아직 게시물 없음',
        ],
    ],

    'hint' => [
        'best_slot' => '지금까지 최적 시간대: :daypart :daytype (n=:n)',
        'daypart' => [
            'morning' => '아침',
            'afternoon' => '오후',
            'evening' => '저녁',
            'night' => '밤',
        ],
        'daytype' => [
            'weekday' => '평일',
            'weekend' => '주말',
        ],
    ],
];
