<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getPusatdata = GetQuery("SELECT p.*,c.CABANG_DESKRIPSI,a.ANGGOTA_NAMA,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when p.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END PUSATDATA_STATUS FROM m_pusatdata p
LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_anggota a ON p.INPUT_BY = a.ANGGOTA_ID");

$getCabang = GetQuery("select * from m_cabang where DELETION_STATUS = 0");
// Fetch all rows into an array
$rows = $getCabang->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPusatdata btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPusatdata"><i class="ico-plus2"></i> Tambah Data Terpusat</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Data Terpusat</h3>
            </div>
            <table class="table table-striped table-bordered" id="pusatdata-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">ID Data Terpusat</th>
                        <th>Cabang</th>
                        <th>Kategori</th>
                        <th>Judul </th>
                        <th>Deskripsi</th>
                        <th>File Data</th>
                        <th>Status </th>
                        <th>Input Oleh </th>
                        <th>Input Tanggal </th>
                    </tr>
                </thead>
                <tbody id="datapusatdata">
                    <?php
                    while ($rowPusatdata = $getPusatdata->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowPusatdata);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a data-toggle="modal" href="#ViewPusatdata" data-pusatid="<?=$PUSATDATA_ID;?>" data-cabangid="<?=$CABANG_KEY;?>" data-cabangnama="<?=$CABANG_DESKRIPSI;?>" data-kategori="<?=$PUSATDATA_KATEGORI;?>" data-judul="<?=$PUSATDATA_JUDUL;?>" data-deskripsi="<?=$PUSATDATA_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-pusatstatus="<?=$PUSATDATA_STATUS;?>" class="open-ViewPusatdata" style="color:forestgreen;"><span class="ico-check"></span> Lihat</a></li>
                                            <li><a data-toggle="modal" href="#EditPusatdata" data-pusatid="<?=$PUSATDATA_ID;?>" data-cabangid="<?=$CABANG_KEY;?>" data-cabangnama="<?=$CABANG_DESKRIPSI;?>" data-kategori="<?=$PUSATDATA_KATEGORI;?>" data-judul="<?=$PUSATDATA_JUDUL;?>" data-deskripsi="<?=$PUSATDATA_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-pusatstatus="<?=$PUSATDATA_STATUS;?>" class="open-EditPusatdata" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onclick="deletepusatdata('<?= $PUSATDATA_ID;?>','deleteevent')" style="color:firebrick;"><span class="ico-trash"></span> Hapus</a></li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td class="hidden"><?= $PUSATDATA_ID; ?></td>
                            <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
                            <td align="center"><?= $PUSATDATA_KATEGORI; ?></td>
                            <td><?= $PUSATDATA_JUDUL; ?></td>
                            <td><?= $PUSATDATA_DESKRIPSI; ?></td>
                            <td align="center"><a href="<?= $PUSATDATA_FILE; ?>" target="_blank"><i class="fa-solid fa-file-circle-check fa-xl"></i><br> Lihat File</a>
                            </td>
                            <td align="center"><?= $PUSATDATA_STATUS; ?></td>
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

<div id="AddPusatdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddPusatdata-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah Data Terpusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="CABANG_KEY" id="selectize-dropdown" required class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                        <?php
                                        foreach ($rows as $rowCabang) {
                                            extract($rowCabang);
                                            ?>
                                            <option value="<?= $CABANG_KEY; ?>"><?= $CABANG_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kategori<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="PUSATDATA_KATEGORI" name="PUSATDATA_KATEGORI" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="PUSATDATA_JUDUL" name="PUSATDATA_JUDUL" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="PUSATDATA_DESKRIPSI" name="PUSATDATA_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Upload File </label><br>
                                <div>
                                    <input type="file" name="PUSATDATA_FILE[]" id="PUSATDATA_FILE" /><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="savepusatdata" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewPusatdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewPusatdata-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat Data Terpusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewCABANG_ID" name="CABANG_ID" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kategori<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewPUSATDATA_KATEGORI" name="PUSATDATA_KATEGORI" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewPUSATDATA_JUDUL" name="PUSATDATA_JUDUL" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="viewPUSATDATA_DESKRIPSI" name="PUSATDATA_DESKRIPSI" value="" readonly></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewDELETION_STATUS" name="DELETION_STATUS" value="" required readonly data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">File Lampiran </label><br>
                                <div class="row" id="viewpusatfile"></div>
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

<div id="EditPusatdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPusatdata-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Edit Data Terpusat</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editPUSATDATA_ID" name="PUSATDATA_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Cabang<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="CABANG_KEY" id="selectize-dropdown2" required class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                        <?php
                                        foreach ($rows as $rowEditCabang) {
                                            extract($rowEditCabang);
                                            ?>
                                            <option value="<?= $CABANG_KEY; ?>"><?= $CABANG_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kategori<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editPUSATDATA_KATEGORI" name="PUSATDATA_KATEGORI" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Judul<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editPUSATDATA_JUDUL" name="PUSATDATA_JUDUL" value="" required data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="editPUSATDATA_DESKRIPSI" name="PUSATDATA_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status<span class="text-danger">*</span></label>
                                <select name="DELETION_STATUS" id="editDELETION_STATUS" required class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Tidak Aktif</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Upload File </label><br>
                                <div>
                                    <input type="file" name="PUSATDATA_FILE[]" id="PUSATDATA_FILE" /><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">File Lampiran </label><br>
                                <div class="row" id="editpusatfile"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editpusatdata" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>