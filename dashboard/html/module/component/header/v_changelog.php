<?php
$GetVersi = GetQuery("SELECT c.*,DATE_FORMAT(c.CHANGELOG_DATE, '%d %M %Y') CHANGELOG_DATE,DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,a.ANGGOTA_NAMA INPUT_BY
FROM p_changelog c
LEFT JOIN m_anggota a ON c.CHANGELOG_BY = a.ANGGOTA_ID
ORDER BY c.CHANGELOG_DATE desc");
?>
<div id="ChangeLog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ChangeLog-form" class="form form-horizontal form-striped" action="" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Riwayat Log Perubahan Versi</h3>
                </div>
                <div class="modal-body">
                    <!-- Form horizontal layout striped -->         
                    <div class="panel-body">
                        <table class="table table-striped table-bordered" id="logversi-table">
                            <thead>
                                <tr>
                                    <th>Versi </th>
                                    <th>Deskripsi </th>
                                    <th>Tanggal Versi</th>
                                    <th>Input Oleh</th>
                                    <th>Input Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while($rowVersi = $GetVersi->fetch(PDO::FETCH_ASSOC)) {
                                    extract($rowVersi);
                                    ?>
                                    <tr>
                                        <td align="center"><b><?= $CHANGELOG_VERSI; ?></b></td>
                                        <td>
                                            <b><?= $CHANGELOG_HEADER; ?></b><br>
                                            <?php
                                            $DetailVersi = GetQuery("SELECT * FROM d_changelog WHERE CHANGELOG_ID = '$CHANGELOG_ID'");
                                            while($rowDetailVersi = $DetailVersi->fetch(PDO::FETCH_ASSOC)) {
                                                extract($rowDetailVersi);
                                                ?>
                                                <ul>
                                                    <li>
                                                        <?= $CHANGELOG_DESK; ?>
                                                    </li>
                                                </ul>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td align="center"><?= $CHANGELOG_DATE; ?></td>
                                        <td align="center"><?= $INPUT_BY; ?></td>
                                        <td align="center"><?= $INPUT_DATE; ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!--/ Form horizontal layout striped -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
