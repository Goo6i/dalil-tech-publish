<?php

return [
    'title' => 'İçgörüler',
    'description' => 'Analitik toplayıcıdan video ve hesap performansı.',
    'no_accounts' => 'İçgörü verisi olan bağlı hesap yok.',

    'tabs' => [
        'nav_label' => 'Analitik görünümü',
        'live' => 'Canlı',
        'insights' => 'İçgörüler',
        'best_times' => 'En İyi Zamanlar',
    ],

    'filters' => [
        'all_accounts' => 'Tüm hesaplar',
    ],

    'data_quality' => [
        'title' => 'Veri kalitesi uyarısı',
    ],

    'stats' => [
        'followers' => 'Takipçiler',
        'net_7d' => 'Net takipçi (7g)',
        'received_views_7d' => 'Alınan görüntülemeler (7g)',
        'received_views_7d_scope' => 'Tüm bağlı hesaplar genelinde',
    ],

    'chart' => [
        'title' => 'Takipçi artışı',
        'no_data' => 'Henüz yeterli veri yok.',
        'followers' => 'Takipçiler',
        'posts_that_day' => 'Yayınlanan gönderiler',
    ],

    'scorecard' => [
        'title' => 'Video karnesi',
        'no_data' => 'Henüz izlenen video yok.',
        'class_experimental_tooltip' => 'DENEYSEL: bu sınıflandırıcı henüz gerçek sonuçlara karşı doğrulanmadı. Bunu kesin bir hüküm değil, bir ipucu olarak değerlendirin.',
        'columns' => [
            'title' => 'Video',
            'posted_at' => 'Yayınlandı',
            'views' => 'Görüntülemeler',
            'views_24h' => 'Görüntülemeler (24s)',
            'er_views' => 'ER (görüntülemeler)',
            'share_rate' => 'Paylaşım oranı',
            'rank_24h' => 'Sıralama (24s)',
            'trajectory' => 'Seyir',
            'class' => 'Sınıf',
        ],
    ],

    'chips' => [
        'unknown' => 'N/A',
        'trajectory' => [
            'active' => 'Aktif',
            'fading' => 'Zayıflıyor',
            'dead' => 'Durdu',
        ],
        'class' => [
            'spike' => 'Ani yükseliş',
            'word_of_mouth' => 'Ağızdan ağıza',
            'mixed' => 'Karışık',
        ],
    ],

    'alerts' => [
        'title' => 'Son uyarılar',
        'empty' => 'Henüz gönderilmiş uyarı yok.',
        'types' => [
            'breakout' => 'Patlama',
            'flop' => 'Başarısızlık',
            'resurgence' => 'Yeniden yükseliş',
        ],
    ],

    'video' => [
        'posted_label' => 'Yayınlandı',
        'experimental_badge' => 'DENEYSEL',

        'trajectory' => [
            'title' => 'Saatlik görüntülemeler',
            'no_data' => 'Henüz yeterli anlık görüntü yok.',
            'views_per_hour' => 'Görüntüleme/saat',
            'peak_marker' => 'Zirve',
            'per_hour' => 'görüntüleme/s',
        ],

        'milestones' => [
            'title' => 'Kilometre taşları',
            'no_data' => 'Henüz yaşa dayalı anlık görüntü yok.',
            'measured_at_label' => 'Ölçüldüğü tarih',
        ],

        'expectation' => [
            'title' => '7. gün beklenen görüntülemeler',
            'band_label' => 'Beklenen aralık',
            'actual_label' => 'Şu ana kadar gerçekleşen',
            'training_n_label' => 'Şu kadar videoyla eğitildi',
            'explanation' => 'Bu aralık, bu hesabın kendi olgunlaşmış videolarının 1. gün ile 7. gün arasında nasıl büyüdüğünden gelir. Bir söz değil, bir bant aralığıdır.',
            'insufficient_data' => 'Bir aralık tahmin etmek için henüz yeterli olgunlaşmış video yok.',
        ],

        'class' => [
            'title' => 'Trafik şekli',
            'peak_share_label' => 'Zirve günü payı',
            'explanation' => 'Ani yükseliş, görüntülemelerin çoğunun tek bir günde geldiği anlamına gelir; ağızdan ağıza ise görüntülemelerin zaman içinde gelmeye devam ettiği anlamına gelir.',
            'insufficient_data' => 'Video, veri toplama başlamadan önce yayınlandığından erken dönemi hiç gözlemlenmedi. Eksik bir seriden güvenilir bir şekil okunamaz.',
        ],

        'mix' => [
            'title' => 'Etkileşim karması',
            'like_rate' => 'Görüntüleme başına beğeni',
            'comment_rate' => 'Görüntüleme başına yorum',
            'share_rate' => 'Görüntüleme başına paylaşım',
        ],

        'attribution' => [
            'title' => 'Takipçi atfı',
            'followers_earned' => 'Kazanılan takipçiler',
            'followers_per_1k_views' => '1000 görüntüleme başına takipçi',
            'avg_confidence' => 'Ortalama güven',
            'disclaimer' => 'TAHMİNİ: görüntülemeden takibe tekdüze bir dönüşüm olduğunu varsayar, bu da geniş kitleye ulaşan videoları sistematik olarak fazla, niş dönüştürücüleri ise az değerlendirir. İçerik için asla geçti/kaldı notu değildir. Negatif değişimler korunur (takipten çıkmalar gerçektir).',
        ],

        'tiktok_studio_link' => 'Elde tutma ve trafik kaynakları TikTok Studio\'da bulunur',
    ],

    'best_times' => [
        'title' => 'Paylaşım için en iyi zamanlar',
        'description' => 'Sıralanan hücreler kendi paylaşım geçmişinizden gelir ve her gönderiden 24 saat sonra puanlanır. Bir hücre yalnızca en az 3 puanlanmış gönderiye sahip olduğunda sıralanır; bunun altında ise sıra yerine kaç gönderisi olduğunu gösterir.',
        'columns' => [
            'weekday' => 'Hafta içi',
            'weekend' => 'Hafta sonu',
        ],
        'dayparts' => [
            'morning' => 'Sabah',
            'afternoon' => 'Öğleden sonra',
            'evening' => 'Akşam',
            'night' => 'Gece',
        ],
        'cell' => [
            'median_views_24h' => 'Medyan görüntüleme (24s)',
            'n' => 'n=:n',
            'best' => 'En iyi',
            'empty' => 'Henüz gönderi yok',
        ],
    ],

    'hint' => [
        'best_slot' => 'Şimdiye kadarki en iyi aralık: :daypart, :daytype (n=:n)',
        'daypart' => [
            'morning' => 'sabah',
            'afternoon' => 'öğleden sonra',
            'evening' => 'akşam',
            'night' => 'gece',
        ],
        'daytype' => [
            'weekday' => 'hafta içi',
            'weekend' => 'hafta sonu',
        ],
    ],
];
