<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getGrup = GetQuery("SELECT * FROM m_menu WHERE LENGTH(MENU_ID) <= 1 ORDER BY MENU_ID");
$getAkses = GetQuery("select * from p_param where KATEGORI = 'USER_AKSES' ORDER BY TEXT");
$rowGrup = $getGrup->fetchAll(PDO::FETCH_ASSOC);
$rowakses = $getAkses->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }
    table.dataTable {
        width: 100% !important;
    }
    .icon-circle {
        display: inline-block;
        width: 40px;  /* Adjust the size as needed */
        height: 40px; /* Adjust the size as needed */
        border: 2px solid black; /* Circle outline color */
        border-radius: 50%; /* Makes it a circle */
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<!-- Filter Section -->
<div class="panel-group" id="accordion1">
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data menu
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterMenu" id="filterMenu">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Menu</label>
                                <input type="text" class="form-control" id="filterMENU_ID" name="MENU_ID" value="" placeholder="Input Nama Menu">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Grup Menu</label>
                                <div id="selectize-wrapper3" style="position: relative;">
                                    <select name="GRUP_MENU" id="selectize-dropdown3" required="" class="form-control" >
                                    <option value="">-- Tampilkan semua --</option>
                                        <?php
                                        foreach ($rowGrup as $dataGrup) {
                                            extract($dataGrup);
                                            ?>
                                            <option value="<?= $MENU_ID; ?>"> <?= $MENU_ID . '. ' .$MENU_NAMA; ?> </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Menu</label>
                                <input type="text" class="form-control" id="filterMENU_NAMA" name="MENU_NAMA" value="" placeholder="Input Nama Menu">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Akses User</label>
                                <select id="filterUSER_AKSES" name="USER_AKSES" class="form-control"  data-parsley-required required>
                                    <option value="">-- Tampilkan semua --</option>
                                    <?php
                                    foreach ($rowakses as $dataAkses) {
                                        extract($dataAkses);
                                        ?>
                                        <option value="<?= $TEXT; ?>"> <?= $TEXT; ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" align="center">
                            <button type="button" id="reloadButton" onclick="clearForm()" class="submit btn btn-teal btn-outline mb5 btn-rounded"><span class="ico-refresh"></span> Reset Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<hr>
<!-- End Filter Section -->
<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Menu Akses</h3>
            </div>
            <table class="table table-striped table-bordered" id="menuakses-table">
                <thead>
                    <tr>
                        <th>ID Menu</th>
                        <th>Nama Menu </th>
                        <th>Akses User </th>
                        <th>View</th>
                        <th>Add</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Approve</th>
                        <th>Print</th>
                        <th>Input Oleh</th>
                        <th>Input Date</th>
                    </tr>
                </thead>
                <tbody id="menuaksesdata">
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->


<div id="EditMenu" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditMenu-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Akses Menu</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="MENU_KEY">.</label>
                                <input type="text" class="form-control" id="MENU_KEY" name="MENU_KEY" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="MENU_ID">ID Menu</label>
                                <input type="text" class="form-control" id="MENU_ID" name="MENU_ID" value="" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="MENU_NAMA">Nama Menu</label>
                                <input type="text" class="form-control" id="MENU_NAMA" name="MENU_NAMA" value="" readonly/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="USER_AKSES">Akses User</label>
                                <input type="text" class="form-control" id="USER_AKSES" name="USER_AKSES" value="" readonly/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="VIEW">View<span class="text-danger">*</span></label>
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="VIEW" name="VIEW">
                                    <span class="switch"></span>
                                </label>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ADD">Add<span class="text-danger">*</span></label>
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="ADD" name="ADD">
                                    <span class="switch"></span>
                                </label>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="EDIT">Edit<span class="text-danger">*</span></label>
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="EDIT" name="EDIT">
                                    <span class="switch"></span>
                                </label>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="DELETE">Delete<span class="text-danger">*</span></label>
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="DELETE" name="DELETE">
                                    <span class="switch"></span>
                                </label>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="APPROVE">Approve<span class="text-danger">*</span></label>
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="APPROVE" name="APPROVE">
                                    <span class="switch"></span>
                                </label>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="PRINT">Print<span class="text-danger">*</span></label>
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="PRINT" name="PRINT">
                                    <span class="switch"></span>
                                </label>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updatemenu" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

