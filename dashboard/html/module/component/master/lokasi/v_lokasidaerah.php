<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getDaerah = GetQuery("select d.*,p.PUSAT_DESKRIPSI,a.ANGGOTA_NAMA,case when d.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END DAERAH_STATUS,DATE_FORMAT(d.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE from m_daerah d left join m_pusat p on d.PUSAT_ID = p.PUSAT_ID left join m_anggota a on d.INPUT_BY = a.ANGGOTA_ID order by d.PUSAT_ID asc");

$getPusat = GetQuery("select * from m_pusat where DELETION_STATUS = 0");
// Fetch all rows into an array
$rows = $getPusat->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddDaerah btn btn-inverse btn-outline mb5 btn-rounded" href="#AddDaerah"><i class="ico-plus2"></i> Tambah Lokasi Daerah</a>
    </div>
</div>
<br>
<!--/ END row -->

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
                    while ($rowDaerah = $getDaerah->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowDaerah);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= $DAERAH_ID; ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewDaerah" data-id="<?=$DAERAH_ID;?>" data-pusatid="<?=$PUSAT_ID;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-ViewDaerah" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditDaerah" data-id="<?=$DAERAH_ID;?>" data-pusatid="<?=$PUSAT_ID;?>" data-pusatdes="<?=$PUSAT_DESKRIPSI;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-daerahstatus="<?=$DAERAH_STATUS;?>" class="open-EditDaerah" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletedaerah('<?= $DAERAH_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
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
                    <h3 class="semibold modal-title text-success">Tambah Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Pusat<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="PUSAT_ID" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Pusat --</option>
                                        <?php
                                        foreach ($rows as $rowPusat) {
                                            extract($rowPusat);
                                            ?>
                                            <option value="<?= $PUSAT_ID; ?>"><?= $PUSAT_DESKRIPSI; ?></option>
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
                                <label for="">Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="DAERAH_ID" name="DAERAH_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="DAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savedaerah" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
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
                    <h3 class="semibold modal-title text-success">Lihat Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Pusat<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewPUSAT_ID" name="PUSAT_ID" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewDAERAH_ID" name="DAERAH_ID" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewDAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewDELETION_STATUS" name="DELETION_STATUS" value="" data-parsley-required readonly>
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

<div id="EditDaerah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditDaerah-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Edit Lokasi Daerah</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Pusat<span class="text-danger">*</span></label>
                                <select name="PUSAT_ID" id="editPUSAT_ID" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Pusat --</option>
                                    <?php
                                    foreach ($rows as $rowEditPusat) {
                                        extract($rowEditPusat);
                                        ?>
                                        <option value="<?= $PUSAT_ID; ?>"><?= $PUSAT_DESKRIPSI; ?></option>
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
                                <label for="">Daerah ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editDAERAH_ID" name="DAERAH_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Lokasi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editDAERAH_DESKRIPSI" name="DAERAH_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status</label>
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
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editdaerah" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>