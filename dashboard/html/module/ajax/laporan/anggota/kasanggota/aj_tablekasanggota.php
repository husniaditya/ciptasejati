<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_KEY = $_SESSION["LOGINKEY_CS"];
$USER_NAMA = $_SESSION["LOGINNAME_CS"];
$USER_TINGKATAN = $_SESSION["LOGINTING_CS"];

if (isset($_POST["DAERAH_KEY"]) || isset($_POST["CABANG_KEY"]) || isset($_POST["KAS_ID"]) || isset($_POST["KAS_JENIS"]) || isset($_POST["ANGGOTA_ID"]) || isset($_POST["ANGGOTA_NAMA"]) || isset($_POST["TINGKATAN_ID"]) || isset($_POST["KAS_DK"]) || isset($_POST["TANGGAL_AWAL"]) || isset($_POST["TANGGAL_AKHIR"])) {
    
    if ($USER_AKSES == "Administrator") {
        $DAERAH_KEY = $_POST["DAERAH_KEY"];
        $CABANG_KEY = $_POST["CABANG_KEY"];
        $ANGGOTA_ID = $_POST["ANGGOTA_ID"];
        $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];
    } else {
        $DAERAH_KEY = "";
        $CABANG_KEY = $USER_CABANG;
        $ANGGOTA_ID = $USER_ID;
        $ANGGOTA_NAMA = $USER_NAMA;
        $TINGKATAN_ID = $USER_TINGKATAN;
    }
    $KAS_ID = $_POST["KAS_ID"];
    $KAS_JENIS = $_POST["KAS_JENIS"];
    $KAS_DK = $_POST["KAS_DK"];
    $TANGGAL_AWAL = $_POST["TANGGAL_AWAL"];
    $TANGGAL_AKHIR = $_POST["TANGGAL_AKHIR"];

    $sql = "SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_RANTING,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
    CASE
        WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
        ELSE FORMAT(k.KAS_JUMLAH, 0)
    END AS FKAS_JUMLAH,
    CASE 
        WHEN k.KAS_DK = 'D' THEN 'Debit'
        ELSE 'Kredit' 
    END AS KAS_DK_DES,
    CASE
        WHEN k.KAS_DK = 'D' THEN 'color: green;'
        ELSE 'color: red;' 
    END AS KAS_COLOR
    FROM t_kas k
    LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0 AND (d.DAERAH_KEY LIKE CONCAT('%','$DAERAH_KEY','%')) AND (c.CABANG_KEY LIKE CONCAT('%','$CABANG_KEY','%')) AND (k.KAS_ID LIKE CONCAT('%','$KAS_ID','%')) AND (k.KAS_JENIS LIKE CONCAT('%','$KAS_JENIS','%')) AND (a.ANGGOTA_ID LIKE CONCAT('%','$ANGGOTA_ID','%')) AND (a.ANGGOTA_NAMA LIKE CONCAT('%','$ANGGOTA_NAMA','%')) AND (t.TINGKATAN_ID LIKE CONCAT('%','$TINGKATAN_ID','%')) AND (k.KAS_DK LIKE CONCAT('%','$KAS_DK','%'))";

    if ($TANGGAL_AWAL != "" && $TANGGAL_AKHIR != "") {
        $sql .= " AND (k.KAS_TANGGAL BETWEEN '$TANGGAL_AWAL' AND '$TANGGAL_AKHIR')";
    } elseif ($TANGGAL_AWAL != "" && $TANGGAL_AKHIR == "") {
        $sql .= " AND (k.KAS_TANGGAL BETWEEN '$TANGGAL_AWAL' AND '$TANGGAL_AWAL')";
    } elseif ($TANGGAL_AWAL == "" && $TANGGAL_AKHIR != "") {
        $sql .= " AND (k.KAS_TANGGAL BETWEEN '$TANGGAL_AKHIR' AND '$TANGGAL_AKHIR')";
    }
    $sql .= "ORDER BY k.KAS_ID";

    $getKas = GetQuery($sql);

} else {
    if ($USER_AKSES == "Administrator") {
        $getKas = GetQuery("SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_RANTING,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
        CASE
            WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
            ELSE FORMAT(k.KAS_JUMLAH, 0)
        END AS FKAS_JUMLAH,
        CASE 
            WHEN k.KAS_DK = 'D' THEN 'Debit'
            ELSE 'Kredit' 
        END AS KAS_DK_DES,
        CASE
            WHEN k.KAS_DK = 'D' THEN 'color: green;'
            ELSE 'color: red;' 
        END AS KAS_COLOR
        FROM t_kas k
        LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
        LEFT JOIN m_cabang c ON k.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0
        ORDER BY k.KAS_ID");
    } else {
        $getKas = GetQuery("SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
        CASE
            WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
            ELSE FORMAT(k.KAS_JUMLAH, 0)
        END AS FKAS_JUMLAH,
        CASE 
            WHEN k.KAS_DK = 'D' THEN 'Debit'
            ELSE 'Kredit' 
        END AS KAS_DK_DES,
        CASE
            WHEN k.KAS_DK = 'D' THEN 'color: green;'
            ELSE 'color: red;' 
        END AS KAS_COLOR
        FROM t_kas k
        LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
        LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
        LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
        WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0 AND a.CABANG_KEY = '$USER_CABANG' AND a.ANGGOTA_KEY = '$USER_KEY'
        ORDER BY k.KAS_ID");
    }
    
}

while ($rowKas = $getKas->fetch(PDO::FETCH_ASSOC)) {
    extract($rowKas);

    $getSaldo = GetQuery("SELECT 
        CASE
            WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
            ELSE FORMAT(SUM(KAS_JUMLAH), 0)
        END AS FKAS_SALDO
    FROM 
        t_kas
    WHERE 
        DELETION_STATUS = 0 
        AND ANGGOTA_KEY = '$ANGGOTA_KEY' 
        AND KAS_ID <= '$KAS_ID' 
        AND KAS_JENIS = '$KAS_JENIS';
    ");

    while ($rowSaldo = $getSaldo->fetch(PDO::FETCH_ASSOC)) {
        extract($rowSaldo);
    }
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewKasAnggota" class="open-ViewKasAnggota" style="color:#222222;" data-id="<?= $KAS_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-jenis="<?= $KAS_JENIS; ?>" data-cabang="<?= $CABANG_KEY; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <li class="divider"></li>
                        <li><a href="assets/print/transaksi/kas/print_kas.php?id=<?= encodeIdToBase64($KAS_ID); ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $KAS_ID; ?></td>
        <td align="center"><?= $ANGGOTA_ID; ?></td>
        <td align="center"><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
        <td align="center"><?= $KAS_JENIS; ?></td>
        <td align="center"><?= $FKAS_TANGGAL; ?></td>
        <td align="center"><?= $KAS_DK_DES; ?></td>
        <td><?= $KAS_DESKRIPSI; ?></td>
        <td align="right" style="<?= $KAS_COLOR; ?>"><?= $FKAS_JUMLAH; ?></td>
        <td align="right"><?= $FKAS_SALDO; ?></td>
        <td align="center"><?= $ANGGOTA_RANTING; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}

?>