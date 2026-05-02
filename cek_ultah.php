<?php

// --- PENGATURAN ---
$token = getenv('BOT_TOKEN');
$groupID = getenv('GROUP_ID');

// --- DAFTAR ULANG TAHUN ---
// Format: 'Bulan-Tanggal' => 'Nama'
$daftarUlangTahun = [
    '12-23' => 'Mas Ahmad Sulkhan Yusuf Mubarok',
    // '07-20' => 'Aditya Pratama',
    '01-06' => 'Mas Masroni Fiqi Muntholip',
    '02-12' => 'Mas Sukron Al Amin',
    '01-28' => 'Mas Rendi Irawan',
    '02-15' => 'Mas Totok Agung Prasetyo',
    '07-04' => 'Mas Teddy Adi Prasongko',
    '09-11' => 'Mas Ari Septian Wijanarko',
    '04-22' => 'Mba Berliyandy Taurusti R',
    '12-08' => 'Mas Amirul Huda',
    '10-10' => 'Mas Gurnito Pamungkas',
    '05-01' => 'Bu Elok Aris Wahyu Kundari',
    '11-21' => 'Bu Anggia Dini Marsaroha Boru Panggabean Simorangkir'
];
// -------------------------

// --- KOLEKSI UCAPAN VARIATIF ---
$ucapanUmum = [
    "🎉 **Selamat Ulang Tahun!** 🎉\n\nHari ini adalah hari yang spesial untuk:\n\n{NAMA}\n\nSemoga panjang umur, sehat selalu, dan seperti biasa hehehehe 🥳",
    
    "🎂 **Ups, tua lagi!** 🎂\n\nSelamat ulang tahun untuk:\n\n{NAMA}\n\nMakin tua makin bijak (semoga)... Sehat-sehat aja ya! 😄",
    
    "🎈 **HAPPY BIRTHDAY** 🎈\n\n{NAMA} naik level lagi! 🎮\n\nSemoga umur bertambah, derajat terus naik, dan kalori turun (yang ini mungkin sulit ya) 😅🥳",
    
    "🥳 **Selamat! Anda berhasil survive setahun lagi!** 🥳\n\nUntuk:\n\n{NAMA}\n\nTanpa kena penyakit, tidak kena musibah, hanya ditampar takdir berkali-kali 😂 Semoga tahun depan lebih baik! 🎉",
    
    "🎉 **HAPPY BIRTHDAY!** 🎉\n\n{NAMA}\n\nAlhamdulillah masih hidup! Semoga tahun ini lebih produktif dari tahun lalu, atau setidaknya jangan lebih ngenes 😄🙏",
    
    "🎂 **Selamat Ulang Tahun!** 🎂\n\n{NAMA}\n\nSatu tahun lagi berkurang dari umur, hitung-hitungan biar cepat ke surga 😄 Just kidding! Semoga panjang umur dan bahagia! 🎊",
];

$ucapanBuAnggia = "Kami mengucapkan selamat ulang tahun kepada:\n\n**Bu Anggia Dini Marsaroha Boru Panggabean Simorangkir**\n\nSemoga kesehatan dan kebahagiaan selalu menyertai, serta semoga segala yang direncanakan dapat berjalan dengan lancar. 🎂";

// -------------------------


// --- LOGIKA SCRIPT ---

// 1. Dapatkan tanggal dan bulan hari ini
date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu
$hariIni = date('m-d'); // Format: 09-21 (bulan-tanggal)

// 2. Siapkan wadah untuk nama-nama yang berulang tahun
$yangUlangTahun = [];
$yangUlangTahunBuAnggia = false;

foreach ($daftarUlangTahun as $tanggal => $nama) {
    if ($tanggal == $hariIni) {
        if ($nama === 'Bu Anggia Dini Marsaroha Boru Panggabean Simorangkir') {
            $yangUlangTahunBuAnggia = true;
        } else {
            $yangUlangTahun[] = $nama;
        }
    }
}

// 3. Jika ada yang berulang tahun, kirim pesan
if (count($yangUlangTahun) > 0 || $yangUlangTahunBuAnggia) {

    // Jika Bu Anggia berulang tahun, kirim ucapan khusus
    if ($yangUlangTahunBuAnggia) {
        kirimPesanTelegram($token, $groupID, "🎉 **Selamat Ulang Tahun!** 🎉\n\n" . $ucapanBuAnggia);
    }

    // Jika ada orang lain yang berulang tahun, kirim ucapan variatif
    if (count($yangUlangTahun) > 0) {
        // Pilih ucapan secara acak
        $ucapanTerpilih = $ucapanUmum[array_rand($ucapanUmum)];
        
        // Ganti placeholder {NAMA} dengan nama-nama yang berulang tahun
        $namaList = "";
        foreach ($yangUlangTahun as $nama) {
            $namaList .= "🎂  **" . $nama . "**\n";
        }
        
        $pesan = str_replace("{NAMA}", trim($namaList), $ucapanTerpilih);
        
        kirimPesanTelegram($token, $groupID, $pesan);
    }

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
