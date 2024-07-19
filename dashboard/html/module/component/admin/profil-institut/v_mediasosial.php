<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getMedia = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END MEDIA_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_mediasosial c
LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID");

$rowMedia = $getMedia->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddMedia btn btn-inverse btn-outline mb5 btn-rounded" href="#AddMedia"><i class="ico-plus2"></i> Tambah Media Sosial</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Media Sosial</h3>
            </div>
            <table class="table table-striped table-bordered" id="mediasosial-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Media ID</th>
                        <th>Media Sosial</th>
                        <th>Link </th>
                        <th>Status </th>
                        <th>Input Oleh </th>
                        <th>Input Tanggal </th>
                    </tr>
                </thead>
                <tbody id="mediadata">
                    <?php
                    foreach ($rowMedia as $dataMedia) {
                        extract($dataMedia);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= $MEDIA_ID; ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#EditMedia" class="open-EditMedia" style="color:cornflowerblue;" data-id="<?= $MEDIA_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletemedia('<?= $MEDIA_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $MEDIA_ID; ?></td>
                            <td><i class="<?= $MEDIA_ICON; ?>"></i> <?= $MEDIA_DESKRIPSI; ?></td>
                            <td><a href="<?= $MEDIA_LINK; ?>" target="_blank"><?= $MEDIA_LINK; ?></td>
                            <td><?= $MEDIA_STATUS; ?></td>
                            <td><?= $INPUT_BY; ?></td>
                            <td><?= $INPUT_DATE; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="AddMedia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddMedia-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Media Sosial</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MEDIA_DESKRIPSI">Nama Media Sosial<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="MEDIA_DESKRIPSI" name="MEDIA_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MEDIA_ICON">Icon Media Sosial</label>
                                <input type="text" class="form-control" required id="MEDIA_ICON" name="MEDIA_ICON" value="">
                                <small style="color:gray">Untuk referensi icon bisa klik <a href="https://fontawesome.com/search?o=r&m=free&f=brands" target="_blank">di sini</a></small>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="MEDIA_LINK">URL / Link<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="MEDIA_LINK" name="MEDIA_LINK" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savemedia" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditMedia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditMedia-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Media Sosial</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 hidden">
                            <div class="form-group">
                                <label for="MEDIA_ID">.</label>
                                <input type="text" class="form-control" required id="editMEDIA_ID" name="MEDIA_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MEDIA_DESKRIPSI">Nama Media Sosial<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editMEDIA_DESKRIPSI" name="MEDIA_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="MEDIA_ICON">Icon Media Sosial</label>
                                <input type="text" class="form-control" required id="editMEDIA_ICON" name="MEDIA_ICON" value="">
                                <small style="color:gray">Untuk referensi icon bisa klik <a href="https://fontawesome.com/search?o=r&m=free&f=brands" target="_blank">di sini</a></small>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="MEDIA_LINK">URL / Link<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editMEDIA_LINK" name="MEDIA_LINK" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updatemedia" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>