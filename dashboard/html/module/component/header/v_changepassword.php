
<div id="ChangePassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="changepassword-form" method="post" class="form form-horizontal form-striped" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Password</h3>
                </div>
                <div class="modal-body">
                    <!-- Form horizontal layout striped -->         
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="form-stack has-icon pull-left">
                                <label class="control-label col-sm-4">Password Lama<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <div class="form-stack has-icon pull-left">
                                        <input name="OLDPASSWORD" id="OLDPASSWORD" type="password" class="form-control" required data-parsley-required>
                                        <div class="input-group-append form-control-icon" onclick="togglePassword('OLDPASSWORD')">
                                            <span class="input-group-text toggle-password" >
                                                <i class="fas fa-lock fa-lg form-control-icon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Password Baru<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="form-stack has-icon pull-left">
                                    <input name="NEWPASSWORD" id="NEWPASSWORD" type="password" class="form-control checkpassword" required data-parsley-required>
                                    <div class="input-group-append form-control-icon" onclick="togglePassword('NEWPASSWORD')">
                                        <span class="input-group-text toggle-password">
                                            <i class="fas fa-lock fa-lg form-control-icon"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">Konfirmasi Password Baru<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="form-stack has-icon pull-left">
                                    <input name="CONFIRMPASSWORD" id="CONFIRMPASSWORD" type="password" class="form-control checkpassword" required data-parsley-required>
                                    <div class="input-group-append form-control-icon" onclick="togglePassword('CONFIRMPASSWORD')">
                                        <span class="input-group-text toggle-password">
                                            <i class="fas fa-lock fa-lg form-control-icon"></i>
                                        </span>
                                    </div>
                                </div>
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
