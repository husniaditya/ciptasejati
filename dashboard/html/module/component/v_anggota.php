<?php
$USER_ID = $_SESSION["LOGINIDUS_WEDD"];


$GetGuest = GetQuery("select * from m_guest where GUEST_STATUS = '0' order by GUEST_NAME");
?>
<h4>Filter Anggota</h4>
<form method="post" class="form filterGuest" id="filterGuest">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="GUEST_NAME">Nama</label>
                <input type="text" class="form-control" id="filterGUEST_NAME" name="GUEST_NAME" value="" placeholder="Input Nama">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="GUEST_PHONE">KTP</label>
                <input type="text" class="form-control" id="filterGUEST_PHONE" name="GUEST_PHONE" value="" placeholder="Input Nomor HP">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="GUEST_PHONE">No HP</label>
                <input type="text" class="form-control" id="filterGUEST_PHONE" name="GUEST_PHONE" value="" placeholder="Input Nomor HP">
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="GUEST_PHONE">Alamat</label>
                <input type="text" class="form-control" id="filterGUEST_PHONE" name="GUEST_PHONE" value="" placeholder="Input Nomor HP">
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="GUEST_PHONE">Sabuk / Gelar</label>
                <select id="selectize-select" name="SABUK" class="form-control" placeholder="Pilih Sabuk...">
                    <option value="1">Pilih Sabuk...</option>
                    <option value="2">Putih - Pra Pemula</option>
                    <option value="3">Kuning - Pemula</option>
                    <option value="4">Hijau - Dasar-I</option>
                    <option value="5">Biru - Dasar-II</option>
                    <option value="6">Coklat - Asisten Pelatih</option>
                    <option value="7">Hitam-I - Pelatih Muda</option>
                    <option value="8">Hitam-II - Pelatih Madya</option>
                    <option value="9">Hitam-III - Pelatih Utama</option>
                    <option value="10">Hitam-IV - Guru Muda Junior</option>
                    <option value="11">Merah-I - Guru Muda</option>
                    <option value="12">Merah-II - Guru Madya</option>
                    <option value="13">Merah-III - Guru Utama</option>
                    <option value="14">Merah-IV - Guru Besar</option>
                    <option value="15">Pengurus - Pengurus</option>
                </select>
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="">Tanggal Join</label>
                <input type="text" class="form-control" id="datepicker1" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly/>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" align="center">
            <button type="button" id="reloadButton" onclick="clearForm()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Clear Filter</button>
        </div>
    </div>
</form>
<hr>
<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddAnggota btn btn-inverse btn-outline mb5 btn-rounded" href="#AddAnggota"><i class="ico-plus2"></i> Tambah Data</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Anggota</h3>
            </div>
            <table class="table table-striped table-bordered" id="anggota-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">ID Anggota</th>
                        <th>Cabang </th>
                        <th>Sabuk / Gelar </th>
                        <th>KTP</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Pekerjaan</th>
                        <th>L / P</th>
                        <th>TTL</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Tgl Bergabung</th>
                        <th>Tgl Resign</th>
                    </tr>
                </thead>
                <tbody id="guestdata">
                    <!-- Use a loop to generate 100 rows -->
                    <script>
                        for (let i = 1; i <= 100; i++) {
                            // Example data generation (you can replace this with your own logic)
                            const cabangData = ["Banjarmasin", "Sampit", "Banjarbaru", "Palangkaraya", "Kuala Kapuas", "Pangkalan Bun", "Ketapang", "Sintang", "Samarinda", "Balikpapan", "Bontang", "Tarakan", "Tanjung Selor", "Tanjung Redeb", "Tanjung Balai", "Tanjung Pinang", "Batam", "Medan", "Pekanbaru", "Padang", "Palembang", "Jambi", "Bengkulu", "Bandar Lampung", "Jakarta", "Bandung", "Semarang", "Yogyakarta", "Surabaya", "Denpasar", "Mataram", "Kupang", "Pontianak", "Manado", "Gorontalo", "Makassar", "Palu", "Kendari", "Ambon", "Jayapura", "Sorong", "Merauke", "Timika", "Biak", "Manokwari", "Ternate", "Tidore", "Tual", "Lhokseumawe", "Banda Aceh", "Padang Sidempuan", "Pematangsiantar", "Tebing Tinggi", "Binjai", "Tanjung Balai", "Gunungsitoli", "Sibolga", "Medan", "Pekanbaru", "Dumai", "Bukittinggi", "Padang", "Pariaman", "Payakumbuh", "Sawahlunto", "Solok", "Lubuklinggau", "Pagar Alam", "Palembang", "Prabumulih", "Bandar Lampung", "Metro", "Bengkulu", "Jambi", "Sungai Penuh", "Bandung", "Banjar", "Bekasi", "Bogor", "Cimahi", "Cirebon", "Depok", "Sukabumi", "Tasikmalaya", "Jakarta", "Magelang", "Pekalongan", "Purwokerto", "Salatiga", "Semarang", "Surakarta", "Tegal", "Yogyakarta", "Batam", "Tanjung Pinang", "Bandar Lampung", ""];
                            const sabukData = ["Putih - Pra Pemula", "Kuning - Pemula", "Hijau - Dasar I", "Biru - Dasar II","Coklat - Ass. Pelatih","Hitam I - Pelatih Muda", "Hitam II - Pelatih Madya"];
                            const pekerjaanData = ["Guru", "Dokter", "Insinyur"];
                            const genderData = ["L", "P"];
                            const emailData = ["user1@example.com", "user2@example.com", "user3@example.com"];

                            const row = document.createElement("tr");

                            row.innerHTML = `
                                <td align="center">
                                    <form id="eventoption-form" method="post" class="form">
                                        <div class="btn-group" style="margin-bottom:5px;">
                                            <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a data-toggle="modal" href="#ViewAnggota" class="open-ViewAnggota" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                                <li><a data-toggle="modal" href="#EditAnggota" class="open-EditAnggota" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </form>
                                </td>
                                <td class="hidden">${i}</td>
                                <td>${cabangData[Math.floor(Math.random() * cabangData.length)]}</td>
                                <td>${sabukData[Math.floor(Math.random() * sabukData.length)]}</td>
                                <td>${Math.floor(Math.random() * 1000000000)}</td>
                                <td>Nama ${Math.floor(Math.random() * 1000)}</td>
                                <td>Alamat ${Math.floor(Math.random() * 100)}</td>
                                <td>${pekerjaanData[Math.floor(Math.random() * pekerjaanData.length)]}</td>
                                <td>${genderData[Math.floor(Math.random() * genderData.length)]}</td>
                                <td>Tempat, ${Math.floor(Math.random() * 100)}/${Math.floor(Math.random() * 12)}/${Math.floor(Math.random() * 50)}</td>
                                <td>+1 ${Math.floor(Math.random() * 1000000000)}</td>
                                <td>${emailData[Math.floor(Math.random() * emailData.length)]}</td>
                                <td>${Math.floor(Math.random() * 31)}/${Math.floor(Math.random() * 12)}/${Math.floor(Math.random() * 50)}</td>
                                <td>${Math.floor(Math.random() * 31)}/${Math.floor(Math.random() * 12)}/${Math.floor(Math.random() * 50)}</td>
                            `;

                            document.querySelector("tbody").appendChild(row);
                        }
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="AddAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddAnggota-form" method="post" class="form" data-parsley-validate>
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
                                <label for="GUEST_NAME">ID Anggota<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="ANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Inputkan ID keanggotaan" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Tanggal Bergabung</label>
                                <input type="text" class="form-control" id="datepicker1" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly data-parsley-required/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">KTP</label>
                                <input type="text" class="form-control" id="GUEST_RELATION" name="GUEST_RELATION" value="" placeholder="Inputkan no KTP" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Nama</label>
                                <input type="text" class="form-control" id="GUEST_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Jenis Kelamin</label>
                                <select id="selectize-select" name="ANGGOTA_CABANG" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required>
                                    <option value="">Pilih Jenis Kelamin...</option>
                                    <option value="2">Pria</option>
                                    <option value="3">Wanita</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Pekerjaan</label>
                                <input type="text" class="form-control" id="GUEST_RELATION" name="GUEST_RELATION" value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Alamat</label>
                                <textarea type="text" rows="4" class="form-control" id="GUEST_ADDRESS" name="GUEST_ADDRESS" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">No HP</label>
                                <input type="text" class="form-control" id="GUEST_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Tempat Lahir</label>
                                <input type="text" class="form-control" id="GUEST_RELATION" name="GUEST_RELATION" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Tanggal Lahir</label>
                                <input type="text" class="form-control" id="datepicker1" name="ANGGOTA_JOIN" placeholder="Pilih tanggal" readonly data-parsley-required/>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Email</label>
                                <input type="email" class="form-control" id="EMAIL" name="ANGGOTA_JOIN" data-parsley-required/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">Upload Foto </label><br>
                                <div>
                                    <input type="file" name="GROOMBRIDE_PHOTO" id="GROOMBRIDE_PHOTO" accept="image/*"/> <span><i style="font-size: 10px;color: red;"><strong>NOTE : MAX SIZE 1MB / FILE</strong></i></span><br/>
                                </div>
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

<div id="ViewAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewAnggota-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat Data Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_NAME">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="ViewAnggota_NAME" name="GUEST_NAME" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Phone Number</label>
                                <input type="text" class="form-control" id="ViewAnggota_PHONE" name="GUEST_PHONE" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Relation</label>
                                <input type="text" class="form-control" id="ViewAnggota_RELATION" name="GUEST_RELATION" readonly value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Address</label>
                                <textarea type="text" rows="4" class="form-control" id="ViewAnggota_ADDRESS" name="GUEST_ADDRESS" readonly value=""></textarea>
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

<div id="EditAnggota" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditAnggota-form" method="post" class="form" data-parsley-validate>
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
                                <label for="GUEST_NAME">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editGUEST_NAME" name="GUEST_NAME" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_PHONE">Phone Number</label>
                                <input type="text" class="form-control" id="editGUEST_PHONE" name="GUEST_PHONE" value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_RELATION">Relation</label>
                                <input type="text" class="form-control" id="editGUEST_RELATION" name="GUEST_RELATION" value="">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="GUEST_ADDRESS">Address</label>
                                <textarea type="text" rows="4" class="form-control" id="editGUEST_ADDRESS" name="GUEST_ADDRESS" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="EditAnggota" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>