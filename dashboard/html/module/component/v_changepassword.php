
<div id="ChangePassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="changepassword-form" method="post" class="form form-horizontal form-striped" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Ubah Password</h3>
                </div>
                <div class="modal-body">
                    <!-- Form horizontal layout striped -->         
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-sm-4">Password Lama<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="OLDPASSWORD" type="password" class="form-control" required data-parsley-required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Password Baru<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="NEWPASSWORD" id="NEWPASSWORD" type="password" class="form-control checkpassword" required data-parsley-required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Konfirmasi Password Baru<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="CONFIRMPASSWORD" id="CONFIRMPASSWORD" type="password" class="form-control checkpassword" required data-parsley-required>
                                <div id="passwordcheck"></div>
                            </div>
                        </div>
                    </div>
                    <!--/ Form horizontal layout striped -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="changepassword" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
