<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];


?>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Konten Header</h3>
            </div>
            <table class="table table-striped table-bordered" id="kontenheader-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">Header ID</th>
                        <th>Logo Institut</th>
                        <th>Telp </th>
                        <th>Media Sosial </th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="guestdata">
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-regular fa-image"></i></span> Ubah Logo</a></li>
                                        <li><a href="profil" style="color:cornflowerblue;"><i class="fa-solid fa-phone"></i> Ubah Telepon</a></li>
                                        <li><a href="mediasosial.php" ><i class="fa-solid fa-share-nodes"></i> Ubah Media Sosial</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td class="hidden">HDR-1023-0001</td>
                        <td style="text-align: center;"><img src="/../ciptasejati/img/demos/renewable-energy/logo.png" alt="A sample image" width="250" height="50"></td>
                        <td><i class="fa-solid fa-phone"></i> 800-123-4567</td>
                        <td style="text-align: center;">
                            <a href="https://www.facebook.com/groups/834169006751695/" target="_blank"><i class="ico-facebook"></i> Facebook</a> <br> 
                            <a href="https://www.instagram.com/ciptasejati1996/" target="_blank"><i class="ico-instagram"></i> Instagram</a></td>
                        <td>Husni Aditya</td>
                        <td>28-10-2023 11:37</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->


<div id="EditHeader" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditHeader-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Ubah Logo</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="File">Upload logo Header </label><br>
                                <div>
                                    <input type="file" name="GROOMBRIDE_PHOTO" id="GROOMBRIDE_PHOTO" accept="image/*"/> 
                                    <span><i style="font-size: 10px;color: red;"><strong>NOTE : MAX SIZE 1MB / FILE</strong></i></span><br/>
                                    <span><i style="font-size: 10px;color: red;"><strong>UKURAN : P: 600px / L: 113px</strong></i></span><br/>
                                </div>
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
