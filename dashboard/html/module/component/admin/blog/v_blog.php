<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getBlog = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END BLOG_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_blog c
LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID AND a.ANGGOTA_STATUS = 0 AND a.DELETION_STATUS = 0");
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-2">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddBlog btn btn-inverse btn-outline mb5 btn-rounded" href="#AddBlog"><i class="ico-plus2"></i> Tambah Data Blog</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Halaman Blog</h3>
            </div>
            <table class="table table-striped table-bordered" id="blog-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Gambar</th>
                        <th>Judul Berita </th>
                        <th>Deskripsi </th>
                        <th>Status </th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="blogdata">
                    <?php
                    while ($rowBlog = $getBlog->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowBlog);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= $BLOG_ID; ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewBlog" class="open-ViewBlog" style="color:#222222;" data-id="<?= $BLOG_ID; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditBlog" class="open-EditBlog" style="color:#00a5d2;" data-id="<?= $BLOG_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deleteblog('<?= $BLOG_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center"><img src="<?= $BLOG_IMAGE; ?>" alt="" style="text-align: center;overflow: hidden;position: relative;width: 100px;height: 100px;"></td>
                            <td><?= $BLOG_TITLE; ?></td>
                            <td><?= substr_replace($BLOG_MESSAGE, '...', 100); ?></td>
                            <td align="center"><?= $BLOG_STATUS; ?></td>
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

<div id="AddBlog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddBlog-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Blog</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BLOG_TITLE">Judul Blog<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="BLOG_TITLE" name="BLOG_TITLE" value="" data-parsley-required/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="BLOG_MESSAGE">Deskripsi<span class="text-danger">*</span></label>
                                <textarea rows="5" class="form-control" id="BLOG_MESSAGE" name="BLOG_MESSAGE" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BLOG_IMAGE">Upload Gambar</label>
                                <input type="file" name="BLOG_IMAGE[]" id="BLOG_IMAGE" accept="image/*" onchange="previewImage(this);" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Preview -->
                        <div class="col-md-12">
                            <div id="preview-container" class="text-center">
                                <img class="img-bordered" id="preview-image" src="" alt="" style="text-align: center;overflow: hidden;position: relative;height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="saveblog" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditBlog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditBlog-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Data Blog</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 hidden">
                            <div class="form-group">
                                <label for="BLOG_ID">.<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editBLOG_ID" name="BLOG_ID" value="" data-parsley-required readonly/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BLOG_TITLE">Judul Blog<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editBLOG_TITLE" name="BLOG_TITLE" value="" data-parsley-required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DELETION_STATUS">Status<span class="text-danger">*</span></label>
                                <select id="editDELETION_STATUS" name="DELETION_STATUS" class="form-control" placeholder="Pilih Status..." data-parsley-required required>
                                    <option value="">Pilih Status...</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="BLOG_MESSAGE">Deskripsi<span class="text-danger">*</span></label>
                                <textarea rows="5" class="form-control" id="editBLOG_MESSAGE" name="BLOG_MESSAGE" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BLOG_IMAGE">Gambar</label>
                                <input type="file" name="BLOG_IMAGE[]" id="editBLOG_IMAGE" accept="image/*" onchange="previewImageedit(this);" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Preview -->
                        <div class="col-md-12">
                            <div id="preview-container-edit" class="text-center">
                                <img class="img-bordered" id="preview-image-edit" src="" alt="" style="text-align: center;overflow: hidden;position: relative;height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updateblog" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewBlog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewBlog-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Blog</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="BLOG_TITLE">Judul Blog</label>
                                <input type="text" class="form-control" id="viewBLOG_TITLE" name="BLOG_TITLE" value="" readonly/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="BLOG_MESSAGE">Deskripsi</label>
                                <textarea rows="5" class="form-control" id="viewBLOG_MESSAGE" name="BLOG_MESSAGE" value="" readonly></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <!-- Logo Preview -->
                        <div class="col-md-12">
                            <label for="BLOG_IMAGE">Upload Gambar</label>
                            <div id="preview-container" class="text-center">
                                <img class="img-bordered" id="viewBLOG_IMAGE" src="" alt="" style="text-align: center;overflow: hidden;position: relative;height: 200px;">
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