<?php
require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

$getKas = GetQuery("SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
CASE
    WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
    ELSE FORMAT(k.KAS_JUMLAH, 0)
END AS FKAS_JUMLAH,
CASE 
    WHEN k.KAS_DK = 'D' THEN 'Debit'
    ELSE 'Kredit' 
END AS KAS_DK_DES,
CASE
    WHEN k.KAS_DK = 'D' THEN 'color: green;'
    ELSE 'color: red;' 
END AS KAS_COLOR
FROM t_kas k
LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0
ORDER BY k.KAS_ID");

while ($rowMutasi = $getKas->fetch(PDO::FETCH_ASSOC)) {
    extract($rowMutasi);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewKasAnggota" class="open-ViewKasAnggota" style="color:#222222;" data-id="<?= $KAS_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditKasAnggota" class="open-EditKasAnggota" style="color:#00a5d2;" data-id="<?= $KAS_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="<?= $KAS_FILE; ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="eventkas('<?= $KAS_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $KAS_ID; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $ANGGOTA_ID; ?></td>
        <td align="center"><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_NAMA; ?></td>
        <td align="center"><?= $TINGKATAN_SEBUTAN; ?></td>
        <td align="center"><?= $FKAS_TANGGAL; ?></td>
        <td align="center"><?= $KAS_DK_DES; ?></td>
        <td align="center"><?= $KAS_DESKRIPSI; ?></td>
        <td align="right" style="<?= $KAS_COLOR; ?>"><?= $FKAS_JUMLAH; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}

?>