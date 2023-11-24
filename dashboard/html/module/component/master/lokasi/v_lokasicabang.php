<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI,RIGHT(c.CABANG_ID,3) SHORT_ID FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_ID = d.DAERAH_ID
WHERE c.DELETION_STATUS = 0
ORDER BY c.CABANG_ID");

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_ID");
// Fetch all rows into an array
$rows = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddCabang btn btn-inverse btn-outline mb5 btn-rounded" href="#AddCabang"><i class="ico-plus2"></i> Tambah Lokasi Cabang</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Lokasi Cabang</h3>
            </div>
            <table class="table table-striped table-bordered" id="lokasicabang-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Cabang ID</th>
                        <th>Daerah </th>
                        <th>Lokasi </th>
                        <th>Alamat </th>
                        <th>Kepengurusan</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Map</th>
                    </tr>
                </thead>
                <tbody id="cabangdata">
                    <?php
                    while ($rowCabang = $getCabang->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowCabang);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewCabang" class="open-ViewCabang" style="color:forestgreen;" data-daerahid="<?=$DAERAH_ID;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-cabangid="<?=$CABANG_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-desk="<?=$CABANG_DESKRIPSI;?>" data-pengurus="<?=$CABANG_PENGURUS;?>" data-sekre="<?=$CABANG_SEKRETARIAT;?>" data-map="<?=$CABANG_MAP;?>" data-lat="<?=$CABANG_LAT;?>" data-long="<?=$CABANG_LONG;?>"><span class="ico-check"></span> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditCabang" class="open-EditCabang" style="color:cornflowerblue;" data-daerahid="<?=$DAERAH_ID;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-cabangid="<?=$CABANG_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-desk="<?=$CABANG_DESKRIPSI;?>" data-pengurus="<?=$CABANG_PENGURUS;?>" data-sekre="<?=$CABANG_SEKRETARIAT;?>" data-map="<?=$CABANG_MAP;?>" data-lat="<?=$CABANG_LAT;?>" data-long="<?=$CABANG_LONG;?>"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deleteCabang('<?= $CABANG_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $CABANG_ID; ?></td>
                            <td><?= $DAERAH_DESKRIPSI; ?></td>
                            <td><?= $CABANG_DESKRIPSI; ?></td>
                            <td><?= $CABANG_SEKRETARIAT; ?></td>
                            <td><?= $CABANG_PENGURUS; ?></td>
                            <td><?= $CABANG_LAT; ?></td>
                            <td><?= $CABANG_LONG; ?></td>
                            <td>
                                <iframe src="<?= $CABANG_MAP; ?>" width="250" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </td>
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

<div id="AddCabang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddCabang-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Data Cabang</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="DAERAH_ID" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        foreach ($rows as $rowCabang) {
                                            extract($rowCabang);
                                            ?>
                                            <option value="<?= $DAERAH_ID; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="CABANG_ID" name="CABANG_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Lokasi Cabang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="CABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kepengurusan</label>
                                <input type="text" class="form-control" id="CABANG_PENGURUS" name="CABANG_PENGURUS" value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="CABANG_SEKRETARIAT" name="CABANG_SEKRETARIAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="CABANG_MAP" name="CABANG_MAP" value="" onkeyup="getMapsAdd(this.value)" onClick="getMapsAdd(this.value)" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="CABANG_LAT" name="CABANG_LAT" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="CABANG_LONG" name="CABANG_LONG" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="addCabangMap">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Google Maps</span></label><br>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savecabang" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewCabang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewCabang-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat Data Cabang</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="viewDAERAH_ID" name="DAERAH_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="viewCABANG_ID" name="CABANG_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewCABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewCABANG_PENGURUS" name="CABANG_PENGURUS" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="viewCABANG_SEKRETARIAT" name="CABANG_SEKRETARIAT" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="viewCABANG_MAP" name="CABANG_MAP" readonly value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewCABANG_LAT" name="CABANG_LAT" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewCABANG_LONG" name="CABANG_LONG" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Google Maps</label>
                                <iframe id="ViewCabangMap" src="" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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

<div id="EditCabang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditCabang-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Ubah Data Cabang</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editID" name="ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Daerah<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="DAERAH_ID" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Daerah --</option>
                                        <?php
                                        foreach ($rows as $rowCabang) {
                                            extract($rowCabang);
                                            ?>
                                            <option value="<?= $DAERAH_ID; ?>"><?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editCABANG_ID" name="CABANG_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Lokasi Cabang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editCABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kepengurusan</label>
                                <input type="text" class="form-control" id="editCABANG_PENGURUS" name="CABANG_PENGURUS" value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="editCABANG_SEKRETARIAT" name="CABANG_SEKRETARIAT" value="" data-parsley-required></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="editCABANG_MAP" name="CABANG_MAP" value="" onkeyup="getMapsEdit(this.value)" onClick="getMapsEdit(this.value)" data-parsley-required></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="editCABANG_LAT" name="CABANG_LAT" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editCABANG_LONG" name="CABANG_LONG" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12" id="editCabangMap">
                            <div class="form-group">
                                <label for="">Google Maps</span></label><br>
                                <iframe id="EditCabangMap" src="" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editcabang" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>