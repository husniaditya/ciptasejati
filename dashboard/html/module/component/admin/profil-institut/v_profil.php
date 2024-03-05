<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getProfil = GetQuery("select * from c_profil");
?>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Profil Institut</h3>
            </div>
            <table class="table table-striped table-bordered" id="profilinstitut-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">Institut ID</th>
                        <th>Logo</th>
                        <th>Nama Institut </th>
                        <th>Telp </th>
                        <th>Sejarah </th>
                    </tr>
                </thead>
                <tbody id="profildata">
                    <?php
                    while ($rowProfil = $getProfil->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowProfil);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#EditProfil" class="open-EditProfil" data-id="<?= $PROFIL_ID; ?>" style="color:forestgreen;"><i class="fa-regular fa-address-card"></i> Ubah profil</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td class="hidden"><?= $PROFIL_ID; ?></td>
                            <td align="center"><img src="<?= $PROFIL_LOGO; ?>" alt="A sample image" width="100" height="100"></td>
                            <td><?= $PROFIL_NAMA; ?></td>
                            <td align="center"><i class="fa-solid fa-phone"></i> <?= $PROFIL_TELP; ?></td>
                            <td><?= substr_replace($PROFIL_SEJARAH, '...', 200); ?></td>
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


<div id="EditProfil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditProfil-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Profil Institut</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 hidden">
                            <div class="form-group">
                                <label for="PROFIL_ID">Profil ID</label>
                                <input type="text" class="form-control" id="PROFIL_ID" name="PROFIL_ID" value="" readonly>
                            </div>
                        </div>
                        <!-- Logo Preview -->
                        <div class="col-md-1">
                            <div id="preview-container">
                                <img class="img-circle img-bordered" id="preview-image" src="" alt="" style="text-align: center;overflow: hidden;position: relative;width: 60px;height: 60px;">
                            </div>
                        </div>
                        <!-- Logo Input -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PROFIL_LOGO">Logo</label>
                                <input type="file" name="PROFIL_LOGO[]" id="PROFIL_LOGO" accept="image/*" onchange="previewImageedit(this);" />
                                <span><i style="font-size: 10px;color: red;"><strong>NOTE : MAX SIZE 1MB / FILE | UKURAN : 500 x 500 </strong></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PROFIL_NAMA">Nama Institut<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="PROFIL_NAMA" name="PROFIL_NAMA" value="" data-parsley-required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PROFIL_TELP">No Telp<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="PROFIL_TELP" name="PROFIL_TELP" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="PROFIL_SEJARAH">Sejarah<span class="text-danger">*</span></label>
                                <textarea rows="8" class="form-control" id="PROFIL_SEJARAH" name="PROFIL_SEJARAH" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="editprofil" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

