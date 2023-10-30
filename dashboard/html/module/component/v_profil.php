<?php
$USER_ID = $_SESSION["LOGINIDUS_WEDD"];


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
                        <th>Visi</th>
                        <th>Misi</th>
                        <th>Arti Lambang </th>
                        <th>Arti Warna</th>
                        <th>Makna Keseluruhan</th>
                    </tr>
                </thead>
                <tbody id="guestdata">
                    <tr>
                        <td align="center">
                            <form id="eventoption-form" method="post" class="form">
                                <div class="btn-group" style="margin-bottom:5px;">
                                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a data-toggle="modal" href="#EditHeader" class="open-EditHeader" style="color:forestgreen;"><i class="fa-regular fa-address-card"></i> Ubah profil</a></li>
                                    </ul>
                                </div>
                            </form>
                        </td>
                        <td class="hidden">INST-102023-0001</td>
                        <td style="text-align: center;"><img src="/../ciptasejati/img/demos/renewable-energy/generic/generic-1.png" alt="A sample image" width="100" height="100"></td>
                        <td>Cipta Sejati Indonesia</td>
                        <td><i class="fa-solid fa-phone"></i> 800-123-4567</td>
                        <td>“Cipta Sejati”, selanjutnya disebut dengan “CS” adalah Institut Seni Bela Diri Silat yang mengajarkan “tenaga dalam”, juga dikenal dengan istilah Prana atau Chi (Ki). Diantara manfaat terpenting bagi peserta latih adalah dapat meningkatkan dan memelihara kesehatan, pengobatan diri sendiri dan orang lain (bidang kesehatan). Kewaskitaan secara umum juga digunakan untuk mengatasi semua problem gangguan dalam kehidupan baik secara nyata (fisik) maupun non fisik (gaib). Namun keilmuan “CS” bersifat pasif (defensif), dalam arti dapat bekerja pada saat terdesak (darurat) saja, yaitu saat ingin membela diri. Tidak dapat digunakan untuk menyerang. Setiap siswa dibimbing secara moral, sehingga untuk dapat naik tingkat dituntut untuk mengamalkan dan mengembangkan ilmu “CS” dalam kebaikan. Nilai-nilai ajaran Islam menjadi landasan pendekatan, pemahaman dan pengembangan keilmuan “CS”. Setiap anggta “CS” sangat tidak dianjurkan berhubungan dengan makhluk astral (jin). Latihan yang dilakukan adalah senam, senam pernafasan, gerakan jurus silat, konsentrasi / pemusatan dan pemanfaatan tenaga dalam / prana.</td>
                        <td>Sebagai sarana dan prasarana silaturahmi dan mempererat tali persaudaraan, khususnya untuk melestarikan budaya pencak silat indonesia</td>
                        <td>Membuat generasi muda untuk mencintai budaya kita diantaranya pencak silat yang menjadi kebanggaan bangsa indonesia

                        Menciptakan karakter - karakter manusia yang ber akhlak mulia, bermental kuat dan mempunyai dasar - dasar etika yang baik

                        Mempunyai kedisiplinan dan kejujuran

                        Mencetak bibit - bibit pesilat yang baik dan handal</td>
                        <td>BINTANG LIMA melambangkan Ketuhanan Yang Maha Esa
                            RANTAI melambangkan persaudaraan
                            CAKRA melambangkan senjata bela diri
                            SEGI LIMA melambangkan Pancasila
                            TANGAN melambangkan kesungguhan hati dan berserah diri kepada Tuhan Yang Maha Esa
                            SABUK MERAH PUTIH melambangkan Nasionalisme
                            LINGKARAN melambangkan suatu wadah dalam hal ini adalah Institutt Seni Bela Diri Silat “CIPTA SEJATI”</td>
                        <td>BIRU melambangkan perdamaian
                            KUNING melambangkan cita-cita
                            PUTIH melambangkan Kesucian
                            MERAH melambangkan Keberanian
                            HITAM melambangkan Kejujuran
                            UNGU melambangkan Cinta Kasih</td>
                        <td>Dengan rahmad Tuhan Yang Maha Esa dan didorong oleh cita-cita suci yang luhur serta kejujuran, kita harus berani membela kebenaran demi mewujudkan perdamaian yang dilandasi persatuan, persaudaraan dan semangat nasionalisme serta sanggup menggembangkan kemampuan yang dimiliki dimana kemampuan itu berasal dari jiwa dan semangat yang bersumber dari dalam / spiritual.</td>
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
                                <textarea type="text" rows="5" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>“Cipta Sejati”, selanjutnya disebut dengan “CS” adalah Institut Seni Bela Diri Silat yang mengajarkan “tenaga dalam”, juga dikenal dengan istilah Prana atau Chi (Ki). Diantara manfaat terpenting bagi peserta latih adalah dapat meningkatkan dan memelihara kesehatan, pengobatan diri sendiri dan orang lain (bidang kesehatan). Kewaskitaan secara umum juga digunakan untuk mengatasi semua problem gangguan dalam kehidupan baik secara nyata (fisik) maupun non fisik (gaib). Namun keilmuan “CS” bersifat pasif (defensif), dalam arti dapat bekerja pada saat terdesak (darurat) saja, yaitu saat ingin membela diri. Tidak dapat digunakan untuk menyerang. Setiap siswa dibimbing secara moral, sehingga untuk dapat naik tingkat dituntut untuk mengamalkan dan mengembangkan ilmu “CS” dalam kebaikan. Nilai-nilai ajaran Islam menjadi landasan pendekatan, pemahaman dan pengembangan keilmuan “CS”. Setiap anggta “CS” sangat tidak dianjurkan berhubungan dengan makhluk astral (jin). Latihan yang dilakukan adalah senam, senam pernafasan, gerakan jurus silat, konsentrasi / pemusatan dan pemanfaatan tenaga dalam / prana.</textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Visi</label>
                                <textarea type="text" rows="4" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>Sebagai sarana dan prasarana silaturahmi dan mempererat tali persaudaraan, khususnya untuk melestarikan budaya pencak silat indonesia</textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Misi</label>
                                <textarea type="text" rows="4" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>Membuat generasi muda untuk mencintai budaya kita diantaranya pencak silat yang menjadi kebanggaan bangsa indonesia Menciptakan karakter - karakter manusia yang ber akhlak mulia, bermental kuat dan mempunyai dasar - dasar etika yang baik Mempunyai kedisiplinan dan kejujuran Mencetak bibit - bibit pesilat yang baik dan handal</textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Arti Lambang</label>
                                <textarea type="text" rows="4" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>BINTANG LIMA melambangkan Ketuhanan Yang Maha Esa
                                    RANTAI melambangkan persaudaraan
                                    CAKRA melambangkan senjata bela diri
                                    SEGI LIMA melambangkan Pancasila
                                    TANGAN melambangkan kesungguhan hati dan berserah diri kepada Tuhan Yang Maha Esa 
                                    SABUK MERAH PUTIH melambangkan Nasionalisme 
                                    LINGKARAN melambangkan suatu wadah dalam hal ini adalah Institutt Seni Bela Diri Silat “CIPTA SEJATI”</textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Arti Warna</label>
                                <textarea type="text" rows="4" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>BIRU melambangkan perdamaian 
                                    KUNING melambangkan cita-cita 
                                    PUTIH melambangkan Kesucian 
                                    MERAH melambangkan Keberanian 
                                    HITAM melambangkan Kejujuran 
                                    UNGU melambangkan Cinta Kasih</textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Makna Keseluruhan</label>
                                <textarea type="text" rows="5" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required>Dengan rahmad Tuhan Yang Maha Esa dan didorong oleh cita-cita suci yang luhur serta kejujuran, kita harus berani membela kebenaran demi mewujudkan perdamaian yang dilandasi persatuan, persaudaraan dan semangat nasionalisme serta sanggup menggembangkan kemampuan yang dimiliki dimana kemampuan itu berasal dari jiwa dan semangat yang bersumber dari dalam / spiritual.</textarea>
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
