<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <!-- Brand -->
        <div class="text-center" style="margin-bottom:20px;">
            <span class="logo-figure inverse"></span>
                <h4 class="semibold text-muted mt-5">CIPTA SEJATI INDONESIA</h4>
        </div>
        <!--/ Brand -->

        <!-- Register form -->
        <form class="panel" name="form-register" action="">
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">ID Anggota</label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ID_ANGGOTA" data-parsley-required>
                        <i class="ico-user2 form-control-icon"></i>
                    </div>
                    <label class="control-label"><i id="cekAnggota" style="color: red;"></i></label>
                </div>
                <div class="form-group">
                    <label class="control-label">Nama Lengkap </label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ANGGOTA_NAMA" data-parsley-required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Tempat & Tanggal Lahir</label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ANGGOTA_TTL" data-parsley-required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Cabang</label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ANGGOTA_CABANG" data-parsley-required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Daerah</label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ANGGOTA_DAERAH" data-parsley-required readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <div class="has-icon pull-left">
                        <input type="password" class="form-control" name="ANGGOTA_PASSWORD" data-parsley-required>
                        <i class="ico-key2 form-control-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Konfirmasi Password</label>
                    <div class="has-icon pull-left">
                        <input type="password" class="form-control" name="retype-password" data-parsley-equalto="input[name=password]">
                        <i class="ico-asterisk form-control-icon"></i>
                    </div>
                    <span id="passwordError" style="color: red;"></span><br>
                </div>
            </div>
            <hr class="nm">
            <div class="panel-body">
                <p class="semibold text-muted">Untuk konfirmasi aktivasi akun baru, kami membutuhkan email dan nomor HP untuk mengirimkan kode aktivasi.</p>
                <div class="form-group">
                    <label class="control-label">No HP</label>
                    <div class="has-icon pull-left">
                        <input type="password" class="form-control" name="retype-password" data-parsley-equalto="input[name=password]">
                        <i class="ico-phone2 form-control-icon"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <div class="has-icon pull-left">
                        <input type="email" class="form-control" name="email" placeholder="you@mail.com">
                        <i class="ico-envelop form-control-icon"></i>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-block btn-primary"><span class="semibold">Daftar</span></button>
            </div>
        </form>
        <!-- Register form -->

        <hr><!-- horizontal line -->

        <p class="text-center">
            <span class="text-muted">Sudah terdaftar user? <a class="semibold" href="index">Klik di sini untuk login</a></span>
        </p>
    </div>
</div>