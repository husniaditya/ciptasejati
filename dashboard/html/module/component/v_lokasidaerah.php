<?php
$USER_ID = $_SESSION["LOGINIDUS_WEDD"];


?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPusat"><i class="ico-plus2"></i> Tambah Lokasi Daerah</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Lokasi Daerah</h3>
            </div>
            <table class="table table-striped table-bordered" id="lokasidaerah-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Daerah ID</th>
                        <th>Lokasi </th>
                        <th>Status </th>
                        <th>Diinput Oleh </th>
                        <th>Diinput Tanggal </th>
                    </tr>
                </thead>
                <tbody id="guestdata">
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td>001.001</td>
                        <td>Kalimantan Selatan</td>
                        <td>Aktif</td>
                        <td>Husni Aditya</td>
                        <td>26-10-2023 16:47</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td>001.002</td>
                        <td>Jawa Timur</td>
                        <td>Aktif</td>
                        <td>Husni Aditya</td>
                        <td>26-10-2023 16:47</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td>001.003</td>
                        <td>Kalimantan Tengah</td>
                        <td>Aktif</td>
                        <td>Husni Aditya</td>
                        <td>26-10-2023 16:47</td>
                    </tr>
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td>001.004</td>
                        <td>Jawa Tengah</td>
                        <td>Aktif</td>
                        <td>Husni Aditya</td>
                        <td>26-10-2023 16:47</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="AddPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Banjarmasin" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="saveguest" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat Data Pusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Banjarmasin" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Edit Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Banjarmasin" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="EditPusat" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>