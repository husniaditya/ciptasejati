<?php
require_once __DIR__ . '/../../connection/conn.php';

$DATENOW = date("d-m-Y H:i:s");
$USER_NAMA = $_SESSION["LOGINNAME_CS"] ?? 'Guest';
$USER_CABANG = $_SESSION["LOGINCAB_CS"] ?? '';

// Include the main TCPDF library
require_once('../../../assets/tcpdf/tcpdf.php');

// Get transaction ID from request
$transactionId = isset($_GET['trx_id']) ? trim($_GET['trx_id']) : '';

if (empty($transactionId)) {
    die('Transaction ID is required');
}

// Fetch transaction data
$sql = "SELECT t.*, m.payment_code, m.payment_name, m.payment_category, m.account_number, m.account_name,
               c.CABANG_DESKRIPSI, c.CABANG_SEKRETARIAT
        FROM t_payment t
        INNER JOIN m_payment m ON t.payment_id = m.id
        LEFT JOIN m_cabang c ON t.cabang_key = c.CABANG_KEY
        WHERE t.transaction_id = :transaction_id";

$stmt = $db1->prepare($sql);
$stmt->execute([':transaction_id' => $transactionId]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die('Transaction not found');
}

// Format currency function
function formatRupiah($amount) {
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

// Status labels
$statusLabels = [
    'pending' => 'Menunggu Pembayaran',
    'success' => 'Lunas',
    'settlement' => 'Lunas',
    'failed' => 'Gagal',
    'expired' => 'Kadaluarsa',
    'cancel' => 'Dibatalkan'
];

$statusLabel = $statusLabels[$transaction['status']] ?? 'Unknown';

// Create new PDF document
class MYPDF extends TCPDF {
    public $cabangNama = '';
    public $cabangAlamat = '';
    
    public function Header() {
        // Logo - adjust path as needed
        // $this->Image('path/to/logo.png', 15, 8, 25);
        
        // Company name
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(0, 6, 'CIPTA SEJATI INDONESIA', 0, 1, 'C');
        
        // Branch name
        if (!empty($this->cabangNama)) {
            $this->SetFont('helvetica', 'B', 10);
            $this->SetTextColor(100, 116, 139);
            $this->Cell(0, 5, 'Cabang ' . $this->cabangNama, 0, 1, 'C');
        }
        
        // Branch address
        $this->SetFont('helvetica', '', 7);
        $this->SetTextColor(128, 128, 128);
        if (!empty($this->cabangAlamat)) {
            $this->MultiCell(0, 4, $this->cabangAlamat, 0, 'C');
        } else {
            $this->Cell(0, 4, 'Jl. Contoh Alamat No. 123, Kota, Indonesia', 0, 1, 'C');
        }
        
        // Line separator
        $currentY = $this->GetY() + 2;
        $this->SetDrawColor(200, 200, 200);
        $this->Line(15, $currentY, 195, $currentY);
        $this->SetY($currentY + 3);
    }
    
    public function Footer() {
        $this->SetY(-12);
        $this->SetFont('helvetica', 'I', 7);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 5, 'Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan. | Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Create PDF
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set branch info for header
$pdf->cabangNama = $transaction['CABANG_DESKRIPSI'] ?? '';
$pdf->cabangAlamat = $transaction['CABANG_SEKRETARIAT'] ?? '';

// Set document information
$pdf->SetCreator('Cipta Sejati');
$pdf->SetAuthor('Cipta Sejati');
$pdf->SetTitle('Invoice Pembayaran Website - ' . $transactionId);
$pdf->SetSubject('Invoice Pembayaran Website');

// Set margins (increased top margin to accommodate branch address in header)
$pdf->SetMargins(15, 33, 15);
$pdf->SetHeaderMargin(8);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

// Invoice Title
$pdf->SetFont('helvetica', 'B', 13);
$pdf->SetTextColor(51, 51, 51);
$pdf->Cell(0, 7, 'INVOICE PEMBAYARAN', 0, 1, 'C');
$pdf->Ln(2);

// Status Badge
$statusColors = [
    'pending' => [245, 158, 11],
    'success' => [16, 185, 129],
    'settlement' => [16, 185, 129],
    'failed' => [239, 68, 68],
    'expired' => [107, 114, 128],
    'cancel' => [107, 114, 128]
];
$statusColor = $statusColors[$transaction['status']] ?? [128, 128, 128];

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetTextColor($statusColor[0], $statusColor[1], $statusColor[2]);
$pdf->Cell(0, 8, 'Status: ' . strtoupper($statusLabel), 0, 1, 'C');
$pdf->Ln(5);

// Transaction Info Box
$pdf->SetFillColor(248, 250, 252);
$pdf->SetDrawColor(226, 232, 240);
$pdf->RoundedRect(15, $pdf->GetY(), 180, 25, 3, '1111', 'DF');

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(100, 116, 139);
$startY = $pdf->GetY() + 5;
$pdf->SetXY(20, $startY);
$pdf->Cell(85, 6, 'No. Transaksi:', 0, 0, 'L');
$pdf->Cell(85, 6, 'Tanggal:', 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetTextColor(30, 41, 59);
$pdf->SetX(20);
$pdf->Cell(85, 6, $transaction['transaction_id'], 0, 0, 'L');
$pdf->Cell(85, 6, date('d F Y, H:i', strtotime($transaction['created_at'])), 0, 1, 'L');

$pdf->Ln(15);

// Order Details Section
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(51, 51, 51);
$pdf->Cell(0, 8, 'Detail Pesanan', 0, 1, 'L');
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(3);

// Details table
$pdf->SetFont('helvetica', '', 10);

// Order ID
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(60, 7, 'Order ID', 0, 0, 'L');
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(0, 7, ': ' . $transaction['order_id'], 0, 1, 'L');

// Metode Pembayaran
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(60, 7, 'Metode Pembayaran', 0, 0, 'L');
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(0, 7, ': ' . $transaction['payment_name'], 0, 1, 'L');

// Kategori
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(60, 7, 'Kategori', 0, 0, 'L');
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(0, 7, ': ' . ucwords(str_replace('_', ' ', $transaction['payment_category'])), 0, 1, 'L');

$pdf->Ln(5);

// Payment Details Section
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(51, 51, 51);
$pdf->Cell(0, 8, 'Rincian Pembayaran', 0, 1, 'L');
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(3);

$pdf->SetFont('helvetica', '', 10);

// Subtotal
$pdf->SetTextColor(100, 116, 139);
$pdf->Cell(140, 7, 'Subtotal', 0, 0, 'L');
$pdf->SetTextColor(30, 41, 59);
$pdf->Cell(40, 7, formatRupiah($transaction['amount']), 0, 1, 'R');

// Fee (if any)
$feeAmount = floatval($transaction['fee_amount'] ?? 0);
if ($feeAmount > 0) {
    $pdf->SetTextColor(100, 116, 139);
    $pdf->Cell(140, 7, 'Biaya Admin', 0, 0, 'L');
    $pdf->SetTextColor(30, 41, 59);
    $pdf->Cell(40, 7, formatRupiah($feeAmount), 0, 1, 'R');
}

// Total line
$pdf->Ln(2);
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(2);

// Total Amount
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(51, 51, 51);
$pdf->Cell(140, 10, 'TOTAL PEMBAYARAN', 0, 0, 'L');
$pdf->SetTextColor(16, 185, 129);
$pdf->Cell(40, 10, formatRupiah($transaction['total_amount']), 0, 1, 'R');

$pdf->Ln(6);

// Payment Account Info (for bank transfer/VA)
if (in_array($transaction['payment_category'], ['transfer_bank', 'virtual_account'])) {
    $pdf->SetFillColor(239, 246, 255);
    $pdf->SetDrawColor(191, 219, 254);
    
    $boxHeight = 30;
    $pdf->RoundedRect(15, $pdf->GetY(), 180, $boxHeight, 3, '1111', 'DF');
    
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetTextColor(30, 64, 175);
    $pdf->SetXY(20, $pdf->GetY() + 5);
    $pdf->Cell(0, 6, 'Informasi Pembayaran', 0, 1, 'L');
    
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetX(20);
    $pdf->SetTextColor(51, 51, 51);
    
    if ($transaction['payment_category'] === 'transfer_bank') {
        $pdf->Cell(0, 6, 'Bank: ' . $transaction['payment_name'] . ' | No. Rekening: ' . ($transaction['account_number'] ?? '-'), 0, 1, 'L');
        $pdf->SetX(20);
        $pdf->Cell(0, 6, 'Atas Nama: ' . ($transaction['account_name'] ?? 'PT Cipta Sejati'), 0, 1, 'L');
    } else {
        // Virtual Account
        $paymentData = json_decode($transaction['payment_data'], true);
        $vaNumber = $paymentData['va_number'] ?? '-';
        $pdf->Cell(0, 6, 'Virtual Account: ' . $vaNumber, 0, 1, 'L');
        $pdf->SetX(20);
        $pdf->Cell(0, 6, 'Bank: ' . $transaction['payment_name'], 0, 1, 'L');
    }
    
    $pdf->Ln($boxHeight - 20);
}

$pdf->Ln(5);

// Timestamps Section
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(51, 51, 51);
$pdf->Cell(0, 8, 'Informasi Waktu', 0, 1, 'L');
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(3);

$pdf->SetFont('helvetica', '', 10);

$timestamps = [
    ['Dibuat', $transaction['created_at']],
    ['Kadaluarsa', $transaction['expired_at']],
    ['Dibayar', $transaction['paid_at'] ?? '-']
];

foreach ($timestamps as $ts) {
    if ($ts[1] && $ts[1] !== '-') {
        $formattedDate = date('d F Y, H:i:s', strtotime($ts[1]));
    } else {
        $formattedDate = '-';
    }
    
    $pdf->SetTextColor(100, 116, 139);
    $pdf->Cell(60, 7, $ts[0], 0, 0, 'L');
    $pdf->SetTextColor(30, 41, 59);
    $pdf->Cell(0, 7, ': ' . $formattedDate, 0, 1, 'L');
}

$pdf->Ln(10);

// Notes Section
$pdf->SetFillColor(254, 243, 199);
$pdf->SetDrawColor(251, 191, 36);
$pdf->RoundedRect(15, $pdf->GetY(), 180, 20, 3, '1111', 'DF');

$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(120, 53, 15);
$pdf->SetXY(20, $pdf->GetY() + 4);
$pdf->Cell(0, 5, 'Catatan Penting:', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 9);
$pdf->SetX(20);
$pdf->MultiCell(170, 5, 'Harap simpan invoice ini sebagai bukti pembayaran. Jika ada pertanyaan, silakan hubungi customer service kami.', 0, 'L');

// Print info
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 5, 'Dicetak pada: ' . $DATENOW . ' oleh ' . $USER_NAMA, 0, 1, 'C');

// Output the PDF
$downloadMode = isset($_GET['download']) && $_GET['download'] == '1';
$outputMode = $downloadMode ? 'D' : 'I'; // D = Download, I = Inline (browser)
$pdf->Output('Invoice_' . $transactionId . '.pdf', $outputMode);
?>