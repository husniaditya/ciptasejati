<?php
require_once ("../../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../../../assets/tcpdf/tcpdf.php');

if ($_GET["id"]) {
    $id = $_GET["id"];
    $decodedId = decodeBase64ToId($id);
    $PPD_ID = $decodedId;
    $encodedKoor = encodeIdToBase64('Koor');
    $encodedGuru = encodeIdToBase64('Guru');

    try {
        $getData = GetQuery("SELECT p.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,c.CABANG_KEY,c.CABANG_DESKRIPSI,c.CABANG_SEKRETARIAT,d2.DAERAH_KEY LOKASI_DAERAH_KEY,d2.DAERAH_DESKRIPSI LOKASI_DAERAH,c2.CABANG_KEY LOKASI_CABANG_KEY,c2.CABANG_DESKRIPSI LOKASI_CABANG,t.TINGKATAN_NAMA PPD_TINGKATAN,t.TINGKATAN_SEBUTAN PPD_SEBUTAN,a.ANGGOTA_NAMA,t2.TINGKATAN_NAMA, t2.TINGKATAN_SEBUTAN,DATE_FORMAT(p.PPD_TANGGAL, '%d %M %Y') PPD_TANGGAL,koor.ANGGOTA_NAMA PPD_KOORDINATOR,guru.ANGGOTA_NAMA PPD_GURU,DATE_FORMAT(p.PPD_APPROVE_GURU_TGL, '%d %M %Y') PPD_GURU_TGL,DATE_FORMAT(p.PPD_APPROVE_PELATIH_TGL, '%d %M %Y') PPD_PELATIH_TGL,
        CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
            ELSE 'Ulang'
            END PPD_JENIS_DESKRIPSI
        FROM t_ppd p
        LEFT JOIN m_cabang c ON p.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
        LEFT JOIN m_anggota koor ON p.PPD_APPROVE_PELATIH_BY = koor.ANGGOTA_ID AND p.CABANG_KEY = koor.CABANG_KEY
        LEFT JOIN m_anggota guru ON p.PPD_APPROVE_GURU_BY = guru.ANGGOTA_ID AND guru.ANGGOTA_STATUS = 0
        LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID = t.TINGKATAN_ID
        LEFT JOIN m_tingkatan t2 ON a.TINGKATAN_ID = t2.TINGKATAN_ID
        WHERE p.PPD_ID = '$PPD_ID'");

        $getURL = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'url'");
        while ($urlData = $getURL->fetch(PDO::FETCH_ASSOC)) {
            $URL = $urlData["DESK"];
        }
    
        while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
            extract($data);
    
            // Extend the TCPDF class to create custom Header and Footer
            class MYPDF extends TCPDF {
    
                //Page header
                public function Header() {
                    global $DAERAH_DESKRIPSI,$CABANG_DESKRIPSI,$CABANG_SEKRETARIAT;

                    
                    $this->SetMargins(40, PDF_MARGIN_TOP, 40);
                
                    // Logo
                    $image_ipsi = '../../../../../assets/images/logo/ipsi.png';
                    $this->Image($image_ipsi, 15, 9, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

                    $image_cs = '../../../../../assets/images/logo/logo.png';
                    $this->Image($image_cs, 170, 9, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                
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
                    $this->Cell(40,5,'',0,0,"L");
                    $this->MultiCell(120, 5, $CABANG_SEKRETARIAT . "\n", 0, 'C', 0, 1);
                    $this->Cell(40,5,'',0,0,"L");
                    $this->Ln(-1);
                    // Draw a horizontal line under the header
                    $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
    
                }
    
                // Page footer
                public function Footer() {
                    global $URL, $id, $encodedKoor, $encodedGuru, $USER_NAMA, $CABANG_DESKRIPSI, $PPD_PELATIH_TGL, $PPD_GURU_TGL, $PPD_APPROVE_GURU, $PPD_APPROVE_PELATIH_BY, $PPD_APPROVE_GURU_BY, $PPD_KOORDINATOR, $PPD_GURU, $PPD_APPROVE_PELATIH, $DATENOW;
    
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
                    $this->SetY(-90);
                    $pageWidth = $this->getPageWidth();
                    $this->SetFont('times', '', 12); // Set font for body
                    // Draw a horizontal line under the header
                    $this->Cell(10,5,$CABANG_DESKRIPSI.', '.$PPD_PELATIH_TGL,0,0,"L");
                    $this->Cell(170,5,$CABANG_DESKRIPSI.', '.$PPD_GURU_TGL,0,0,"R");
                    $this->Ln();
                    $this->Cell(10,5,"Koordinator Cabang,",0,0,"L");
                    $this->Cell(170,5,"Guru Besar,",0,0,"R");
                    $this->Ln();
                    // QRCODE,H : QR-CODE Best error correction
                    if ($PPD_APPROVE_PELATIH == 1) {
                        $this->write2DBarcode($URL.'/dashboard/html/assets/token/tokenverify.php?id='.$id.'&data='.$encodedKoor, 'QRCODE,H', 15, 220, 25, 25, $style, 'N');
                    }
                    if ($PPD_APPROVE_GURU == 1) {
                        $this->write2DBarcode($URL.'/dashboard/html/assets/token/tokenverify.php?id='.$id.'&data='.$encodedGuru, 'QRCODE,H', 160, 220, 25, 25, $style, 'N');
                    }
                    $this->Ln(3);
                    $this->SetFont('times', 'U', 12); // Set font for body
                    $this->Cell(10,5,$PPD_KOORDINATOR,0,0,"L");
                    $this->Cell(170,5,$PPD_GURU,0,0,"R");
                    $this->Ln();
                    $this->SetFont('times', '', 12); // Set font for body
                    $this->Cell(10,5,$PPD_APPROVE_PELATIH_BY,0,0,"L");
                    $this->Cell(170,5,$PPD_APPROVE_GURU_BY,0,0,"R");
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
            $pdf->SetTitle($PPD_ID. ' Mutasi Anggota ' . $ANGGOTA_NAMA . '  ' . $PPD_TANGGAL);
    
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
            // ---------------------------------------------------------
    
            // add a page
            $pdf->AddPage();
            // Get the width of the page
            
            $pdf->SetMargins(30, PDF_MARGIN_TOP, 30);

            // Calculate the full width of the cell
            $pageWidth = $pdf->getPageWidth();
            $leftMargin = $pdf->getMargins()['left'];
            $rightMargin = $pdf->getMargins()['right'];
            $fullWidth = $pageWidth - $leftMargin - $rightMargin;
            // Initial position
            $x = 10;
            $y = 10;
            $pdf->SetXY($x, $y); // Set the initial position

            // CONTENT
            $pdf->SetFont('times', 'B', 13); // Set font for body

            $pdf->Ln(35);
            $pdf->MultiCell($fullWidth, 5, 'BERITA ACARA UPACARA PEMBUKAAN PUSAT DAYA' . "\n" . 'INSTITUT SENI BELA DIRI SILAT "CIPTA SEJATI"' . "\n", 0, 'C', 0, 1);

            
            $pdf->SetFont('times', '', 12); // Set font for body
            $pdf->Ln(20);
            $pdf->Cell(35,5,"Nomor Dokumen",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$PPD_ID,0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Tanggal PPD",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$PPD_TANGGAL,0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Jenis PPD",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$PPD_JENIS_DESKRIPSI,0,0,"L");
            
            $pdf->Ln(30);

            $pdf->Cell(35,5,"Menyatakan bahwa anggota di bawah ini :",0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Nama Anggota",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$ANGGOTA_NAMA,0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Tingkatan",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$TINGKATAN_NAMA . ' - ' . $TINGKATAN_SEBUTAN,0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Cabang",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$CABANG_DESKRIPSI . ', ' . $DAERAH_DESKRIPSI,0,0,"L");
            
            $pdf->Ln(15);

            $pdf->Cell(35,5,"Telah dibuka pusat dayanya sesuai dengan tingkatan dengan detail sebagai berikut :",0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Tingkatan " . $PPD_JENIS_DESKRIPSI,0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$PPD_TINGKATAN . ' - ' . $PPD_SEBUTAN,0,0,"L");
            $pdf->Ln();
            $pdf->Cell(35,5,"Cabang PPD ",0,0,"L");
            $pdf->Cell(5,5," : ",0,0,"L");
            $pdf->Cell(35,5,$LOKASI_CABANG . ', ' . $LOKASI_DAERAH,0,0,"L");
            

            
            $pdf->Output($PPD_ID. ' PPD ' . $TINGKATAN_NAMA . ' ' . $ANGGOTA_NAMA . ' ' . $PPD_TANGGAL.'.pdf', 'I');

            $response = "Success";
            echo $response;
        }
    } catch (Exception $e) {
        // Generic exception handling
        $response =  "Caught Exception: " . $e->getMessage();
        echo $response;
    }
}
