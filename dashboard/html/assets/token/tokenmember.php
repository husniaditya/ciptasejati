<?php
require_once ("../../module/connection/conn.php");

if (isset($_GET['id'])) {
    $encodedId = $_GET['id'];
    $decodedId = decodeBase64ToId($encodedId);

    $getData = GetQuery("SELECT a.*, c.CABANG_DESKRIPSI, d.DAERAH_DESKRIPSI, t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN, date_format(a.ANGGOTA_KTA_AKTIF, '%d-%m-%Y') AS KTA_AKTIF, date_format(a.ANGGOTA_KTA_EXP, '%d-%m-%Y') AS KTA_EXP,
    CASE 
        WHEN a.ANGGOTA_STATUS = 0 THEN 'Aktif'
        WHEN a.ANGGOTA_STATUS = 1 THEN 'Non Aktif'
        ELSE 'Mutasi'
    END AS ANGGOTA_STATUS
    FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE a.ANGGOTA_KEY = '$decodedId'");
}

if (isset($_GET['id']) && isset($_GET['data'])) {
    $encodedId = $_GET['id'];
    $decodedId = decodeBase64ToId($encodedId);
    $decodeData = $_GET['data'];
    $decodedData = decodeBase64ToId($decodeData);

    $getData = GetQuery("SELECT a.*, c.CABANG_DESKRIPSI, d.DAERAH_DESKRIPSI, t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN, date_format(a.ANGGOTA_KTA_AKTIF, '%d-%m-%Y') AS KTA_AKTIF, date_format(a.ANGGOTA_KTA_EXP, '%d-%m-%Y') AS KTA_EXP,
    CASE 
        WHEN a.ANGGOTA_STATUS = 0 THEN 'Aktif'
        WHEN a.ANGGOTA_STATUS = 1 THEN 'Non Aktif'
        ELSE 'Mutasi'
    END AS ANGGOTA_STATUS
    FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE a.ANGGOTA_KEY = '$decodedData'");
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
                <label>ID Anggota</label>
                <p><?= $ANGGOTA_ID; ?></p>
                <label>Nama Anggota</label>
                <p><?= $ANGGOTA_NAMA; ?></p>
                <label>Status Anggota</label>
                <p><?= $ANGGOTA_STATUS; ?></p>
            </div>
            <div class="col-md-6 icon-wrapper">
                <i class="fa-regular fa-circle-check icon" style="color: #2E8B57;"></i>
            </div>
        </div>
        <h3>Info Keanggotaan </h3>
        <hr>
        <label>Daerah</label>
        <p><?= $DAERAH_DESKRIPSI; ?></p>
        <label>Cabang</label>
        <p><?= $CABANG_DESKRIPSI; ?></p>
        <label>Tingkatan</label>
        <p><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></p>
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
