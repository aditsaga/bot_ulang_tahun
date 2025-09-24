<?php

// --- PENGATURAN ---
$token = getenv('BOT_TOKEN');
$groupID = getenv('GROUP_ID');

// --- DAFTAR ULANG TAHUN ---
// Format: 'Bulan-Tanggal' => 'Nama'
$daftarUlangTahun = [
    '12-23' => 'Ahmad Sulkhan Yusuf Mubarok',
    // '07-20' => 'Aditya Pratama',
    '01-06' => 'Masroni Fiqi Muntholip',
    '02-12' => 'Sukron Al Amin',
    '01-28' => 'Rendi Irawan',
    '02-15' => 'Totok Agung Prasetyo',
    '07-04' => 'Teddy Adi Prasongko',
    '09-11' => 'Ari Septian Wijanarko',
    '04-22' => 'Berliyandy Taurusti R',
    '12-08' => 'Amirul Huda',
    '10-10' => 'Gurnito Pamungkas',
    '05-01' => 'Elok Aris Wahyu Kundari',
    '11-21' => 'Anggia Dini Marsaroha Boru Panggabean Simorangkir'
];
// -------------------------


// --- LOGIKA SCRIPT ---

// 1. Dapatkan tanggal dan bulan hari ini
date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu
$hariIni = date('m-d'); // Format: 09-21 (bulan-tanggal)

// 2. Siapkan wadah untuk nama-nama yang berulang tahun
$yangUlangTahun = [];
foreach ($daftarUlangTahun as $tanggal => $nama) {
    if ($tanggal == $hariIni) {
        $yangUlangTahun[] = $nama;
    }
}

// 3. Jika ada yang berulang tahun, kirim pesan
if (count($yangUlangTahun) > 0) {

    // Buat pesan ucapan
    $pesan = "🎉 **Selamat Ulang Tahun!** 🎉\n\n";
    $pesan .= "Hari ini adalah hari yang spesial untuk:\n\n";

    foreach ($yangUlangTahun as $nama) {
        $pesan .= "🎂  **" . $nama . "**\n";
    }

    $pesan .= "\nSemoga panjang umur, sehat selalu, dan seperti biasa info pergerakan  🥳";

    // Kirim pesan ke grup
    kirimPesanTelegram($token, $groupID, $pesan);
} else {
    // Jika tidak ada yang ulang tahun, bisa di-log atau didiamkan saja
    echo "Tidak ada ulang tahun hari ini";
}


// --- FUNGSI UNTUK MENGIRIM PESAN ---
function kirimPesanTelegram($token, $chatID, $pesan) {
    // Gunakan parse_mode=Markdown agar format tebal (**), miring (__), dll bisa digunakan
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID . "&text=" . urlencode($pesan) . "&parse_mode=Markdown";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    // Untuk debugging, tampilkan hasil
    echo "Pesan terkirim! \n";
    echo $result;
}

?>
