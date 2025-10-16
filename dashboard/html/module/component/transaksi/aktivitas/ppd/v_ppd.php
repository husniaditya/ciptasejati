<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$getDaerah = GetQuery("select * from m_daerah where DELETION_STATUS = 0 order by DAERAH_DESKRIPSI asc");
$getPPDCabang = GetQuery("SELECT c.*,d.DAERAH_DESKRIPSI FROM m_cabang c
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY 
WHERE c.DELETION_STATUS = 0
ORDER BY CABANG_DESKRIPSI ASC");
$getTingkatan = GetQuery("SELECT * FROM m_tingkatan WHERE DELETION_STATUS = 0 ORDER BY TINGKATAN_LEVEL ASC");
// Fetch all rows into an array
$rowd = $getDaerah->fetchAll(PDO::FETCH_ASSOC);
$rowPPDCabang = $getPPDCabang->fetchAll(PDO::FETCH_ASSOC);
$rowt = $getTingkatan->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="panel-group" id="accordion1"> <!-- Input Filter -->
    <div class="panel panel-default">
        <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="collapsed">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa-solid fa-chevron-down"></i> Filter Data Pembukaan Pusat Daya
                </h4>
            </div>
        </a>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <form method="post" class="form filterPPD resettable-form" id="filterPPD">
                    <div class="row">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <select name="DAERAH_KEY" id="selectize-select3" required="" class="form-control" data-parsley-required>
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
                                <label>Cabang PPD</label>
                                <div id="selectize-wrapper" style="position: relative;">
                                    <select name="PPD_LOKASI" id="selectize-dropdown" required="" class="form-control" data-parsley-required>
                                        <option value="">-- Pilih Cabang --</option>
                                        <?php
                                        foreach ($rowPPDCabang as $rowCabang) {
                                            extract($rowCabang);
                                            ?>
                                            <option value="<?= $CABANG_KEY; ?>"><?= $CABANG_DESKRIPSI; ?> - <?= $DAERAH_DESKRIPSI; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No Dokumen</label>
                                <input type="text" class="form-control" id="filterPPD_ID" name="PPD_ID" value="" placeholder="Input No Dokumen PPD">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Anggota</label>
                                <input type="text" class="form-control" id="filterANGGOTA_ID" name="ANGGOTA_ID" value="" placeholder="Input ID Anggota">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" id="filterANGGOTA_NAMA" name="ANGGOTA_NAMA" value="" placeholder="Input Nama">
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tingkatan PPD</label>
                                <select name="TINGKATAN_ID" id="selectize-select" required="" class="form-control" data-parsley-required>
                                    <option value="">-- Pilih Tingkatan --</option>
                                    <?php
                                    foreach ($rowt as $filterTingkatan) {
                                        extract($filterTingkatan);
                                        ?>
                                        <option value="<?= $TINGKATAN_ID; ?>"><?= $TINGKATAN_NAMA; ?> - <?= $TINGKATAN_SEBUTAN; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jenis PPD</label>
                                <select id="filterPPD_JENIS" name="PPD_JENIS" class="form-control"  data-parsley-required required>
                                    <option value="">Tampilkan semua</option>
                                    <option value="0">Kenaikan</option>
                                    <option value="1">Ulang</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal PPD</label>
                                <input type="text" class="form-control" id="datepicker42" name="PPD_TANGGAL" placeholder="Pilih Tanggal PPD" readonly/>
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
<!-- START row -->
<div class="row"> <!-- Add Data Button -->
    <div class="col-lg-12">
        <a data-toggle="modal" data-toggle="modal" title="Add this item" class="open-AddPPD btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPPD"><i class="ico-plus2"></i> Tambah Data Pembukaan Pusat Daya</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row"> <!-- Table UKT -->
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Pembukaan Pusat Daya</h3>
            </div>
            <table class="table table-striped table-bordered" id="ppd-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>No Dokumen</th>
                        <th>ID Anggota </th>
                        <th>Nama Anggota </th>
                        <th>Daerah </th>
                        <th>Cabang </th>
                        <th>Ranting </th>
                        <th>Jenis PPD </th>
                        <th>Tingkatan</th>
                        <th>Tingkatan PPD </th>
                        <th>Cabang PPD</th>
                        <th>Tanggal </th>
                        <th>Deskripsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>Dokumen UKT </th>
                        <th>Tanggal UKT </th>
                        <th>No Sertifikat</th>
                        <th>Input Oleh</th>
                        <th>Input Tanggal</th>
                    </tr>
                </thead>
                <tbody id="ppddata"></tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<div id="AddPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddPPD-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpic"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper9" style="position: relative;">
                                        <select name="DAERAH_KEY" id="selectize-dropdown9" required="" class="form-control" data-parsley-required>
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
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper10" style="position: relative;">
                                        <select name="CABANG_KEY" id="selectize-dropdown10" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="datepicker4" name="PPD_TANGGAL" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Jenis PPD<span class="text-danger">*</span></label>
                                    <select id="PPD_JENIS" name="PPD_JENIS" class="form-control" placeholder="-- Pilih Jenis PPD --" data-parsley-required required>
                                        <option value="">-- Pilih Jenis PPD --</option>
                                        <option value="0">Kenaikan</option>
                                        <option value="1">Ulang</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan Lanjutan<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper2" style="position: relative;">
                                        <select name="TINGKATAN_ID" id="selectize-dropdown2" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Tingkatan --</option>
                                            <?php
                                            foreach ($rowt as $rowTingkatan) {
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
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper4" style="position: relative;">
                                        <select name="ANGGOTA_ID" id="selectize-dropdown4" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Anggota --</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang PPD<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper8" style="position: relative;">
                                        <select name="PPD_LOKASI" id="selectize-dropdown8" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>
                                            <?php
                                            foreach ($rowPPDCabang as $rowCabang) {
                                                extract($rowCabang);
                                                ?>
                                                <option value="<?= $CABANG_KEY; ?>"><?= $CABANG_DESKRIPSI; ?> - <?= $DAERAH_DESKRIPSI; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="MUTASI_DESKRIPSI" name="PPD_DESKRIPSI" value="" ></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="saveppd" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewPPD-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicview"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <?php
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <div class="form-group">
                                    <label>Daerah</label>
                                    <input type="text" class="form-control" id="viewPPD_DAERAH" name="DAERAH_KEY" readonly />
                                </div> 
                                <div class="short-div">
                                    <div class="form-group">
                                        <label>Cabang</label>
                                        <input type="text" class="form-control" id="viewPPD_CABANG" name="CABANG_KEY" readonly />
                                    </div> 
                                </div>
                                <?php
                            }
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" id="viewPPD_TANGGAL" name="PPD_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Jenis PPD</label>
                                    <input type="text" class="form-control" id="viewPPD_JENIS" name="PPD_JENIS" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan Lanjutan</label>
                                    <input type="text" class="form-control" id="viewPPD_TINGKATAN" name="PPD_TINGKATAN" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Anggota</label>
                                    <input type="text" class="form-control" id="viewPPD_ANGGOTA" name="ANGGOTA_KEY" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang PPD</label>
                                    <input type="text" class="form-control" id="viewPPD_LOKASI" name="PPD_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea type="text" rows="4" class="form-control" id="viewPPD_DESKRIPSI" name="PPD_DESKRIPSI" value="" readonly></textarea>
                                </div> 
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

<div id="EditPPD" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPPD-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Data PPD Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicedit"></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="short-div hidden">
                                <div class="form-group">
                                    <label>ID</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="editPPD_ID" name="PPD_ID" readonly required data-parsley-required/>
                                </div> 
                            </div>
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="short-div">
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
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper5" style="position: relative;">
                                        <select name="CABANG_KEY" id="selectize-dropdown5" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>]
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal</label><span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="datepicker41" name="PPD_TANGGAL" placeholder="Pilih tanggal" readonly required data-parsley-required/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Jenis PPD<span class="text-danger">*</span></label>
                                    <select id="editPPD_JENIS" name="PPD_JENIS" class="form-control" placeholder="-- Pilih Jenis PPD --" data-parsley-required required>
                                        <option value="">-- Pilih Jenis PPD --</option>
                                        <option value="0">Kenaikan</option>
                                        <option value="1">Ulang</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan Lanjutan<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper6" style="position: relative;">
                                        <select name="TINGKATAN_ID" id="selectize-dropdown6" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Tingkatan --</option>
                                            <?php
                                            foreach ($rowt as $rowTingkatan) {
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
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper7" style="position: relative;">
                                        <select name="ANGGOTA_ID" id="selectize-dropdown7" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Anggota --</option>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang PPD<span class="text-danger">*</span></label>
                                    <div id="selectize-wrapper11" style="position: relative;">
                                        <select name="PPD_LOKASI" id="selectize-dropdown11" required="" class="form-control" data-parsley-required>
                                            <option value="">-- Pilih Cabang --</option>
                                            <?php
                                            foreach ($rowPPDCabang as $rowCabang) {
                                                extract($rowCabang);
                                                ?>
                                                <option value="<?= $CABANG_KEY; ?>"><?= $CABANG_DESKRIPSI; ?> - <?= $DAERAH_DESKRIPSI; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="editPPD_DESKRIPSI" name="PPD_DESKRIPSI" value="" ></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updateppd" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="ViewUKT" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="ViewUKT-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Lihat Data UKT Anggota</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" align="center">
                            <div class="short-div">
                                <label>Foto Anggota </label><br>
                                <div id="loadpicukt"></div>
                            </div>
                            <br>
                            <br>
                            <div class="short-div">
                                <div class="col-md-6">
                                    <h2><u>Nilai UKT</u></h2>
                                    <h2 id="viewUKT_TOTAL"></h2>
                                </div>
                                <div class="col-md-6">
                                    <h2><u>Predikat</u></h2>
                                    <h2 id="viewUKT_NILAI"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <?php
                        if ($USER_AKSES == "Administrator") {
                            ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Daerah </label>
                                    <input type="text" class="form-control" id="viewDAERAH_KEY" name="DAERAH_KEY" readonly/>
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Cabang </label>
                                    <input type="text" class="form-control" id="viewCABANG_KEY" name="CABANG_KEY" readonly />
                                </div> 
                            </div>
                            <?php
                        }
                        ?>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tanggal </label>
                                    <input type="text" class="form-control" id="viewUKT_TANGGAL" name="UKT_TANGGAL" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Pilih Anggota </label>
                                    <input type="text" class="form-control" id="viewANGGOTA_ID" name="ANGGOTA_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Tingkatan UKT </label>
                                    <input type="text" class="form-control" id="viewTINGKATAN_ID" name="TINGKATAN_ID" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Lokasi Cabang UKT </label>
                                    <input type="text" class="form-control" id="viewUKT_LOKASI" name="UKT_LOKASI" readonly />
                                </div> 
                            </div>
                            <div class="short-div">
                                <div class="form-group">
                                    <label>Deskripsi </label>
                                    <textarea type="text" rows="4" class="form-control" id="viewUKT_DESKRIPSI" name="UKT_DESKRIPSI" value="" readonly></textarea>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4>Daftar Penguji</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading">
                                <h3 class="panel-title">Tabel Daftar Penguji</h3>
                            </div>
                            <table class="table table-striped table-bordered" id="viewPenguji-table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Nama Penguji </th>
                                        <th>Tingkatan </th>
                                    </tr>
                                </thead>
                                <tbody id="viewPengujiData">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h4>Rincian Nilai UKT</h4>
                    <div id="viewrincianukt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                </div>
            </div>
        </div>
    </form>
</div>