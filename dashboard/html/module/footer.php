<!-- START Template Footer -->
<!-- START container-fluid -->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <!-- copyright -->
            <p class="nm text-muted">&copy; Copyright 2023. Institut Seni Bela Diri Silat CIPTA SEJATI.</p>
            <!--/ copyright -->
            <input type="text" class="form-control hidden" id="TOKEN" name="TOKEN" value="<?= $_SESSION["LOGINKEY_CS"]; ?>" readonly>
            <input type="text" class="form-control hidden" id="TOKENC" name="TOKENC" value="<?= $_SESSION["LOGINCAB_CS"]; ?>" readonly>
        </div>
    </div>
</div>
<!--/ END container-fluid -->
<!--/ END Template Footer -->