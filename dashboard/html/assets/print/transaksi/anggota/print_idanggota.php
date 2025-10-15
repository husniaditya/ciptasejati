<?php
require_once ("../../../../module/connection/conn.php");

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"];

// Include the main TCPDF library (search for installation path).
require_once('../../../tcpdf/tcpdf.php');

if (isset($_GET['id'])) {
    $ANGGOTA_KEY = decodeBase64ToId($_GET['id']);
    $id = $ANGGOTA_KEY;

    // Define the query with placeholders
    $query = "UPDATE m_anggota SET ANGGOTA_KTA_AKTIF=NOW(), ANGGOTA_KTA_EXP = DATE_ADD(NOW(), INTERVAL 5 YEAR) WHERE ANGGOTA_KEY = '$ANGGOTA_KEY'";
    // Execute the query with the parameters
    GetQuery2($query, []);

    $getData = GetQuery("SELECT a.*, c.CABANG_DESKRIPSI, c.CABANG_SEKRETARIAT, d.DAERAH_DESKRIPSI, t.TINGKATAN_NAMA, t.TINGKATAN_SEBUTAN, date_format(a.ANGGOTA_TANGGAL_LAHIR, '%d %M %Y') TGL_LAHIR, date_format(a.ANGGOTA_KTA_EXP, '%d %M %Y') ANGGOTA_KTA_EXP, s.IDSERTIFIKAT_IDFILE_FRONT, s.IDSERTIFIKAT_IDFILE_BACK FROM m_anggota a
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    LEFT JOIN m_idsertifikat s ON a.TINGKATAN_ID = s.TINGKATAN_ID AND s.DELETION_STATUS = 0
    WHERE a.ANGGOTA_KEY = '$ANGGOTA_KEY'");

    $getURL = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'url'");
    while ($urlData = $getURL->fetch(PDO::FETCH_ASSOC)) {
        $URL = $urlData["DESK"];
    }

    try {
        while ($data = $getData->fetch(PDO::FETCH_ASSOC)) {
            extract($data);

            $getKoor = GetQuery("SELECT ANGGOTA_KEY AS KOORDINATOR_KEY, ANGGOTA_NAMA AS KOORDINATOR_NAMA FROM m_anggota WHERE ANGGOTA_AKSES = 'Ketua' AND DELETION_STATUS = 0 AND ANGGOTA_STATUS = 0 AND CABANG_KEY = '$CABANG_KEY' LIMIT 1");
            // ---------------------------------------------------------
            while ($koorData = $getKoor->fetch(PDO::FETCH_ASSOC)) {
                extract($koorData);
            }

            // Extend the TCPDF class to create custom Header and Footer
            class MYPDF extends TCPDF {
                // Add Header and Footer methods if needed
            }
    
            // ID Card dimensions (CR80): 85.6mm x 54mm. We'll use landscape for more width
            $cardWidth = 85.6;  // mm
            $cardHeight = 54.0; // mm
            // create new PDF document with custom size
            $pdf = new MYPDF('L', 'mm', array($cardWidth, $cardHeight), true, 'UTF-8', false);
            // For an edge-to-edge ID card we remove all margins / header / footer and auto page breaks
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(0);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false, 0);
    
            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Cipta Sejati Indonesia');
            $pdf->SetTitle('Kartu ID ' . $ANGGOTA_ID . ' ' . $ANGGOTA_NAMA . '  ' . $ANGGOTA_KTA_EXP);
    
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    
    
            // (Auto page break already disabled above for full-bleed design)
    
            // ---------------------------------------------------------
    
            // Resolve image paths (stored like "./images/idsertifikat/KTA_FRONT.jpg")
            // The correct absolute path (per user example) is dashboard/html/images/... not dashboard/html/assets/images
            // Current script: dashboard/html/assets/print/transaksi/anggota/print_idanggota.php
            // So dashboard/html is three levels above assets (../../..) then one more up: ../../../../
            $htmlRootDir = realpath(__DIR__ . '/../../../..'); // points to dashboard/html
            $imagesDir = realpath($htmlRootDir . '/images');    // dashboard/html/images
            $frontPath = !empty($IDSERTIFIKAT_IDFILE_FRONT) ? $IDSERTIFIKAT_IDFILE_FRONT : '';
            $backPath  = !empty($IDSERTIFIKAT_IDFILE_BACK) ? $IDSERTIFIKAT_IDFILE_BACK : '';

            $buildPath = function($p) use ($imagesDir, $htmlRootDir) {
                if ($p === '') return '';
                $p = trim($p);
                // Strip leading ./
                $p = preg_replace('#^\./#','',$p);
                if (preg_match('/^https?:/i', $p)) return $p; // URL
                if (strpos($p, DIRECTORY_SEPARATOR) === 0) return $p; // absolute POSIX
                if (preg_match('/^[A-Za-z]:\\\\/', $p)) return $p; // absolute Windows
                // If starts with images/ treat relative to html root; else assume already rooted inside images dir
                if (strpos($p, 'images/') === 0) {
                    $candidate = $htmlRootDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p);
                } else {
                    $candidate = $imagesDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p);
                }
                return $candidate;
            };

            $frontImg = $buildPath($frontPath);
            $backImg  = $buildPath($backPath);

            // set style for barcode
            $style = array(
                'border' => true,
                'vpadding' => 'auto',
                'hpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                // Set white background for QR code
                'bgcolor' => array(255,255,255),
                'module_width' => 1, // width of a single module in points
                'module_height' => 1 // height of a single module in points
            );

            // Page 1 (Front)
            $pdf->AddPage();
            if ($frontImg && @getimagesize($frontImg)) {
                // Draw background scaled to card size (no margins)
                $pdf->Image($frontImg, 0, 0, $cardWidth, $cardHeight, '', '', '', false, 300, '', false, false, 0);
                $pdf->setPageMark();
            }

            // ---------------------------------------------------------
            // Member photo / avatar
            // Raw value may look like: ./assets/images/daftaranggota/default/avatar.png
            $avatarRaw = isset($ANGGOTA_PIC) && trim($ANGGOTA_PIC) !== ''
                ? $ANGGOTA_PIC
                : './assets/images/daftaranggota/default/avatar.png';

            $assetsDir = realpath($htmlRootDir . '/assets'); // dashboard/html/assets
            $buildAssetPath = function($p) use ($htmlRootDir, $assetsDir) {
                if ($p === '') return '';
                $p = trim($p);
                $p = preg_replace('#^\.\/#','',$p); // strip leading ./
                if (preg_match('/^https?:/i', $p)) return $p; // URL
                if (strpos($p, DIRECTORY_SEPARATOR) === 0) return $p; // absolute POSIX
                if (preg_match('/^[A-Za-z]:\\\\/', $p)) return $p; // absolute Windows
                // If starts with assets/ resolve from html root; else keep relative to assets dir if it exists
                if (strpos($p, 'assets/') === 0) {
                    return $htmlRootDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p);
                }
                // fallback: assume inside assets/images
                return $assetsDir
                    ? $assetsDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p)
                    : $htmlRootDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $p);
            };

            $avatarImg = $buildAssetPath($avatarRaw);

            // Place avatar photo on left side of card (avoid overlay region starting at x=46)
            if ($avatarImg && @getimagesize($avatarImg)) {
                $photoX = 5;    // mm
                $photoY = 20;   // mm
                $photoW = 18;   // mm (height auto to preserve aspect if 0, but we set both to fit box)
                $photoH = 23;   // mm
                $pdf->Image($avatarImg, $photoX, $photoY, $photoW, $photoH, '', '', '', false, 300, '', false, false, 0, false, false, false);
            }

            // Overlay values: use fixed coordinates so long text stays on page
            $pdf->SetFont('helvetica', '', 7.2);
            // $pdf->SetTextColor(255,255,255);
            $valueX = 46;  // align with colon on template
            $startY = 19;  // first line (Nama)
            $gap = 5;      // vertical spacing
            $w = $cardWidth - $valueX - 2; // width for value

            $put = function($y,$text) use($pdf,$valueX,$w){
                $pdf->SetXY($valueX,$y);
                // trim to avoid accidental whitespace causing wrap
                $text = trim($text);
                $pdf->Cell($w,3.8,$text,0,0,'L');
            };

            $y = $startY;
            $put(19.8, $ANGGOTA_NAMA); $y += $gap;          // Nama
            $put(23.9, $ANGGOTA_ID);   $y += $gap;          // NIA
            $put(28.3, $ANGGOTA_TEMPAT_LAHIR . ', ' . $TGL_LAHIR); $y += $gap; // Tempat, Tgl Lahir
            $put(32.8, $CABANG_DESKRIPSI); $y += $gap; // Cabang
            // Alamat -> limit to max 2 lines, append '...' if it would exceed
            $alamatY = 37; // fixed Y aligned with template row for Alamat
            $alamatRaw = trim((string)$ANGGOTA_ALAMAT);
            $maxLines = 2;
            $lineH = 3.8; // line height similar to other fields
            $ellipsis = '...';

            $substr = function($s,$start,$len=null){
                if (function_exists('mb_substr')) return mb_substr($s,$start,$len,'UTF-8');
                return isset($len) ? substr($s,$start,$len) : substr($s,$start);
            };
            $strlen = function($s){
                if (function_exists('mb_strlen')) return mb_strlen($s,'UTF-8');
                return strlen($s);
            };

            $alamatDisplay = $alamatRaw;
            if ($pdf->getNumLines($alamatDisplay, $w) > $maxLines) {
                $len = $strlen($alamatRaw);
                $low = 0; $high = $len; $best = 0; $truncated = $alamatRaw;
                while ($low <= $high) {
                    $mid = (int)floor(($low + $high)/2);
                    $candidate = rtrim($substr($alamatRaw, 0, $mid));
                    if ($candidate !== '') { $candidate .= $ellipsis; }
                    // If empty, still test with ellipsis
                    if ($candidate === '') { $candidate = $ellipsis; }
                    if ($pdf->getNumLines($candidate, $w) <= $maxLines) {
                        $best = $mid;
                        $truncated = $candidate;
                        $low = $mid + 1;
                    } else {
                        $high = $mid - 1;
                    }
                }
                $alamatDisplay = $truncated;
            }

            $pdf->SetXY($valueX, $alamatY);
            $pdf->MultiCell(27, $lineH, $alamatDisplay, 0, 'L', false, 1, $valueX, $alamatY, true, 0, false, true);

            $pdf->Ln(-2);
            $pdf->SetFont('helvetica', 'B', 5.5);
            $pdf->Cell(4,5,"",0,0,"L");
            $pdf->Cell(14,5,"Masa Berlaku:",0,0,"L");
            $pdf->Cell(20,5,$ANGGOTA_KTA_EXP,0,0,"L");

            // (We don't rely on $y after this, but if needed we could update it like below)
            $y = max($y, $pdf->GetY());
            $expText = $ANGGOTA_KTA_EXP ?: '-';
            $missingFrontNote = '';
            
            // Place QR code on back page (bottom-right corner)
            $qrUrl = $URL . '/dashboard/html/assets/token/tokenmember.php?id=' . encodeIdToBase64($id);
            $qrSize = 13; // mm
            $qrX = $cardWidth - $qrSize - 1; // 4mm padding from right
            $qrY = 40; // 4mm from top
            $pdf->write2DBarcode($qrUrl, 'QRCODE,L', $qrX, $qrY, $qrSize, $qrSize, $style, 'N');
            
            // (Removed obsolete large-page image placement for $ANGGOTA_PIC)
            if ($frontPath && !$frontImg || ($frontImg && !@getimagesize($frontImg))) {
                $missingFrontNote = "\n(Template depan tidak ditemukan)";
            }

            // ---------------------------------------------------------
            // Page 2 (Back)
            $pdf->AddPage();
            $backImgOk = ($backImg && @getimagesize($backImg));
            if ($backImgOk) {
                $pdf->Image($backImg, 0, 0, $cardWidth, $cardHeight, '', '', '', false, 300, '', false, false, 0);
                $pdf->setPageMark();
            }
            
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', '', 5.5);
            $pdf->SetTextColor(255,255,255);
            $pdf->Cell(5,5,"",0,0,"L");
            // Use MultiCell for sekretariat to allow wrapping
            $curX = $pdf->GetX();
            $curY = $pdf->GetY();
            $pdf->MultiCell(76, 0, $CABANG_SEKRETARIAT, 0, 'C', false, 0, $curX, $curY, true, 0, false, true);
            $pdf->Cell(5,5,"",0,0,"L");
            $pdf->Ln(16);
            $pdf->SetFont('helvetica', 'B', 5.5);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(56.5,5,"",0,0,"L");
            $pdf->Cell(20,5,'CABANG ' . strtoupper($CABANG_DESKRIPSI),0,0,"C");
            $pdf->Ln(20);
            $pdf->Cell(56.5,5,"",0,0,"L");
            $pdf->Cell(20,5,$KOORDINATOR_NAMA,0,0,"C");
            // Place QR code on back page (top-right corner)
            $qrUrl = $URL . '/dashboard/html/assets/token/tokenmember.php?id=' . encodeIdToBase64($id).'&data='.encodeIdToBase64($KOORDINATOR_KEY);
            $qrSize = 13; // mm
            $qrX = $cardWidth - $qrSize - 13; // 4mm padding from right
            $qrY = 28; // 4mm from top

            $pdf->write2DBarcode($qrUrl, 'QRCODE,H', $qrX, $qrY, $qrSize, $qrSize, $style, 'N');

            $pdf->SetFont('helvetica', '', 5.5);
            $pdf->SetXY(4, $cardHeight - 8);

            // Output the PDF to browser with a safe, consistent filename
            $safeId = preg_replace('/[^A-Za-z0-9_-]+/', '', (string)$ANGGOTA_ID);
            if ($safeId === '') { $safeId = 'ID'; }
            $fileName = 'Kartu_ID_' . $safeId . '.pdf';

            // Use 'D' to force download so browsers honor the filename; switch to 'I' if you prefer inline preview
            $pdfFilePath = '../../../idcard/'.$CABANG_DESKRIPSI.'/'.$ANGGOTA_ID.' '.$ANGGOTA_NAMA;

            // Create directory if not exists
            if (!file_exists($pdfFilePath)) {
                mkdir($pdfFilePath, 0777, true);
            }
            
            // Prepare the parameters
            $file_db = "./assets/idcard/$CABANG_DESKRIPSI/$ANGGOTA_ID $ANGGOTA_NAMA/KTA $ANGGOTA_ID $ANGGOTA_NAMA $TINGKATAN_NAMA.pdf";
            
            // Define the query with placeholders
            $query = "UPDATE m_anggota SET ANGGOTA_KTA = ? WHERE ANGGOTA_KEY = '$ANGGOTA_KEY'";
            // Execute the query with the parameters
            GetQuery2($query, [$file_db]);

            //Close and output PDF document
            $pdf->Output(__DIR__ .'/'.$pdfFilePath.'/KTA ' . $ANGGOTA_ID . ' ' . $ANGGOTA_NAMA . ' ' . $TINGKATAN_NAMA.'.pdf', 'F');
            $pdf->Output('KTA ' . $ANGGOTA_ID . ' ' . $ANGGOTA_NAMA . ' ' . $TINGKATAN_NAMA.'.pdf', 'I');
            exit;
        }
    } catch (\Throwable $th) {
        //throw $th;
        $response =  "Caught Exception: " . $th->getMessage();
        echo $response;
    }
}
?>