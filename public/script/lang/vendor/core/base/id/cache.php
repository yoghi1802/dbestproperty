<?php

return [
    'cache_management' => 'Manajemen cache',
    'cache_commands' => 'Hapus perintah cache',
    'commands' => [
        'clear_cms_cache' => [
            'title' => 'Hapus semua cache CMS',
            'description' => 'Hapus cache CMS: cache database, blok statis... Jalankan perintah ini jika Anda tidak melihat perubahan setelah memperbarui data.',
            'success_msg' => 'Cache dibersihkan',
        ],
        'refresh_compiled_views' => [
            'title' => 'Segarkan tampilan yang dikompilasi',
            'description' => 'Hapus tampilan yang dikompilasi untuk memperbarui tampilan.',
            'success_msg' => 'Tampilan cache disegarkan',
        ],
        'clear_config_cache' => [
            'title' => 'Hapus cache konfigurasi',
            'description' => 'Anda mungkin perlu menyegarkan cache konfigurasi ketika Anda mengubah sesuatu di lingkungan produksi.',
            'success_msg' => 'Cache konfigurasi dibersihkan',
        ],
        'clear_route_cache' => [
            'title' => 'Hapus cache rute',
            'description' => 'Hapus perutean cache.',
            'success_msg' => 'Cache rute telah dibersihkan',
        ],
        'clear_log' => [
            'title' => 'Hapus log',
            'description' => 'Hapus file log sistem',
            'success_msg' => 'Log sistem telah dibersihkan',
        ],
    ],
];
