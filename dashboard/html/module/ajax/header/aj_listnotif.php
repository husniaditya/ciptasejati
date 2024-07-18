<?php
require_once ("../../connection/conn.php");

$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$USER_KEY = $_SESSION["LOGINKEY_CS"];

$getNotif = GetQuery("SELECT n.*,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI,COALESCE(m.ANGGOTA_KEY,k.ANGGOTA_KEY) as ANGGOTA_KEY,COALESCE(m.ANGGOTA_ID,k.ANGGOTA_ID) as ANGGOTA_ID, k.KAS_JENIS,
CASE
    WHEN n.KATEGORI = 'KAS' THEN
        CASE
            WHEN k.KAS_DK = 'D' THEN 'Debit'
            ELSE 'Kredit'
        END
    WHEN n.KATEGORI = 'MUTASI' OR n.KATEGORI = 'PPD' OR n.KATEGORI = 'UKT' THEN
        CASE
            WHEN n.APPROVE_STATUS = 0 THEN 'Menunggu'
            WHEN n.APPROVE_STATUS = 1 THEN 'Disetujui'
            ELSE 'Ditolak'
        END
END AS APPROVAL,
CASE
    WHEN n.KATEGORI = 'KAS' THEN
        CASE
            WHEN k.KAS_DK = 'D' THEN 'badge badge-success'
            ELSE 'badge badge-danger'
        END
    WHEN n.KATEGORI = 'MUTASI' OR n.KATEGORI = 'PPD' OR n.KATEGORI = 'UKT' THEN
        CASE
            WHEN n.APPROVE_STATUS = 0 THEN 'badge badge-inverse'
            WHEN n.APPROVE_STATUS = 1 THEN 'badge badge-success'
            ELSE 'badge badge-danger'
        END
END AS NOTIF_BADGE,
CASE
    WHEN n.KATEGORI = 'KAS' THEN
        CASE
            WHEN k.KAS_DK = 'D' THEN 'fa-solid fa-right-to-bracket'
            ELSE 'fa-solid fa-right-from-bracket'
        END
    WHEN n.KATEGORI = 'MUTASI' OR n.KATEGORI = 'PPD' OR n.KATEGORI = 'UKT' THEN
        CASE
            WHEN n.APPROVE_STATUS = 0 THEN 'fa-solid fa-spinner fa-spin'
            WHEN n.APPROVE_STATUS = 1 THEN 'fa-solid fa-check'
            ELSE 'fa-solid fa-xmark'
        END
END AS NOTIF_ICON,
CASE
    WHEN TIMESTAMPDIFF(MINUTE, n.INPUT_DATE, NOW()) < 10 THEN
        ' Baru saja'
    WHEN TIMESTAMPDIFF(HOUR, n.INPUT_DATE, NOW()) < 1 THEN
        CONCAT(
            TIMESTAMPDIFF(MINUTE, n.INPUT_DATE, NOW()), ' Menit yang lalu'
        )
    WHEN TIMESTAMPDIFF(HOUR, n.INPUT_DATE, NOW()) < 24 THEN
        CONCAT(
            TIMESTAMPDIFF(HOUR, n.INPUT_DATE, NOW()), ' Jam ',
            MOD(TIMESTAMPDIFF(MINUTE, n.INPUT_DATE, NOW()), 60), ' Menit yang lalu'
        )
    ELSE
        CONCAT(
            TIMESTAMPDIFF(DAY, n.INPUT_DATE, NOW()), ' Hari ',
            MOD(TIMESTAMPDIFF(HOUR, n.INPUT_DATE, NOW()), 24), ' Jam ',
            MOD(TIMESTAMPDIFF(MINUTE, n.INPUT_DATE, NOW()), 60), ' Menit yang lalu'
        )
END AS difference
FROM t_notifikasi n
LEFT JOIN t_mutasi m ON n.DOKUMEN_ID = m.MUTASI_ID
LEFT JOIN t_kas k ON n.DOKUMEN_ID = k.KAS_ID
LEFT JOIN m_anggota a ON n.INPUT_BY = a.ANGGOTA_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
WHERE NOTIFIKASI_USER = '$USER_KEY' ORDER BY READ_STATUS ASC,INPUT_DATE DESC LIMIT 15");

while ($rowNotif = $getNotif->fetch(PDO::FETCH_ASSOC)) {
    extract($rowNotif);
    if ($READ_STATUS == 0 && $APPROVE_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
        ?>
        <a href="#<?= $HREF; ?>" data-toggle="modal" class="media border-dotted <?= $TOGGLE; ?>" style="background-color: lavender;" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-anggotaid="<?= $ANGGOTA_ID; ?>" data-jenis="<?= $KAS_JENIS; ?>" data-cabang="<?= $CABANG_AWAL; ?>" onclick="getNotif(this)">
            <span class="media-body">
                <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                <span class="media-text nm semibold"><?= $BODY; ?></span>
                <!-- meta icon -->
                <span class="media-meta pull-left"><i class="fa-regular fa-pen-to-square"></i> <?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                <span class="media-meta pull-right"><i class="fa-regular fa-clock"></i> <?= $difference; ?></span>
                <!--/ meta icon -->
            </span>
        </a>
        <?php
    } elseif ($READ_STATUS == 0 && $APPROVE_STATUS <> 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
        ?>
        <a href="#<?= $HREF; ?>" data-toggle="modal" class="media border-dotted <?= $TOGGLE; ?>" style="background-color: lavender;" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-anggotaid="<?= $ANGGOTA_ID; ?>" data-jenis="<?= $KAS_JENIS; ?>" data-cabang="<?= $CABANG_AWAL; ?>" onclick="getNotif(this)">
            <span class="media-body">
                <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                <span class="media-text nm semibold"><?= $BODY; ?></span>
                <!-- meta icon -->
                <span class="media-meta pull-left"><i class="fa-regular fa-pen-to-square"></i> <?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                <span class="media-meta pull-right"><i class="fa-regular fa-clock"></i> <?= $difference; ?></span>
                <!--/ meta icon -->
            </span>
        </a>
        <?php
    } elseif ($READ_STATUS == 1 && $APPROVE_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
        ?>
        <a href="#<?= $HREF; ?>" data-toggle="modal" data-toggle="modal" class="media read border-dotted <?= $TOGGLE; ?>" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-anggotaid="<?= $ANGGOTA_ID; ?>" data-jenis="<?= $KAS_JENIS; ?>" data-cabang="<?= $CABANG_AWAL; ?>" onclick="getNotif(this)">
            <span class="media-body">
                <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                <span class="media-heading"><?= $DOKUMEN_ID; ?></span>
                <span class="media-heading"><?= $SUBJECT; ?></span>
                <span class="media-text nm"><?= $BODY; ?></span>
                <!-- meta icon -->
                <span class="media-meta pull-left"><i class="fa-regular fa-pen-to-square"></i> <?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                <span class="media-meta pull-right"><i class="fa-regular fa-clock"></i> <?= $difference; ?></span>
                <!--/ meta icon -->
            </span>
        </a>
        <?php
    } 
    else {
        if ($READ_STATUS == 0) {
            ?>
            <a href="#<?= $HREF; ?>" data-toggle="modal" data-toggle="modal" class="media border-dotted <?= $TOGGLE; ?>" style="background-color: lavender;" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-anggotaid="<?= $ANGGOTA_ID; ?>" data-jenis="<?= $KAS_JENIS; ?>" data-cabang="<?= $CABANG_AWAL; ?>" onclick="getNotif(this)">
                <span class="media-body">
                    <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                    <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                    <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                    <span class="media-text nm semibold"><?= $BODY; ?></span>
                    <!-- meta icon -->
                    <span class="media-meta pull-left"><i class="fa-regular fa-pen-to-square"></i> <?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                    <span class="media-meta pull-right"><i class="fa-regular fa-clock"></i> <?= $difference; ?></span>
                    <!--/ meta icon -->
                </span>
            </a>
            <?php
        } else {
            ?>
            <a href="#<?= $HREF; ?>" data-toggle="modal" data-toggle="modal" class="media read border-dotted <?= $TOGGLE; ?>" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" data-anggotaid="<?= $ANGGOTA_ID; ?>" data-jenis="<?= $KAS_JENIS; ?>" data-cabang="<?= $CABANG_AWAL; ?>" onclick="getNotif(this)">
                <span class="media-body">
                    <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                    <span class="media-heading"><?= $DOKUMEN_ID; ?></span>
                    <span class="media-heading"><?= $SUBJECT; ?></span>
                    <span class="media-text nm"><?= $BODY; ?></span>
                    <!-- meta icon -->
                    <span class="media-meta pull-left"><i class="fa-regular fa-pen-to-square"></i> <?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                    <span class="media-meta pull-right"><i class="fa-regular fa-clock"></i> <?= $difference; ?></span>
                    <!--/ meta icon -->
                </span>
            </a>
            <?php
        }
    }
    
}

?>