<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];


?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPusat"><i class="ico-plus2"></i> Tambah Media Sosial</a>
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
                <tbody id="guestdata">
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td>MED-102023-0001</td>
                        <td><i class="fab fa-facebook-f"></i> Facebook</td>
                        <td><a href="https://www.facebook.com/groups/834169006751695/" target="_blank">https://www.facebook.com/groups/834169006751695/</td>
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
                                        <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td>MED-102023-0002</td>
                        <td><i class="fab fa-instagram"></i> Instagram</td>
                        <td><a href="https://www.instagram.com/ciptasejati1996/" target="_blank">https://www.instagram.com/ciptasejati1996/</a></td>
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
                    <h3 class="semibold modal-title text-inverse">Tambah Media Sosial</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Nama Media Sosial<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Facebook" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Icon Media Sosial</label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="fab fa-facebook-f">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_PHONE">URL / Link<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="https://www.facebook.com/groups/834169006751695/" data-parsley-required>
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

<div id="EditPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Nama Media Sosial<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Facebook" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Icon Media Sosial</label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="fab fa-facebook-f">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_PHONE">URL / Link<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="https://www.facebook.com/groups/834169006751695/" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Status</label>
                                <select id="selectize-select" name="ANGGOTA_CABANG" class="form-control" data-parsley-required>
                                    <option value="2">Aktif</option>
                                    <option value="3">Tidak Aktif</option>
                                </select>
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