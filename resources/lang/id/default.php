<?php

return [
    'title' => 'Edit Profil',
    'profile_information' => 'Informasi Profil',
    'profile_information_description' => 'Perbarui informasi profil dan alamat email Anda.',
    'name' => 'Nama',
    'email' => 'Email',
    'avatar' => 'Foto',
    'password' => 'Kata Sandi',
    'update_password' => 'Perbarui Kata Sandi',
    'current_password' => 'Kata Sandi Saat Ini',
    'new_password' => 'Kata Sandi Baru',
    'confirm_password' => 'Konfirmasi Kata Sandi',
    'ensure_your_password' => 'Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk keamanan.',
    'delete_account' => 'Hapus Akun',
    'delete_account_description' => 'Hapus akun Anda secara permanen.',
    'yes_delete_it' => 'Ya, hapus!',
    'are_you_sure' => 'Apakah Anda yakin ingin menghapus akun Anda? Ini tidak dapat dibatalkan!',
    'incorrect_password' => 'Kata sandi yang Anda masukkan salah. Silakan coba lagi.',
    'user_load_error' => 'Objek pengguna yang terautentikasi harus merupakan model Eloquent agar halaman profil dapat memperbaruinya.',
    'delete_account_card_description' => 'Setelah akun Anda dihapus, semua data dan sumber daya Anda akan dihapus secara permanen. Sebelum menghapus akun, unduh data atau informasi yang ingin Anda simpan.',
    'saved_successfully' => 'Informasi profil Anda telah berhasil disimpan.',
    'custom_fields' => 'Bidang Kustom',
    'custom_fields_description' => 'Perbarui bidang kustom Anda.',
    'save' => 'Simpan',
    'token_name' => 'Nama Token',
    'token_abilities' => 'Kemampuan',
    'token_created_at' => 'Dibuat pada',
    'token_expires_at' => 'Berlaku hingga',
    'token_section_title' => 'Token API',
    'token_section_description' => 'Kelola token API yang memungkinkan layanan pihak ketiga mengakses aplikasi ini atas nama Anda.',
    'token_action_label' => 'Buat Token',
    'token_modal_heading' => 'Buat',
    'token_create_notification' => 'Token berhasil dibuat!',
    'token_helper_text' => 'Token Anda hanya ditampilkan sekali saat pembuatan. Jika Anda kehilangan token, Anda perlu menghapusnya dan membuat yang baru.',
    'token_modal_heading_2' => 'Salin Token Akses Pribadi',
    'token_empty_state_heading' => 'Buat token pertama Anda',
    'token_empty_state_description' => 'Buat token akses pribadi untuk memulai.',
    'browser_section_title' => 'Sesi Browser',
    'browser_section_description' => 'Kelola dan keluar dari sesi aktif Anda di browser dan perangkat lain.',
    'browser_sessions_content' => 'Jika perlu, Anda dapat keluar dari semua sesi browser Anda di semua perangkat. Beberapa sesi terbaru Anda terdaftar di bawah; daftar ini mungkin tidak lengkap. Jika Anda merasa akun Anda telah dikompromikan, perbarui kata sandi Anda.',
    'browser_sessions_device' => 'Perangkat ini',
    'browser_sessions_last_active' => 'Terakhir aktif',
    'browser_sessions_log_out' => 'Keluar dari Sesi Browser Lain',
    'browser_sessions_confirm_pass' => 'Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin keluar dari sesi browser lain di semua perangkat.',
    'browser_sessions_logout_success_notification' => 'Semua sesi browser lainnya telah berhasil keluar.',
    'two_factor' => [
        'heading'                      => 'Autentikasi Dua Faktor',
        'description'                  => 'Tambahkan keamanan tambahan ke akun Anda menggunakan autentikasi dua faktor',
        'password_incorrect'           => 'Atribut :attribute yang diberikan salah.',
        'notification_title_approved'  => 'Autentikasi Dua Faktor telah disetujui!',
        'code'                         => 'Kode',
        'recovery_code'                => 'Kode Pemulihan',
        'use_recovery_code'            => 'Gunakan kode pemulihan',
        'you_can_logout'               => 'atau Anda dapat keluar',
        'button_confirm'               => 'Konfirmasi',
        'code_incorrect'               => 'Kode tidak benar!',
        'enabled' => [
            'heading'                    => 'Aktifkan Autentikasi Dua Faktor',
            'description'                => 'Autentikasi dua faktor menambahkan lapisan keamanan ekstra ke akun Anda. Saat diaktifkan, Anda akan diminta token acak yang aman saat autentikasi.',
            'sub_description'            => 'Untuk mengaktifkan autentikasi dua faktor, silakan konfirmasi kata sandi Anda di bawah ini.',
            'label'                      => 'Aktifkan Autentikasi Dua Faktor',
            'modal_heading'              => 'Aktifkan Autentikasi Dua Faktor',
            'modal_description'          => 'Apakah Anda yakin ingin mengaktifkan autentikasi dua faktor?',
            'modal_submit_action_label'  => 'Aktifkan',
            'notification_title'         => 'Autentikasi Dua Faktor telah diaktifkan',
        ],
        'disabled' => [
            'heading'                       => 'Anda telah mengaktifkan autentikasi dua faktor.',
            'description'                   => 'Ketika autentikasi dua faktor diaktifkan, Anda akan diminta token acak yang aman selama autentikasi.',
            'sub_description'               => 'Untuk menyelesaikan pengaktifan autentikasi dua faktor, pindai kode QR berikut menggunakan aplikasi autentikator di ponsel Anda atau masukkan kunci pengaturan dan berikan kode OTP yang dihasilkan.',
            'setup_key'                     => 'Kunci Pengaturan:',
            'recovery_code_description'     => 'Simpan kode pemulihan ini di pengelola kata sandi yang aman. Mereka dapat digunakan untuk memulihkan akses ke akun Anda jika perangkat autentikasi dua faktor Anda hilang.',
            'label'                         => 'Nonaktifkan Autentikasi Dua Faktor',
            'modal_heading'                 => 'Nonaktifkan Autentikasi Dua Faktor',
            'modal_description'             => 'Apakah Anda yakin ingin menonaktifkan autentikasi dua faktor?',
            'modal_submit_action_label'     => 'Nonaktifkan',
            'notification_title'            => 'Autentikasi Dua Faktor telah dinonaktifkan',
        ],
    ],
];
