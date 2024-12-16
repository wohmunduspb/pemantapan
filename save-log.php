<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Jakarta'); // Atur timezone ke Asia/Jakarta

// Membaca input JSON
$data = json_decode(file_get_contents("php://input"), true);

// Jika data kosong atau format salah
if (!$data) {
    error_log("Data tidak valid: " . file_get_contents("php://input"));
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("status" => "Gagal menerima data"));
    exit;
}

// Menyiapkan path file log
$filePath = __DIR__ . '/log_prints.csv';

// Memastikan file bisa dibuka untuk menulis
if (!is_writable($filePath)) {
    error_log("File log_prints.csv tidak bisa ditulis. Pastikan file memiliki izin akses yang benar.");
    echo json_encode(array("status" => "Gagal membuka file untuk menulis"));
    exit;
}

// Membuka file log untuk ditulis
$file = fopen($filePath, "a");
if ($file) {
    // Membuat array log
    $log = array(
        date('Y-m-d H:i:s'),
        $data['nis'],
        $data['nama'],
        $data['kelas']
    );

    // Menambahkan log ke file CSV
    if (fputcsv($file, $log)) {
        // Berhasil menulis log
        echo json_encode(array("status" => "Data dicetak berhasil"));
    } else {
        // Gagal menulis log
        error_log("Gagal menulis ke file CSV");
        echo json_encode(array("status" => "Gagal mencatat log"));
    }
    fclose($file);
} else {
    // Gagal membuka file
    error_log("Gagal membuka file log_prints.csv untuk menulis");
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("status" => "Gagal membuka file untuk menulis"));
}
?>
