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
                </div>
            </div>
            <hr class="nm">
            <div class="panel-body">
                <p class="semibold text-muted">Untuk konfirmasi reset password akun, kami membutuhkan email dan nomor HP untuk mengirimkan kode aktivasi.</p>
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
                <button type="submit" class="btn btn-block btn-primary"><span class="semibold">Kirim Kode Aktivasi</span></button>
            </div>
        </form>
        <!-- Register form -->

        <hr><!-- horizontal line -->

        <p class="text-center">
            <span class="text-muted">Sudah terdaftar user? <a class="semibold" href="index.php">Klik di sini untuk login</a></span>
        </p>
    </div>
</div>