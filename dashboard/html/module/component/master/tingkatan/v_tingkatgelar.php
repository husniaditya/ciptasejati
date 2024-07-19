<?php

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getTingkatGelar = GetQuery("SELECT t.*,case when t.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END TINGKATAN_STATUS,a.ANGGOTA_NAMA,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE FROM m_tingkatan t LEFT JOIN m_anggota a ON t.INPUT_BY = a.ANGGOTA_ID where t.DELETION_STATUS = 0 order by t.TINGKATAN_LEVEL asc");


if ($_SESSION["ADD_TingkatandanGelar"] == "Y") {
    ?>
    <!-- START row -->
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddTingkatGelar btn btn-inverse btn-outline mb5 btn-rounded" href="#AddTingkatGelar"><i class="ico-plus2"></i> Tambah Data Tingkatan</a>
        </div>
    </div>
    <br>
    <!--/ END row -->
    <?php
}
?>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Tingkatan dan Gelar</h3>
            </div>
            <table class="table table-striped table-bordered" id="tingkatgelar-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">Gelar ID</th>
                        <th>Sabuk</th>
                        <th>Tingkatan</th>
                        <th>Gelar</th>
                        <th>Sertifikat</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="tingkatgelardata">
                    <?php
                    while($rowTingkat = $getTingkatGelar->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowTingkat);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php
                                            if ($_SESSION["VIEW_TingkatandanGelar"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#ViewTingkatGelar" class="open-ViewTingkatGelar" style="color:#222222;" data-nama="<?= $TINGKATAN_NAMA; ?>" data-sebutan="<?= $TINGKATAN_SEBUTAN; ?>" data-gelar="<?= $TINGKATAN_GELAR;?>" data-level="<?= $TINGKATAN_LEVEL; ?>" data-sertifikat="<?= $TINGKATAN_SERTIFIKAT; ?>" data-status="<?= $TINGKATAN_STATUS; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["EDIT_TingkatandanGelar"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#EditTingkatGelar" class="open-EditTingkatGelar" style="color:cornflowerblue;" data-id="<?= $TINGKATAN_ID; ?>" data-nama="<?= $TINGKATAN_NAMA; ?>" data-sebutan="<?= $TINGKATAN_SEBUTAN; ?>" data-gelar="<?= $TINGKATAN_GELAR;?>" data-level="<?= $TINGKATAN_LEVEL; ?>" data-sertifikat="<?= $TINGKATAN_SERTIFIKAT; ?>" data-status="<?= $DELETION_STATUS; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["DELETE_TingkatandanGelar"] == "Y") {
                                                ?>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="deleteTingkatan('<?= $TINGKATAN_ID;?>','deletetingkatan')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td class="hidden"><?= $TINGKATAN_ID; ?></td>
                            <td><?= $TINGKATAN_NAMA; ?></td>
                            <td><?= $TINGKATAN_SEBUTAN; ?></td>
                            <td align="center"><?= $TINGKATAN_GELAR; ?></td>
                            <td align="center"><?= $TINGKATAN_SERTIFIKAT; ?></td>
                            <td align="center"><?= $TINGKATAN_LEVEL; ?></td>
                            <td align="center"><?= $TINGKATAN_STATUS; ?></td>
                            <td><?= $ANGGOTA_NAMA; ?></td>
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

<div id="AddTingkatGelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddTingkatGelar-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Tingkatan & Gelar</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sabuk<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="TINGKATAN_NAMA" name="TINGKATAN_NAMA" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="TINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gelar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="TINGKATAN_GELAR" name="TINGKATAN_GELAR" value="" required data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tingkatan Level<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="TINGKATAN_LEVEL" name="TINGKATAN_LEVEL" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sertifikat<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="TINGKATAN_SERTIFIKAT" name="TINGKATAN_SERTIFIKAT" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savetingkatan" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewTingkatGelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewTingkatGelar-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data TIngkatan & Gelar</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sabuk<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewTINGKATAN_NAMA" name="TINGKATAN_NAMA" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gelar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewTINGKATAN_GELAR" name="TINGKATAN_GELAR" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tingkatan Level<span class="text-danger">*</span></label></label>
                                <input type="text" class="form-control" id="viewTINGKATAN_LEVEL" name="TINGKATAN_LEVEL" readonly value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sertifikat<span class="text-danger">*</span></label></label>
                                <input type="text" class="form-control" id="viewTINGKATAN_SERTIFIKAT" name="TINGKATAN_SERTIFIKAT" readonly value="" required data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label></label>
                                <input type="text" class="form-control" id="viewTINGKATAN_STATUS" name="DELETION_STATUS" readonly value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditTingkatGelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditTingkatGelar-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Data Tingkat & Gelar</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tingkatan ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editTINGKATAN_ID" name="TINGKATAN_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sabuk<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editTINGKATAN_NAMA" name="TINGKATAN_NAMA" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTINGKATAN_SEBUTAN" name="TINGKATAN_SEBUTAN" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gelar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTINGKATAN_GELAR" name="TINGKATAN_GELAR" value="" required data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tingkatan Level<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTINGKATAN_LEVEL" name="TINGKATAN_LEVEL" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sertifikat<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTINGKATAN_SERTIFIKAT" name="TINGKATAN_SERTIFIKAT" value="" required data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label>
                                <select name="DELETION_STATUS" id="editTINGKATAN_STATUS" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Tidak Aktif</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="edittingkatan" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
