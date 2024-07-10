<?php
$getJadwal = GetQuery("SELECT p.PPD_TANGGAL TANGGAL,'Pembukaan Pusat Daya' KATEGORI,CONCAT('PPD Anggota ',SUBSTRING_INDEX(CONCAT_WS(' ', a.ANGGOTA_NAMA), ' ', 2)) NAMA, c.CABANG_DESKRIPSI CABANG, pic.ANGGOTA_NAMA PIC_NAMA, pic.ANGGOTA_HP PIC_HP
FROM t_ppd p
LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
LEFT JOIN m_anggota pic ON p.INPUT_BY = pic.ANGGOTA_ID AND p.CABANG_KEY = pic.CABANG_KEY
LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
WHERE p.DELETION_STATUS = 0 AND p.PPD_TANGGAL >= CURDATE()
UNION ALL
SELECT u.UKT_TANGGAL TANGGAL,'Uji Kenaikan Tingkat' KATEGORI,CONCAT('UKT Anggota ',SUBSTRING_INDEX(CONCAT_WS(' ', a.ANGGOTA_NAMA), ' ', 2)) NAMA, c.CABANG_DESKRIPSI CABANG, pic.ANGGOTA_NAMA PIC_NAMA, pic.ANGGOTA_HP PIC_HP
FROM t_ukt u
LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
LEFT JOIN m_anggota pic ON u.INPUT_BY = pic.ANGGOTA_ID AND u.CABANG_KEY = pic.CABANG_KEY
LEFT JOIN m_cabang c ON u.UKT_LOKASI = c.CABANG_KEY
WHERE u.DELETION_STATUS = 0 AND u.UKT_TANGGAL >= CURDATE()");

$rowJadwal = $getJadwal->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="row">
    <div class="col-md-6" style="margin-top: 20px;">
        <div id="anggotaChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-md-6" style="margin-top: 20px;">
        <div id="tingkatanChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-top: 20px;">
        <div id="aktivitasChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-6" style="margin-top: 20px;">
        <div id="pieAktivitasChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-md-6" style="margin-top: 20px;">
        <div id="statusChart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-6" style="margin-top: 20px;">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Jadwal Kegiatan Mendatang</h3>
            </div>
            <table class="table table-striped table-bordered" id="dashboard-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori </th>
                        <th>Deskripsi </th>
                        <th>Cabang </th>
                        <th>PIC </th>
                        <th>Kontak PIC</th>
                    </tr>
                </thead>
                <tbody id="">
                    <?php
                    foreach ($rowJadwal as $dataJadwal) {
                        extract($dataJadwal);
                        ?>
                        <tr>
                            <td><?= $TANGGAL; ?></td>
                            <td><?= $KATEGORI; ?></td>
                            <td><?= $NAMA; ?></td>
                            <td><?= $CABANG; ?></td>
                            <td><?= $PIC_NAMA; ?></td>
                            <td><?= $PIC_HP; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br><br>
