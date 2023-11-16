<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];


?>

<!-- START row -->
<div class="row">
    <div class="col-lg-2">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPusat"><i class="ico-plus2"></i> Tambah Data</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- tab -->
<ul class="nav nav-pills nav-justified">
    <li class="active"><a href="#tabone2" data-toggle="tab">Arti Warna</a></li>
    <li><a href="#tabthree2" data-toggle="tab">Arti Lambang</a></li>
    <li><a href="#tabthree2" data-toggle="tab">Makna Keseluruhan</a></li>
</ul>
<!--/ tab -->
<!-- tab content -->
<div class="tab-content panel">
    <div class="tab-pane active" id="tabone2">
        <!-- START row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" id="demo">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabel Visi</h3>
                    </div>
                    <table class="table table-striped table-bordered" id="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="hidden">Institut ID</th>
                                <th>Deskripsi </th>
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
                                                <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-solid fa-check"></i> Ubah Visi</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td class="hidden">VISI-102023-0001</td>
                                <td>Sebagai sarana dan prasarana silaturahmi dan mempererat tali persaudaraan, khususnya untuk melestarikan budaya pencak silat indonesia</td>
                                <td>Aktif</td>
                                <td>Husni Aditya</td>
                                <td>11-11-2023 11:25</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tabthree2">
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
                                <th class="hidden">Institut ID</th>
                                <th>Deskripsi </th>
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
                                                <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-solid fa-check-double"></i> Ubah Misi</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td class="hidden">MISI-102023-0001</td>
                                <td>Membuat generasi muda untuk mencintai budaya kita diantaranya pencak silat yang menjadi kebanggaan bangsa indonesia</td>
                                <td>Aktif</td>
                                <td>Husni Aditya</td>
                                <td>11-11-2023 11:25</td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <form id="eventoption-form" method="post" class="form">
                                        <div class="btn-group" style="margin-bottom:5px;">
                                            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-solid fa-check-double"></i> Ubah Misi</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td class="hidden">MISI-102023-0002</td>
                                <td>Menciptakan karakter - karakter manusia yang ber akhlak mulia, bermental kuat dan mempunyai dasar - dasar etika yang baik</td>
                                <td>Aktif</td>
                                <td>Husni Aditya</td>
                                <td>11-11-2023 11:25</td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <form id="eventoption-form" method="post" class="form">
                                        <div class="btn-group" style="margin-bottom:5px;">
                                            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-solid fa-check-double"></i> Ubah Misi</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td class="hidden">MISI-102023-0003</td>
                                <td>Mempunyai kedisiplinan dan kejujuran</td>
                                <td>Aktif</td>
                                <td>Husni Aditya</td>
                                <td>11-11-2023 11:25</td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <form id="eventoption-form" method="post" class="form">
                                        <div class="btn-group" style="margin-bottom:5px;">
                                            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-solid fa-check-double"></i> Ubah Misi</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td class="hidden">MISI-102023-0004</td>
                                <td>Mencetak bibit - bibit pesilat yang baik dan handal</td>
                                <td>Aktif</td>
                                <td>Husni Aditya</td>
                                <td>11-11-2023 11:25</td>
                            </tr>
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


<div id="EditHeader" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditHeader-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Ubah Logo</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">Upload Logo </label><br>
                                <div>
                                    <input type="file" name="GROOMBRIDE_PHOTO" id="GROOMBRIDE_PHOTO" accept="image/*"/> <span><i style="font-size: 10px;color: red;"><strong>NOTE : MAX SIZE 1MB / FILE | UKURAN : 500 x 500 </strong></i></span><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Nama Institut</label>
                                <input type="email" class="form-control" id="EMAIL" name="ANGGOTA_JOIN" value="Cipta Sejati Indonesia" data-parsley-required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">No Telp<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan ID keanggotaan" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Sejarah</label>
                                <textarea type="text" rows="8" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>“Cipta Sejati”, selanjutnya disebut dengan “CS” adalah Institut Seni Bela Diri Silat yang mengajarkan “tenaga dalam”, juga dikenal dengan istilah Prana atau Chi (Ki). Diantara manfaat terpenting bagi peserta latih adalah dapat meningkatkan dan memelihara kesehatan, pengobatan diri sendiri dan orang lain (bidang kesehatan). Kewaskitaan secara umum juga digunakan untuk mengatasi semua problem gangguan dalam kehidupan baik secara nyata (fisik) maupun non fisik (gaib). Namun keilmuan “CS” bersifat pasif (defensif), dalam arti dapat bekerja pada saat terdesak (darurat) saja, yaitu saat ingin membela diri. Tidak dapat digunakan untuk menyerang. Setiap siswa dibimbing secara moral, sehingga untuk dapat naik tingkat dituntut untuk mengamalkan dan mengembangkan ilmu “CS” dalam kebaikan. Nilai-nilai ajaran Islam menjadi landasan pendekatan, pemahaman dan pengembangan keilmuan “CS”. Setiap anggta “CS” sangat tidak dianjurkan berhubungan dengan makhluk astral (jin). Latihan yang dilakukan adalah senam, senam pernafasan, gerakan jurus silat, konsentrasi / pemusatan dan pemanfaatan tenaga dalam / prana.</textarea>
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
