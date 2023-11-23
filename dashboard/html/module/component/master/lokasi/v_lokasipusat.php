<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getPusat = GetQuery("select * from m_pusat p left join m_anggota a on p.INPUT_BY = a.ANGGOTA_ID where p.DELETION_STATUS = 0");
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPusat"><i class="ico-plus2"></i> Tambah Lokasi Pusat</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Lokasi Pusat</h3>
            </div>
            <table class="table table-striped table-bordered" id="lokasipusat-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Pusat ID</th>
                        <th>Lokasi </th>
                        <th>Alamat </th>
                        <th>Kepengurusan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Map</th>
                    </tr>
                </thead>
                <tbody id="pusatdata">
                    <?php
                    while($rowPusat = $getPusat->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowPusat);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= $PUSAT_ID; ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewPusat" class="open-ViewPusat" style="color:forestgreen;" data-id="<?= $PUSAT_ID; ?>" data-desc="<?= $PUSAT_DESKRIPSI; ?>" data-sekre="<?= $PUSAT_SEKRETARIAT; ?>" data-pengurus="<?= $PUSAT_KEPENGURUSAN; ?>" data-map="<?= $PUSAT_MAP; ?>" data-lat="<?= $PUSAT_LAT; ?>" data-long="<?= $PUSAT_LONG; ?>"><span class="ico-check"></span> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditPusat" class="open-EditPusat" style="color:cornflowerblue;" data-id="<?= $PUSAT_ID; ?>" data-desc="<?= $PUSAT_DESKRIPSI; ?>" data-sekre="<?= $PUSAT_SEKRETARIAT; ?>" data-pengurus="<?= $PUSAT_KEPENGURUSAN; ?>" data-map="<?= $PUSAT_MAP; ?>" data-lat="<?= $PUSAT_LAT; ?>" data-long="<?= $PUSAT_LONG; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletePusat('<?= $PUSAT_ID;?>','deletepusat')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td align="center"><?= $PUSAT_ID; ?></td>
                            <td><?= $PUSAT_DESKRIPSI; ?></td>
                            <td><?= $PUSAT_SEKRETARIAT; ?></td>
                            <td><?= $PUSAT_KEPENGURUSAN; ?></td>
                            <td><?= $PUSAT_LAT; ?></td>
                            <td><?= $PUSAT_LONG; ?></td>
                            <td><iframe src="<?= $PUSAT_MAP; ?>" width="300" height="175" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></td>
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

<div id="AddPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Lokasi Pusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="PUSAT_DESKRIPSI" name="PUSAT_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="PUSAT_KEPENGURUSAN" name="PUSAT_KEPENGURUSAN" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="PUSAT_SEKRETARIAT" name="PUSAT_SEKRETARIAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="PUSAT_MAP" name="PUSAT_MAP" value="" onkeyup="getMapsAdd(this.value)" onClick="getMapsAdd(this.value)" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="PUSAT_LAT" name="PUSAT_LAT" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="PUSAT_LONG" name="PUSAT_LONG" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="addPusatMap">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Google Maps</span></label><br>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savepusat" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat Data Pusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewPUSAT_DESKRIPSI" name="PUSAT_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewPUSAT_KEPENGURUSAN" name="PUSAT_KEPENGURUSAN" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="viewPUSAT_SEKRETARIAT" name="PUSAT_SEKRETARIAT" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="viewPUSAT_MAP" name="PUSAT_MAP" readonly value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewPUSAT_LAT" name="PUSAT_LAT" value="-3.3063120100780785" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewPUSAT_LONG" name="PUSAT_LONG" readonly value="114.56921894055016">
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Google Maps</label><br>
                                <iframe id="ViewPusatMap" src="" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

<div id="EditPusat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPusat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Edit Lokasi Pusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Pusat ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editPUSAT_ID" name="PUSAT_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editPUSAT_DESKRIPSI" name="PUSAT_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editPUSAT_KEPENGURUSAN" name="PUSAT_KEPENGURUSAN" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="editPUSAT_SEKRETARIAT" name="PUSAT_SEKRETARIAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="editPUSAT_MAP" name="PUSAT_MAP" value="" onkeyup="getMapsEdit(this.value)" onClick="getMapsEdit(this.value)" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editPUSAT_LAT" name="PUSAT_LAT" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6" id="pusatmapedit">
                            <div class="form-group">
                                <label for="">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editPUSAT_LONG" name="PUSAT_LONG" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="editPusatMap">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Google Maps</label>
                                <iframe id="EditPusatMap" src="" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editpusat" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>