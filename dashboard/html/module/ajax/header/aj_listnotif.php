<?php
require_once ("../../connection/conn.php");

$USER_KEY = $_SESSION["LOGINKEY_CS"];

$getNotif = GetQuery("SELECT n.*,a.ANGGOTA_NAMA,c.CABANG_DESKRIPSI,
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
LEFT JOIN m_anggota a ON n.INPUT_BY = a.ANGGOTA_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
WHERE NOTIFIKASI_USER = '$USER_KEY' ORDER BY READ_STATUS ASC,INPUT_DATE DESC LIMIT 15");

while ($rowNotif = $getNotif->fetch(PDO::FETCH_ASSOC)) {
    extract($rowNotif);
    if ($READ_STATUS == 0) {
        ?>
        <a href="#" data-toggle="modal" data-toggle="modal" class="media border-dotted open-ChangePassword" style="background-color: lavender;">
            <span class="media-body">
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
        <a href="javascript:void(0);" class="media read border-dotted">
            <span class="media-body">
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

?>