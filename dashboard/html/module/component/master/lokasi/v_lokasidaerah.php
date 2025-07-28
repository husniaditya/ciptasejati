<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$PPARAM = "Daerah";
$params = ['GET', $PPARAM] + array_fill(0, 10, '');
$getDaerah = GetQueryParam("zsp_m_daerah", $params);

$PPARAM = "Pusat";
$params = ['GET', $PPARAM] + array_fill(0, 10, '');
$getPusat = GetQueryParam("zsp_m_daerah", $params);
?>

<?php
if ($_SESSION["ADD_LokasiDaerah"] == "Y") {
    ?>
    <!-- START row -->
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddDaerah btn btn-inverse btn-outline mb5 btn-rounded" href="#AddDaerah"><i class="ico-plus2"></i> Tambah Lokasi Daerah</a>
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
                <h3 class="panel-title">Tabel Lokasi Daerah</h3>
            </div>
            <table class="table table-striped table-bordered" id="lokasidaerah-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Daerah ID</th>
                        <th>Pusat</th>
                        <th>Nama Daerah </th>
                        <th>Status </th>
                        <th>Input Oleh </th>
                        <th>Input Tanggal </th>
                    </tr>
                </thead>
                <tbody id="daerahdata">
                    <?php
                    foreach ($getDaerah as $rowDaerah) {
                        extract($rowDaerah);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php
                                            if ($_SESSION["VIEW_LokasiDaerah"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#ViewDaerah" data-key=<?=$DAERAH_KEY;?> data-id="<?=$DAERAH_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-pusatid="<?=$PUSAT_KEY;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-ViewDaerah" style="color:#222222;"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["EDIT_LokasiDaerah"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#EditDaerah" data-key=<?=$DAERAH_KEY;?> data-id="<?=$DAERAH_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-pusatid="<?=$PUSAT_KEY;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-EditDaerah" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["DELETE_LokasiDaerah"] == "Y") {
                                                ?>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="deletedaerah('<?= $DAERAH_KEY;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $DAERAH_ID; ?></td>
                            <td><?= $PUSAT_DESKRIPSI; ?></td>
                            <td><?= $DAERAH_DESKRIPSI; ?></td>
                            <td><?= $DAERAH_STATUS; ?></td>
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

<div id="AddDaerah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddDaerah-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pusat<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="PUSAT_KEY" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Pusat --</option>
                                        <?php
                                        foreach ($getPusat as $rowPusat) {
                                            extract($rowPusat);
                                            ?>
                                            <option value="<?= $PUSAT_KEY; ?>"><?= $PUSAT_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="DAERAH_ID" name="DAERAH_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="DAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savedaerah" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewDaerah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewDaerah-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pusat<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewPUSAT_KEY" name="PUSAT_KEY" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewDAERAH_ID" name="DAERAH_ID" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewDAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewDELETION_STATUS" name="DELETION_STATUS" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="EditDaerah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditDaerah-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Edit Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editDAERAH_KEY" name="DAERAH_KEY" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pusat<span class="text-danger">*</span></label>
                                <select name="PUSAT_KEY" id="editPUSAT_KEY" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Pusat --</option>
                                    <?php
                                    foreach ($getPusat as $EditPusat) {
                                        extract($EditPusat);
                                        ?>
                                        <option value="<?= $PUSAT_KEY; ?>"><?= $PUSAT_DESKRIPSI; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editDAERAH_ID" name="DAERAH_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editDAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select id="editDELETION_STATUS" name="DELETION_STATUS" class="form-control" placeholder="" data-parsley-required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="editdaerah" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>