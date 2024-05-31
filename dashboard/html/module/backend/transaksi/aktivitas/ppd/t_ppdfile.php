<?php
require_once ("../../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../../../assets/tcpdf/tcpdf.php');

if (isset($_POST['id'])) {
    $PPD_ID = $_POST["id"];

    $getData = GetQuery("SELECT k.*,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_AKSES,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,c.CABANG_SEKRETARIAT,a2.ANGGOTA_ID INPUT_BY_ID,a2.ANGGOTA_NAMA INPUT_BY,a2.ANGGOTA_AKSES INPUT_AKSES, DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y') INPUT_DATE,
    CASE
        WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
        ELSE FORMAT(k.KAS_JUMLAH, 0)
    END AS FKAS_JUMLAH,
    CASE 
        WHEN k.KAS_DK = 'D' THEN 'Debit'
        ELSE 'Kredit' 
    END AS KAS_DK_DES 
    FROM t_kas k
    LEFT JOIN m_anggota a ON k.ANGGOTA_KEY = a.ANGGOTA_KEY
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    WHERE k.KAS_ID = '$KAS_ID'");

    try {
        while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
            extract($data);
    
            // create new PDF document
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Cipta Sejati Indonesia');
            $pdf->SetTitle($KAS_ID. ' Kas ' . $KAS_JENIS . ' ' . $ANGGOTA_NAMA . '  ' . $FKAS_TANGGAL);
    
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
            // ---------------------------------------------------------
    
            // add a page
            $pdf->AddPage('PDF_PAGE_FORMAT', 'A4');;

            $pdf->SetFont('times', '', 11); // Set font for body

            $pdf->Ln(96.5);
            $pdf->Cell(30,15,$INPUT_DATE,1,0,"C");
            $pdf->Cell(35,15,$KAS_ID,1,0,"C");
            $pdf->Cell(15,15,$KAS_DK_DES,1,0,"C");
            $pdf->Cell(25,15,$FKAS_JUMLAH,1,0,"R");
            $pdf->Cell(30,15,'',1,0,"R");
            $pdf->SetFont('times', 'B', 11); // Set font for body
            $pdf->Cell(30,15,$SALDOAKHIR,1,0,"R");
            $pdf->Cell(25,15,'',1,0,"C");
            $pdf->Ln(40);
            $pdf->SetFont('times', '', 11); // Set font for body
            $pdf->MultiCell(190,5,"Dengan ini, laporan kas harian kami disusun sebagai refleksi akurat dari transaksi keuangan yang tercatat hari ini. Semoga laporan ini bermanfaat sebagai panduan yang jelas dan dapat diandalkan untuk melacak arus kas perusahaan. Terima kasih atas perhatian dan kerja sama semua pihak dalam menjaga keteraturan dan keakuratan catatan keuangan kami.", 0, 'J', false, 1, '', '', true);     
            $pdf->Ln(5);
            $pdf->MultiCell(190,5,"Demikian formulir kas anggota ini disampaikan dengan sebenarnya untuk digunakan sebagaimana mestinya. Kami berharap agar ke depannya, manajemen keuangan perusahaan dapat terus ditingkatkan demi mencapai tujuan dan pertumbuhan yang lebih baik. \n", 0, 'J', false, 1, '', '', true);
    
            GetQuery("update t_kas set KAS_FILE = './assets/sertifikat/$CABANG_DESKRIPSI/$PPD_ID $ANGGOTA_NAMA $FKAS_TANGGAL.pdf' where PPD_ID = '$PPD_ID'");

            $pdfFilePath = '../../../../../assets/sertifikat/'.$CABANG_DESKRIPSI;

            // Create directory if not exists
            if (!file_exists($pdfFilePath)) {
                mkdir($pdfFilePath, 0777, true);
            }

            //Close and output PDF document
            $pdf->Output(__DIR__ .'/'.$pdfFilePath.'/'.$KAS_ID. ' Kas ' . $KAS_JENIS . ' ' . $ANGGOTA_NAMA . ' ' . $FKAS_TANGGAL.'.pdf', 'F');
            $pdf->Output($KAS_ID. ' Kas ' . $KAS_JENIS . ' ' . $ANGGOTA_NAMA . ' ' . $FKAS_TANGGAL.'.pdf', 'I');

            
            $response = "Success";
            echo $response;
        }
    } catch (\Throwable $th) {
        //throw $th;
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>