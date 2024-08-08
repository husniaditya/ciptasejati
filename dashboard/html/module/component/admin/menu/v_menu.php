<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getMenu = GetQuery("SELECT m.*,u.MENU_NAMA,a.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
FROM m_menuakses m
LEFT JOIN m_menu u ON m.MENU_ID = u.MENU_ID
LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID
ORDER BY m.USER_AKSES,m.MENU_ID");
$getAkses = GetQuery("select * from p_param where KATEGORI = 'USER_AKSES' ORDER BY TEXT");
$rowakses = $getAkses->fetchAll(PDO::FETCH_ASSOC);
?>

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
                        <th></th>
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
                    <?php
                    while ($rowMenu = $getMenu->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowMenu);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= $MENU_KEY; ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#EditMenu" class="open-EditMenu" data-id="<?= $MENU_KEY; ?>" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah Akses</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $MENU_ID; ?></td>
                            <td><?= $MENU_NAMA; ?></td>
                            <td><?= $USER_AKSES; ?></td>
                            <td align="center">
                                <?php
                                if ($VIEW == "Y") {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" checked disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                }
                                
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                if ($ADD == "Y") {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" checked disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                }
                                
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                if ($EDIT == "Y") {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" checked disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                }
                                
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                if ($DELETE == "Y") {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" checked disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                }
                                
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                if ($APPROVE == "Y") {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" checked disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                }
                                
                                ?>
                            </td>
                            <td align="center">
                                <?php
                                if ($PRINT == "Y") {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" checked disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                } else {
                                    ?>
                                    <label class="switch switch-primary">
                                        <input type="checkbox" disabled>
                                        <span class="switch"></span>
                                    </label>
                                    <?php
                                }
                                
                                ?>
                            </td>
                            <td><?= $INPUT_BY; ?></td>
                            <td><?= $INPUT_DATE; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
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

