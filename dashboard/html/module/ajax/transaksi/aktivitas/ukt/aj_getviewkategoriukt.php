<?php
require_once("../../../../../module/connection/conn.php");

$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$UKT_ID = $_POST["id"];

$getMateri = GetQuery("SELECT
    CASE
        WHEN rn = 1 THEN 'active'
        ELSE ''
    END AS isActive,
    MATERI_ID,
    MATERI_DESKRIPSI,
    REPLACE(MATERI_DESKRIPSI, ' ', '') AS TabID
FROM (
    SELECT
        MATERI_ID,
        MATERI_DESKRIPSI,
        ROW_NUMBER() OVER (ORDER BY MATERI_ID) AS rn
    FROM (
        SELECT DISTINCT
            d.MATERI_ID,
            m.MATERI_DESKRIPSI
        FROM m_materi m
        LEFT JOIN t_ukt_detail d ON m.MATERI_ID = d.MATERI_ID
        WHERE d.UKT_ID = '$UKT_ID'
    ) AS distinct_data
) TableDistinct");

$getNilai = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'UKT_NILAI' ORDER BY CODE DESC");

$rowM = $getMateri->fetchAll(PDO::FETCH_ASSOC);
$rowN = $getNilai->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills nav-justified">
            <?php
            foreach ($rowM as $rowMateri) {
                extract($rowMateri);
            ?>
            <li class="<?= $isActive; ?>"><a href="#view<?= $TabID; ?>" data-toggle="tab" class="<?= $TabID; ?>"><?= $MATERI_DESKRIPSI; ?></a></li>
            <?php
            }
            ?>
        </ul>
        <br>
        <!--/ tab -->
        <!-- tab content -->
        <div class="tab-content">
            <?php
            foreach ($rowM as $detailMateri) {
                extract($detailMateri);
                ?>
                <div class="tab-pane <?= $isActive; ?>" id="view<?= $TabID; ?>">
                    <div class="panel-body pt0 pb0">
                        <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel <?= $MATERI_DESKRIPSI; ?></h3>
                            </div>
                            <table class="table table-striped table-bordered" id="view<?= $TabID; ?>-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Materi </th>
                                        <th>Nilai </th>
                                        <th>Keterangan </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getDetail = GetQuery("SELECT ROW_NUMBER() OVER (ORDER BY d.MATERI_ID) AS row_num,d.DETAIL_DESKRIPSI,ukd.* 
                                    FROM m_materi_detail d
                                    LEFT JOIN t_ukt_detail ukd ON ukd.UKT_DETAIL = d._key
                                    WHERE ukd.UKT_ID = '$UKT_ID' AND ukd.MATERI_ID = '$MATERI_ID'");
                                    while ($rowDetail = $getDetail->fetch(PDO::FETCH_ASSOC)) {
                                        extract($rowDetail);
                                        ?>
                                        <tr>
                                            <td align="center"><?= $row_num; ?>.</td>
                                            <td><?= $DETAIL_DESKRIPSI; ?></td>
                                            <td align="center"><?= $UKT_DETAIL_NILAI; ?></td>
                                            <td><?= $UKT_DETAIL_REMARK; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                }
            ?>
        </div>
    </div>
</div>
<?php
?>