<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI,RIGHT(c.CABANG_ID,3) SHORT_ID FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
WHERE c.DELETION_STATUS = 0
ORDER BY c.CABANG_ID");

?>

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
                                            <li><a data-toggle="modal" href="#ViewCabang" class="open-ViewCabang" style="color:#222222;" data-key="<?=$CABANG_KEY;?>" data-daerahid="<?=$DAERAH_KEY;?>" data-daerahdes="<?=$DAERAH_DESKRIPSI;?>" data-cabangid="<?=$CABANG_ID;?>" data-shortid="<?=$SHORT_ID;?>" data-desk="<?=$CABANG_DESKRIPSI;?>" data-pengurus="<?=$CABANG_PENGURUS;?>" data-sekre="<?=$CABANG_SEKRETARIAT;?>" data-map="<?=$CABANG_MAP;?>" data-lat="<?=$CABANG_LAT;?>" data-long="<?=$CABANG_LONG;?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td><?= $CABANG_ID; ?></td>
                            <td><?= $DAERAH_DESKRIPSI; ?></td>
                            <td><?= $CABANG_DESKRIPSI; ?></td>
                            <td><?= $CABANG_SEKRETARIAT; ?></td>
                            <td><?= $CABANG_PENGURUS; ?></td>
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

<div id="ViewCabang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewCabang-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data Cabang</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Daerah<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="viewDAERAH_ID" name="DAERAH_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cabang ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="viewCABANG_ID" name="CABANG_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewCABANG_DESKRIPSI" name="CABANG_DESKRIPSI" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kepengurusan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewCABANG_PENGURUS" name="CABANG_PENGURUS" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="viewCABANG_SEKRETARIAT" name="CABANG_SEKRETARIAT" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Map Link<span class="text-danger">*</span></label>
                                <textarea type="text" rows="5" class="form-control" id="viewCABANG_MAP" name="CABANG_MAP" readonly value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Latitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly required id="viewCABANG_LAT" name="CABANG_LAT" value="" data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Longitude<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewCABANG_LONG" name="CABANG_LONG" readonly value="">
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Google Maps</label>
                                <iframe id="ViewCabangMap" src="" width="100%" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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