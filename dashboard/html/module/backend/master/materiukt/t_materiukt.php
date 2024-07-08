<?php
require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];
$USER_CABANG = $_SESSION["LOGINCAB_CS"];
$USER_AKSES = $_SESSION["LOGINAKS_CS"];
$USER_KEY = $_SESSION["LOGINKEY_CS"];

$YEAR=date("Y");
$MONTH=date("m");

if (isset($_POST["savemateri"])) {  // Add a new materi

    try {
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        } else {
            $CABANG_KEY = $USER_CABANG;
        }
        $MATERI_ID = createKode("m_materi","MATERI_ID","MTR-$YEAR$MONTH-",3);
        $MATERI_DESKRIPSI = $_POST["MATERI_DESKRIPSI"];
        $MATERI_BOBOT = $_POST["MATERI_BOBOT"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];

        GetQuery("insert into m_materi select '$MATERI_ID', '$CABANG_KEY', '$TINGKATAN_ID', '$MATERI_DESKRIPSI', '$MATERI_BOBOT', '0', '$USER_ID', now()");

        GetQuery("UPDATE m_materi_detail SET MATERI_ID = '$MATERI_ID' WHERE MATERI_ID = '$USER_KEY'");

        $response="Success,$MATERI_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["updatemateri"])) { // Update an existing materi

    try {
        if ($USER_AKSES == "Administrator") {
            $CABANG_KEY = $_POST["CABANG_KEY"];
        } else {
            $CABANG_KEY = $USER_CABANG;
        }
        $MATERI_ID = $_POST["MATERI_ID"];
        $MATERI_DESKRIPSI = $_POST["MATERI_DESKRIPSI"];
        $MATERI_BOBOT = $_POST["MATERI_BOBOT"];
        $TINGKATAN_ID = $_POST["TINGKATAN_ID"];

        GetQuery("UPDATE m_materi SET CABANG_KEY = '$CABANG_KEY', TINGKATAN_ID = '$TINGKATAN_ID', MATERI_DESKRIPSI = '$MATERI_DESKRIPSI', MATERI_BOBOT = '$MATERI_BOBOT', INPUT_BY = '$USER_ID', INPUT_DATE = now() WHERE MATERI_ID = '$MATERI_ID'");

        $response="Success,$MATERI_ID";
        echo $response;

    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}

if (isset($_POST["ADD_MODAL_DETAIL"])) { // Add a new detail ADD MODAL

    try {
        $MATERI_ID = $_POST["materi"];
        $DETAIL_DESKRIPSI = $_POST["deskripsi"];
        $MATERI_BOBOT = $_POST["totalbobot"];
        $DETAIL_BOBOT = $_POST["bobot"];

        $getTotalBobot = GetQuery("SELECT SUM(DETAIL_BOBOT) TOTAL_BOBOT
        FROM m_materi_detail
        WHERE MATERI_ID = '$MATERI_ID'");
        while ($rowTotalBobot = $getTotalBobot->fetch(PDO::FETCH_ASSOC)) {
            extract($rowTotalBobot);
        }

        if ($MATERI_BOBOT == 0 || $DETAIL_BOBOT == 0) {
            $response="Mohon isi nilai bobot materi dan bobot detail materi";
            echo $response;
        } else if ($TOTAL_BOBOT + $DETAIL_BOBOT <= $MATERI_BOBOT) {
            // Prepare the query
            $query = "INSERT INTO m_materi_detail SELECT UUID(), ?, ?, ?, '0', ?, NOW()";

            // Execute the query with parameters
            GetQuery2($query, [$MATERI_ID, $DETAIL_DESKRIPSI, $DETAIL_BOBOT, $USER_ID]);

            $response="Success";
            echo $response;
        } else {
            $response="Bobot melebihi batas maksimal total bobot materi";
            echo $response;
        }
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["DELETE_MATERI_DETAIL"])) { // Delete a detail ADD MODAL

    try {
        $ID = $_POST["id"];
    
        GetQuery("DELETE FROM m_materi_detail WHERE MATERI_ID = '$ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["ADD_DETAIL"])) { // Add a new detail EDIT MODAL

    try {
        $MATERI_ID = $_POST["materi"];
        $DETAIL_DESKRIPSI = $_POST["deskripsi"];
        $DETAIL_BOBOT = $_POST["bobot"];

        $getTotalBobot = GetQuery("SELECT SUM(d.DETAIL_BOBOT) TOTAL_BOBOT, m.MATERI_BOBOT
        FROM m_materi m
        LEFT JOIN m_materi_detail d ON m.MATERI_ID = d.MATERI_ID AND d.DELETION_STATUS = 0
        WHERE m.MATERI_ID = '$MATERI_ID'");
        while ($rowTotalBobot = $getTotalBobot->fetch(PDO::FETCH_ASSOC)) {
            extract($rowTotalBobot);
        }

        if ($MATERI_BOBOT == 0 || $DETAIL_BOBOT == 0) {
            $response="Mohon isi nilai bobot materi dan bobot detail materi";
            echo $response;
        } else if ($TOTAL_BOBOT + $DETAIL_BOBOT <= $MATERI_BOBOT) {
            // Prepare the query
            $query = "INSERT INTO m_materi_detail SELECT UUID(), ?, ?, ?, '0', ?, NOW()";

            // Execute the query with parameters
            GetQuery2($query, [$MATERI_ID, $DETAIL_DESKRIPSI, $DETAIL_BOBOT, $USER_ID]);

            $response="Success";
            echo $response;
        } else {
            $response="Bobot melebihi batas maksimal total bobot materi";
            echo $response;
        }
    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["DELETE_DETAIL"])) { // Delete a detail EDIT MODAL

    try {
        $ID = $_POST["id"];
    
        GetQuery("UPDATE m_materi_detail SET DELETION_STATUS = 1 WHERE _key = '$ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}

if (isset($_POST["EVENT_ACTION"])) { // Delete a materi

    try {
        $MATERI_ID = $_POST["MATERI_ID"];
    
        GetQuery("UPDATE m_materi SET DELETION_STATUS = 1 WHERE MATERI_ID = '$MATERI_ID'");
        $response="Success";
        echo $response;

    } catch (\Throwable $th) {
        // Generic exception handling
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>