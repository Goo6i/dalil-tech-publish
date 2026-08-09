<?php

return [
    'title' => 'インサイト',
    'description' => 'アナリティクス収集ツールによる動画とアカウントのパフォーマンス。',
    'no_accounts' => 'インサイトデータのある連携済みアカウントがありません。',

    'tabs' => [
        'nav_label' => 'アナリティクス表示',
        'live' => 'ライブ',
        'insights' => 'インサイト',
        'best_times' => 'ベストタイム',
    ],

    'filters' => [
        'all_accounts' => 'すべてのアカウント',
    ],

    'data_quality' => [
        'title' => 'データ品質の警告',
    ],

    'stats' => [
        'followers' => 'フォロワー',
        'net_7d' => '純増フォロワー数（7日間）',
        'received_views_7d' => '獲得再生数（7日間）',
        'received_views_7d_scope' => '連携済みの全アカウント合計',
    ],

    'chart' => [
        'title' => 'フォロワー増加数',
        'no_data' => 'まだ十分なデータがありません。',
        'followers' => 'フォロワー',
        'posts_that_day' => '公開された投稿',
    ],

    'scorecard' => [
        'title' => '動画スコアカード',
        'no_data' => 'まだ追跡された動画がありません。',
        'class_experimental_tooltip' => '実験的機能: この分類器はまだ実際の結果で検証されていません。判定ではなく目安として参考にしてください。',
        'columns' => [
            'title' => '動画',
            'posted_at' => '投稿日',
            'views' => '再生数',
            'views_24h' => '再生数（24時間）',
            'er_views' => 'ER（再生数）',
            'share_rate' => 'シェア率',
            'rank_24h' => '順位（24時間）',
            'trajectory' => '推移',
            'class' => '分類',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => '好調',
            'fading' => '減速中',
            'dead' => '停止',
        ],
        'class' => [
            'spike' => '急上昇',
            'word_of_mouth' => '口コミ',
            'mixed' => '混合',
        ],
    ],

    'alerts' => [
        'title' => '最近のアラート',
        'empty' => 'まだアラートは送信されていません。',
        'types' => [
            'breakout' => 'ブレイク',
            'flop' => '不発',
            'resurgence' => '再燃',
        ],
    ],

    'video' => [
        'posted_label' => '投稿日',
        'experimental_badge' => '実験的機能',

        'trajectory' => [
            'title' => '時間あたりの再生数',
            'no_data' => 'まだ十分なスナップショットがありません。',
            'views_per_hour' => '再生数/時',
            'peak_marker' => 'ピーク',
            'per_hour' => '再生/時',
        ],

        'milestones' => [
            'title' => 'マイルストーン',
            'no_data' => 'まだ経過時間基準のスナップショットがありません。',
            'measured_at_label' => '計測日時',
        ],

        'expectation' => [
            'title' => '7日目の予測再生数',
            'band_label' => '予測範囲',
            'actual_label' => '現時点の実績',
            'training_n_label' => '学習に使用した動画数',
            'explanation' => 'この範囲は、このアカウント自身の成熟した動画が1日目から7日目までにどれだけ伸びたかから算出されています。あくまで幅であり、約束ではありません。',
            'insufficient_data' => '範囲を予測するのに十分な成熟した動画がまだありません。',
        ],

        'class' => [
            'title' => 'トラフィックの形状',
            'peak_share_label' => 'ピーク日の割合',
            'explanation' => '急上昇は再生数の大半が1日に集中したことを意味し、口コミは再生数が時間をかけて伸び続けたことを意味します。',
            'insufficient_data' => 'この動画は収集開始より前に投稿されたため、初期の推移が観測されていません。不完全なデータ系列から正確な形状を読み取ることはできません。',
        ],

        'mix' => [
            'title' => 'エンゲージメントの内訳',
            'like_rate' => '再生あたりのいいね数',
            'comment_rate' => '再生あたりのコメント数',
            'share_rate' => '再生あたりのシェア数',
        ],

        'attribution' => [
            'title' => 'フォロワー獲得の帰属',
            'followers_earned' => '獲得フォロワー数',
            'followers_per_1k_views' => '再生1,000回あたりのフォロワー数',
            'avg_confidence' => '平均信頼度',
            'disclaimer' => '推定値: 再生からフォローへの転換率が一定であると仮定しているため、幅広い層に届く動画を過大評価し、ニッチな層への訴求を過小評価する傾向が構造的にあります。コンテンツの合否判定に使うものではありません。マイナスの変動もそのまま反映しています（フォロー解除は実際に起きています）。',
        ],

        'tiktok_studio_link' => '視聴維持率と流入経路はTikTok Studioで確認できます',
    ],

    'best_times' => [
        'title' => '投稿に最適な時間帯',
        'description' => 'ランク付けされたセルは、あなた自身の投稿履歴から算出され、各投稿の24時間後にスコアリングされます。セルは少なくとも3件のスコア付き投稿がないとランク表示されず、それ未満の場合は投稿数のみが表示されます。',
        'columns' => [
            'weekday' => '平日',
            'weekend' => '週末',
        ],
        'dayparts' => [
            'morning' => '朝',
            'afternoon' => '昼',
            'evening' => '夕方',
            'night' => '夜',
        ],
        'cell' => [
            'median_views_24h' => '再生数の中央値（24時間）',
            'n' => 'n=:n',
            'best' => 'ベスト',
            'empty' => 'まだ投稿がありません',
        ],
    ],

    'hint' => [
        'best_slot' => 'これまでの最適時間帯: :daypart・:daytype（n=:n）',
        'daypart' => [
            'morning' => '朝',
            'afternoon' => '昼',
            'evening' => '夕方',
            'night' => '夜',
        ],
        'daytype' => [
            'weekday' => '平日',
            'weekend' => '週末',
        ],
    ],
];
