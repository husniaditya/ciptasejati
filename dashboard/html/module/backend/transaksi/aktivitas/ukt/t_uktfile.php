<?php
require_once ("../../../../../module/connection/conn.php");
require_once('../../../../../assets/tcpdf/tcpdf.php');

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

if (isset($_POST['id'])) {
    $UKT_ID = $_POST["id"];

    try {
        // Fetch main data
        $getData = GetQuery("SELECT u.*,d.DAERAH_KEY,d.DAERAH_DESKRIPSI,d2.DAERAH_DESKRIPSI UKT_DAERAH,c.CABANG_DESKRIPSI,c.CABANG_SEKRETARIAT,c2.CABANG_DESKRIPSI UKT_CABANG,a.ANGGOTA_ID,a.ANGGOTA_NAMA,a.ANGGOTA_RANTING,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,t2.TINGKATAN_NAMA UKT_TINGKATAN_NAMA,t2.TINGKATAN_SEBUTAN UKT_TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(u.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,DATE_FORMAT(u.UKT_TANGGAL, '%d %M %Y') UKT_TANGGAL_DESKRIPSI,koor.ANGGOTA_ID KOOR_ID,koor.ANGGOTA_NAMA KOOR_NAMA,koor.ANGGOTA_AKSES KOOR_AKSES,guru.ANGGOTA_ID GURU_ID,guru.ANGGOTA_NAMA GURU_NAMA,
        CASE
        WHEN u.UKT_TOTAL >= 85 THEN 'A'
        WHEN u.UKT_TOTAL >= 75 THEN 'B'
        WHEN u.UKT_TOTAL >= 60 THEN 'C'
        WHEN u.UKT_TOTAL >= 40 THEN 'D'
        ELSE 'E' END UKT_NILAI
        FROM t_ukt u
        LEFT JOIN m_anggota a ON u.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
        LEFT JOIN m_anggota a2 ON u.INPUT_BY = a2.ANGGOTA_ID AND u.CABANG_KEY = a2.CABANG_KEY
        LEFT JOIN m_anggota koor ON u.UKT_APP_KOOR_BY = koor.ANGGOTA_ID AND u.CABANG_KEY = koor.CABANG_KEY
        LEFT JOIN m_anggota guru ON u.UKT_APP_GURU_BY = guru.ANGGOTA_ID
        LEFT JOIN m_cabang c ON u.CABANG_KEY = c.CABANG_KEY
        LEFT JOIN m_cabang c2 ON u.UKT_LOKASI = c2.CABANG_KEY
        LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
        LEFT JOIN m_daerah d2 ON c2.DAERAH_KEY = d2.DAERAH_KEY
        LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
        LEFT JOIN m_tingkatan t2 ON t2.TINGKATAN_ID = u.TINGKATAN_ID
        WHERE u.DELETION_STATUS = 0 AND u.UKT_ID = '$UKT_ID'");

        if ($getData->rowCount() > 0) {
            $data = $getData->fetch(PDO::FETCH_ASSOC);

            $UKT_LOKASI = $data['UKT_LOKASI'];
            $DAERAH_DESKRIPSI = $data['DAERAH_DESKRIPSI'];
            $CABANG_KEY = $data['CABANG_KEY'];
            $CABANG_DESKRIPSI = $data['CABANG_DESKRIPSI'];
            $CABANG_SEKRETARIAT = $data['CABANG_SEKRETARIAT'];
            $UKT_DAERAH = $data['UKT_DAERAH'];
            $UKT_CABANG = $data['UKT_CABANG'];
            $ANGGOTA_ID = $data['ANGGOTA_ID'];
            $ANGGOTA_NAMA = $data['ANGGOTA_NAMA'];
            $ANGGOTA_RANTING = $data['ANGGOTA_RANTING'];
            $TINGKATAN_ID = $data['TINGKATAN_ID'];
            $UKT_TINGKATAN_NAMA = $data['UKT_TINGKATAN_NAMA'];
            $UKT_TINGKATAN_SEBUTAN = $data['UKT_TINGKATAN_SEBUTAN'];
            $UKT_TOTAL = $data['UKT_TOTAL'];
            $UKT_NILAI = $data['UKT_NILAI'];
            $UKT_DESKRIPSI = $data['UKT_DESKRIPSI'];
            $UKT_APP_KOOR_DATE = $data['UKT_APP_KOOR_DATE'];
            $UKT_APP_KOOR_BY = $data['UKT_APP_KOOR_BY'];
            $KOOR_NAMA = $data["KOOR_NAMA"];
            $KOOR_AKSES = $data["KOOR_AKSES"];
            $UKT_APP_KOOR = $data['UKT_APP_KOOR'];
            $UKT_APP_GURU_DATE = $data['UKT_APP_GURU_DATE'];
            $UKT_APP_GURU_BY = $data['UKT_APP_GURU_BY'];
            $GURU_NAMA = $data['GURU_NAMA'];
            $UKT_APP_GURU = $data['UKT_APP_GURU'];
            $UKT_TANGGAL = $data['UKT_TANGGAL'];
            $UKT_TANGGAL_DESKRIPSI = $data['UKT_TANGGAL_DESKRIPSI'];
            $UKT_CABANG = $data["UKT_CABANG"];

            // Create a DateTime object from the date string
            $dateTime1 = new DateTime($UKT_TANGGAL);
            $dateTime2 = new DateTime($UKT_APP_KOOR_DATE);

            // Create an instance of IntlDateFormatter
            $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
            $formatter2 = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

            // Set the pattern for the date format you desire
            $formatter->setPattern("EEEE / dd MMMM yyyy");
            $formatter2->setPattern("dd MMMM yyyy");

            // Format the date using the formatter
            $UKT_TANGGAL_DESKRIPSI = $formatter->format($dateTime1);
            $UKT_APP_KOOR_DATE = $formatter2->format($dateTime2);

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
                    global $UKT_ID, $USER_NAMA, $UKT_APP_KOOR, $UKT_APP_KOOR_BY, $KOOR_ID, $DATENOW;
                
                    $style = array('border' => true, 'vpadding' => 'auto', 'hpadding' => 'auto', 'fgcolor' => array(0, 0, 0), 'bgcolor' => false, 'module_width' => 1, 'module_height' => 1);
                
                    $this->SetY(-85);
                    $pageWidth = $this->getPageWidth();
                    $this->SetFont('times', '', 12);
                    $this->Cell(0, 5, "TANDA TANGAN TIM PENGUJI", 0, 1, "C");
                    $this->Ln(-5);
                    $this->Cell(0, 0, '', 'B', 1, 'C');

                
                    $getPenguji = GetQuery("SELECT p.*, a.ANGGOTA_NAMA, t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN
                                            FROM t_ukt_penguji p
                                            LEFT JOIN t_ukt u ON p.UKT_ID = u.UKT_ID
                                            LEFT JOIN m_anggota a ON p.ANGGOTA_ID = a.ANGGOTA_ID AND u.CABANG_KEY = a.CABANG_KEY
                                            LEFT JOIN m_tingkatan t ON t.TINGKATAN_ID = a.TINGKATAN_ID
                                            WHERE p.UKT_ID = '$UKT_ID' AND p.DELETION_STATUS = 0");
                
                    $rowNama = $getPenguji->fetchAll(PDO::FETCH_ASSOC);
                
                    // Set the initial Y position for the QR codes
                    $startX = 10; // starting X position for the first cell
                    $startY = $this->GetY() + 10; // starting Y position
                    $cellWidth = 60; // Width of each cell
                    $cellHeight = 5; // Height of each cell
                    $qrSize = 30; // Size of the QR code
                
                    foreach ($rowNama as $index => $row) {
                        extract($row);
                
                        // Calculate the X position for the QR code
                        $qrX = $startX + ($index * $cellWidth) + ($cellWidth / 2) - ($qrSize / 2);
                        $qrY = $startY;
                
                        // Draw the QR code
                        $this->write2DBarcode($ANGGOTA_ID . ' - ' . $ANGGOTA_NAMA, 'QRCODE,H', $qrX, $qrY, $qrSize, $qrSize, $style, 'N');
                
                        // Set X and Y for the cell content below the QR code
                        $contentX = $startX + ($index * $cellWidth);
                        $contentY = $qrY + $qrSize + 5;
                        
                        // Set position for the name
                        $this->SetFont('times', 'U', 12);
                        $this->SetXY($contentX, $contentY);
                        $this->Cell($cellWidth, $cellHeight, $ANGGOTA_NAMA, 0, 2, "C");
                
                        // Set position for the tingkatan
                        $this->SetFont('times', '', 12);
                        $this->SetXY($contentX, $this->GetY());
                        $this->Cell($cellWidth, $cellHeight, $TINGKATAN_NAMA . ' - ' . $TINGKATAN_SEBUTAN, 0, 2, "C");
                    }
                
                    // Move to a new line after the last entry
                    $this->Ln();
                    $this->Line(10, $this->GetY() + 2, $pageWidth - 10, $this->GetY() + 2);
                    $this->SetFont('helvetica', '', 8);
                    $this->Cell(0, 10, 'Dicetak Oleh: ' . $USER_NAMA . " (" . $DATENOW . ")", 0, 0, 'L');
                    $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
                }                             
            }

            // Create PDF
            $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('UKT ' . $UKT_CABANG . ' ' . $UKT_TANGGAL);
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
            $pdf->Ln(30);
            $pdf->MultiCell($fullWidth, 5, 'FORM PENILAIAN UJI KENAIKAN TINGKAT - UKT' . "\n", 0, 'C');
            $pdf->Ln();
            
            $pdf->SetFont('times', '', 12);
            $pdf->Cell(27, 5, "ID Anggota", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(27, 5, $ANGGOTA_ID, 0, 0, "L");
            $pdf->Cell(35, 5, "", 0, 0, "L");
            $pdf->Cell(25, 5, "Cabang", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(25, 5, $CABANG_DESKRIPSI, 0, 0, "L");
            $pdf->Ln();
            $pdf->Cell(27, 5, "Nama Anggota", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(27, 5, $ANGGOTA_NAMA, 0, 0, "L");
            $pdf->Cell(35, 5, "", 0, 0, "L");
            $pdf->Cell(25, 5, "Ranting", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(25, 5, $ANGGOTA_RANTING, 0, 0, "L");
            $pdf->Ln();
            $pdf->Cell(27, 5, "Sabuk / Tingkat", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(27, 5, $UKT_TINGKATAN_NAMA . " / " . $UKT_TINGKATAN_SEBUTAN, 0, 0, "L");
            $pdf->Cell(35, 5, "", 0, 0, "L");
            $pdf->Cell(25, 5, "Tanggal UKT", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(25, 5, $UKT_TANGGAL_DESKRIPSI, 0, 0, "L");
            $pdf->Ln(10);

            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(10, 5, "No", 1, 0, "C");
            $pdf->Cell(105, 5, "Materi", 1, 0, "C");
            $pdf->Cell(15, 5, "Nilai", 1, 0, "C");
            $pdf->Cell(15, 5, "Bobot", 1, 0, "C");
            $pdf->Cell(15, 5, "Skor", 1, 0, "C");
            $pdf->Ln();

            $getMateri  = GetQuery("SELECT
                m.MATERI_ID,
                m.MATERI_DESKRIPSI,
                m.MATERI_BOBOT,
                t.TOTAL_NILAI,
                ROW_NUMBER() OVER (ORDER BY m.MATERI_ID) AS rn
            FROM m_materi m
            LEFT JOIN (
                SELECT MATERI_ID, SUM(UKT_DETAIL_NILAI * (UKT_BOBOT / 100)) AS TOTAL_NILAI
                FROM t_ukt_detail
                WHERE UKT_ID = '$UKT_ID'
                GROUP BY MATERI_ID
            ) t ON m.MATERI_ID = t.MATERI_ID
            WHERE m.CABANG_KEY = '$CABANG_KEY' AND m.TINGKATAN_ID = '$TINGKATAN_ID'");

            while ($rowMateri = $getMateri->fetch(PDO::FETCH_ASSOC)) {
                extract($rowMateri);
                
                $pdf->SetFont('times', 'B', 12);
                $pdf->Cell(10, 5, $rn . ".", 1, 0, "C");
                $pdf->Cell(105, 5, $MATERI_DESKRIPSI, 1, 0, "L");
                $pdf->Cell(15, 5, "", 1, 0, "C");
                $pdf->Cell(15, 5, $MATERI_BOBOT . "%", 1, 0, "C");
                $pdf->Cell(15, 5, $TOTAL_NILAI, 1, 0, "C");
                $pdf->Ln();

                $getMateriDetail = GetQuery("SELECT m.DETAIL_DESKRIPSI,d.UKT_BOBOT,d.UKT_DETAIL_NILAI,d.UKT_DETAIL_NILAI * (d.UKT_BOBOT / 100) NILAI_BOBOT,
                LOWER(CHAR(64 + (ROW_NUMBER() OVER (ORDER BY d.MATERI_ID)))) AS rnd
                FROM t_ukt_detail d
                LEFT JOIN m_materi_detail m ON d.UKT_DETAIL = m._key
                WHERE d.UKT_ID = '$UKT_ID' AND d.MATERI_ID = '$MATERI_ID';");

                while ($rowDetail = $getMateriDetail->fetch(PDO::FETCH_ASSOC)) {
                    extract($rowDetail);
                    
                    $pdf->SetFont('times', '', 12);
                    $pdf->Cell(10, 5, "", 1, 0, "C");
                    $pdf->Cell(105, 5, $rnd . ". " . $DETAIL_DESKRIPSI, 1, 0, "L");
                    $pdf->Cell(15, 5, $UKT_DETAIL_NILAI, 1, 0, "C");
                    $pdf->Cell(15, 5, $UKT_BOBOT . "%", 1, 0, "C");
                    $pdf->Cell(15, 5, $NILAI_BOBOT, 1, 0, "C");
                    $pdf->Ln();
                }
            }
            
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(145, 5, "TOTAL NILAI UKT", 1, 0, "C");
            $pdf->Cell(15, 5, $UKT_TOTAL . " / " . $UKT_NILAI, 1, 0, "C");
            $pdf->Ln(10);
            
            $pdf->SetFont('times', '', 12);
            $pdf->Cell(50, 5, "Cabang Penyelenggara UKT", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(105, 5, $UKT_CABANG . ", " . $UKT_DAERAH, 0, 0, "L");
            $pdf->Ln();
            $pdf->Cell(25, 5, "Keterangan", 0, 0, "L");
            $pdf->Cell(5, 5, " : ", 0, 0, "L");
            $pdf->Cell(130, 5, $UKT_DESKRIPSI, 0, 0, "L");

            GetQuery("update t_ukt set UKT_FILE = './assets/report/ukt/$CABANG_DESKRIPSI/$ANGGOTA_ID $ANGGOTA_NAMA/$UKT_ID UKT $UKT_TINGKATAN_NAMA $ANGGOTA_NAMA $UKT_TANGGAL.pdf' where UKT_ID = '$UKT_ID'");

            $pdfFilePath = '../../../../../assets/report/ukt/'.$CABANG_DESKRIPSI.'/'.$ANGGOTA_ID.' '.$ANGGOTA_NAMA;

            // Create directory if not exists
            if (!file_exists($pdfFilePath)) {
                mkdir($pdfFilePath, 0777, true);
            }
            
            $pdf->Output(__DIR__ .'/'.$pdfFilePath.'/'.$UKT_ID. ' UKT ' . $UKT_TINGKATAN_NAMA . ' ' . $ANGGOTA_NAMA . ' ' . $UKT_TANGGAL.'.pdf', 'F');
            
            // $pdf->Output($UKT_ID. ' UKT ' . $UKT_TINGKATAN_NAMA . ' ' . $ANGGOTA_NAMA . ' ' . $UKT_TANGGAL.'.pdf', 'I');

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
