<?php
require_once ("../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../tcpdf/tcpdf.php');

if (isset($_GET['id'])) {
    $MUTASI_ID = $_GET['id'];
}

$getData = GetQuery("SELECT t.MUTASI_ID,daeawal.DAERAH_KEY AS DAERAH_AWAL_KEY,daeawal.DAERAH_DESKRIPSI AS DAERAH_AWAL_DES,t.CABANG_AWAL,cabawal.CABANG_DESKRIPSI AS CABANG_AWAL_DES,cabawal.CABANG_SEKRETARIAT,daetujuan.DAERAH_KEY AS DAERAH_TUJUAN_KEY,daetujuan.DAERAH_DESKRIPSI AS DAERAH_TUJUAN_DES,t.CABANG_TUJUAN,cabtujuan.CABANG_DESKRIPSI AS CABANG_TUJUAN_DES,cabtujuan.CABANG_SEKRETARIAT AS CABANG_TUJUAN_SEKRETARIAT,a.ANGGOTA_KEY,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t2.TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN,t.MUTASI_DESKRIPSI,t.MUTASI_TANGGAL,t.MUTASI_STATUS,t.MUTASI_STATUS_TANGGAL,t.MUTASI_APPROVE_TANGGAL,a2.ANGGOTA_NAMA INPUT_BY,a2.ANGGOTA_ID INPUT_BY_ID,DATE_FORMAT(t.INPUT_DATE, '%d %M %Y') INPUT_DATE,DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y %H:%i') MUTASI_TGL, DATE_FORMAT(t.MUTASI_STATUS_TANGGAL, '%d %M %Y %H:%i') MUTASI_STATUS_TANGGAL, DATE_FORMAT(t.MUTASI_TANGGAL, '%d %M %Y') TANGGAL_EFEKTIF, DATE_FORMAT(t.MUTASI_APPROVE_TANGGAL, '%d %M %Y') MUTASI_APPROVE_TANGGAL,a3.ANGGOTA_NAMA APPROVE_BY,a2.ANGGOTA_AKSES INPUT_AKSES,a3.ANGGOTA_AKSES APPROVE_AKSES,a3.ANGGOTA_ID APPROVE_BY_ID,
CASE 
    WHEN t.MUTASI_STATUS = '0' THEN 'Menunggu' 
    WHEN t.MUTASI_STATUS = '1' THEN 'Disetujui' 
    ELSE 'Ditolak' 
END AS MUTASI_STATUS_DES
FROM t_mutasi t
LEFT JOIN m_anggota a ON t.ANGGOTA_KEY = a.ANGGOTA_KEY
LEFT JOIN m_anggota a2 ON t.INPUT_BY = a2.ANGGOTA_ID
LEFT JOIN m_anggota a3 ON t.MUTASI_APPROVE_BY = a3.ANGGOTA_ID
LEFT JOIN m_cabang cabawal ON t.CABANG_AWAL = cabawal.CABANG_KEY
LEFT JOIN m_daerah daeawal ON cabawal.DAERAH_KEY = daeawal.DAERAH_KEY
LEFT JOIN m_cabang cabtujuan ON t.CABANG_TUJUAN = cabtujuan.CABANG_KEY
LEFT JOIN m_daerah daetujuan ON cabtujuan.DAERAH_KEY = daetujuan.DAERAH_KEY
left join m_tingkatan t2 on a.TINGKATAN_ID = t2.TINGKATAN_ID
ORDER BY t.MUTASI_STATUS ASC, t.MUTASI_TANGGAL DESC");

while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
    extract($data);

    // Extend the TCPDF class to create custom Header and Footer
    class MYPDF extends TCPDF {

        //Page header
        public function Header() {
            global $DAERAH_AWAL_DES,$CABANG_AWAL_DES, $CABANG_SEKRETARIAT;
        
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
            $this->Write(5,$CABANG_AWAL_DES.' - '.$DAERAH_AWAL_DES, '', 0, 'C', true, 0, false, false, 0);
            $this->Ln(-1);
            $branchWidth = $this->GetStringWidth($CABANG_SEKRETARIAT);
            $this->SetX(($pageWidth - $branchWidth) / 8);
            $this->Write(5, $CABANG_SEKRETARIAT, '', 0, 'C', true, 0, false, false, 0);
            $this->Ln(-1);
            // Draw a horizontal line under the header
            $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);

        }

        // Page footer
        public function Footer() {
            global $USER_NAMA;
            global $DATENOW;
            // Position at 15 mm from bottom
            $this->SetY(-15);
            $pageWidth = $this->getPageWidth();
            // Draw a horizontal line under the header
            $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
            $this->Ln();
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
    $pdf->SetTitle($MUTASI_ID. ' Mutasi Anggota ' . $ANGGOTA_NAMA . '  ' . $TANGGAL_EFEKTIF);

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // ---------------------------------------------------------

    // add a page
    $pdf->AddPage();
    // Get the width of the page
    $pageWidth = $pdf->getPageWidth();

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

    // set some text to print
    $pdf->Ln(30);// Set font for title
    $pdf->SetFont('helvetica', 'BU', 13);
    $pdf->Cell($pageWidth-15,10,"Formulir Mutasi Anggota",0,0,"C");
    $pdf->Ln(15);
    $pdf->SetFont('times', '', 12); // Set font for body
    $pdf->Cell(35,5,"Nomor Dokumen",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$MUTASI_ID,0,0,"L");
    
    
    if ($MUTASI_STATUS == 0) {
        $pdf->Image('../../../images/statusapproval/Pending.png', 140, 50, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    } elseif ($MUTASI_STATUS == 1) {
        $pdf->Image('../../../images/statusapproval/Approved.png', 140, 50, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    } else {
        $pdf->Image('../../../images/statusapproval/Rejected.png', 140, 50, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }

    $pdf->Ln(10);
    $pdf->Cell(35,5,"Tanggal Pengajuan",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$INPUT_DATE,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(35,5,"Tanggal Approval",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$MUTASI_APPROVE_TANGGAL,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(35,5,"Tangal Efektif",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$TANGGAL_EFEKTIF,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(35,5,"Status Dokumen",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$MUTASI_STATUS_DES,0,0,"L");
    $pdf->Ln(15);
    $pdf->SetFont('times','',12);
    $pdf->Cell(20,5,"Yang bertanda tangan di bawah ini: ",0,0,"L");
    $pdf->Ln(10);
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"ID Anggota",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$ANGGOTA_ID,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"Nama",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$ANGGOTA_NAMA,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"Asal Cabang",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$DAERAH_AWAL_DES." - ".$CABANG_AWAL_DES,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"Tingkatan",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$TINGKATAN_NAMA." - ".$TINGKATAN_SEBUTAN,0,0,"L");
    $pdf->Ln(10);
    $pdf->Cell(20,5,"Telah pindah tempat cabang ke: ",0,0,"L");
    $pdf->Ln(10);
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"Cabang Tujuan",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->Cell(30,5,$DAERAH_TUJUAN_DES." - ".$CABANG_TUJUAN_DES,0,0,"L");
    $pdf->Ln();
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"Alamat Cabang",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->MultiCell(120,5,$CABANG_TUJUAN_SEKRETARIAT, 0, 'L', false, 1, '', '', true);
    $pdf->Ln(2);
    $pdf->Cell(10,5,"",0,0,"L");
    $pdf->Cell(30,5,"Deskripsi Mutasi",0,0,"L");
    $pdf->Cell(5,5,":",0,0,"L");
    $pdf->MultiCell(120,5,$MUTASI_DESKRIPSI, 0, 'L', false, 1, '', '', true);
    $pdf->Ln(10);
    $pdf->MultiCell(180,5,"Keputusan ini atas kesepakatan bersama antar dua belah pihak dan akan efektif per tanggal ".$TANGGAL_EFEKTIF.". Dimohon anggota menyelesaikan urusan dengan baik di cabang lama dan menyiapkan segala sesuatunya dengan baik ke cabang yang baru.", 0, 'L', false, 1, '', '', true);
    $pdf->Ln(5);
    $pdf->MultiCell(180,5,"Demikian formulir mutasi anggota ini disampaikan dengan sebenarnya untuk digunakan sebagaimana mestinya.", 0, 'J', false, 1, '', '', true);
    $pdf->Ln(10);
    $pdf->Cell(10,5,$CABANG_AWAL_DES.', '.$INPUT_DATE,0,0,"L");
    $pdf->Cell(170,5,$CABANG_TUJUAN_DES.', '.$MUTASI_APPROVE_TANGGAL,0,0,"R");
    $pdf->Ln();
    $pdf->Cell(10,5,"Diajukan Oleh,",0,0,"L");
    $pdf->Cell(170,5,"Disetujui Oleh,",0,0,"R");
    $pdf->Ln();
    // QRCODE,H : QR-CODE Best error correction
    $pdf->write2DBarcode($INPUT_BY_ID.' - '.$INPUT_BY, 'QRCODE,H', 15, 225, 25, 25, $style, 'N');
    if ($APPROVE_BY) {
        $pdf->write2DBarcode($APPROVE_BY_ID.' - '.$APPROVE_BY, 'QRCODE,H', 160, 225, 25, 25, $style, 'N');
    }
    $pdf->Ln(3);
    $pdf->SetFont('times', 'U', 12); // Set font for body
    $pdf->Cell(10,5,$INPUT_BY,0,0,"L");
    $pdf->Cell(170,5,$APPROVE_BY,0,0,"R");
    $pdf->Ln();
    $pdf->SetFont('times', '', 12); // Set font for body
    $pdf->Cell(10,5,$INPUT_AKSES .' - '.$CABANG_AWAL_DES,0,0,"L");
    $pdf->Cell(170,5,$APPROVE_AKSES.' - '.$CABANG_TUJUAN_DES,0,0,"R");

    // ---------------------------------------------------------
    GetQuery("update t_mutasi set MUTASI_FILE = './assets/report/mutasi/$CABANG_AWAL_DES/$MUTASI_ID Mutasi Anggota $ANGGOTA_NAMA  $TANGGAL_EFEKTIF.pdf' where MUTASI_ID = '$MUTASI_ID'");

    $pdfFilePath = '../../../report/mutasi/'.$CABANG_AWAL_DES;

    // Create directory if not exists
    if (!file_exists($pdfFilePath)) {
        mkdir($pdfFilePath, 0777, true);
    }

    //Close and output PDF document
    $pdf->Output(__DIR__ .'/'.$pdfFilePath.'/'.$MUTASI_ID. ' Mutasi Anggota ' . $ANGGOTA_NAMA . '  ' . $TANGGAL_EFEKTIF.'.pdf', 'F');
    $pdf->Output($MUTASI_ID. ' Mutasi Anggota ' . $ANGGOTA_NAMA . '  ' . $TANGGAL_EFEKTIF.'.pdf', 'I');

    //============================================================+
    // END OF FILE
    //============================================================+
}