<?php
require_once("../../../../module/connection/conn.php");
// your_php_script.php
$USER_CABANG = $_SESSION['LOGINCAB_CS'];
$USER_AKSES = $_SESSION['LOGINAKS_CS'];


$getProfil = GetQuery("select * from c_profil"); 

$data = array();
while ($Profil = $getProfil->fetch(PDO::FETCH_ASSOC)) {
    $data['PROFIL_ID'] = $Profil["PROFIL_ID"];
    $data['PROFIL_LOGO'] = $Profil["PROFIL_LOGO"];
    $data['PROFIL_NAMA'] = $Profil["PROFIL_NAMA"];
    $data['PROFIL_SEJARAH'] = $Profil["PROFIL_SEJARAH"];
    $data['PROFIL_TELP_1'] = $Profil["PROFIL_TELP_1"];
    $data['PROFIL_TELP_2'] = $Profil["PROFIL_TELP_2"];
    $data['PROFIL_EMAIL_1'] = $Profil["PROFIL_EMAIL_1"];
    $data['PROFIL_EMAIL_2'] = $Profil["PROFIL_EMAIL_2"];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
