<?php

return [
    'title' => '洞察',
    'description' => '来自分析采集器的视频与账号表现数据。',
    'no_accounts' => '没有包含洞察数据的已连接账号。',

    'tabs' => [
        'nav_label' => '分析视图',
        'live' => '实时',
        'insights' => '洞察',
        'best_times' => '最佳时段',
    ],

    'filters' => [
        'all_accounts' => '所有账号',
    ],

    'data_quality' => [
        'title' => '数据质量警告',
    ],

    'stats' => [
        'followers' => '粉丝',
        'net_7d' => '净增粉丝（7天）',
        'received_views_7d' => '获得观看量（7天）',
        'received_views_7d_scope' => '所有已连接账号汇总',
    ],

    'chart' => [
        'title' => '粉丝增长',
        'no_data' => '数据还不够。',
        'followers' => '粉丝',
        'posts_that_day' => '当日发布的帖子',
    ],

    'scorecard' => [
        'title' => '视频成绩单',
        'no_data' => '暂无被追踪的视频。',
        'class_experimental_tooltip' => '实验性功能：该分类器尚未通过实际结果验证，请将其视为参考提示，而非最终结论。',
        'columns' => [
            'title' => '视频',
            'posted_at' => '发布时间',
            'views' => '观看量',
            'views_24h' => '观看量（24小时）',
            'er_views' => '互动率（观看量）',
            'share_rate' => '分享率',
            'rank_24h' => '排名（24小时）',
            'trajectory' => '走势',
            'class' => '分类',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => '活跃',
            'fading' => '减弱中',
            'dead' => '已停滞',
        ],
        'class' => [
            'spike' => '爆发式',
            'word_of_mouth' => '口碑传播',
            'mixed' => '混合型',
        ],
    ],

    'alerts' => [
        'title' => '最新提醒',
        'empty' => '暂无已发送的提醒。',
        'types' => [
            'breakout' => '爆款',
            'flop' => '失利',
            'resurgence' => '回升',
        ],
    ],

    'video' => [
        'posted_label' => '发布时间',
        'experimental_badge' => '实验性功能',

        'trajectory' => [
            'title' => '每小时观看量',
            'no_data' => '快照数据还不够。',
            'views_per_hour' => '观看量/小时',
            'peak_marker' => '峰值',
            'per_hour' => '次/小时',
        ],

        'milestones' => [
            'title' => '里程碑',
            'no_data' => '暂无按发布时长记录的快照。',
            'measured_at_label' => '测量时间',
        ],

        'expectation' => [
            'title' => '第7天预期观看量',
            'band_label' => '预期区间',
            'actual_label' => '目前实际值',
            'training_n_label' => '训练所用视频数',
            'explanation' => '该区间来自本账号自身成熟视频在第1天到第7天之间的增长情况，是一个估算范围，而非承诺。',
            'insufficient_data' => '成熟视频数量还不够，无法预测区间。',
        ],

        'class' => [
            'title' => '流量形态',
            'peak_share_label' => '峰值日占比',
            'explanation' => '爆发式表示大部分观看量集中在某一天出现；口碑传播表示观看量随时间持续流入。',
            'insufficient_data' => '该视频发布早于数据采集开始时间，其早期表现从未被记录，无法从不完整的数据序列中得出可靠的形态判断。',
        ],

        'mix' => [
            'title' => '互动构成',
            'like_rate' => '每次观看的点赞数',
            'comment_rate' => '每次观看的评论数',
            'share_rate' => '每次观看的分享数',
        ],

        'attribution' => [
            'title' => '粉丝归因',
            'followers_earned' => '获得的粉丝数',
            'followers_per_1k_views' => '每千次观看获得的粉丝数',
            'avg_confidence' => '平均置信度',
            'disclaimer' => '估算值：假设观看到关注的转化率是均匀的，这会系统性地高估受众广泛的视频，同时低估垂直细分内容的转化效果。绝不应作为内容合格与否的评判标准。负值变化会被保留（取消关注是真实发生的）。',
        ],

        'tiktok_studio_link' => '留存率与流量来源可在TikTok Studio中查看',
    ],

    'best_times' => [
        'title' => '最佳发布时段',
        'description' => '排名的单元格数据来自你自己的发布历史，在每次发布24小时后进行评分。只有当某个单元格至少有3条已评分的帖子时才会显示排名；未达到该数量前，会显示帖子数量而非排名。',
        'columns' => [
            'weekday' => '工作日',
            'weekend' => '周末',
        ],
        'dayparts' => [
            'morning' => '上午',
            'afternoon' => '下午',
            'evening' => '晚上',
            'night' => '深夜',
        ],
        'cell' => [
            'median_views_24h' => '观看量中位数（24小时）',
            'n' => 'n=:n',
            'best' => '最佳',
            'empty' => '暂无帖子',
        ],
    ],

    'hint' => [
        'best_slot' => '目前最佳时段：:daypart:daytype（n=:n）',
        'daypart' => [
            'morning' => '上午',
            'afternoon' => '下午',
            'evening' => '晚上',
            'night' => '深夜',
        ],
        'daytype' => [
            'weekday' => '工作日',
            'weekend' => '周末',
        ],
    ],
];
