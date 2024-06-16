<?php
require_once ("../../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../../../assets/tcpdf/tcpdf.php');

if (isset($_POST['id'])) {
    $PPD_ID = $_POST["id"];

    $getData = GetQuery("SELECT p.*,a.ANGGOTA_NAMA,a.ANGGOTA_TEMPAT_LAHIR,a.ANGGOTA_TANGGAL_LAHIR,a.ANGGOTA_ALAMAT,a.ANGGOTA_PEKERJAAN,a.ANGGOTA_PIC,c.CABANG_DESKRIPSI,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,s.IDSERTIFIKAT_SERTIFIKATFILE,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL, DATE_FORMAT(p.INPUT_DATE, '%d %M %Y') INPUT_DATE, DATE_FORMAT(a.ANGGOTA_TANGGAL_LAHIR, '%d/%m/%Y') ANGGOTA_TANGGAL_LAHIR,REPEAT('_. ', CHAR_LENGTH(a.ANGGOTA_NAMA)) AS ANGGOTA_NAMA_DOT,REPEAT('. ', CHAR_LENGTH(CONCAT(a.ANGGOTA_TEMPAT_LAHIR,', ',DATE_FORMAT(p.PPD_TANGGAL, '%d/%m/%Y')))) AS ANGGOTA_LAHIR_DOT,REPEAT('. ', CHAR_LENGTH(a.ANGGOTA_ALAMAT)) AS ANGGOTA_ALAMAT_DOT,REPEAT('. ', CHAR_LENGTH(a.ANGGOTA_PEKERJAAN)) AS ANGGOTA_PEKERJAAN_DOT,REPEAT('. ', CHAR_LENGTH(p.PPD_DESKRIPSI)) AS PPD_DESKRIPSI_DOT,REPEAT('. ', CHAR_LENGTH(t.TINGKATAN_NAMA)) AS TINGKATAN_NAMA_DOT,REPEAT('. ', CHAR_LENGTH(t.TINGKATAN_SEBUTAN)) AS TINGKATAN_SEBUTAN_DOT,koor.ANGGOTA_ID KOOR_ID,koor.ANGGOTA_NAMA KOOR_NAMA,guru.ANGGOTA_ID GURU_ID,guru.ANGGOTA_NAMA GURU_NAMA
    FROM t_ppd p
    LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
    LEFT JOIN m_anggota koor on p.PPD_APPROVE_PELATIH_BY = koor.ANGGOTA_ID and p.CABANG_KEY = koor.CABANG_KEY
    LEFT JOIN m_anggota guru on p.PPD_APPROVE_GURU_BY = guru.ANGGOTA_ID
    LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_idsertifikat s ON p.TINGKATAN_ID = s.TINGKATAN_ID
    WHERE p.PPD_ID = '$PPD_ID'");

    try {
        while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
            extract($data);

            class MYPDF extends TCPDF {
                //Page header
                public function Header() {
                    // get the current page break margin
                    $bMargin = $this->getBreakMargin();
                    // get current auto-page-break mode
                    $auto_page_break = $this->AutoPageBreak;
                    // disable auto-page-break
                    $this->SetAutoPageBreak(false, 0);
                    // set bacground image
                    $img_file = '../../../../.$IDSERTIFIKAT_SERTIFIKATFILE';
                    $this->Image($img_file, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0);
                    // restore auto-page-break status
                    $this->SetAutoPageBreak(false, 0);
                    // set the starting point for the page content
                    $this->setPageMark();
                }

                // Page footer
                public function Footer() {
                    global $CABANG_DESKRIPSI, $GURU_ID, $GURU_NAMA, $PPD_TANGGAL;

                    $this->SetMargins(40, PDF_MARGIN_TOP, 40);
                    $pageWidth = $this->getPageWidth();
                    $leftMargin = $this->getMargins()['left'];
                    $rightMargin = $this->getMargins()['right'];
                    $fullWidth = $pageWidth - $leftMargin - $rightMargin;

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
                    $this->SetY(-63);
                    $this->SetFont('times', '', 12); // Set font for body
                    // Draw a horizontal line under the header
                    $this->Cell($fullWidth,5,$CABANG_DESKRIPSI.', '.$PPD_TANGGAL,0,0,"R");
                    $this->Ln();
                    $this->Cell($fullWidth,5,"Disetujui Oleh,",0,0,"R");
                    $this->Ln();
                    // QRCODE,H : QR-CODE Best error correction
                    $this->write2DBarcode($GURU_ID.' - '.$GURU_NAMA, 'QRCODE,H', 230, 163, 50, 50, $style, 'N');
                    // $this->Ln(-1);
                    // $this->SetFont('times', 'BU', 15); // Set font for body
                    // $this->Cell($fullWidth,5,$GURU_NAMA,0,0,"R");
                    // $this->Ln(5);
                    // $this->SetFont('times', 'B', 15); // Set font for body
                    // $this->Cell($fullWidth,5,'Guru Besar',0,0,"R");
                }
            }
    
            // create new PDF document
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Cipta Sejati Indonesia');
            $pdf->SetTitle($PPD_ID. ' - PPD ' . $TINGKATAN_NAMA . ' ' . $ANGGOTA_NAMA . '  ' . $PPD_TANGGAL);
            // remove default header/footer
            $pdf->setPrintHeader(false);
    
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
            // ---------------------------------------------------------
    
            // add a page
            $pdf->AddPage('L', 'A4');


            $pdf->SetMargins(40, PDF_MARGIN_TOP, 40);

            // Calculate the full width of the cell
            $pageWidth = $pdf->getPageWidth();
            $leftMargin = $pdf->getMargins()['left'];
            $rightMargin = $pdf->getMargins()['right'];
            $fullWidth = $pageWidth - $leftMargin - $rightMargin;
            // Initial position
            $x = 10;
            $y = 10;
            $pdf->SetXY($x, $y); // Set the initial position
            
            // -- set new background ---
            // get the current page break margin
            $bMargin = $pdf->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $pdf->getAutoPageBreak();
            // disable auto-page-break
            $pdf->SetAutoPageBreak(false, 0);
            // set bacground image
            $img_file = '../../../../.'.$IDSERTIFIKAT_SERTIFIKATFILE;
            $pdf->Image($img_file, 0, 0, 300, 210, '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            $pdf->SetAutoPageBreak(false, 0);
            // set the starting point for the page content
            $pdf->setPageMark();

            // CONTENT
            $pdf->SetFont('times', '', 13); // Set font for body

            $pdf->Ln(54.5);
            $pdf->Cell($fullWidth, 5, "Nomor : " . $PPD_FILE_NAME, 0, 0, "C");
            $pdf->Ln(7);
            $pdf->SetFont('times', 'U', 13); // Set font for body
            $pdf->Cell($fullWidth, 5, "Diberikan kepada:", 0, 0, "C");
            $pdf->SetFont('times', '', 13); // Set font for body
            $pdf->Ln(5);
            $pdf->Cell($fullWidth, 5, "Issued to:", 0, 0, "C");
            $pdf->SetFont('times', 'B', 30); // Set font for body
            $pdf->Ln(10);
            $pdf->Cell($fullWidth, 5, $ANGGOTA_NAMA, 0, 0, "C");
            $pdf->SetFont('times', '', 13); // Set font for body
            $pdf->Ln(12);
            $pdf->Cell($fullWidth, 5, "NIA : " . $ANGGOTA_ID, 0, 0, "C");
            $pdf->Ln(10);

            $pdf->SetFont('times', '', 13); // Set font for body

            $pdf->Cell(50, 5, "Tempat / Tanggal Lahir : ", 0, 0, "L");
            $pdf->Cell(70, 5, $ANGGOTA_TEMPAT_LAHIR . ", " . $ANGGOTA_TANGGAL_LAHIR, 0, 0, "L");
            $pdf->Cell(20, 5, "Alamat : ", 0, 0, "L");

            $currentX = $pdf->GetX();
            $currentY = $pdf->GetY();
            $pdf->SetXY($currentX, $currentY);
            $pdf->MultiCell(100, 5, $ANGGOTA_ALAMAT . "\n", 0, 'J', 0, 1);
            $pdf->SetXY($currentX + 100, $currentY); // Adjust X position after MultiCell
            $pdf->Ln(1); // Adjust the line break to suit your needs

            $pdf->SetXY(40, $pdf->GetY());
            $pdf->Cell(50, 5, "___________________", 0, 0, "L");
            $pdf->Cell(70, 5, $ANGGOTA_LAHIR_DOT, 0, 0, "L");
            $pdf->Cell(20, 5, "______", 0, 0, "L");
            $currentX = $pdf->GetX();
            $currentY = $pdf->GetY();
            $pdf->SetXY($currentX, $currentY);
            $pdf->MultiCell(100, 5, $ANGGOTA_ALAMAT_DOT . "\n", 0, 'J', 0, 1);
            $pdf->SetXY($currentX + 100, $currentY); // Adjust X position after MultiCell
            $pdf->Ln(4.5);

            $pdf->Cell(50,5,"Place / Date of Birth",0,0,"L");
            $pdf->Cell(70,5,"",0,0,"L");
            $pdf->Cell(20,5,"Address",0,0,"L");
            $pdf->Ln(8);
            $pdf->Cell(25,5,"Pekerjaan   : ",0,0,"L");
            $pdf->Cell(50, 5, $ANGGOTA_PEKERJAAN, 0, 0, "L");
            $pdf->Cell(70,5,"Kebangsaan : Indonesia",0,0,"L");
            $pdf->Cell(25,5,"Keterangan : ",0,0,"L");
            $pdf->Cell(65, 5, $PPD_DESKRIPSI, 0, 0, "L");
            $pdf->Ln(1); // Adjust the line break to suit your needs
            $pdf->Cell(25, 5, "_________", 0, 0, "L");
            $pdf->Cell(50, 5, $ANGGOTA_PEKERJAAN_DOT, 0, 0, "L");
            $pdf->Cell(25,5,"__________",0,0,"L");
            $pdf->Cell(45,5," . . . . . . . .",0,0,"L");
            $pdf->Cell(25,5,"_________",0,0,"L");
            $pdf->Cell(65, 5, $PPD_DESKRIPSI_DOT, 0, 0, "L");
            $pdf->Ln(4.5);
            $pdf->Cell(75,5,"Occupation",0,0,"L");
            $pdf->Cell(70,5,"Nationality",0,0,"L");
            $pdf->Cell(90,5,"Remark",0,0,"L");
            $pdf->Ln(7);
            $pdf->Cell($fullWidth,5,"Yang telah mengikuti kegiatan Pembukaan Pusat Daya (PPD) :
            ",0,0,"C");
            $pdf->Ln();
            $pdf->Cell(30,5,"",0,0,"C");
            $pdf->Cell(35,5,"Sabuk Tingkatan : ",0,0,"L");
            $pdf->Cell(55,5,$TINGKATAN_NAMA,0,0,"L");
            $pdf->Cell(15,5,"Gelar : ",0,0,"L");
            $pdf->Cell(75,5,$TINGKATAN_SEBUTAN,0,0,"L");
            $pdf->Cell(30,5,"",0,0,"C");
            $pdf->Ln(1); // Adjust the line break to suit your needs
            $pdf->Cell(30,5,"",0,0,"C");
            $pdf->Cell(35,5,"______________",0,0,"L");
            $pdf->Cell(55,5,$TINGKATAN_NAMA_DOT,0,0,"L");
            $pdf->Cell(15,5,"_____",0,0,"L");
            $pdf->Cell(75,5,$TINGKATAN_SEBUTAN_DOT,0,0,"L");
            $pdf->Ln(4.5);
            $pdf->Cell(30,5,"",0,0,"C");
            $pdf->Cell(90,5,"Belt Level",0,0,"L");
            $pdf->Cell(90,5,"Degree",0,0,"L");
            $pdf->Cell(30,5,"",0,0,"C");
            $pdf->Ln();
            $pdf->Image('../../../../.'.$ANGGOTA_PIC, 70, 155, 40, 40, '', '', 'T', false, 500, '', false, false, 1, false, false, false);

            GetQuery("update t_ppd set PPD_FILE = './assets/sertifikat/$CABANG_DESKRIPSI/$ANGGOTA_ID $ANGGOTA_NAMA/$PPD_ID PPD $TINGKATAN_NAMA $ANGGOTA_NAMA $PPD_TANGGAL.pdf' where PPD_ID = '$PPD_ID'");

            $pdfFilePath = '../../../../../assets/sertifikat/'.$CABANG_DESKRIPSI.'/'.$ANGGOTA_ID.' '.$ANGGOTA_NAMA;

            // Create directory if not exists
            if (!file_exists($pdfFilePath)) {
                mkdir($pdfFilePath, 0777, true);
            }

            //Close and output PDF document
            $pdf->Output(__DIR__ .'/'.$pdfFilePath.'/'.$PPD_ID. ' PPD ' . $TINGKATAN_NAMA . ' ' . $ANGGOTA_NAMA . ' ' . $PPD_TANGGAL.'.pdf', 'F');
            $pdf->Output($PPD_ID. ' PPD ' . $TINGKATAN_NAMA . ' ' . $ANGGOTA_NAMA . ' ' . $PPD_TANGGAL.'.pdf', 'I');

            
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