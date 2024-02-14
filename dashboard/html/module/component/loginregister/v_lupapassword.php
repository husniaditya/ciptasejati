<div class="row">
    <div class="col-lg-4 col-lg-offset-4">
        <!-- Brand -->
        <div class="text-center" style="margin-bottom:20px;">
            <span class="logo-figure inverse"></span>
                <h4 class="semibold text-muted mt-5">CIPTA SEJATI INDONESIA</h4>
        </div>
        <!--/ Brand -->

        <!-- Reset form -->
        <form class="panel" name="form-reset" id="form-reset" action="" data-parsley-validate>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">ID Anggota<span class="text-danger">*</span></label>
                    <div class="has-icon pull-left">
                        <input type="text" class="form-control" name="ANGGOTA_ID" id="ANGGOTA_ID" maxlength="16" placeholder="Masukkan ID Anggota" data-parsley-required>
                        <i class="ico-user2 form-control-icon"></i>
                    </div>
                    <label class="control-label"><i id="cekAnggota" style="color: red;"></i></label>
                </div>
            </div>
            <hr class="nm">
            <div class="panel-body">
                <p class="semibold text-muted text-justify">Password sementara akan dikirimkan melalui email anda yang sudah terdaftar. Dimohon untuk segera mengganti password setelah login ke dalam web</p>
                <div class="form-group">
                    <label class="control-label">Email<span class="text-danger">*</span></label>
                    <div class="has-icon pull-left">
                        <input type="email" class="form-control" name="ANGGOTA_EMAIL" id="ANGGOTA_EMAIL" readonly  data-parsley-required>
                        <i class="ico-envelop form-control-icon"></i>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-block btn-primary" id="sendemail"><span class="semibold">Kirim Kode Reset Password</span><span id="countdown" style="display:none;"></span></button>
            </div>
        </form>
        <!-- Register form -->

        <hr><!-- horizontal line -->

        <p class="text-center">
            <span class="text-muted">Sudah terdaftar user? <a class="semibold" href="index.php">Klik di sini untuk login</a></span>
        </p>
    </div>
</div>