<?php
require_once ("../../module/connection/conn.php");

if (isset($_GET['id']) && isset($_GET['data'])) {
    $encodedId = $_GET['id'];
    $encodedApp = $_GET['data'];
    $decodedId = decodeBase64ToId($encodedId);
    $decodedApp = decodeBase64ToId($encodedApp);

    $getData = GetQuery("SELECT p.PPD_ID DOKUMEN_ID,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA, koor.ANGGOTA_NAMA KOOR_NAMA, guru.ANGGOTA_NAMA GURU_NAMA, date_format(p.PPD_TANGGAL, '%d-%m-%Y') DOKUMEN_DATE, date_format(p.INPUT_DATE, '%H:%m:%s') DOKUMEN_TIME, date_format(NOW(), '%d-%m-%Y') DATENOW, date_format(NOW(), '%H:%m:%s') TIMENOW
    FROM t_ppd p
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota guru ON p.PPD_APPROVE_GURU_BY = guru.ANGGOTA_ID
    LEFT JOIN m_anggota koor ON p.PPD_APPROVE_PELATIH_BY = koor.ANGGOTA_ID AND p.CABANG_KEY = koor.CABANG_KEY
    WHERE p.PPD_ID = '$decodedId' AND (p.PPD_APPROVE_PELATIH = 1 OR p.PPD_APPROVE_GURU = 1)
    UNION ALL
    SELECT p.PPD_FILE_NAME DOKUMEN_ID,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA, koor.ANGGOTA_NAMA KOOR_NAMA, guru.ANGGOTA_NAMA GURU_NAMA, date_format(NOW(), p.PPD_TANGGAL) DOKUMEN_DATE, date_format(p.INPUT_DATE, '%H:%m:%s') DOKUMEN_TIME, date_format(NOW(), '%d-%m-%Y') DATENOW, date_format(NOW(), '%H:%m:%s') TIMENOW
    FROM t_ppd p
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota guru ON p.PPD_APPROVE_GURU_BY = guru.ANGGOTA_ID
    LEFT JOIN m_anggota koor ON p.PPD_APPROVE_PELATIH_BY = koor.ANGGOTA_ID AND p.CABANG_KEY = koor.CABANG_KEY
    WHERE p.PPD_FILE_NAME = '$decodedId' AND (p.PPD_APPROVE_PELATIH = 1 OR p.PPD_APPROVE_GURU = 1)
    UNION ALL
    SELECT k.KAS_ID DOKUMEN_ID,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA, koor.ANGGOTA_NAMA KOOR_NAMA, guru.ANGGOTA_NAMA GURU_NAMA, date_format(k.KAS_TANGGAL, '%d-%m-%Y') DOKUMEN_DATE, date_format(k.INPUT_DATE, '%H:%m:%s') DOKUMEN_TIME, date_format(NOW(), '%d-%m-%Y') DATENOW, date_format(NOW(), '%H:%m:%s') TIMENOW
    FROM t_kas k
    LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON k.ANGGOTA_ID = a.ANGGOTA_ID AND k.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota guru ON k.INPUT_BY = guru.ANGGOTA_ID
    LEFT JOIN m_anggota koor ON k.INPUT_BY = koor.ANGGOTA_ID AND k.CABANG_KEY = koor.CABANG_KEY
    WHERE k.KAS_ID = '$decodedId'
    UNION ALL
    SELECT m.MUTASI_ID DOKUMEN_ID,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA, koor.ANGGOTA_NAMA KOOR_NAMA, guru.ANGGOTA_NAMA GURU_NAMA, date_format(m.INPUT_DATE, '%d-%m-%Y') DOKUMEN_DATE, date_format(m.INPUT_DATE, '%H:%m:%s') DOKUMEN_TIME, date_format(NOW(), '%d-%m-%Y') DATENOW, date_format(NOW(), '%H:%m:%s') TIMENOW
    FROM t_mutasi m
    LEFT JOIN m_cabang c ON m.CABANG_AWAL = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON m.ANGGOTA_ID = a.ANGGOTA_ID AND m.CABANG_AWAL = a.CABANG_KEY
    LEFT JOIN m_anggota guru ON m.MUTASI_APPROVE_BY = guru.ANGGOTA_ID
    LEFT JOIN m_anggota koor ON m.INPUT_BY = koor.ANGGOTA_ID AND m.CABANG_AWAL = koor.CABANG_KEY
    WHERE m.MUTASI_ID = '$decodedId'");
}

if (isset($_GET['id']) && isset($_GET['data']) && isset($_GET['pic'])) {
    $encodedId = $_GET['id'];
    $encodedApp = $_GET['data'];
    $encodedAng = $_GET['pic'];
    $decodedId = decodeBase64ToId($encodedId);
    $decodedApp = decodeBase64ToId($encodedApp);
    $decodedAng = decodeBase64ToId($encodedAng);

    $getData = GetQuery("SELECT u.UKT_ID DOKUMEN_ID,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA, koor.ANGGOTA_NAMA KOOR_NAMA, guru.ANGGOTA_NAMA GURU_NAMA, date_format(u.INPUT_DATE, '%d-%m-%Y') DOKUMEN_DATE, date_format(u.INPUT_DATE, '%H:%m:%s') DOKUMEN_TIME, date_format(NOW(), '%d-%m-%Y') DATENOW, date_format(NOW(), '%H:%m:%s') TIMENOW
    FROM t_ukt u
    LEFT JOIN t_ukt_penguji ud ON u.UKT_ID = ud.UKT_ID
    LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota guru ON ud.ANGGOTA_ID = guru.ANGGOTA_ID AND u.CABANG_KEY = guru.CABANG_KEY
    LEFT JOIN m_anggota koor ON ud.ANGGOTA_ID = koor.ANGGOTA_ID AND u.CABANG_KEY = koor.CABANG_KEY
    WHERE u.UKT_ID = '$decodedId' AND ud.ANGGOTA_ID = '$decodedAng' AND u.UKT_APP_KOOR = 1");
}

while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
    extract($data);
}

$getMaintenance = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'isMaintenance'");
while ($mt = $getMaintenance->fetch(PDO::FETCH_ASSOC)) {
    extract($mt);
}
$getLogo = GetQuery("SELECT PROFIL_LOGO FROM c_profil");
while ($logoCS = $getLogo->fetch(PDO::FETCH_ASSOC)) {
    extract($logoCS);
}
$getSocial = GetQuery("SELECT * FROM c_mediasosial WHERE DELETION_STATUS = 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pemeliharaan</title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../../../stylesheet/fontawesome-free/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://cdn0-production-images-kly.akamaized.net/peBTVTtV2gJNrp8VAAEwUphQSt0=/640x360/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/1511326/original/016255100_1487375130-Martial-arts-21.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            margin-top: 70px; /* Adjust the margin-top as needed */
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: left; /* Add this line */
        }
        .container img {
            width: 150px;
            margin-bottom: 20px;
        }
        .row {
            display: flex;
            align-items: center; /* Center align items vertically */
            margin-bottom: 10px;
        }
        .col-md-6 {
            flex: 1;
            padding: 10px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #C70039;
            text-align: center; /* Add this line */
        }
        h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #333;
            text-align: center; /* Add this line */
        }
        p {
            margin: 0 0 20px;
            font-size: 16px;
            text-align: left;
            
        }
        label {
            font-weight: bold;
            font-size: 16px;
            display: block; /* Add this line */
        }
        .contact-info {
            margin-top: 20px;
        }
        .contact-info a {
            color: #007BFF;
            text-decoration: none;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .social-icons a {
            margin: 0 10px;
            color: #C70039;
            font-size: 24px;
            text-decoration: none;
        }
        .icon-wrapper {
            display: flex;
            justify-content: center; /* Center items horizontally */
            align-items: center; /* Center items vertically */
            height: 100%; /* Ensure the wrapper takes full height of the column */
        }
        .icon {
            font-size: 5em; /* Adjust the size of the icon */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <label>Nomor Dokumen</label>
                <p><?= $DOKUMEN_ID; ?></p>
                <label>Nama Anggota</label>
                <p><?= $ANGGOTA_NAMA; ?></p>
            </div>
            <div class="col-md-6 icon-wrapper">
                <i class="fa-regular fa-circle-check icon" style="color: #2E8B57;"></i>
            </div>
        </div>
        <h3>Info Penandatanganan</h3>
        <hr>
        <label>Daerah</label>
        <p><?= $DAERAH_DESKRIPSI; ?></p>
        <label>Cabang</label>
        <p><?= $CABANG_DESKRIPSI; ?></p>
        <label>Penandatangan</label>
        <?php
        if ($decodedApp == "Koor") {
            ?>
            <p><?= $KOOR_NAMA; ?></p>
            <?php
        } elseif ($decodedApp == "Guru") {
            ?>
            <p><?= $GURU_NAMA; ?></p>
            <?php
        } else {
            ?>
            <p><?= $ANGGOTA_NAMA; ?></p>
            <?php
        }
        ?>
        <h3>Informasi</h3>
        <hr>
        <label>Tanggal Pindai</label>
        <p><i class="fa-regular fa-calendar-days"></i> <?= $DATENOW; ?> &nbsp; <i class="fa-regular fa-clock"></i> <?= $TIMENOW; ?></p>
        <label>Tanggal Penandatanganan</label>
        <p><i class="fa-regular fa-calendar-days"></i> <?= $DOKUMEN_DATE; ?> &nbsp; <i class="fa-regular fa-clock"></i> <?= $DOKUMEN_TIME; ?></p>
        <hr>
        <div class="contact-info">
            <p>Perlu menghubungi kami? Hubungi kami di: <br> 
            <a href="mailto:admincs@ciptasejatiindonesia.com"><i class="fa-regular fa-envelope"></i> admincs@ciptasejatiindonesia.com</a> <br>
            <a href="mailto:ciptasejatiindonesia@gmail.com"><i class="fa-regular fa-envelope"></i> ciptasejatiindonesia@gmail.com</a></p>
        </div>
        <div class="social-icons">
            <?php
            while ($iconCS = $getSocial->fetch(PDO::FETCH_ASSOC)) {
                extract($iconCS);
                ?>
                <a href="<?= $MEDIA_LINK; ?>" target="_blank" rel="noopener noreferrer"><i class="<?= $MEDIA_ICON; ?>"></i></a>
                <?php
            }
            ?>
            <!-- Add more social media icons as needed -->
        </div>
    </div>
</body>
</html>
