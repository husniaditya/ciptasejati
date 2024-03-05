<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getVisi = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END VISIMISI_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_visimisi v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID
WHERE v.VISIMISI_KATEGORI = 'Visi'");

$getMisi = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END VISIMISI_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_visimisi v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID
WHERE v.VISIMISI_KATEGORI = 'Misi'");

$rowVisi = $getVisi->fetchAll(PDO::FETCH_ASSOC);
$rowMisi = $getMisi->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-2">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddVisiMisi"><i class="ico-plus2"></i> Tambah Data Visi Misi</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- tab -->
<ul class="nav nav-pills nav-justified">
    <li class="active"><a href="#tabVisi" data-toggle="tab">Visi</a></li>
    <li><a href="#tabMisi" data-toggle="tab">Misi</a></li>
</ul>
<!--/ tab -->
<!-- tab content -->
<div class="tab-content panel">
    <div class="tab-pane active" id="tabVisi">
        <!-- START row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="demo">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabel Visi</h3>
                    </div>
                    <table class="table table-striped table-bordered" id="visi-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deskripsi </th>
                                <th>Status </th>
                                <th>Input Oleh </th>
                                <th>Input Tanggal </th>
                            </tr>
                        </thead>
                        <tbody id="visidata">
                            <?php
                            foreach ($rowVisi as $dataVisi) {
                                extract($dataVisi);
                                ?>
                                <tr>
                                    <td align="center">
                                        <form id="eventoption-form-<?= $VISIMISI_ID; ?>" method="post" class="form">
                                            <div class="btn-group" style="margin-bottom:5px;">
                                                <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a data-toggle="modal" href="#EditVisiMisi" class="open-EditVisiMisi" data-id="<?= $VISIMISI_ID; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Visi</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#" onclick="deletevisimisi('<?= $VISIMISI_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                    <td><?= $VISIMISI_DESKRIPSI; ?></td>
                                    <td><?= $VISIMISI_STATUS; ?></td>
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
    <div class="tab-pane" id="tabMisi">
        <!-- START row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="demo">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabel Misi</h3>
                    </div>
                    <table class="table table-striped table-bordered" id="misi-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Deskripsi </th>
                                <th>Status </th>
                                <th>Input Oleh </th>
                                <th>Input Tanggal </th>
                            </tr>
                        </thead>
                        <tbody id="misidata">
                            <?php
                            foreach ($rowMisi as $dataMisi) {
                                extract($dataMisi);
                                ?>
                                <tr>
                                    <td align="center">
                                        <form id="eventoption-form-<?= $VISIMISI_ID; ?>" method="post" class="form">
                                            <div class="btn-group" style="margin-bottom:5px;">
                                                <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a data-toggle="modal" href="#EditVisiMisi" class="open-EditVisiMisi" style="color:cornflowerblue;" data-id="<?= $VISIMISI_ID; ?>"><span class="ico-edit"></span> Ubah Misi</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#" onclick="deletevisimisi('<?= $VISIMISI_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                    <td><?= $VISIMISI_DESKRIPSI; ?></td>
                                    <td><?= $VISIMISI_STATUS; ?></td>
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

<div id="AddVisiMisi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddVisiMisi-form" method="post" class="form" data-parsley-validate>
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
                                <label for="VISIMISI_KATEGORI">Kategori<span class="text-danger">*</span></label>
                                <select id="VISIMISI_KATEGORI" name="VISIMISI_KATEGORI" class="form-control" placeholder="Pilih Kategori..." data-parsley-required required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Visi">Visi</option>
                                    <option value="Misi">Misi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="VISIMISI_DESKRIPSI">Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="VISIMISI_DESKRIPSI" name="VISIMISI_DESKRIPSI" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="savevisimisi" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditVisiMisi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditVisiMisi-form" method="post" class="form" data-parsley-validate>
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
                                <label for="VISIMISI_ID">.</label>
                                <input type="text" class="form-control" id="editVISIMISI_ID" name="VISIMISI_ID" value="" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VISIMISI_KATEGORI">Kategori<span class="text-danger">*</span></label>
                                <select id="editVISIMISI_KATEGORI" name="VISIMISI_KATEGORI" class="form-control" placeholder="Pilih Kategori..." data-parsley-required required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Visi">Visi</option>
                                    <option value="Misi">Misi</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="VISIMISI_DESKRIPSI">Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="editVISIMISI_DESKRIPSI" name="VISIMISI_DESKRIPSI" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editvisimisi" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
