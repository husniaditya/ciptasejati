<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getWarna = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END WLAMBANG_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_warnalambang v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID
WHERE v.WLAMBANG_KATEGORI = 'Arti Warna'");

$getLambang = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END WLAMBANG_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_warnalambang v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID
WHERE v.WLAMBANG_KATEGORI = 'Arti Lambang'");

$getMakna = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END WLAMBANG_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_warnalambang v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID
WHERE v.WLAMBANG_KATEGORI = 'Makna Keseluruhan'");

$rowWarna = $getWarna->fetchAll(PDO::FETCH_ASSOC);
$rowLambang = $getLambang->fetchAll(PDO::FETCH_ASSOC);
$rowMakna = $getMakna->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-2">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddWarnaLambang"><i class="ico-plus2"></i> Tambah Data Warna, Lambang, Makna</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- tab -->
<ul class="nav nav-pills nav-justified">
    <li class="active"><a href="#tabLambang" data-toggle="tab">Arti Lambang</a></li>
    <li><a href="#tabWarna" data-toggle="tab">Arti Warna</a></li>
    <li><a href="#tabMakna" data-toggle="tab">Makna Keseluruhan</a></li>
</ul>
<!--/ tab -->
<!-- tab content -->
<div class="tab-content panel">
    <div class="tab-pane active" id="tabLambang">
        <!-- START row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="demo">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabel Arti Lambang</h3>
                    </div>
                    <table class="table table-striped table-bordered" id="lambang-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deskripsi </th>
                                <th>Status </th>
                                <th>Input Oleh </th>
                                <th>Input Tanggal </th>
                            </tr>
                        </thead>
                        <tbody id="lambangdata">
                            <?php
                            foreach ($rowLambang as $dataLambang) {
                                extract($dataLambang);
                                ?>
                                <tr>
                                    <td align="center">
                                        <form id="eventoption-form-<?= $WLAMBANG_ID; ?>" method="post" class="form">
                                            <div class="btn-group" style="margin-bottom:5px;">
                                                <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a data-toggle="modal" href="#EditWarnaLambang" class="open-EditWarnaLambang" data-id="<?= $WLAMBANG_ID; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Data</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#" onclick="deletewarnalambang('<?= $WLAMBANG_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                    <td><?= $WLAMBANG_DESKRIPSI; ?></td>
                                    <td><?= $WLAMBANG_STATUS; ?></td>
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
    </div>
    <div class="tab-pane" id="tabWarna">
        <!-- START row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="demo">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabel Arti Warna</h3>
                    </div>
                    <table class="table table-striped table-bordered" id="warna-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deskripsi </th>
                                <th>Status </th>
                                <th>Input Oleh </th>
                                <th>Input Tanggal </th>
                            </tr>
                        </thead>
                        <tbody id="warnadata">
                            <?php
                            foreach ($rowWarna as $dataWarna) {
                                extract($dataWarna);
                                ?>
                                <tr>
                                    <td align="center">
                                        <form id="eventoption-form-<?= $WLAMBANG_ID; ?>" method="post" class="form">
                                            <div class="btn-group" style="margin-bottom:5px;">
                                                <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a data-toggle="modal" href="#EditWarnaLambang" class="open-EditWarnaLambang" style="color:cornflowerblue;" data-id="<?= $WLAMBANG_ID; ?>"><span class="ico-edit"></span> Ubah Data</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#" onclick="deletewarnalambang('<?= $WLAMBANG_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                    <td><?= $WLAMBANG_DESKRIPSI; ?></td>
                                    <td><?= $WLAMBANG_STATUS; ?></td>
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
    </div>
    <div class="tab-pane" id="tabMakna">
        <!-- START row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="demo">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabel Makna Keseluruhan</h3>
                    </div>
                    <table class="table table-striped table-bordered" id="makna-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deskripsi </th>
                                <th>Status </th>
                                <th>Input Oleh </th>
                                <th>Input Tanggal </th>
                            </tr>
                        </thead>
                        <tbody id="maknadata">
                            <?php
                            foreach ($rowMakna as $dataMakna) {
                                extract($dataMakna);
                                ?>
                                <tr>
                                    <td align="center">
                                        <form id="eventoption-form-<?= $WLAMBANG_ID; ?>" method="post" class="form">
                                            <div class="btn-group" style="margin-bottom:5px;">
                                                <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a data-toggle="modal" href="#EditWarnaLambang" class="open-EditWarnaLambang" style="color:cornflowerblue;" data-id="<?= $WLAMBANG_ID; ?>"><span class="ico-edit"></span> Ubah Data</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#" onclick="deletewarnalambang('<?= $WLAMBANG_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                    <td><?= $WLAMBANG_DESKRIPSI; ?></td>
                                    <td><?= $WLAMBANG_STATUS; ?></td>
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
    </div>
</div>
<!--/ tab content -->
<br><br>
<!--/ END row -->

<div id="AddWarnaLambang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddWarnaLambang-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Visi / Misi</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="WLAMBANG_KATEGORI">Kategori<span class="text-danger">*</span></label>
                                <select id="WLAMBANG_KATEGORI" name="WLAMBANG_KATEGORI" class="form-control" placeholder="Pilih Kategori..." data-parsley-required required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Arti Lambang">Arti Lambang</option>
                                    <option value="Arti Warna">Arti Warna</option>
                                    <option value="Makna Keseluruhan">Makna Keseluruhan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="WLAMBANG_DESKRIPSI">Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="WLAMBANG_DESKRIPSI" name="WLAMBANG_DESKRIPSI" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="savewarnalambang" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditWarnaLambang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditWarnaLambang-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Logo</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 hidden">
                            <div class="form-group">
                                <label for="WLAMbANG_ID">.</label>
                                <input type="text" class="form-control" id="editWLAMBANG_ID" name="WLAMBANG_ID" value="" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="WLAMBANG_KATEGORI">Kategori<span class="text-danger">*</span></label>
                                <select id="editWLAMBANG_KATEGORI" name="WLAMBANG_KATEGORI" class="form-control" placeholder="Pilih Kategori..." data-parsley-required required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Arti Lambang">Arti Lambang</option>
                                    <option value="Arti Warna">Arti Warna</option>
                                    <option value="Makna Keseluruhan">Makna Keseluruhan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="editWLAMBANG_DESKRIPSI">Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="editWLAMBANG_DESKRIPSI" name="WLAMBANG_DESKRIPSI" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editwarnalambang" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
