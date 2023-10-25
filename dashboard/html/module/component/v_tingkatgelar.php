<?php

?>
<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddTingkatGelar btn btn-inverse btn-outline mb5 btn-rounded" href="#AddTingkatGelar"><i class="ico-plus2"></i> Tambah Data Tingkatan</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Tingkatan dan Gelar</h3>
            </div>
            <table class="table table-striped table-bordered" id="tingkatgelar-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">Gelar ID</th>
                        <th>Sabuk Tingkatan</th>
                        <th>Gelar</th>
                        <th>Tingkatan</th>
                    </tr>
                </thead>
                <tbody id="countdowndata">
                    <script>
                        // Function to generate random data for the table
                        function generateRandomData() {
                            for (let i = 1; i <= 15; i++) {
                                let sabuk_id = `SBK-0923-000${i}`;
                                let sabuk_nama = `Color ${i}`;
                                let sabuk_deskripsi = `Description ${i}`;
                                let sabuk_tingkatan = Math.floor(Math.random() * 15) + 1;

                                const row = document.createElement("tr");

                                row.innerHTML = `
                                    <td align="center">
                                        <form id="eventoption-form" method="post" class="form">
                                            <div class="btn-group" style="margin-bottom:5px;">
                                                <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li><a data-toggle="modal" href="#ViewTingkatGelar" class="open-ViewTingkatGelar" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                                    <li><a data-toggle="modal" href="#EditTingkatGelar" class="open-EditTingkatGelar" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="#" onclick="confirmAndPost('<?= $EVENT_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                                </ul>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="hidden">${sabuk_id}</td>
                                    <td>${sabuk_nama}</td>
                                    <td>${sabuk_deskripsi}</td>
                                    <td>${sabuk_tingkatan}</td>
                                `;

                                document.querySelector("tbody").appendChild(row);
                            }
                        }

                        // Call the function to generate random data
                        generateRandomData();
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="AddTingkatGelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="addTingkatGelar-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Data Tingkatan & Gelar</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sabuk Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="COUNTDOWN_TEXT1" name="COUNTDOWN_TEXT1" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Gelar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="COUNTDOWN_TEXT2" name="COUNTDOWN_TEXT2" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan Level<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="COUNTDOWN_TEXT2" name="COUNTDOWN_TEXT2" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savecountdown" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewTingkatGelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="viewTingkatGelar-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat Data TIngkatan & Gelar</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sabuk Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewCOUNTDOWN_TEXT1" name="COUNTDOWN_TEXT1" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Gelar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewCOUNTDOWN_TEXT2" name="COUNTDOWN_TEXT2" readonly value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan Level<span class="text-danger">*</span></label></label>
                                <input type="text" class="form-control" id="viewCOUNTDOWN_DATE" name="COUNTDOWN_DATE" readonly value="" required data-parsley-required>
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

<div id="EditTingkatGelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="editTingkatGelar-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Edit Data Tingkat & Gelar</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Countdown ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editCOUNTDOWN_ID" name="COUNTDOWN_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Sabuk Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editCOUNTDOWN_TEXT1" name="COUNTDOWN_TEXT1" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Gelar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editCOUNTDOWN_TEXT2" name="COUNTDOWN_TEXT2" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan Level<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="COUNTDOWN_TEXT2" name="COUNTDOWN_TEXT2" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editTingkatGelar" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>