<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Jakarta'); // Atur timezone ke Asia/Jakarta

// Fungsi untuk membaca data dari file CSV
function getStudentData($nis) {
    $result = []; // Deklarasikan $result sebagai array kosong
    if (($file = fopen(__DIR__ . "/database_siswa.csv", "r")) !== FALSE) {
        fgetcsv($file, 1000, ";"); // Abaikan baris header
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
            if ($data[0] == $nis) {
                $result[] = array(
                    'NIS' => $data[0],
                    'Nama' => $data[1],
                    'Jenis Pemantapan' => $data[2],
                    'Jenis Kelas' => isset($data[7]) ? $data[7] : '',
                    'Kelas' => isset($data[6]) ? $data[6] : '',
                    'Tagihan Terakhir' => isset($data[4]) ? $data[4] : ''
                );
            }
        }
        fclose($file);
    }
    return $result;
}


// Ambil input NIS
$nis = isset($_GET['nis_siswa']) ? trim($_GET['nis_siswa']) : null;
$student = $nis ? getStudentData($nis) : null;

// Array bulan
$bulan = array(
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
    7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
);

$bulanSekarang = $bulan[(int)date('n')] . ' ' . date('Y');
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pemantapan Sekolah Putra Batam</title>
    <style>
        /* CSS untuk menyembunyikan kolom saat print */
        @media print {
            .hidden-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <table width="95%" height="68" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="110" height="65">
                        <img src="/img/logo_sekolah.gif" height="64" />
                        </td>
                        <td class="header">
                            <div align="center">
                                <span class="header1 style15">
                                    <strong>SEKOLAH PUTRA BATAM</strong>
                                </span><br />
                                Jl. Letjend. R. Soeprapto, Muka Kuning - Batam<br />
                                Telp: (0778) 364035
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr color="#000000"></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <p align="center" class="judulbiru"><strong><span class="style4"><u>FORM PERUBAHAN DATA SISWA PEMANTAPAN</u></span></strong></p>
    <p align="center" class="judulbiru"><strong><span class="style4">BERHENTI PEMANTAPAN</span></strong></p>

    <!-- Kolom untuk input NIS -->
    <form method="GET" class="hidden-print">
        <table width="100%" border="0" align="center">
            <tr>
                <td width="37%"><span class="style18">NIS Siswa Berhenti</span></td>
                <td>
                    <input type="text" name="nis_siswa" placeholder="Masukkan NIS Siswa" style="width: 95%;" value="<?php echo htmlspecialchars($nis ?? '', ENT_QUOTES, 'UTF-8'); ?>"
">
                </td>
                <td>
                    <button type="submit">Cari</button><button onclick="printForm()">Print</button>
                </td>
            </tr>
        </table>
        <?php if ($student && !empty($student)): ?>
    <select id="jenis_pemantapan" name="jenis_pemantapan">
        <?php foreach ($student as $data): ?>
            <option value="<?php echo htmlspecialchars($data['Jenis Kelas']); ?>">
                <?php echo htmlspecialchars($data['Jenis Kelas']); ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php endif; ?>

    </form>

    <!-- Menampilkan data siswa jika ditemukan -->
    <?php if ($student && !empty($student)): ?>
    <table width="100%" border="0" align="center">
        <tr>
            <td colspan="100%">Dengan ini saya sebagai Orang Tua/Wali/Siswa dari siswa :</td>
        </tr>
        <tr>
            <td width="37%"><span class="style18">Nomor Siswa</span></td>
            <td colspan="2">
                <span class="style18">:&nbsp;&nbsp;<strong><?php echo htmlspecialchars($student[0]['NIS']); ?></strong></span>
            </td>
        </tr>
        <tr>
            <td><span class="style18">Nama Siswa</span></td>
            <td colspan="2">
                <span class="style18">:&nbsp;&nbsp;<?php echo htmlspecialchars($student[0]['Nama']); ?></span>
            </td>
        </tr>
        <tr>
            <td><span class="style18">Jenis Pemantapan</span></td>
            <td colspan="2">
                <span class="style18" id="info_jenis_kelas">:&nbsp;&nbsp;<?php echo htmlspecialchars($student[0]['Jenis Kelas']); ?></span>
            </td>
        </tr>
        <tr>
            <td><span class="style18">Kelas / Pemantapan</span></td>
            <td colspan="2">
                <span class="style18">:&nbsp;&nbsp;<?php echo htmlspecialchars($student[0]['Kelas']); ?></span>
            </td>
        </tr>
        <tr>
            <td><span class="style18">Tagihan Terakhir</span></td>
            <td colspan="2">
                <span class="style18">:&nbsp;&nbsp;<?php echo htmlspecialchars($bulanSekarang); ?></span>
            </td>
        </tr>
    </table>
<?php else: ?>
    <p align="center" style="color: red; font-weight: bold;">Data tidak ditemukan. Pastikan NIS yang dimasukkan benar.</p>
<?php endif; ?>


    <p align="justify" style="width:95%">
        Dengan ini saya bersedia dan setuju akan melunasi seluruh biaya tunggakan yang ada,
        dan bersedia untuk melakukan pendaftaran ulang serta membayar biaya pendaftaran Rp 50.000,- jika ingin kembali mengikuti pemantapan.
    </p>

    <table width="90%" align="left" border="0px">
        <tr>
            <td colspan="2"><b>Batam, <?php echo date('d-m-Y'); ?></b></td>
        </tr>
        <tr>
            <td><b>Diajukan oleh,</b></td>
            <td align="right"><b>Diterima oleh,</b></td>
        </tr>
        <tr>
            <td><br /><br /><br /><br /><b>(_________________)</b><br />Orang Tua/Wali/Siswa</td>
            <td align="right"><br /><br /><br /><br /><b>(Ilham Nur Septian)</b><br />Bagian Layanan Siswa</td>
        </tr>
    </table>

</body>
<script>
    // Fungsi untuk memperbarui elemen saat dropdown dipilih
    document.getElementById('jenis_pemantapan').addEventListener('change', function() {
        var selectedValue = this.value;
        // Mengupdate elemen HTML dengan &nbsp;&nbsp;
        document.getElementById('info_jenis_kelas').innerHTML = ':&nbsp;&nbsp;' + selectedValue;
    });
</script>

<script>
    function printForm() {
    var nis = document.querySelector('[name="nis_siswa"]').value;
    var jenisKelas = document.getElementById('info_jenis_kelas').innerText.trim();
    var namaSiswa = "<?php echo $student[0]['Nama']; ?>";

    console.log('Data Siswa dicetak:', {nis, namaSiswa, jenisKelas});

    // Kirim data ke server untuk membuat log
    savePrintLog(nis, namaSiswa, jenisKelas);
    
    // Menampilkan log di konsol setelah data terkirim
    alert("Data telah dicetak:\nNIS: " + nis + "\nNama: " + namaSiswa + "\nKelas: " + jenisKelas);

    window.print();
}

function savePrintLog(nis, nama, kelas) {
    console.log('Data yang dikirim:', { nis, nama, kelas }); // Periksa di konsol

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save-log.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function() {
        if (xhr.status == 200) {
            console.log('Log berhasil dikirim:', xhr.responseText);
        } else {
            console.error('Gagal mengirim log:', xhr.status, xhr.responseText);
        }
    };

    xhr.send(JSON.stringify({ nis: nis, nama: nama, kelas: kelas }));
}


</script>

<?php
// Membaca input JSON
$data = json_decode(file_get_contents("php://input"), true);

// Jika data kosong atau format salah
if (!$data) {
    error_log("Data yang diterima tidak valid atau kosong: " . file_get_contents("php://input"));
    header("HTTP/1.1 400 Bad Request");
    exit;
}

$file = fopen(__DIR__ . '/log_prints.csv', "a");
if ($file) {
    $log = array(
        date('Y-m-d H:i:s'),
        $data['nis'],
        $data['nama'],
        $data['kelas']
    );

    // Menulis log ke CSV
    if (!fputcsv($file, $log)) {
        error_log("Gagal menulis ke file CSV");
    }

    fclose($file);
}

error_log("Data yang diterima: " . print_r($data, true));

ob_end_flush();

if (!$data) {
    error_log("Data tidak diterima atau format salah");
    header("HTTP/1.1 400 Bad Request");
    exit;
}

?>
</html>
