<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <!-- Brand -->
        <div class="text-center" style="margin-bottom:20px;">
            <span class="logo-figure inverse"></span>
                <h4 class="semibold text-muted mt-5">CIPTA SEJATI INDONESIA</h4>
        </div>
        <!--/ Brand -->

        <!-- Register form -->
        <form class="panel" name="form-register" id="form-register" action="" data-parsley-validate>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">ID Anggota<span class="text-danger">*</span></label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ANGGOTA_ID" maxlength="16" placeholder="ID Anggota" data-parsley-required>
                        <i class="ico-user2 form-control-icon"></i>
                    </div>
                    <label class="control-label"><i id="cekAnggota" style="color: red;"></i></label>
                </div>
                <div class="form-group">
                    <label class="control-label">Nama Lengkap </label>
                    <input type="text" class="form-control" id="ANGGOTA_NAMA" name="ANGGOTA_NAMA" readonly>
                </div>
                <div class="form-group">
                    <label class="control-label">Tempat & Tanggal Lahir</label>
                    <input type="text" class="form-control" id="ANGGOTA_TTL" name="ANGGOTA_TTL" readonly>
                </div>
                <div class="form-group">
                    <label class="control-label">Ranting</label>
                    <input type="text" class="form-control" id="ANGGOTA_RANTING" name="ANGGOTA_RANTING" readonly>
                </div>
                <div class="form-group">
                    <label class="control-label">Cabang</label>
                    <input type="text" class="form-control" id="CABANG_KEY" name="CABANG_KEY" readonly>
                </div>
                <div class="form-group">
                    <label class="control-label">Daerah</label>
                    <input type="text" class="form-control" id="DAERAH_KEY" name="DAERAH_KEY" readonly>
                </div>
                <div class="form-group" id="passwordbaru">
                    <label class="control-label">Password<span class="text-danger">*</span></label>
                    <div class="has-icon pull-left input-group">
                        <input type="password" class="form-control checkpassword" name="NEWPASSWORD" id="NEWPASSWORD" placeholder="Password" data-parsley-required data-parsley-minlength="8" data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" data-parsley-trigger="keyup" data-parsley-error-message="Password must be at least 8 characters long and contain at least one symbol, one uppercase letter, and one number">
                        <div class="input-group-append form-control-icon" onclick="togglePassword('NEWPASSWORD')">
                            <span class="input-group-text toggle-password">
                                <i class="fas fa-lock fa-lg form-control-icon"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="konfirmasi">
                    <label class="control-label">Konfirmasi Password<span class="text-danger">*</span></label>
                    <div class="has-icon pull-left input-group">
                        <input type="password" class="form-control checkpassword" name="CONFIRMPASSWORD" id="CONFIRMPASSWORD" placeholder="Konfirmasi Password" data-parsley-required>
                        <div class="input-group-append form-control-icon" onclick="togglePassword('CONFIRMPASSWORD')">
                            <span class="input-group-text toggle-password">
                                <i class="fas fa-lock fa-lg form-control-icon"></i>
                            </span>
                        </div>
                    </div>
                    <div id="passwordcheck"></div><br>
                </div>
            </div>
            <hr class="nm">
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control" id="ANGGOTA_EMAIL" name="ANGGOTA_EMAIL" readonly>
                </div>
                <p class="semibold text-muted">Untuk konfirmasi aktivasi akun baru, kami akan mengirimkan kode aktivasi melaui email yang sudah terdaftar.</p>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-block btn-primary" id="savedaftaruser"><span class="semibold">Daftar</span></button>
                <button type="submit" class="btn btn-block btn-success" id="resendemail"><span class="semibold">Kirim ulang email verifikasi</span><span id="countdown" style="display:none;"></span></button>
            </div>
        </form>
        <!-- Register form -->

        <hr><!-- horizontal line -->

        <p class="text-center">
            <span class="text-muted">Sudah terdaftar user? <a class="semibold" href="index.php">Klik di sini untuk login</a></span>
        </p>
    </div>
</div>