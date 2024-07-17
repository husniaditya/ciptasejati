<?php
require_once ("../../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../../../assets/tcpdf/tcpdf.php');

if (isset($_POST['id'])) {
    $KAS_ID = $_POST["id"];
    $encodedAnggota = encodeIdToBase64('Anggota');
    $encodedKoor = encodeIdToBase64('Koor');
    $encodedGuru = encodeIdToBase64('Guru');

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

            $getSaldo = GetQuery("SELECT
                IFNULL(SALDOAWAL, 0) AS SALDOAWAL,
                IFNULL(SALDOAKHIR, 0) AS SALDOAKHIR
            FROM (
                SELECT
                    CASE
                        WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
                        ELSE FORMAT(SUM(KAS_JUMLAH), 0)
                    END AS SALDOAWAL
                FROM
                    t_kas
                WHERE
                    DELETION_STATUS = 0
                    AND ANGGOTA_KEY = '$ANGGOTA_KEY'
                    AND KAS_JENIS = '$KAS_JENIS'
                    AND KAS_ID < '$KAS_ID'
            ) AS Subquery1
            CROSS JOIN (
                SELECT
                    CASE
                        WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
                        ELSE FORMAT(SUM(KAS_JUMLAH), 0)
                    END AS SALDOAKHIR
                FROM
                    t_kas
                WHERE
                    DELETION_STATUS = 0
                    AND ANGGOTA_KEY = '$ANGGOTA_KEY'
                    AND KAS_JENIS = '$KAS_JENIS'
                    AND KAS_ID <= '$KAS_ID'
            ) AS Subquery2");

            while ($rowSaldo = $getSaldo->fetch(PDO::FETCH_ASSOC)) {
                extract($rowSaldo);
            }

            $getSaldoAwal = GetQuery("SELECT FORMAT(IFNULL(SUM(KAS_JUMLAH),0), 0) AS SALDOAWAL FROM t_kas WHERE DELETION_STATUS = 0 AND ANGGOTA_KEY = '$ANGGOTA_KEY' AND KAS_JENIS = '$KAS_JENIS' AND KAS_ID < '$KAS_ID'");
    
            // Extend the TCPDF class to create custom Header and Footer
            class MYPDF extends TCPDF {
    
                //Page header
                public function Header() {
                    global $DAERAH_DESKRIPSI,$CABANG_DESKRIPSI, $CABANG_SEKRETARIAT, $KAS_JENIS, $KAS_ID, $FKAS_TANGGAL, $ANGGOTA_ID, $ANGGOTA_NAMA, $SALDOAWAL;
                
                    // Logo
                    $image_file = K_PATH_IMAGES.'/../../../../../../img/logo/logo_rev.png';
                    $this->Image($image_file, 15, 9, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                
                    // Set font
                    $this->SetFont('helvetica', 'B', 14);
                
                    // Get the width of the page
                    $pageWidth = $this->getPageWidth();
                
                    // Title
                    $title = "Institut Seni Bela Diri Silat";
                    $titleWidth = $this->GetStringWidth($title);
                
                    $this->SetX(($pageWidth - $titleWidth) / 2); // Centering the title
                    $this->Write(5, $title, '', 0, 'L', true, 0, false, false, 0);
                    $this->Ln(1);
                    $this->Write(5, 'CIPTA SEJATI', '', 0, 'C', true, 0, false, false, 0);
                    $this->Ln(1);
                
                    $this->SetFont('helvetica', '', 8);
                
                    // Centering the branch information
                    $this->Write(5,$CABANG_DESKRIPSI.' - '.$DAERAH_DESKRIPSI, '', 0, 'C', true, 0, false, false, 0);
                    $this->Ln(-1);
                    $branchWidth = $this->GetStringWidth($CABANG_SEKRETARIAT);
                    $this->SetX(($pageWidth - $branchWidth) / 8);
                    $this->Write(5, $CABANG_SEKRETARIAT, '', 0, 'C', true, 0, false, false, 0);
                    $this->Ln(-1);
                    // Draw a horizontal line under the header
                    $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
                    $this->Ln(5);
                    $this->SetFont('helvetica', 'BU', 13);
                    $this->Cell($pageWidth-15,10,"Formulir Kas " .$KAS_JENIS,0,0,"C");
                    $this->Ln(20);
                    $this->SetFont('times', 'B', 11); // Set font for body
                    $this->Cell(35,5,"ID Anggota",0,0,"L");
                    $this->Cell(5,5,":",0,0,"L");
                    $this->Cell(30,5,$ANGGOTA_ID,0,0,"L");
                    $this->Ln();
                    $this->Cell(35,5,"Nama Anggota",0,0,"L");
                    $this->Cell(5,5,":",0,0,"L");
                    $this->Cell(30,5,$ANGGOTA_NAMA,0,0,"L");
                    $this->Ln(15);
                    // Header Row
                    $this->Cell(30, 10, "", 'LTR', 0, "C");
                    $this->Cell(35, 10, "", 'LTR', 0, "C");
                    $this->Cell(100, 10, "Tabungan", 1, 0, "C");
                    $this->Cell(25, 10, "", 'LTR', 0, "C");
                    $this->Ln();

                    // Subheader Row
                    $this->Cell(30, 5, "Tanggal", 'LR', 0, "C");
                    $this->Cell(35, 5, "No Dokumen", 'LR', 0, "C");
                    $this->Cell(15, 5, "Jenis", 1, 0, "C");
                    $this->Cell(25, 5, "Jumlah (Rp)", 1, 0, "C");
                    $this->Cell(30, 5, "Saldo Awal (Rp)", 1, 0, "C");
                    $this->Cell(30, 5, "Saldo Akhir (Rp)", 1, 0, "C");
                    $this->Cell(25, 5, "TTD", 'LR', 0, "C");
                    $this->Ln();
                    $this->Cell(30, 10, "", 'LR', 0, "C");
                    $this->Cell(35, 10, "", 'LR', 0, "C");
                    $this->Cell(15, 10, "", 'LR', 0, "C");
                    $this->Cell(25, 10, "", 'LR', 0, "C");
                    $this->Cell(30, 10, $SALDOAWAL .' ', 'LR', 0, "R");
                    $this->Cell(30, 10, "", 'LR', 0, "C");
                    $this->Cell(25, 10, "", 'LR', 0, "C");
                    $this->Ln();
    
                }

                
                // Page footer
                public function Footer() {
                    global $id, $encodedAnggota, $encodedKoor, $USER_NAMA, $CABANG_DESKRIPSI, $ANGGOTA_ID, $INPUT_BY, $INPUT_BY_ID, $APPROVE_BY, $ANGGOTA_NAMA, $INPUT_AKSES, $ANGGOTA_AKSES, $ANGOTA_NAMA, $DATENOW, $INPUT_DATE;

                    // set style for barcode
                    $style = array(
                        'border' => true,
                        'vpadding' => 'auto',
                        'hpadding' => 'auto',
                        'fgcolor' => array(0,0,0),
                        'bgcolor' => false, //array(255,255,255)
                        'module_width' => 1, // width of a single module in points
                        'module_height' => 1 // height of a single module in points
                    );
                    
                    // Position at 15 mm from bottom
                    $this->SetY(-70);
                    $pageWidth = $this->getPageWidth();
                    $this->SetFont('times', '', 12); // Set font for body
                    // Draw a horizontal line under the header
                    $this->Cell(10,5,$CABANG_DESKRIPSI.', '.$INPUT_DATE,0,0,"L");
                    $this->Cell(170,5,$CABANG_DESKRIPSI.', '.$INPUT_DATE,0,0,"R");
                    $this->Ln();
                    $this->Cell(10,5,"Diajukan Oleh,",0,0,"L");
                    $this->Cell(170,5,"Diinput Oleh,",0,0,"R");
                    $this->Ln();
                    // QRCODE,H : QR-CODE Best error correction
                    $this->write2DBarcode('http://localhost/ciptasejati/dashboard/html/assets/token/tokenverify.php?id='.$id.'&data='.$encodedAnggota, 'QRCODE,H', 15, 240, 25, 25, $style, 'N');
                    if ($INPUT_BY) {
                        $this->write2DBarcode('http://localhost/ciptasejati/dashboard/html/assets/token/tokenverify.php?id='.$id.'&data='.$encodedKoor, 'QRCODE,H', 160, 240, 25, 25, $style, 'N');
                    }
                    $this->Ln(3);
                    $this->SetFont('times', 'U', 12); // Set font for body
                    $this->Cell(10,5,$ANGGOTA_NAMA,0,0,"L");
                    $this->Cell(170,5,$INPUT_BY,0,0,"R");
                    $this->Ln();
                    $this->SetFont('times', '', 12); // Set font for body
                    $this->Cell(10,5,$ANGGOTA_AKSES .' - '.$CABANG_DESKRIPSI,0,0,"L");
                    $this->Cell(170,5,$INPUT_AKSES.' - '.$CABANG_DESKRIPSI,0,0,"R");
                    $this->Ln(10);
                    $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
                    $this->Ln(1);
                    $this->SetFont('helvetica', '', 8); // Set font for body
                    $this->Cell(0, 10, 'Dicetak Oleh: '. $USER_NAMA.' / '.$DATENOW, 0, false, 'L', 0, '', 0, false, 'T', 'M');
                    
                    // Set font
                    $this->SetFont('helvetica', 'I', 8);// Get the width of the page
                    // Page number
                    $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
                }
            }
    
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
    
            GetQuery("update t_kas set KAS_FILE = './assets/report/kas/$CABANG_DESKRIPSI/$KAS_ID Kas $KAS_JENIS $ANGGOTA_NAMA $FKAS_TANGGAL.pdf' where KAS_ID = '$KAS_ID'");

            $pdfFilePath = '../../../../../assets/report/kas/'.$CABANG_DESKRIPSI;

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