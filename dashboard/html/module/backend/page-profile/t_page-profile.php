<?php
require_once ("./module/connection/conn.php");

$USER_KEY = $_SESSION["LOGINKEY_CS"];
$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];

if (isset($_POST["saveprofilakun"])) {
    try {
        $ANGGOTA_NAMA = $_POST["ANGGOTA_NAMA"];
        $ANGGOTA_TEMPAT_LAHIR = $_POST["ANGGOTA_TEMPAT_LAHIR"];
        $ANGGOTA_TANGGAL_LAHIR = $_POST["ANGGOTA_TANGGAL_LAHIR"];
        $ANGGOTA_AGAMA = $_POST["ANGGOTA_AGAMA"];
        $ANGGOTA_KELAMIN = $_POST["ANGGOTA_KELAMIN"];
        $ANGGOTA_ALAMAT = $_POST["ANGGOTA_ALAMAT"];
        $ANGGOTA_PEKERJAAN = $_POST["ANGGOTA_PEKERJAAN"];

        
        $getNamaCabang = GetQuery("select CABANG_DESKRIPSI from m_cabang where CABANG_KEY = '$USER_CABANG'");
        while ($rowNamaCabang = $getNamaCabang->fetch(PDO::FETCH_ASSOC)) {
            extract($rowNamaCabang);
        }

        // Create directory if not exists
        $FilePath = '../../../assets/images/daftaranggota/'.$CABANG_DESKRIPSI.'/'.$USER_ID.' - '. $ANGGOTA_NAMA;

        // Create directory if not exists
        if (!file_exists($FilePath)) {
            mkdir($FilePath, 0777, true);
        }

        // Handle ANGGOTA_PIC files
        if (!empty($_FILES['ANGGOTA_PIC']['tmp_name'][0])) {
            foreach ($_FILES['ANGGOTA_PIC']['tmp_name'] as $key => $idCardFileTmp) {
                $idCardFileName = $_FILES['ANGGOTA_PIC']['name'][$key];
                $idCardFileDestination = $FilePath . "/" . $idCardFileName;
                move_uploaded_file($idCardFileTmp, $idCardFileDestination);

                // Re-initialize the variable for database
                $idCardFileDestination = "./assets/images/daftaranggota/".$CABANG_DESKRIPSI."/".$USER_ID." - ".$ANGGOTA_NAMA."/" . $idCardFileName;

                GetQuery("update m_anggota set ANGGOTA_PIC = '$idCardFileDestination' where ANGGOTA_KEY = '$USER_KEY'");

                $_SESSION['LOGINPP_CS'] = $idCardFileDestination;
            }
        }

        GetQuery("UPDATE m_anggota set ANGGOTA_NAMA = '$ANGGOTA_NAMA', ANGGOTA_TEMPAT_LAHIR = '$ANGGOTA_TEMPAT_LAHIR', ANGGOTA_TANGGAL_LAHIR = '$ANGGOTA_TANGGAL_LAHIR', ANGGOTA_AGAMA = '$ANGGOTA_AGAMA', ANGGOTA_KELAMIN = '$ANGGOTA_KELAMIN', ANGGOTA_ALAMAT = '$ANGGOTA_ALAMAT', ANGGOTA_PEKERJAAN = '$ANGGOTA_PEKERJAAN' where ANGGOTA_KEY = '$USER_KEY'");

        $response="Success";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
?>