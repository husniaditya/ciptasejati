<style>
    /* Ensure the table takes up 100% width */
    #riwayatppd-table {
        width: 100%;
    }
</style>


<!-- START row -->
<div class="row">
        <!-- Left / Top Side -->
        <div class="col-lg-3">
            <!-- figure with progress -->
            <ul class="list-table">
                <li style="width:70px;">
                    <img class="img-circle img-bordered" id="ANGGOTA_PIC" src="" alt="" style="text-align: center;overflow: hidden;position: relative;width: 65px;height: 65px;border-radius: 50%;">
                </li>
                <li class="text-left">
                    <span class="semibold ellipsis mt0 pull-left"><?= $_SESSION["LOGINIDUS_CS"];?></span>
                    <span class="semibold ellipsis mt0 pull-left hidden" id="JANGGOTA_KEY"><?= $_SESSION["LOGINKEY_CS"];?></span>
                    <span class="semibold ellipsis mt0 pull-left hidden" id="JCABANG_KEY"><?= $_SESSION["LOGINCAB_CS"];?></span>
                    <span class="semibold ellipsis mt0 pull-left hidden" id="JANGGOTA_ID"><?= $_SESSION["LOGINIDUS_CS"];?></span>
                    <br>
                    <span class="semibold ellipsis mt0 pull-left"><?= $_SESSION["LOGINNAME_CS"];?></span>
                    <br>
                    <div style="max-width:200px;" class="mt10">
                        <p class="text-muted clearfix nm">
                            <span class="pull-left">Biru</span>
                            <span class="pull-right">Dasar-II</span>
                        </p>
                    </div>
                </li>
            </ul>
            <!--/ figure with progress -->

            <hr><!-- horizontal line -->

            <!-- follower stats -->
            <ul class="nav nav-section nav-justified mt15">
                <li>
                    <div class="section">
                        <h5 class="nm semibold" id="CABANG_DESKRIPSI2"></h5>
                        <p class="nm text-muted">Cabang</p>
                    </div>
                </li>
                <li>
                    <div class="section">
                        <h5 class="nm semibold" id="KAS_ANGGOTA"></h5>
                        <p class="nm text-muted">Saldo Kas</p>
                    </div>
                </li>
            </ul>
            <!--/ follower stats -->

            <hr><!-- horizontal line -->
            <!-- tab menu -->
            <ul class="list-group list-group-tabs">
                <li class="list-group-item active"><a href="#profile" data-toggle="tab"><i class="ico-user2 mr5"></i> Profil Pengguna</a></li>
                <li class="list-group-item"><a href="#account" data-toggle="tab"><i class="ico-archive2 mr5"></i> Informasi Keanggotaan</a></li>
                <li class="list-group-item mutasikas"><a href="#mutasikas" data-toggle="tab"><i class="fa-solid fa-money-bill-transfer"></i> Mutasi Kas</a></li>
                <li class="list-group-item riwayatppd"><a href="#riwayatppd" data-toggle="tab"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat PPD</a></li>
                <li class="list-group-item riwayatukt"><a href="#riwayatukt" data-toggle="tab"><i class="fa-regular fa-newspaper"></i> Riwayat UKT</a></li>
                <li class="list-group-item mutasi"><a href="#mutasi" data-toggle="tab"><i class="fa-solid fa-recycle"></i> Riwayat Mutasi</a></li>
            </ul>
            <!-- tab menu -->
        </div>
        <!--/ Left / Top Side -->

        <!-- Left / Bottom Side -->
        <div class="col-lg-9">
            <!-- START Tab-content -->
            <div class="tab-content">
                <!-- tab-pane: profile -->
                <div class="tab-pane active" id="profile">
                    <!-- form profile -->
                    <form method="post" class="panel form-horizontal form-bordered" name="form-profile" id="EditProfile-form"  data-parsley-validate>
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Profil Pengguna</h4>
                                    <p class="text-default nm">Informasi ini muncul di profil publik anda, hasil pencarian, dan lainnya.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Foto</label>
                                <div class="col-sm-9">
                                    <div class="btn-group pr5">
                                        <div id="preview-container">
                                            <img class="img-circle img-bordered" id="preview-image" src="" alt="" style="text-align: center;overflow: hidden;position: relative;width: 50px;height: 50px;">
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                    <input type="file" name="ANGGOTA_PIC[]" id="ANGGOTA_PIC" accept="image/*" onchange="previewImageedit(this);" /> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ANGGOTA_NAMA" id="ANGGOTA_NAMA" value="" data-parsley-required data-parsley-maxlength="50">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tempat &amp; Tanggal Lahir</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="ANGGOTA_TEMPAT_LAHIR" id="ANGGOTA_TEMPAT_LAHIR" value="" data-parsley-required>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="datepicker44" name="ANGGOTA_TANGGAL_LAHIR" value="" readonly data-parsley-required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Agama</label>
                                <div class="col-sm-5">
                                    <select id="ANGGOTA_AGAMA" name="ANGGOTA_AGAMA" class="form-control" placeholder="Pilih Agama..." data-parsley-required required>
                                        <option value="">Pilih Agama...</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Khonghucu">Khonghucu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jenis Kelamin</label>
                                <div class="col-sm-5">
                                <select id="ANGGOTA_KELAMIN" name="ANGGOTA_KELAMIN" class="form-control" placeholder="Pilih Jenis Kelamin..." data-parsley-required required>
                                    <option value="">Pilih Jenis Kelamin...</option>
                                    <option value="L">Pria</option>
                                    <option value="P">Wanita</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">KTP</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ANGGOTA_KTP" id="ANGGOTA_KTP" value="" readonly data-parsley-required>
                                    <p class="help-block">Hubungi koordinator untuk merubah data ini.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Alamat</label>
                                <div class="col-sm-5">
                                    <textarea type="text" rows="3" class="form-control" id="ANGGOTA_ALAMAT" name="ANGGOTA_ALAMAT" value="" data-parsley-required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pekerjaan</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="ANGGOTA_PEKERJAAN" id="ANGGOTA_PEKERJAAN" value="" data-parsley-required>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" name="submit" id="saveprofilakun" class="btn btn-primary btn-rounded btn-outline"><i class="fa-regular fa-floppy-disk"></i> Simpan Profil</button>
                        </div>
                    </form>
                    <!--/ form profile -->
                </div>
                <!--/ tab-pane: profile -->

                <!-- tab-pane: account -->
                <div class="tab-pane" id="account">
                    <!-- form account -->
                    <form class="panel form-horizontal form-bordered" name="form-account">
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Informasi Keanggotaan</h4>
                                    <p class="text-default nm">Informasi ini mengenai keanggotaan anda</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ID Anggota</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ANGGOTA_ID" id="ANGGOTA_ID" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tingkatan</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="TINGKATAN" id="TINGKATAN" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Ranting</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ANGGOTA_RANTING" id="ANGGOTA_RANTING" value="" readonly>
                                    <p class="help-block">Hubungi koordinator untuk merubah data ini.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cabang</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="CABANG_DESKRIPSI" id="CABANG_DESKRIPSI" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Daerah</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="DAERAH_DESKRIPSI" id="DAERAH_DESKRIPSI" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal Bergabung</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ANGGOTA_JOIN" id="ANGGOTA_JOIN" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal Resign</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ANGGOTA_RESIGN" id="ANGGOTA_RESIGN" value="" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ANGGOTA_EMAIL" id="ANGGOTA_EMAIL" readonly>
                                    <p class="help-block">Hubungi koordinator untuk merubah data ini.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No Telpon</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ANGGOTA_HP" id="ANGGOTA_HP" readonly>
                                    <p class="help-block">Hubungi koordinator untuk merubah data ini.</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                        </div>
                    </form>
                    <!--/ form account -->
                </div>
                <!--/ tab-pane: account -->

                <!-- tab-pane: mutasi -->
                <div class="tab-pane" id="mutasi">
                    <!-- form mutasi -->
                    <form class="panel form-horizontal form-bordered" name="form-account">
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Riwayat Mutasi</h4>
                                    <p class="text-default nm">Informasi mengenai data riwayat mutasi anda.</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default" id="demo">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Tabel Riwayat Mutasi</h3>
                                        </div>
                                        <table class="table table-striped table-bordered" id="riwayatmutasi-table">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>No Dokumen</th>
                                                    <th>Daerah Awal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>Cabang Awal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>Daerah Tujuan </th>
                                                    <th>Cabang Tujuan </th>
                                                    <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>Tanggal Efektif</th>
                                                    <th>Input Oleh</th>
                                                    <th>Input Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="riwayatmutasi">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--/ form mutasi -->
                </div>
                <!--/ tab-pane: mutasi -->

                <!-- tab-pane: Riwayat UKT -->
                <div class="tab-pane" id="riwayatukt">
                    <!-- form Riwayat UKT -->
                    <form class="panel form-horizontal form-bordered" name="form-account">
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Riwayat Uji Kenaikan Tingkat</h4>
                                    <p class="text-default nm">Informasi mengenai riwayat uji kenaikan tingkat anda.</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default" id="demo">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Tabel Riwayat UKT</h3>
                                        </div>
                                        <table class="table table-striped table-bordered" id="riwayatukt-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>No Dokumen</th>
                                                    <th>ID Anggota </th>
                                                    <th>Nama Anggota </th>
                                                    <th>Daerah </th>
                                                    <th>Cabang </th>
                                                    <th>Ranting </th>
                                                    <th>Tingkatan </th>
                                                    <th>Penyelenggara UKT </th>
                                                    <th>Tanggal UKT </th>
                                                    <th>Total Nilai </th>
                                                    <th>Predikat </th>
                                                    <th>Deskripsi </th>
                                                    <th>Input Oleh</th>
                                                    <th>Input Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="daftarriwayatukt">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--/ form Riwayat UKT -->
                </div>
                <!--/ tab-pane: Riwayat UKT -->

                <!-- tab-pane: Mutasi Kas -->
                <div class="tab-pane" id="mutasikas">
                    <!-- form Mutasi Kas -->
                    <form class="panel form-horizontal form-bordered" name="form-account">
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Mutasi Kas</h4>
                                    <p class="text-default nm">Informasi mengenai riwayat mutasi kas anda.</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default" id="demo">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Tabel Mutasi Kas</h3>
                                        </div>
                                        <table class="table table-striped table-bordered" id="mutasikas-table">
                                            <thead>
                                                <tr>
                                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>No Dokumen</th>
                                                    <th>Jenis</th>
                                                    <th>Tanggal</th>
                                                    <th>Kategori</th>
                                                    <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>Jumlah</th>
                                                    <th>Saldo &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th>Input Oleh</th>
                                                    <th>Input Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="riwayatkas">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--/ form Mutasi Kas -->
                </div>
                <!--/ tab-pane: Mutasi Kas -->

                <!-- tab-pane: Riwayat PPD -->
                <div class="tab-pane" id="riwayatppd">
                    <!-- form Riwayat PPD -->
                    <form class="panel form-horizontal form-bordered" name="form-account">
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Riwayat Pembukaan Pusat Daya</h4>
                                    <p class="text-default nm">Informasi mengenai riwayat pembukaan pusat daya anda.</p>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default" id="demo">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Tabel Riwayat PPD</h3>
                                        </div>
                                        <table class="table table-striped table-bordered" id="riwayatppd-table">
                                            <thead>
                                                <tr>
                                                    <th> </th>
                                                    <th>No Dokumen </th>
                                                    <th>ID Anggota </th>
                                                    <th>Nama Anggota </th>
                                                    <th>Daerah </th>
                                                    <th>Cabang </th>
                                                    <th>Jenis </th>
                                                    <th>Tingkatan </th>
                                                    <th>Tingkatan PPD </th>
                                                    <th>Cabang PPD</th>
                                                    <th>Tanggal</th>
                                                    <th>Deskripsi</th>
                                                    <th>Sertifikat</th>
                                                </tr>
                                            </thead>
                                            <tbody id="daftariwayatppd">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--/ form Riwayat PPD -->
                </div>
                <!--/ tab-pane: Riwayat PPD -->
            </div>
            <!--/ END Tab-content -->
        </div>
        <!--/ Left / Bottom Side -->
    </div>
    <!--/ END row -->