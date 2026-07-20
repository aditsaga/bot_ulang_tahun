<?php

// --- PENGATURAN ---
$token = getenv('BOT_TOKEN');
$groupID = getenv('GROUP_ID');
$mode = getenv('MODE') ?: 'NORMAL'; // DEFAULT: NORMAL, BISA JUGA: VOICE_ONLY

// --- DAFTAR ULANG TAHUN ---
// Format: 'Bulan-Tanggal' => 'Nama'
$daftarUlangTahun = [
    '12-23' => 'Mas Ahmad Sulkhan Yusuf Mubarok',
    '07-20' => 'Aditya Pratama',
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
    
    "🥳 **Selamat! Anda berhasil survive setahun lagi!** 🥳\n\nUntuk:\n\n{NAMA}\n\nTanpa kena penyakit, tidak kena musibah, hanya ditampar takdir berkali-kali 😂 Semoga tahun depan lebih baik!",
    
    "🎉 **HAPPY BIRTHDAY!** 🎉\n\n{NAMA}\n\nAlhamdulillah masih hidup! Semoga tahun ini lebih produktif dari tahun lalu, atau setidaknya jangan lebih ngenes 😄🙏",
    
    "🎂 **Selamat Ulang Tahun!** 🎂\n\n{NAMA}\n\nSatu tahun lagi berkurang dari umur, hitung-hitungan biar cepat ke surga 😄 Just kidding! Semoga panjang umur dan bahagia! 🎊",

    "🎊 **Another Year Older, Still Awesome!** 🎊\n\n{NAMA}\n\nTerima kasih sudah setia menemani kami! Semoga kesehatan dan kebahagiaan terus melingkupi. 🎁✨",

    "🌟 **Selamat Ulang Tahun!** 🌟\n\n{NAMA}\n\nSetiap tahun yang berlalu adalah karunia. Terima kasih telah menjadi bagian dari perjalanan kita bersama! Semoga dimudahkan segalanya. 🙏💫",

    "🎉 **IT'S YOUR DAY!** 🎉\n\n{NAMA}\n\nSemoga hari ini penuh dengan kegembiraan dan tawa! Terus bersemangat dan jangan lupa istirahat cukup ya! 😊🎈",
];

// Ucapan khusus untuk Mas Teddy (ramah, tidak kelewatan bercanda)
$ucapanTeddy = [
    "🎉 **Selamat Ulang Tahun, {NAMA}!** 🎉\n\nSemoga kesehatan dan kebahagiaan selalu bersama Anda. Terima kasih sudah menjadi bagian dari keluarga besar kami! 🙏",
    
    "🎂 **Happy Birthday, {NAMA}!** 🎂\n\nSemoga tahun ini membawa berkah dan kemudahan dalam setiap langkah. Semoga panjang umur dan sehat selalu! 🎊",
    
    "🌟 **Selamat Ulang Tahun!** 🌟\n\nUntuk {NAMA} yang luar biasa. Semoga hidup penuh dengan kegembiraan dan kesuksesan! 💫🎁",
];

$ucapanBuAnggia = "Kami mengucapkan selamat ulang tahun kepada:\n\n**Bu Anggia Dini Marsaroha Boru Panggabean Simorangkir**\n\nSemoga kesehatan dan kebahagiaan selalu menyertai, serta semoga segala yang direncanakan dapat berjalan dengan lancar. 🎂";

// -------------------------


// --- LOGIKA SCRIPT ---

// 1. Dapatkan tanggal dan bulan hari ini
date_default_timezone_set('Asia/Jakarta'); // Atur zona waktu
$hariIni = date('m-d'); // Format: 09-21 (bulan-tanggal)
$jamSekarang = (int)date('H'); // Ambil jam (0-23)

// 2. Siapkan wadah untuk nama-nama yang berulang tahun
$yangUlangTahun = [];
$yangUlangTahunTeddy = false;
$yangUlangTahunBuAnggia = false;
$adityaPratama = false;

foreach ($daftarUlangTahun as $tanggal => $nama) {
    if ($tanggal == $hariIni) {
        if ($nama === 'Bu Anggia Dini Marsaroha Boru Panggabean Simorangkir') {
            $yangUlangTahunBuAnggia = true;
        } elseif ($nama === 'Mas Teddy Adi Prasongko') {
            $yangUlangTahunTeddy = true;
        } elseif ($nama === 'Aditya Pratama') {
            $adityaPratama = true;
        } else {
            $yangUlangTahun[] = $nama;
        }
    }
}

// 3. Logika berdasarkan MODE
if ($mode === 'VOICE_ONLY') {
    // MODE KHUSUS: Hanya mengirim voice note untuk Aditya Pratama di jam 10
    handleVoiceOnlyMode($adityaPratama, $jamSekarang, $token, $groupID);
} else {
    // MODE NORMAL: Mengirim semua ucapan ulang tahun (kecuali Aditya yang voice-only)
    handleNormalMode($yangUlangTahun, $yangUlangTahunTeddy, $yangUlangTahunBuAnggia, $adityaPratama, 
                     $jamSekarang, $token, $groupID, $ucapanUmum, $ucapanTeddy, $ucapanBuAnggia);
}

// -------------------------

/**
 * MODE VOICE_ONLY: Mengirim voice note untuk Aditya Pratama saja
 * Dijalankan pada jam 10 pagi (03:00 UTC)
 */
function handleVoiceOnlyMode($adityaPratama, $jamSekarang, $token, $groupID) {
    if ($adityaPratama) {
        echo "[VOICE_ONLY MODE] Tanggal Aditya Pratama terdeteksi. Jam sekarang: " . $jamSekarang . "\n";
        kirimVoiceNoteTelegram($token, $groupID);
    } else {
        if (!$adityaPratama) {
            echo "[VOICE_ONLY MODE] Bukan tanggal Aditya Pratama (07-20). Tidak ada yang dikirim.\n";
        } else {
            echo "[VOICE_ONLY MODE] Jam sekarang adalah " . $jamSekarang . ", menunggu jam 10 untuk mengirim voice note.\n";
        }
    }
}

/**
 * MODE NORMAL: Mengirim semua ucapan ulang tahun (06:00 WIB)
 * Untuk semua orang kecuali Aditya (yang menunggu jam 10)
 */
function handleNormalMode($yangUlangTahun, $yangUlangTahunTeddy, $yangUlangTahunBuAnggia, $adityaPratama,
                          $jamSekarang, $token, $groupID, $ucapanUmum, $ucapanTeddy, $ucapanBuAnggia) {
    
    $adaYangDikirim = false;

    // Jika Bu Anggia berulang tahun, kirim ucapan khusus
    if ($yangUlangTahunBuAnggia) {
        echo "[NORMAL MODE] Bu Anggia berulang tahun. Mengirim ucapan khusus...\n";
        kirimPesanTelegram($token, $groupID, "🎉 **Selamat Ulang Tahun!** 🎉\n\n" . $ucapanBuAnggia);
        $adaYangDikirim = true;
    }

    // Jika Mas Teddy berulang tahun, kirim ucapan khusus (ramah tanpa berlebihan)
    if ($yangUlangTahunTeddy) {
        echo "[NORMAL MODE] Mas Teddy berulang tahun. Mengirim ucapan khusus...\n";
        $ucapanTeddyTerpilih = $ucapanTeddy[array_rand($ucapanTeddy)];
        kirimPesanTelegram($token, $groupID, $ucapanTeddyTerpilih);
        $adaYangDikirim = true;
    }

    // Jika ada orang lain yang berulang tahun, kirim ucapan variatif
    if (count($yangUlangTahun) > 0) {
        echo "[NORMAL MODE] " . count($yangUlangTahun) . " orang berulang tahun. Mengirim ucapan variatif...\n";
        // Pilih ucapan secara acak
        $ucapanTerpilih = $ucapanUmum[array_rand($ucapanUmum)];
        
        // Ganti placeholder {NAMA} dengan nama-nama yang berulang tahun
        $namaList = "";
        foreach ($yangUlangTahun as $nama) {
            $namaList .= "🎂  **" . $nama . "**\n";
        }
        
        $pesan = str_replace("{NAMA}", trim($namaList), $ucapanTerpilih);
        
        kirimPesanTelegram($token, $groupID, $pesan);
        $adaYangDikirim = true;
    }

    // Khusus untuk Aditya Pratama: catat bahwa dia akan mendapat voice note di jam 10
    if ($adityaPratama) {
        echo "[NORMAL MODE] Aditya Pratama berulang tahun hari ini! Voice note akan dikirim pada jam 10 pagi.\n";
    }

    // Jika tidak ada yang dikirim
    if (!$adaYangDikirim && !$adityaPratama) {
        echo "[NORMAL MODE] Tidak ada ulang tahun hari ini.\n";
    }
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
    echo "✅ Pesan terkirim! \n";
    echo $result . "\n";
}

// --- FUNGSI UNTUK MENGIRIM VOICE NOTE ---
function kirimVoiceNoteTelegram($token, $chatID) {
    // Dapatkan path file voice.ogg secara absolut
    $voiceFilePath = __DIR__ . '/voice.ogg';
    
    // Pengecekan apakah file ada
    if (!file_exists($voiceFilePath)) {
        echo "❌ ERROR: File voice.ogg tidak ditemukan di " . $voiceFilePath . "\n";
        return;
    }
    
    // Pengecekan apakah file dapat dibaca
    if (!is_readable($voiceFilePath)) {
        echo "❌ ERROR: File voice.ogg tidak dapat dibaca (permission issue)\n";
        return;
    }
    
    // Gunakan sendVoice endpoint untuk mengirim voice note
    $url = "https://api.telegram.org/bot" . $token . "/sendVoice";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    
    // Siapkan data file dengan CURLFile
    $postData = array(
        'chat_id' => $chatID,
        'voice' => new CURLFile($voiceFilePath, 'audio/ogg', 'voice.ogg')
    );
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Untuk debugging, tampilkan hasil
    echo "✅ Voice note terkirim! (HTTP Code: " . $httpCode . ")\n";
    echo $result . "\n";
}

?>
