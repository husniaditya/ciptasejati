<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getUser = GetQuery("SELECT u.ANGGOTA_KEY,u.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a.ANGGOTA_RANTING,c.CABANG_DESKRIPSI,d.DAERAH_DESKRIPSI,a.ANGGOTA_AKSES, CASE WHEN u.USER_STATUS = 1 THEN 'Verifikasi' ELSE 'Aktif' END USER_STATUS, u.INPUT_BY, DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM m_user u
LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE a.DELETION_STATUS = 0");

$rowUser = $getUser->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Data User</h3>
            </div>
            <table class="table table-striped table-bordered" id="user-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID Anggota</th>
                        <th>Nama Anggota</th>
                        <th>Sabuk </th>
                        <th>Gelar </th>
                        <th>Ranting </th>
                        <th>Cabang </th>
                        <th>Daerah </th>
                        <th>Akses </th>
                        <th>Status User </th>
                        <th>Input By </th>
                        <th>Input Date </th>
                    </tr>
                </thead>
                <tbody id="userdata">
                    <?php
                    foreach ($rowUser as $dataUser) {
                        extract($dataUser);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= $ANGGOTA_KEY; ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#EditUser" class="open-EditUser" style="color:cornflowerblue;" data-id="<?= $ANGGOTA_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deleteuser('<?= $ANGGOTA_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $ANGGOTA_ID; ?></td>
                            <td><?= $ANGGOTA_NAMA; ?></td>
                            <td><?= $TINGKATAN_NAMA; ?></td>
                            <td><?= $TINGKATAN_SEBUTAN; ?></td>
                            <td><?= $ANGGOTA_RANTING; ?></td>
                            <td><?= $CABANG_DESKRIPSI; ?></td>
                            <td><?= $DAERAH_DESKRIPSI; ?></td>
                            <td><?= $ANGGOTA_AKSES; ?></td>
                            <td><?= $USER_STATUS; ?></td>
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

<div id="EditUser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditUser-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Data User</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="form-group">
                                <!-- PUT THE SAMPLE UPLOAD PHOTO HERE -->
                                <label>Foto Anggota </label><br>
                                <img id="preview-image-edit" src="" alt="Preview" style="width: 300px; height: 300px;text-align: center;overflow: hidden;position: relative; object-fit:contain" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="editDAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="text" class="form-control" id="editCABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Ranting</label></label>
                                    <input type="text" class="form-control" id="editANGGOTA_RANTING" name="ANGGOTA_RANTING" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan</label>
                                    <input type="text" class="form-control" id="editANGGOTA_TINGKATAN" name="ANGGOTA_RANTING" value="" readonly>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>ID Anggota</label>
                                    <input type="text" class="form-control" id="editANGGOTA_ID" name="ANGGOTA_ID" value="" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" id="editANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status User</label>
                                <input type="text" class="form-control" id="editUSER_STATUS" name="USER_STATUS" value="" readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Akses Anggota<span class="text-danger">*</span></label>
                                <select id="editANGGOTA_AKSES" name="ANGGOTA_AKSES" class="form-control" data-parsley-required required>
                                    <option value="User">User</option>
                                    <option value="Koordinator">Koordinator</option>
                                    <option value="Pengurus">Pengurus</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password Baru</label>
                                    <input type="text" class="form-control" id="editPASSWORD" name="USER_PASSWORD" value="">
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="updateuser" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>