<?php

require_once("../../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];

if ($USER_AKSES == "Administrator") {
    $getPPD = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.TINGKATAN_NAMA PPD_TINGKATAN,t.TINGKATAN_SEBUTAN PPD_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE
    FROM t_ppd p
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0
    ORDER BY p.PPD_TANGGAL DESC");
} else {
    $getPPD = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.TINGKATAN_NAMA PPD_TINGKATAN,t.TINGKATAN_SEBUTAN PPD_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(p.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, p.PPD_FILE
    FROM t_ppd p
    LEFT JOIN m_anggota a2 ON p.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID = t2.TINGKATAN_ID
    WHERE p.DELETION_STATUS = 0 and p.CABANG_KEY = '$USER_CABANG'
    ORDER BY p.PPD_TANGGAL DESC");
}

while ($rowPPD = $getPPD->fetch(PDO::FETCH_ASSOC)) {
    extract($rowPPD);
    $getAnggotaPPD = GetQuery("SELECT p.ANGGOTA_TYPE,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING FROM t_ppd_anggota p
    LEFT JOIN m_anggota a ON p.ANGGOTA_KEY = a.ANGGOTA_KEY
    WHERE p.PPD_ID = '$PPD_ID'");
    $AnggotaPPD = $getAnggotaPPD->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= uniqid(); ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewPPD" class="open-ViewPPD" style="color:#222222;" data-id="<?= $PPD_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <?php
                        if ($MUTASI_STATUS == 0 && ($USER_AKSES == "Administrator" || $USER_AKSES == "Koordinator")) {
                            if ($USER_AKSES == "Administrator") {
                                ?>
                                <li><a data-toggle="modal" href="#EditPPD" class="open-EditPPD" style="color:#00a5d2;" data-id="<?= $PPD_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                <?php
                            }
                            if ($USER_AKSES == "Koordinator" && $USER_CABANG == $CABANG_AWAL) {
                                ?>
                                <li><a data-toggle="modal" href="#EditPPD" class="open-EditPPD" style="color:#00a5d2;" data-id="<?= $PPD_ID; ?>" data-anggota="<?= $ANGGOTA_KEY; ?>"><span class="ico-edit"></span> Ubah</a></li>
                                <?php
                            }
                        }
                        ?>
                        <li><a href="assets/print/transaksi/mutasi/print_mutasi.php?id=<?=$PPD_ID; ?>" target="_blank" style="color: darkgoldenrod;"><i class="fa-solid fa-print"></i> Cetak</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="eventmutasi('<?= $PPD_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td><?= $PPD_ID; ?></td>
        <td align="center"><?= $PPD_TANGGAL; ?></td>
        <td>
            <?php
            foreach ($AnggotaPPD as $rowAnggotaPPD) {
                extract($rowAnggotaPPD);
                if ($ANGGOTA_TYPE == "Anggota") {
                    echo $ANGGOTA_ID . " - " . $ANGGOTA_NAMA . "<br>";
                }
            }
            ?>
        </td>
        <td align="center"><?= $PPD_TINGKATAN; ?><br><?= $PPD_SEBUTAN; ?></td>
        <td align="center"><?= $PPD_DESKRIPSI; ?></td>
        <td>
            <?php
            foreach ($AnggotaPPD as $rowAnggotaPPD) {
                extract($rowAnggotaPPD);
                if ($ANGGOTA_TYPE == "PIC") {
                    echo $ANGGOTA_ID . " - " . $ANGGOTA_NAMA . "<br>";
                }
            }
            ?>
        </td>
        <td align="center"><?= $CABANG_DESKRIPSI; ?></td>
        <td align="center"><?= $DAERAH_DESKRIPSI; ?></td>
        <td><?= $INPUT_BY; ?></td>
        <td><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}

?>