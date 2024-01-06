<?php
require_once ("../../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../../../assets/tcpdf/tcpdf.php');

if ($_POST["KAS_ID"]) {
    $KAS_ID = $_POST["KAS_ID"];

    $getData = GetQuery("SELECT k.*,a.ANGGOTA_NAMA,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,c.CABANG_SEKRETARIAT,a2.ANGGOTA_NAMA INPUT_BY, DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
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
    WHERE k.KAS_ID = 'KAS-202401-003'");

    try {
        while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
            extract($data);
    
            // Extend the TCPDF class to create custom Header and Footer
            class MYPDF extends TCPDF {
    
                //Page header
                public function Header() {
                    global $DAERAH_DESKRIPSI,$CABANG_DESKRIPSI, $CABANG_SEKRETARIAT;
                
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
    
                }
    
                // Page footer
                public function Footer() {
                    global $USER_NAMA, $CABANG_TUJUAN_DES, $MUTASI_APPROVE_TANGGAL, $INPUT_BY, $INPUT_BY_ID, $APPROVE_BY, $APPROVE_BY_ID, $INPUT_AKSES, $APPROVE_AKSES, $CABANG_AWAL_DES, $DATENOW, $INPUT_DATE;
    
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
                    $this->Cell(10,5,$CABANG_AWAL_DES.', '.$INPUT_DATE,0,0,"L");
                    $this->Cell(170,5,$CABANG_TUJUAN_DES.', '.$MUTASI_APPROVE_TANGGAL,0,0,"R");
                    $this->Ln();
                    $this->Cell(10,5,"Diajukan Oleh,",0,0,"L");
                    $this->Cell(170,5,"Disetujui Oleh,",0,0,"R");
                    $this->Ln();
                    // QRCODE,H : QR-CODE Best error correction
                    $this->write2DBarcode($INPUT_BY_ID.' - '.$INPUT_BY, 'QRCODE,H', 15, 240, 25, 25, $style, 'N');
                    if ($APPROVE_BY) {
                        $this->write2DBarcode($APPROVE_BY_ID.' - '.$APPROVE_BY, 'QRCODE,H', 160, 240, 25, 25, $style, 'N');
                    }
                    $this->Ln(3);
                    $this->SetFont('times', 'U', 12); // Set font for body
                    $this->Cell(10,5,$INPUT_BY,0,0,"L");
                    $this->Cell(170,5,$APPROVE_BY,0,0,"R");
                    $this->Ln();
                    $this->SetFont('times', '', 12); // Set font for body
                    $this->Cell(10,5,$INPUT_AKSES .' - '.$CABANG_AWAL_DES,0,0,"L");
                    $this->Cell(170,5,$APPROVE_AKSES.' - '.$CABANG_TUJUAN_DES,0,0,"R");
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
            $pdf->SetTitle($MUTASI_ID. ' Mutasi Anggota ' . $ANGGOTA_NAMA . '  ' . $TANGGAL_EFEKTIF);
    
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
            // ---------------------------------------------------------
    
            // add a page
            $pdf->AddPage();
            // Get the width of the page
            $pageWidth = $pdf->getPageWidth();
    
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
                $pdf->Image('../../../../../assets/images/statusapproval/Pending.png', 140, 50, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } elseif ($MUTASI_STATUS == 1) {
                $pdf->Image('../../../../../assets/images/statusapproval/Approved.png', 140, 50, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            } else {
                $pdf->Image('../../../../../assets/images/statusapproval/Rejected.png', 140, 50, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
            $pdf->Cell(30,5,$CABANG_AWAL_DES." - ".$DAERAH_AWAL_DES,0,0,"L");
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
            $pdf->Cell(30,5,$CABANG_TUJUAN_DES." - ".$DAERAH_TUJUAN_DES,0,0,"L");
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
            
    
            // ---------------------------------------------------------
            GetQuery("update t_mutasi set MUTASI_FILE = './assets/report/mutasi/$CABANG_AWAL_DES/$MUTASI_ID Mutasi Anggota $ANGGOTA_NAMA  $TANGGAL_EFEKTIF.pdf' where MUTASI_ID = '$MUTASI_ID'");
    
            $pdfFilePath = '../../../../../assets/report/mutasi/'.$CABANG_AWAL_DES;
    
            // Create directory if not exists
            if (!file_exists($pdfFilePath)) {
                mkdir($pdfFilePath, 0777, true);
            }
    
            //Close and output PDF document
            $pdf->Output(__DIR__ .'/'.$pdfFilePath.'/'.$MUTASI_ID. ' Mutasi Anggota ' . $ANGGOTA_NAMA . '  ' . $TANGGAL_EFEKTIF.'.pdf', 'F');
    
            //============================================================+
            // END OF FILE
            //============================================================+

            $response = "Success";
            echo $response;
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
}
echo "Success";
?>