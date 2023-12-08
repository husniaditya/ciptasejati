<!-- START row -->
<div class="row">
        <!-- Left / Top Side -->
        <div class="col-lg-3">
            <!-- figure with progress -->
            <ul class="list-table">
                <li style="width:70px;">
                    <img class="img-circle img-bordered" src="../image/avatar/avatar7.jpg" alt="" width="65px">
                </li>
                <li class="text-left">
                    <h5 class="semibold ellipsis mt0"><?= $_SESSION["LOGINNAME_CS"];?></h5>
                    <div style="max-width:200px;">
                        <p class="text-muted clearfix nm">
                            <span class="pull-left">Coklat</span>
                            <span class="pull-right">Asisten Pelatih</span>
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
                        <h4 class="nm semibold">Sampit</h4>
                        <p class="nm text-muted">Cabang</p>
                    </div>
                </li>
                <li>
                    <div class="section">
                        <h4 class="nm semibold">10.300.000</h4>
                        <p class="nm text-muted">Saldo Kas</p>
                    </div>
                </li>
            </ul>
            <!--/ follower stats -->

            <hr><!-- horizontal line -->
            <!-- tab menu -->
            <ul class="list-group list-group-tabs">
                <li class="list-group-item active"><a href="#profile" data-toggle="tab"><i class="ico-user2 mr5"></i> Profil</a></li>
                <li class="list-group-item"><a href="#account" data-toggle="tab"><i class="ico-archive2 mr5"></i> Akun</a></li>
                <li class="list-group-item"><a href="#mutasi" data-toggle="tab"><i class="fa-solid fa-recycle"></i> Riwayat Mutasi</a></li>
                <li class="list-group-item"><a href="#idsertifikat" data-toggle="tab"><i class="fa-regular fa-address-card"></i> ID &amp; Sertifikat</a></li>
                <li class="list-group-item"><a href="#idsertifikat" data-toggle="tab"><i class="fa-solid fa-money-bill-transfer"></i> Mutasi Kas</a></li>
                <li class="list-group-item"><a href="#idsertifikat" data-toggle="tab"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat PPD</a></li>
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
                    <form class="panel form-horizontal form-bordered" name="form-profile">
                        <div class="panel-body pt0 pb0">
                            <div class="form-group header bgcolor-default">
                                <div class="col-md-12">
                                    <h4 class="semibold text-primary mt0 mb5">Profil</h4>
                                    <p class="text-default nm">This information appears on your public profile, search results, and beyond.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Photo</label>
                                <div class="col-sm-9">
                                    <div class="btn-group pr5">
                                        <img class="img-circle img-bordered" src="../image/avatar/avatar7.jpg" alt="" width="34px">
                                    </div>
                                    <div class="btn-group">
                                    <input type="file" name="SERTIFIKAT[]" id="SERTIFIKAT" accept="image/*" /> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="name" value="John Doe">
                                    <p class="help-block">Enter your real name.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal Bergabung</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="website" value="http://">
                                    <p class="help-block">Have a homepage or a blog? Put the address here.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cabang</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="location">
                                    <p class="help-block">Where in the world are you?</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tingkatan</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="website" value="http://">
                                    <p class="help-block">Have a homepage or a blog? Put the address here.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tempat / Tanggal lahir</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="website" value="http://">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="website" value="http://">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Alamat</label>
                                <div class="col-sm-6">
                                    <textarea type="text" rows="3" class="form-control" id="editIDSERTIFIKAT_DESKRIPSI" name="IDSERTIFIKAT_DESKRIPSI" value=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan Profil</button>
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
                                    <h4 class="semibold text-primary mt0 mb5">Akun</h4>
                                    <p class="text-default nm">Change your basic account and language settings.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ID Anggota</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="username" value="erich.reyes">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email">
                                    <p class="help-block">Email will not be publicly displayed. <a href="javascript:void(0);">Learn more.</a></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No Telpon</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email">
                                    <p class="help-block">Email will not be publicly displayed. <a href="javascript:void(0);">Learn more.</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan Akun</button>
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
                                    <p class="text-default nm">Change your basic account and language settings.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ID Anggota</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="username" value="erich.reyes">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email">
                                    <p class="help-block">Email will not be publicly displayed. <a href="javascript:void(0);">Learn more.</a></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No Telpon</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="email">
                                    <p class="help-block">Email will not be publicly displayed. <a href="javascript:void(0);">Learn more.</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--/ form mutasi -->
                </div>
                <!--/ tab-pane: mutasi -->
            </div>
            <!--/ END Tab-content -->
        </div>
        <!--/ Left / Bottom Side -->
    </div>
    <!--/ END row -->