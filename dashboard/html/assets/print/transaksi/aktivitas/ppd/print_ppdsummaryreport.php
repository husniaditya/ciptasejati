<?php
require_once ("../../../../../module/connection/conn.php");
require_once('../../../../../assets/tcpdf/tcpdf.php');

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

if ($_GET["tgl"] && $_GET["cbg"]) {
    $PPD_TANGGAL = $_GET["tgl"];
    $PPD_LOKASI = $_GET["cbg"];

    try {
        // Fetch main data
        $getData = GetQuery("SELECT 
            p.*, 
            p.PPD_TANGGAL PPD_TANGGAL_DESKRIPSI,
            t.TINGKATAN_NAMA, 
            c.CABANG_DESKRIPSI, 
            c.CABANG_SEKRETARIAT,
            d.DAERAH_DESKRIPSI,
            d2.DAERAH_DESKRIPSI AS LOKASI_DAERAH,
            c2.CABANG_DESKRIPSI AS LOKASI_CABANG,
            koor.ANGGOTA_NAMA AS PPD_KOORDINATOR,
            guru.ANGGOTA_NAMA AS PPD_GURU,
            DATE_FORMAT(p.PPD_APPROVE_GURU_TGL, '%d %M %Y') AS PPD_GURU_TGL,
            DATE_FORMAT(p.PPD_APPROVE_PELATIH_TGL, '%d %M %Y') AS PPD_PELATIH_TGL,
            (SELECT COUNT(p1.ANGGOTA_ID) 
             FROM t_ppd p1 
             WHERE p1.PPD_TANGGAL = '$PPD_TANGGAL' AND p1.PPD_LOKASI = '$PPD_LOKASI' AND p1.PPD_APPROVE_PELATIH = 1) AS TOTAL_PPD
        FROM t_ppd p
        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID
        LEFT JOIN m_cabang c ON p.PPD_LOKASI = c.CABANG_KEY
        LEFT JOIN m_daerah d ON d.DAERAH_KEY = c.DAERAH_KEY
        LEFT JOIN m_cabang c2 ON p.PPD_LOKASI = c2.CABANG_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        LEFT JOIN m_anggota koor ON p.PPD_APPROVE_PELATIH_BY = koor.ANGGOTA_ID AND p.CABANG_KEY = koor.CABANG_KEY
        LEFT JOIN m_anggota guru ON p.PPD_APPROVE_GURU_BY = guru.ANGGOTA_ID AND p.CABANG_KEY = guru.CABANG_KEY
        WHERE p.PPD_TANGGAL = '$PPD_TANGGAL' AND p.PPD_LOKASI = '$PPD_LOKASI' AND p.PPD_APPROVE_PELATIH = 1
        GROUP BY p.TINGKATAN_ID_BARU
        ORDER BY t.TINGKATAN_LEVEL");

        if ($getData->rowCount() > 0) {
            $data = $getData->fetch(PDO::FETCH_ASSOC);

            $LOKASI_CABANG = $data['LOKASI_CABANG'];
            $DAERAH_DESKRIPSI = $data['DAERAH_DESKRIPSI'];
            $CABANG_DESKRIPSI = $data['CABANG_DESKRIPSI'];
            $CABANG_SEKRETARIAT = $data['CABANG_SEKRETARIAT'];
            $TOTAL_PPD = $data['TOTAL_PPD'];
            $PPD_PELATIH_TGL = $data['PPD_PELATIH_TGL'];
            $PPD_GURU_TGL = $data['PPD_GURU_TGL'];
            $PPD_APPROVE_GURU = $data['PPD_APPROVE_GURU'];
            $PPD_APPROVE_PELATIH_BY = $data['PPD_APPROVE_PELATIH_BY'];
            $PPD_APPROVE_GURU_BY = $data['PPD_APPROVE_GURU_BY'];
            $PPD_KOORDINATOR = $data['PPD_KOORDINATOR'];
            $PPD_GURU = $data['PPD_GURU'];
            $PPD_TANGGAL_DESKRIPSI = $data['PPD_TANGGAL_DESKRIPSI'];

            // Create a DateTime object from the date string
            $dateTime1 = new DateTime($PPD_TANGGAL_DESKRIPSI);
            $dateTime2 = new DateTime($PPD_PELATIH_TGL);
            $dateTime3 = new DateTime($PPD_GURU_TGL);

            // Create an instance of IntlDateFormatter
            $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
            $formatter2 = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

            // Set the pattern for the date format you desire
            $formatter->setPattern("EEEE / dd MMMM yyyy");
            $formatter2->setPattern("dd MMMM yyyy");

            // Format the date using the formatter
            $PPD_TANGGAL_DESKRIPSI = $formatter->format($dateTime1);
            $PPD_PELATIH_TGL = $formatter2->format($dateTime2);
            $PPD_GURU_TGL = $formatter2->format($dateTime3);

            // Extend the TCPDF class to create custom Header and Footer
            class MYPDF extends TCPDF {
                public function Header() {
                    global $DAERAH_DESKRIPSI, $CABANG_DESKRIPSI, $CABANG_SEKRETARIAT;

                    $this->SetMargins(40, 45, 40);

                    // Logo
                    $image_ipsi = K_PATH_IMAGES.'/../../../../../../dashboard/html/assets/images/logo/IPSI.png';
                    $this->Image($image_ipsi, 15, 9, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

                    $image_cs = K_PATH_IMAGES.'/../../../../../../dashboard/html/assets/images/logo/Logo.png';
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
                    $this->Write(5, $CABANG_DESKRIPSI . ' - ' . $DAERAH_DESKRIPSI, '', 0, 'C', true, 0, false, false, 0);
                    $this->Ln(-1);
                    $branchWidth = $this->GetStringWidth($CABANG_SEKRETARIAT);
                    $this->SetX(($pageWidth - $branchWidth) / 8);
                    $this->Cell(40, 5, '', 0, 0, "L");
                    $this->MultiCell(120, 5, $CABANG_SEKRETARIAT . "\n", 0, 'C', 0, 1);
                    $this->Cell(40, 5, '', 0, 0, "L");
                    $this->Ln(-1);
                    // Draw a horizontal line under the header
                    $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
                }

                public function Footer() {
                    global $USER_NAMA, $CABANG_DESKRIPSI, $PPD_PELATIH_TGL, $PPD_GURU_TGL, $PPD_APPROVE_GURU, $PPD_APPROVE_PELATIH_BY, $PPD_APPROVE_GURU_BY, $PPD_KOORDINATOR, $PPD_GURU, $DATENOW;
                    $style = array('border' => true, 'vpadding' => 'auto', 'hpadding' => 'auto', 'fgcolor' => array(0, 0, 0), 'bgcolor' => false, 'module_width' => 1, 'module_height' => 1);
                    $this->SetY(-90);
                    $pageWidth = $this->getPageWidth();
                    $this->SetFont('times', '', 12);
                    $this->Cell(180, 5, $CABANG_DESKRIPSI . ', ' . $PPD_GURU_TGL, 0, 0, "R");
                    $this->Ln();
                    $this->Cell(180, 5, "Guru Besar,", 0, 0, "R");
                    $this->Ln();
                    if ($PPD_APPROVE_GURU == 1) {
                        $this->write2DBarcode($PPD_APPROVE_GURU_BY . ' - ' . $PPD_GURU, 'QRCODE,H', 160, 220, 25, 25, $style, 'N');
                    }
                    $this->Ln(3);
                    $this->SetFont('times', 'U', 12);
                    $this->Cell(180, 5, $PPD_GURU, 0, 0, "R");
                    $this->Ln();
                    $this->SetFont('times', '', 12);
                    $this->Cell(180, 5, $PPD_APPROVE_GURU_BY, 0, 0, "R");
                    $this->Ln(10);
                    $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
                    $this->Ln(1);
                    $this->SetFont('helvetica', '', 8);
                    $this->Cell(0, 10, 'Dicetak Oleh: ' . $USER_NAMA . " (" . $DATENOW . ")", 0, 0, 'L');
                    $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
                }
            }

            // Create PDF
            $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('PPD ' . $LOKASI_CABANG . ' ' . $PPD_TANGGAL);
            $pdf->SetAutoPageBreak(true, 50);

            // Page 1
            $pdf->AddPage();
            $pdf->SetMargins(25, PDF_MARGIN_TOP, 30);

            $pageWidth = $pdf->getPageWidth();
            $leftMargin = $pdf->getMargins()['left'];
            $rightMargin = $pdf->getMargins()['right'];
            $fullWidth = $pageWidth - $leftMargin - $rightMargin;
            $pdf->SetXY(10, 10);
            $pdf->SetFont('times', 'B', 13);
            $pdf->Ln(35);
            $pdf->MultiCell($fullWidth, 5, 'BERITA ACARA UPACARA PEMBUKAAN PUSAT DAYA' . "\n" . 'INSTITUT SENI BELA DIRI SILAT "CIPTA SEJATI"' . "\n", 0, 'C');
            $pdf->Ln(20);
            $pdf->SetFont('times', '', 12);
            $pdf->Cell(25, 5, "Cabang", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(25, 5, $LOKASI_CABANG . ', ' . $DAERAH_DESKRIPSI, 0, 0, "L");
            $pdf->Ln();
            $pdf->Cell(25, 5, "Jumlah Peserta", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(25, 5, $TOTAL_PPD . ' Orang', 0, 0, "L");
            $pdf->Ln(20);
            $pdf->Cell(25, 5, "Terdiri Dari", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Ln(10);

            // Fetch member data
            $getData->execute(); // Execute again to reset cursor
            while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
                $getAnggota = GetQuery("SELECT t.TINGKATAN_NAMA AS TINGKATAN_ANGGOTA, COUNT(p.ANGGOTA_ID) AS COUNT_ANGGOTA 
                                        FROM t_ppd p
                                        LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID
                                        WHERE p.PPD_TANGGAL = '$PPD_TANGGAL' AND p.PPD_LOKASI = '$PPD_LOKASI' AND p.TINGKATAN_ID_BARU = '" . $data['TINGKATAN_ID_BARU'] . "' AND p.PPD_APPROVE_PELATIH = 1
                                        GROUP BY p.TINGKATAN_ID_BARU");

                while ($row = $getAnggota->fetch(PDO::FETCH_ASSOC)) {
                    $pdf->Cell(25, 5, $row['TINGKATAN_ANGGOTA'], 0, 0, "L");
                    $pdf->Cell(5, 5, " : ", 0, 0, "L");
                    $pdf->Cell(25, 5, $row['COUNT_ANGGOTA'] . ' Orang', 0, 0, "L");
                    $pdf->Ln();
                }
            }
            
            $pdf->Ln(20);
            $pdf->MultiCell($fullWidth, 5, "Telah dibuka pusat dayanya sesuai dengan tingkat masing-masing, pada hari / tanggal " . $PPD_TANGGAL_DESKRIPSI . "." . "\n", 0, 'J', 0, 1);

            // Page 2
            $pdf->AddPage();
            $pdf->SetMargins(10, PDF_MARGIN_TOP, 10);
            $pdf->SetAutoPageBreak(true, 90);
            $pdf->SetFont('times', 'BU', 13);
            $pdf->MultiCell(190, 5, 'DAFTAR PESERTA PEMBUKAAN PUSAT DAYA' . "\n", 0, 'C');
            $pdf->Ln();
            

            $getTingkatan = GetQuery("SELECT t.TINGKATAN_ID,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,COUNT(p.ANGGOTA_ID) COUNT_TINGKATAN FROM t_ppd p
            LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_BARU = t.TINGKATAN_ID
            WHERE p.PPD_TANGGAL = '$PPD_TANGGAL' AND p.PPD_LOKASI = '$PPD_LOKASI' AND p.PPD_APPROVE_PELATIH = 1
            GROUP BY t.TINGKATAN_ID
            ORDER BY t.TINGKATAN_LEVEL");

            while ($dataTingkatan = $getTingkatan->fetch(PDO::FETCH_ASSOC)) {
                extract($dataTingkatan);
                
                $pdf->SetFont('times', 'B', 11);
                $pdf->Cell(30, 5, "Sabuk / Tingkatan", 0, 0, "L");
                $pdf->Cell(5, 5, " : ", 0, 0, "L");
                $pdf->Cell(70, 5, $TINGKATAN_NAMA . ' / ' . $TINGKATAN_SEBUTAN, 0, 0, "L");
                $pdf->Cell(35, 5, "", 0, 0, "L");
                $pdf->Cell(30, 5, 'Jumlah Peserta', 0, 0, "L");
                $pdf->Cell(5, 5, " : ", 0, 0, "L");
                $pdf->Cell(25, 5, $COUNT_TINGKATAN . ' Orang', 0, 0, "L");
                $pdf->Ln();
                $pdf->Cell(7, 5, "No.", 1, 0, "C");
                $pdf->Cell(42, 5, "Nama Anggota", 1, 0, "C");
                $pdf->Cell(30, 5, "ID Anggota", 1, 0, "C");
                $pdf->Cell(30, 5, "Keanggotaan", 1, 0, "C");
                $pdf->Cell(30, 5, "Tingkatan Lama", 1, 0, "C");
                $pdf->Cell(30, 5, "Tingkatan Baru", 1, 0, "C");
                $pdf->Cell(20, 5, "Status PPD", 1, 0, "C");
                $pdf->Ln();
                $pdf->SetFont('times', '', 10);

                $getAnggotaTingkatan = GetQuery("SELECT ROW_NUMBER() OVER (ORDER BY p.ANGGOTA_ID) AS row_num,SUBSTRING_INDEX(CONCAT_WS(' ', a.ANGGOTA_NAMA), ' ', 2) ANGGOTA_NAMA,a.ANGGOTA_ID,a.ANGGOTA_RANTING KEANGGOTAAN,t.TINGKATAN_NAMA TINGKATAN_LAMA,t2.TINGKATAN_NAMA TINGKATAN_BARU,
                CASE WHEN p.PPD_JENIS = 0 THEN 'Kenaikan'
                ELSE 'Ulang'
                END PPD_JENIS
                FROM t_ppd p
                LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND p.CABANG_KEY = a.CABANG_KEY
                LEFT JOIN m_tingkatan t ON p.TINGKATAN_ID_LAMA = t.TINGKATAN_ID
                LEFT JOIN m_tingkatan t2 ON p.TINGKATAN_ID_BARU = t2.TINGKATAN_ID
                WHERE p.PPD_TANGGAL = '$PPD_TANGGAL' AND p.PPD_LOKASI = '$PPD_LOKASI' AND p.TINGKATAN_ID_BARU = '$TINGKATAN_ID' AND p.PPD_APPROVE_PELATIH = 1");

                while ($dataAnggota = $getAnggotaTingkatan->fetch(PDO::FETCH_ASSOC)) {
                    extract($dataAnggota);
                    $pdf->Cell(7, 5, $row_num . '.', 1, 0, "C");
                    $pdf->Cell(42, 5, $ANGGOTA_NAMA, 1, 0, "L");
                    $pdf->Cell(30, 5, $ANGGOTA_ID, 1, 0, "C");
                    $pdf->Cell(30, 5, $KEANGGOTAAN, 1, 0, "C");
                    $pdf->Cell(30, 5, $TINGKATAN_LAMA, 1, 0, "C");
                    $pdf->Cell(30, 5, $TINGKATAN_BARU, 1, 0, "C");
                    $pdf->Cell(20, 5, $PPD_JENIS, 1, 0, "C");
                    $pdf->Ln();
                }
                
                $pdf->Ln(7);
            }

            $pdf->Output('PPD ' . $LOKASI_CABANG . ' ' . $PPD_TANGGAL . '.pdf', 'I');

            echo "Success";
        } else {
            echo "No data found";
        }
    } catch (Exception $e) {
        echo "Caught Exception: " . $e->getMessage();
    }
} else {
    echo "Invalid parameters";
}
?>
