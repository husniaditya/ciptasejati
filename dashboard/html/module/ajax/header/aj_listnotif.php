<?php
require_once ("../../connection/conn.php");

$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

$USER_KEY = $_SESSION["LOGINKEY_CS"];

$getNotif = GetQuery("SELECT n.*,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI,m.ANGGOTA_KEY,
CASE
  WHEN n.APPROVE_STATUS = 0 THEN 'Menunggu'
  WHEN n.APPROVE_STATUS = 1 THEN 'Disetujui'
  ELSE 'Ditolak'
END AS APPROVAL,
CASE
  WHEN n.APPROVE_STATUS = 0 THEN 'badge badge-inverse'
  WHEN n.APPROVE_STATUS = 1 THEN 'badge badge-success'
  ELSE 'badge badge-danger'
END AS NOTIF_BADGE,
CASE
  WHEN n.APPROVE_STATUS = 0 THEN 'fa-solid fa-spinner fa-spin'
  WHEN n.APPROVE_STATUS = 1 THEN 'fa-solid fa-check'
  ELSE 'fa-solid fa-xmark'
END AS NOTIF_ICON,
CASE
  WHEN DATEDIFF(NOW(), n.INPUT_DATE) < 1 THEN
      CONCAT(
          HOUR(TIMEDIFF(NOW(), n.INPUT_DATE)), ' Jam ',
          MINUTE(TIMEDIFF(NOW(), n.INPUT_DATE)), ' Menit yang lalu'
      )
  ELSE
      CONCAT(
			DATEDIFF(NOW(), n.INPUT_DATE), ' Hari ',
			HOUR(TIMEDIFF(time(NOW()), time(n.INPUT_DATE))), ' Jam ',
         MINUTE(TIMEDIFF(time(NOW()), time(n.INPUT_DATE))), ' Menit yang lalu'
		)
END AS difference
FROM t_notifikasi n
LEFT JOIN t_mutasi m ON n.DOKUMEN_ID = m.MUTASI_ID
LEFT JOIN m_anggota a ON n.INPUT_BY = a.ANGGOTA_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
WHERE NOTIFIKASI_USER = '$USER_KEY' ORDER BY READ_STATUS ASC,INPUT_DATE DESC LIMIT 15");

while ($rowNotif = $getNotif->fetch(PDO::FETCH_ASSOC)) {
    extract($rowNotif);
    if ($READ_STATUS == 0 && $APPROVE_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
        ?>
        <a href="#<?= $HREF; ?>" data-toggle="modal" class="media border-dotted <?= $TOGGLE; ?>" style="background-color: lavender;" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" onclick="getNotif(this)">
            <span class="media-body">
                <?php
                if ($KATEGORI == "Mutasi") {
                    ?>
                    <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                    <?php
                }
                ?>
                <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                <span class="media-text ellipsis nm semibold"><?= $BODY; ?></span>
                <!-- meta icon -->
                <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                <span class="media-meta pull-right"><?= $difference; ?></span>
                <!--/ meta icon -->
            </span>
        </a>
        <?php
    } elseif ($READ_STATUS == 0 && $APPROVE_STATUS <> 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
        ?>
        <a href="#<?= $HREF; ?>" data-toggle="modal" class="media border-dotted <?= $TOGGLE; ?>" style="background-color: lavender;" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" onclick="getNotif(this)">
            <span class="media-body">
                <?php
                if ($KATEGORI == "Mutasi") {
                    ?>
                    <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                    <?php
                }
                ?>
                <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                <span class="media-text ellipsis nm semibold"><?= $BODY; ?></span>
                <!-- meta icon -->
                <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                <span class="media-meta pull-right"><?= $difference; ?></span>
                <!--/ meta icon -->
            </span>
        </a>
        <?php
    } elseif ($READ_STATUS == 1 && $APPROVE_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
        ?>
        <a href="#<?= $HREF; ?>" data-toggle="modal" data-toggle="modal" class="media read border-dotted <?= $TOGGLE; ?>" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" onclick="getNotif(this)">
            <span class="media-body">
                <?php
                if ($KATEGORI == "Mutasi") {
                    ?>
                    <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                    <?php
                }
                ?>
                <span class="media-heading"><?= $DOKUMEN_ID; ?></span>
                <span class="media-heading"><?= $SUBJECT; ?></span>
                <span class="media-text ellipsis nm"><?= $BODY; ?></span>
                <!-- meta icon -->
                <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                <span class="media-meta pull-right"><?= $difference; ?></span>
                <!--/ meta icon -->
            </span>
        </a>
        <?php
    } 
    else {
        if ($READ_STATUS == 0) {
            ?>
            <a href="#<?= $HREF; ?>" data-toggle="modal" data-toggle="modal" class="media border-dotted <?= $TOGGLE; ?>" style="background-color: lavender;" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" onclick="getNotif(this)">
                <span class="media-body">
                    <?php
                    if ($KATEGORI == "Mutasi") {
                        ?>
                        <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                        <?php
                    }
                    ?>
                    <span class="media-heading text-primary semibold"><?= $DOKUMEN_ID; ?></span>
                    <span class="media-heading text-primary semibold"><?= $SUBJECT; ?></span>
                    <span class="media-text ellipsis nm semibold"><?= $BODY; ?></span>
                    <!-- meta icon -->
                    <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                    <span class="media-meta pull-right"><?= $difference; ?></span>
                    <!--/ meta icon -->
                </span>
            </a>
            <?php
        } else {
            ?>
            <a href="#<?= $HREF; ?>" data-toggle="modal" data-toggle="modal" class="media read border-dotted <?= $TOGGLE; ?>" data-id="<?= $NOTIFIKASI_ID; ?>" data-dokumen="<?= $DOKUMEN_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>" onclick="getNotif(this)">
                <span class="media-body">
                    <?php
                    if ($KATEGORI == "Mutasi") {
                        ?>
                        <span class="media-meta pull-right <?= $NOTIF_BADGE; ?>" style="color: white;"><i class="<?= $NOTIF_ICON; ?>"></i> <?= $APPROVAL; ?></span>
                        <?php
                    }
                    ?>
                    <span class="media-heading"><?= $DOKUMEN_ID; ?></span>
                    <span class="media-heading"><?= $SUBJECT; ?></span>
                    <span class="media-text ellipsis nm"><?= $BODY; ?></span>
                    <!-- meta icon -->
                    <span class="media-meta pull-left"><?= $ANGGOTA_NAMA." / ".$CABANG_DESKRIPSI; ?></span>
                    <span class="media-meta pull-right"><?= $difference; ?></span>
                    <!--/ meta icon -->
                </span>
            </a>
            <?php
        }
    }
    
}

?>