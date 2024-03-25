<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getVisiMisi = GetQuery("SELECT v.*, CASE WHEN v.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END VISIMISI_STATUS, DATE_FORMAT(v.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM cms_visimisi v
LEFT JOIN m_anggota a ON v.INPUT_BY = a.ANGGOTA_ID");
?>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Detail Visi Misi</h3>
            </div>
            <table class="table table-striped table-bordered" id="cmsvisimisi-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Kategori </th>
                        <th>Gambar </th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="cmsvisimisidata">
                    <?php
                    while ($rowVisiMisi = $getVisiMisi->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowVisiMisi);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#EditVisiMisi" class="open-EditVisiMisi" data-id="<?= $CMS_VISIMISI_ID; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Data</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center"><?= $CMS_VISIMISI_ID; ?></td>
                            <td align="center"><?= $CMS_VISIMISI_KATEGORI; ?></td>
                            <td align="center"><img src="<?= $CMS_VISIMISI_PIC; ?>" alt="A sample image" width="500" height="350"></td>
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


<div id="EditVisiMisi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditVisiMisi-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Data Blog</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 hidden">
                            <div class="form-group">
                                <label for="CMS_VISIMISI_ID">.<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editCMS_VISIMISI_ID" name="CMS_VISIMISI_ID" value="" data-parsley-required readonly/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="CMS_VISIMISI_KATEGORI">Kategori Gambar</label>
                                <input type="text" class="form-control" id="editCMS_VISIMISI_KATEGORI" name="CMS_VISIMISI_KATEGORI" value="" data-parsley-required readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="CMS_VISIMISI_PIC">Gambar</label>
                                <input type="file" name="CMS_VISIMISI_PIC[]" id="editCMS_VISIMISI_PIC" accept="image/*" onchange="previewImageedit(this);" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Preview -->
                        <div class="col-md-12">
                            <div id="preview-container-edit" class="text-center">
                                <img class="img-bordered" id="preview-image-edit" src="" alt="" style="text-align: center;overflow: hidden;position: relative;height: 350px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="updatevisimisi" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

