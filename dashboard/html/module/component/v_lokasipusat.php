<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];


?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPusat"><i class="ico-plus2"></i> Tambah Lokasi Pusat</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Lokasi Pusat</h3>
            </div>
            <table class="table table-striped table-bordered" id="lokasipusat-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">Pusat ID</th>
                        <th>Lokasi </th>
                        <th>Alamat </th>
                        <th>Kepengurusan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Map</th>
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
                        <td hidden>001</td>
                        <td>Banjarmasin</td>
                        <td>Jln Pembangunan Ujung Rt 34 No, 30 Banjarmasin, Kalimantan Selatan</td>
                        <td></td>
                        <td>-3.3063120100780785</td>
                        <td>114.56921894055016</td>
                        <td>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d497.89721672377954!2d114.56894083663221!3d-3.306165162787288!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sus!4v1698379031246!5m2!1sen!2sus" 
                                width="400" 
                                height="250" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </td>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Banjarmasin" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>Jln Pembangunan Ujung Rt 34 No, 30 Banjarmasin, Kalimantan Selatan</textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="ViewPusat_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698203909709!5m2!1sen!2sid" width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="-3.3063120100780785" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="114.56921894055016" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Google Maps</label>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698204837772!5m2!1sen!2sid" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
                                <label for="GUEST_NAME">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="ViewPusat_NAME" name="GUEST_NAME" value="Banjarmasin" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required readonly>Jln Pembangunan Ujung Rt 34 No, 30 Banjarmasin, Kalimantan Selatan</textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="ViewPusat_ADDRESS" name="GUEST_ADDRESS" readonly value=""><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698203909709!5m2!1sen!2sid" width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="ViewPusat_NAME" name="GUEST_NAME" value="-3.3063120100780785" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" readonly value="114.56921894055016">
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Google Maps</label>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698204837772!5m2!1sen!2sid" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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
                    <div class="row hidden">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="GUEST_ID">Guest ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editGUEST_ID" name="GUEST_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="Banjarmasin" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>Jln Pembangunan Ujung Rt 34 No, 30 Banjarmasin, Kalimantan Selatan</textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="ViewPusat_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698203909709!5m2!1sen!2sid" width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ViewPusat_NAME" name="GUEST_NAME" value="-3.3063120100780785" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ViewPusat_RELATION" name="GUEST_RELATION" value="114.56921894055016" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Google Maps</label>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.177112189075!2d114.56663797386034!3d-3.306319941168371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2de4224cfc2f6dc7%3A0x6bf4b37319f90a83!2sSekretariat%20Bela%20Diri%20Silat%20CIPTA%20SEJATI!5e0!3m2!1sen!2sid!4v1698204837772!5m2!1sen!2sid" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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