<?php

$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getInformasi = GetQuery("SELECT i.*,case when i.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END INFORMASI_STATUS,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(i.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM c_informasi i 
LEFT JOIN m_anggota a ON i.INPUT_BY = a.ANGGOTA_ID");

$getStatus = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'status'");
$rows = $getStatus->fetchAll(PDO::FETCH_ASSOC);


if ($_SESSION["ADD_BagianInformasi"] == "Y") {
    ?>
    <!-- START row -->
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddInformasi btn btn-inverse btn-outline mb5 btn-rounded" href="#AddInformasi"><i class="ico-plus2"></i> Tambah Data Informasi</a>
        </div>
    </div>
    <br>
    <!--/ END row -->
    <?php
}
?>


<style>
    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }
    table.dataTable {
        width: 100% !important;
    }
</style>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Informasi</h3>
            </div>
            <table class="table table-striped table-bordered" id="informasi-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Informasi ID</th>
                        <th>Informasi Kategori</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="informasidata">
                    <?php
                    while($rowInformasi = $getInformasi->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowInformasi);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewInformasi" class="open-ViewInformasi" style="color:#222222;" data-id="<?= $INFORMASI_ID; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditInformasi" class="open-EditInformasi" style="color:cornflowerblue;" data-id="<?= $INFORMASI_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deleteInformasi('<?= $INFORMASI_ID;?>','deleteinformasi')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center"><?= $INFORMASI_ID; ?></td>
                            <td align="center"><?= $INFORMASI_KATEGORI; ?></td>
                            <td align="center"><?= $INFORMASI_JUDUL; ?></td>
                            <td><?= $INFORMASI_DESKRIPSI; ?></td>
                            <td align="center"><?= $INFORMASI_STATUS; ?></td>
                            <td align="center"><?= $INPUT_BY; ?></td>
                            <td align="center"><?= $INPUT_DATE; ?></td>
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

<div id="AddInformasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddInformasi-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Informasi</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="INFORMASI_JUDUL" name="INFORMASI_JUDUL" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori<span class="text-danger">*</span></label>
                                <select name="INFORMASI_KATEGORI" id="INFORMASI_KATEGORI" required class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Utama">Utama</option>
                                    <option value="Tambahan">Tambahan</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="8" class="form-control" id="INFORMASI_DESKRIPSI" name="INFORMASI_DESKRIPSI" required data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="saveinformasi" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewInformasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewInformasi-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Informasi</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" class="form-control" id="viewINFORMASI_JUDUL" name="INFORMASI_JUDUL" readonly >
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <input type="text" class="form-control" id="viewINFORMASI_STATUS" name="INFORMASI_STATUS" readonly >
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea type="text" rows="8" class="form-control" id="viewINFORMASI_DESKRIPSI" name="INFORMASI_DESKRIPSI" readonly ></textarea>
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

<div id="EditInformasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditInformasi-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Data Kegiatan</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>id<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editINFORMASI_ID" name="INFORMASI_ID" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editINFORMASI_JUDUL" name="INFORMASI_JUDUL" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kategori<span class="text-danger">*</span></label>
                                <select name="INFORMASI_KATEGORI" id="editINFORMASI_KATEGORI" required class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Utama">Utama</option>
                                    <option value="Tambahan">Tambahan</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="8" class="form-control" id="editINFORMASI_DESKRIPSI" name="INFORMASI_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label>
                                <select name="DELETION_STATUS" id="editINFORMASI_STATUS" required class="form-control" data-parsley-required>
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
                    <button type="submit" name="submit" id="updateinformasi" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
