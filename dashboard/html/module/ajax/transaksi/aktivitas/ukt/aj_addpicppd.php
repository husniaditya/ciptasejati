<?php
require_once("../../../../../module/connection/conn.php");

$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_ID = $_SESSION["LOGINIDUS_CS"];

$ANGGOTA_KEY = $_POST["ANGGOTA_KEY"];
$PPD_ID = "Temp_" . $USER_ID;

// Insert data into t_ppd_anggota table
GetQuery("INSERT INTO t_ppd_anggota SELECT UUID(),'$PPD_ID','$ANGGOTA_KEY','PIC'");

// Get the list of anggota based on the inserted data
if ($USER_AKSES == "Administrator") {
    $GetAnggota = GetQuery("SELECT p.*, a.ANGGOTA_ID, a.ANGGOTA_NAMA 
    FROM t_ppd_anggota p
    LEFT JOIN m_anggota a ON p.ANGGOTA_KEY = a.ANGGOTA_KEY
    WHERE p.ANGGOTA_TYPE = 'PIC' AND p.PPD_ID = '$PPD_ID'
    ORDER BY a.ANGGOTA_NAMA ASC");
} else {
    $GetAnggota = GetQuery("SELECT p.*, a.ANGGOTA_ID, a.ANGGOTA_NAMA 
    FROM t_ppd_anggota p
    LEFT JOIN m_anggota a ON p.ANGGOTA_KEY = a.ANGGOTA_KEY
    WHERE p.ANGGOTA_TYPE = 'PIC' AND p.PPD_ID = '$PPD_ID'
    ORDER BY a.ANGGOTA_NAMA ASC");
}


$rowNumber = 1; // Initialize row number
while ($ListAnggota = $GetAnggota->fetch(PDO::FETCH_ASSOC)) {
    extract($ListAnggota);
    ?>
    <tr>
        <td align='center'><?= $rowNumber; ?></td>
        <td align='center'><?= $ANGGOTA_ID; ?></td>
        <td><?= $ANGGOTA_NAMA; ?></td>
        <td align="center"><button type="button" class="btn btn-danger btn-rounded mb5" data-dismiss="modal" onclick="deleteanggota('<?= $PPD_ID; ?>','<?= $ANGGOTA_KEY; ?>','deleteevent')"><i class='fa-regular fa-trash-can'></i></button></td>
    </tr>
    <?php
    $rowNumber++; // Increment row number
}
?>
