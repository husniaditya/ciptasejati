<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getMateri = GetQuery("SELECT m.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE
    FROM m_materi m
    LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID
    WHERE m.DELETION_STATUS = 0");
} else {
    $getMateri = GetQuery("SELECT m.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(m.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE FROM m_materi m
    LEFT JOIN m_cabang c ON m.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_anggota a ON m.INPUT_BY = a.ANGGOTA_ID
    WHERE m.DELETION_STATUS = 0 AND m.CABANG_KEY = '$USER_CABANG'");
}
$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI asc");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .dataTables_wrapper {
        width: 100%;
        overflow: auto;
    }
    table.dataTable {
        width: 100% !important;
    }
</style>

<div class="panel-group" id="accordion1">
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Materi UKT
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterMateriUKT" id="filterMateriUKT">
                    <div class="row">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <select name="DAERAH_KEY" id="selectize-select" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        foreach ($rowd as $filterDaerah) {
                                            extract($filterDaerah);
                                            ?>
                                            <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div> 
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <select name="CABANG_KEY" id="selectize-select2" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                    </select>
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Materi ID</label>
                                <input type="text" class="form-control" id="filterMATERI_ID" name="MATERI_ID" value="" placeholder="Inputkan ID">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" class="form-control" id="filterMATERI_DESKRIPSI" name="MATERI_DESKRIPSI" value="" placeholder="Inputkan Deskripsi">
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

<?php
if ($_SESSION["ADD_MateriUKT"] == "Y") {
    ?>
    <!-- START row -->
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddMateri btn btn-inverse btn-outline mb5 btn-rounded" href="#AddMateri"><i class="ico-plus2"></i> Tambah Data Materi UKT</a>
        </div>
    </div>
    <br>
    <!--/ END row -->
    <?php
}
?>

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Data Materi UKT</h3>
            </div>
            <table class="table table-striped table-bordered" id="materi-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID Materi</th>
                        <th>Daerah</th>
                        <th>Cabang</th>
                        <th>Deskripsi </th>
                        <th>Bobot</th>
                        <th>Input Oleh </th>
                        <th>Input Tanggal </th>
                    </tr>
                </thead>
                <tbody id="materidata">
                    <?php
                    while ($rowMateri = $getMateri->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowMateri);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php
                                            if ($_SESSION["VIEW_MateriUKT"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#ViewMateri" data-id="<?=$MATERI_ID;?>" class="open-ViewMateri" style="color:#222222;"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["EDIT_MateriUKT"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#EditMateri" data-id="<?=$MATERI_ID;?>" class="open-EditMateri" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["DELETE_MateriUKT"] == "Y") {
                                                ?>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="deletemateri('<?= $MATERI_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center"><?= $MATERI_ID; ?></td>
                            <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td><?= $MATERI_DESKRIPSI; ?></td>
                            <td align="center"><?= $MATERI_BOBOT; ?>%</td>
                            <td><?= $ANGGOTA_NAMA; ?></td>
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

<div id="AddMateri" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddMateri-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data Materi UKT</h3>
                </div>
                <div class="modal-body">
                    <?php
                    if ($USER_AKSES == "Administrator") {
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Daerah<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper" style="position: relative;">
                                        <select name="DAERAH_KEY" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Daerah --</option>
                                            <?php
                                            foreach ($rowd as $rowDaerah) {
                                                extract($rowDaerah);
                                                ?>
                                                <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cabang<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper2" style="position: relative;">
                                        <select name="CABANG_KEY" id="selectize-dropdown2" required class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="MATERI_DESKRIPSI" name="MATERI_DESKRIPSI" value="" required data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bobot (%)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="MATERI_BOBOT" name="MATERI_BOBOT" value="0" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <h4>Detail Rincian Materi UKT</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Detail Deskripsi</label>
                                <input type="text" class="form-control" id="DETAIL_DESKRIPSI" name="DETAIL_DESKRIPSI" value="">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Detail Bobot (%)</label>
                                <input type="text" class="form-control" id="DETAIL_BOBOT" name="DETAIL_BOBOT" value="0" data-parsley-type="number">
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="color: transparent;">.</span></label>
                                <button type="button" id="addtambahdetail" class="addtambahdetail submit btn btn-success btn-outline mb5 btn-rounded"><span class="ico-plus"></span> Tambah</button>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default" id="demo">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Tabel Detil Materi UKT</h3>
                                </div>
                                <table class="table table-striped table-bordered" id="detailmateri-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Deskripsi</th>
                                            <th>Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailmateridata">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savemateri" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewMateri" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewMateri-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Materi UKT</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah </label>
                                <input type="text" class="form-control" id="viewDAERAH_KEY" name="DAERAH_KEY" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang </label>
                                <input type="text" class="form-control" id="viewCABANG_KEY" name="CABANG_KEY" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Deskripsi </label>
                                <input type="text" class="form-control" id="viewMATERI_DESKRIPSI" name="MATERI_DESKRIPSI" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bobot (%)</label>
                                <input type="text" class="form-control" id="viewMATERI_BOBOT" name="MATERI_BOBOT" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Materi UKT</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default" id="demo">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Tabel Detil Materi UKT</h3>
                                </div>
                                <table class="table table-striped table-bordered" id="viewdetailmateri-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Deskripsi</th>
                                            <th>Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody id="viewdetailmateridata">
                                    </tbody>
                                </table>
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

<div id="EditMateri" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditMateri-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Data Materi UKT</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID </label>
                                <input type="text" class="form-control" required readonly id="editMATERI_ID" name="MATERI_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <?php
                    if ($USER_AKSES == "Administrator") {
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Daerah<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper3" style="position: relative;">
                                        <select name="DAERAH_KEY" id="selectize-dropdown3" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Daerah --</option>
                                            <?php
                                            foreach ($rowd as $rowDaerah) {
                                                extract($rowDaerah);
                                                ?>
                                                <option value="<?= $DAERAH_KEY; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cabang<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper4" style="position: relative;">
                                        <select name="CABANG_KEY" id="selectize-dropdown4" required class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editMATERI_DESKRIPSI" name="MATERI_DESKRIPSI" value="" required data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Total Bobot (%)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editMATERI_BOBOT" name="MATERI_BOBOT" value="" required data-parsley-required data-parsley-type="number">
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <h4>Detail Rincian Materi UKT</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Detail Deskripsi</label>
                                <input type="text" class="form-control" id="editDETAIL_DESKRIPSI" name="DETAIL_DESKRIPSI" value="">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Detail Bobot (%)</label>
                                <input type="text" class="form-control" id="editDETAIL_BOBOT" name="DETAIL_BOBOT" value="0" data-parsley-type="number">
                            </div> 
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label style="color: transparent;">.</span></label>
                                <button type="button" id="tambahdetail" class="tambahdetail submit btn btn-success btn-outline mb5 btn-rounded"><span class="ico-plus"></span> Tambah</button>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default" id="demo">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Tabel Detil Materi UKT</h3>
                                </div>
                                <table class="table table-striped table-bordered" id="editdetailmateri-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Deskripsi</th>
                                            <th>Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody id="editdetailmateridata">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updatemateri" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>