<?php
require_once("../../../../../module/connection/conn.php");

$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$TINGKATAN_ID = $_POST["tingkatan"];
if ($USER_AKSES == "Administrator") {
    $CABANG_KEY = $_POST["cabang"];
} else {
    $CABANG_KEY = $USER_CABANG;
}

$getMateri = GetQuery("SELECT CASE WHEN ROW_NUMBER() OVER (ORDER BY m.MATERI_ID) = 1 THEN 'active' ELSE '' END isActive,m.*,REPLACE(m.MATERI_DESKRIPSI,' ','') TabID FROM m_materi m WHERE m.CABANG_KEY = '$CABANG_KEY' AND m.TINGKATAN_ID = '$TINGKATAN_ID' AND m.DELETION_STATUS = 0");

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
            <li class="<?= $isActive; ?>"><a href="#<?= $TabID; ?>" data-toggle="tab" class="<?= $TabID; ?>"><?= $MATERI_DESKRIPSI; ?></a></li>
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
                <div class="tab-pane <?= $isActive; ?>" id="<?= $TabID; ?>">
                    <div class="panel-body pt0 pb0">
                        <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel <?= $MATERI_DESKRIPSI; ?></h3>
                            </div>
                            <table class="table table-striped table-bordered" id="<?= $TabID; ?>-table">
                                <thead>
                                    <tr>
                                        <th class="hidden"></th>
                                        <th class="hidden"></th>
                                        <th class="hidden"></th>
                                        <th>No. </th>
                                        <th>Materi </th>
                                        <th>Nilai </th>
                                        <th>Keterangan </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getDetail = GetQuery("SELECT ROW_NUMBER() OVER (ORDER BY d.MATERI_ID) AS row_num,m.MATERI_ID,d.*
                                    FROM m_materi_detail d
                                    LEFT JOIN m_materi m on m.MATERI_ID = d.MATERI_ID
                                    WHERE d.MATERI_ID = '$MATERI_ID' AND d.DELETION_STATUS = 0");
                                    while ($rowDetail = $getDetail->fetch(PDO::FETCH_ASSOC)) {
                                        extract($rowDetail);
                                        ?>
                                        <tr>
                                            <td class="hidden">
                                                <input type="text" class="form-control" id="_key_<?=uniqid();?>" name="_key[]" value="<?= $_key; ?>" readonly>
                                            </td>
                                            <td class="hidden">
                                                <input type="text" class="form-control" id="MATERI_ID_<?=uniqid();?>" name="MATERI_ID[]" value="<?= $MATERI_ID; ?>" readonly>
                                            </td>
                                            <td class="hidden">
                                                <input type="text" class="form-control" id="UKT_BOBOT_<?=uniqid();?>" name="UKT_BOBOT[]" value="<?= $DETAIL_BOBOT; ?>" readonly>
                                            </td>
                                            <td align="center"><?= $row_num; ?>.</td>
                                            <td><?= $DETAIL_DESKRIPSI; ?></td>
                                            <td>
                                                <select id="UKT_DETAIL_NILAI_<?=uniqid();?>" name="UKT_DETAIL_NILAI[]" class="form-control"  data-parsley-required required>
                                                    <?php
                                                    foreach ($rowN as $rowNilai) {
                                                        extract($rowNilai);
                                                        ?><option value="<?= $CODE; ?>"><?= $CODE; ?></option><?php
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                            <input type="text" class="form-control" id="UKT_DETAIL_REMARK_<?=uniqid();?>" name="UKT_DETAIL_REMARK[]" value="">
                                            </td>
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