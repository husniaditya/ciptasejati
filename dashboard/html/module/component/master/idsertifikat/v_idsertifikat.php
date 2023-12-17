<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$getSertifikat = GetQuery("SELECT s.*,t.TINGKATAN_NAMA,a.ANGGOTA_NAMA,DATE_FORMAT(s.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,case when s.DELETION_STATUS = 0 then 'Aktif' ELSE 'Tidak Aktif' END SERTIFIKAT_STATUS
FROM m_idsertifikat s
LEFT JOIN m_tingkatan t ON s.TINGKATAN_ID = t.TINGKATAN_ID
LEFT JOIN m_anggota a ON s.INPUT_BY = a.ANGGOTA_ID");

$getTingkatan = GetQuery("select * from m_tingkatan where DELETION_STATUS = 0");
// Fetch all rows into an array
$rows = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);

if ($_SESSION["ADD_IDdanSertifikat"] == "Y") {
    ?>
    <!-- START row -->
    <div class="row">
        <div class="col-lg-12">
            <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddSertifikat btn btn-inverse btn-outline mb5 btn-rounded" href="#AddSertifikat"><i class="ico-plus2"></i> Tambah ID Sertifikat</a>
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
                <h3 class="panel-title">Tabel ID Sertifikat</h3>
            </div>
            <table class="table table-striped table-bordered" id="idsertifikat-table">
                <thead>
                    <tr>
                        <th></th>
                        <th class="hidden">ID Sertifikat</th>
                        <th>Tingkatan</th>
                        <th>Deskripsi </th>
                        <th>File ID Card</th>
                        <th>File Sertifikat</th>
                        <th>Status </th>
                        <th>Input Oleh </th>
                        <th>Input Tanggal </th>
                    </tr>
                </thead>
                <tbody id="idsertifikatdata">
                    <?php
                    while ($rowSertifikat = $getSertifikat->fetch(PDO::FETCH_ASSOC)) {
                        extract($rowSertifikat);
                        ?>
                        <tr>
                            <td align="center">
                                <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                                    <div class="btn-group" style="margin-bottom:5px;">
                                        <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                        <ul class="dropdown-menu" role="menu">
                                            <?php
                                            if ($_SESSION["VIEW_IDdanSertifikat"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#ViewSertifikat" data-id="<?=$IDSERTIFIKAT_ID;?>" data-tingkatanid="<?=$TINGKATAN_ID;?>" data-tingkatannama="<?=$TINGKATAN_NAMA;?>" data-desk="<?=$IDSERTIFIKAT_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-sertifikatstatus="<?=$SERTIFIKAT_STATUS;?>" class="open-ViewSertifikat" style="color:#222222;"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["EDIT_IDdanSertifikat"] == "Y") {
                                                ?>
                                                <li><a data-toggle="modal" href="#EditSertifikat" data-id="<?=$IDSERTIFIKAT_ID;?>" data-tingkatanid="<?=$TINGKATAN_ID;?>" data-tingkatannama="<?=$TINGKATAN_NAMA;?>" data-desk="<?=$IDSERTIFIKAT_DESKRIPSI;?>" data-status="<?=$DELETION_STATUS;?>" data-sertifikatstatus="<?=$SERTIFIKAT_STATUS;?>" class="open-EditSertifikat" style="color:cornflowerblue;"><span class="ico-edit"></span> Ubah</a></li>
                                                <?php
                                            }
                                            if ($_SESSION["DELETE_IDdanSertifikat"] == "Y") {
                                                ?>
                                                <li class="divider"></li>
                                                <li><a href="#" onclick="deletesertifikat('<?= $IDSERTIFIKAT_ID;?>','deleteevent')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td class="hidden"><?= $IDSERTIFIKAT_ID; ?></td>
                            <td><?= $TINGKATAN_NAMA; ?></td>
                            <td><?= $IDSERTIFIKAT_DESKRIPSI; ?></td>
                            <td align="center">
                                <div>
                                    <a href="<?= $IDSERTIFIKAT_IDFILE; ?>" target="_blank">
                                    <img src="<?= $IDSERTIFIKAT_IDFILE; ?>" alt="Image" width="100" height="75" />
                                    </a>
                                </div>
                            </td>
                            <td align="center">
                                <div>
                                    <a href="<?= $IDSERTIFIKAT_SERTIFIKATFILE; ?>" target="_blank">
                                    <img src="<?= $IDSERTIFIKAT_SERTIFIKATFILE; ?>" alt="Image" width="100" height="75" />
                                    </a>
                                </div>
                            </td>
                            <td align="center"><?= $SERTIFIKAT_STATUS; ?></td>
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

<div id="AddSertifikat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddSertifikat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Tambah ID dan Sertifikat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="TINGKATAN_ID" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Tingkatan --</option>
                                        <?php
                                        foreach ($rows as $rowTingkatan) {
                                            extract($rowTingkatan);
                                            ?>
                                            <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="IDSERTIFIKAT_DESKRIPSI" name="IDSERTIFIKAT_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">Upload File ID Card </label><br>
                                <div>
                                    <input type="file" name="ID_CARD[]" id="ID_CARD" accept="image/*" /><br/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">Upload File Sertifikat </label><br>
                                <div>
                                    <input type="file" name="SERTIFIKAT[]" id="SERTIFIKAT" accept="image/*" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Cancel</button>
                    <button type="submit" name="submit" id="saveidsertifikat" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewSertifikat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewSertifikat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Lihat ID dan Sertifikat</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="viewTINGKATAN_ID" name="TINGKATAN_ID" value="" readonly>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="viewDELETION_STATUS" name="DELETION_STATUS" value="" data-parsley-required readonly>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Deskripsi<span class="text-danger">*</span></label>
                                <textarea type="text" rows="3" class="form-control" id="viewIDSERTIFIKAT_DESKRIPSI" name="IDSERTIFIKAT_DESKRIPSI" value="" data-parsley-required readonly></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">File ID Card </label><br>
                                <div class="row" id="loadviewid"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">File Sertifikat </label><br>
                                <div class="row" id="loadviewsertifikat"></div>
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

<div id="EditSertifikat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditSertifikat-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-success">Edit ID dan Sertifikat</h3>
                </div>
                <div class="modal-body">
                    <div class="row hidden">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">ID<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required readonly id="editIDSERTIFIKAT_ID" name="IDSERTIFIKAT_ID" value="" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tingkatan<span class="text-danger">*</span></label>
                                <div id="selectize-wrapper2" style="position: relative;">
                                    <select name="TINGKATAN_ID" id="selectize-dropdown2" required class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Tingkatan --</option>
                                        <?php
                                        foreach ($rows as $rowTingkatan) {
                                            extract($rowTingkatan);
                                            ?>
                                            <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea type="text" rows="3" class="form-control" id="editIDSERTIFIKAT_DESKRIPSI" name="IDSERTIFIKAT_DESKRIPSI" value=""></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">Upload File ID Card </label><br>
                                <div>
                                    <input type="file" name="ID_CARD[]" id="ID_CARD" accept="image/*" /><br/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">Upload File Sertifikat </label><br>
                                <div>
                                    <input type="file" name="SERTIFIKAT[]" id="SERTIFIKAT" accept="image/*" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">File ID Card </label><br>
                                <div class="row" id="loadeditid"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="File">File Sertifikat </label><br>
                                <div class="row" id="loadeditsertifikat"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Close</button>
                    <button type="submit" name="submit" id="editsertifikat" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Save</button>
                </div>
            </div>
        </div>
    </form>
</div>