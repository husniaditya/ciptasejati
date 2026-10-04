<?php
$expiredCabangName = 'Cabang Anda';
$expiredDateDisplay = '-';

$expiredViewStmt = $db1->prepare(
    "SELECT CABANG_DESKRIPSI, EXPIRY_DATE
     FROM m_cabang
     WHERE CONVERT(CABANG_KEY USING utf8mb4) COLLATE utf8mb4_general_ci
         = CONVERT(:cabang_key USING utf8mb4) COLLATE utf8mb4_general_ci
     LIMIT 1"
);
$expiredViewStmt->execute([':cabang_key' => $_SESSION['LOGINCAB_CS'] ?? '']);
$expiredViewData = $expiredViewStmt->fetch(PDO::FETCH_ASSOC);

if ($expiredViewData) {
    $expiredCabangName = $expiredViewData['CABANG_DESKRIPSI'] ?: $expiredCabangName;
    if (!empty($expiredViewData['EXPIRY_DATE'])) {
        $expiredDateDisplay = date('d/m/Y', strtotime($expiredViewData['EXPIRY_DATE']));
    }
}
?>

<style>
    .expired-page-card {
        max-width: 760px;
        margin: 45px auto;
        padding: 45px 35px;
        text-align: center;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .08);
    }

    .expired-page-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        background: linear-gradient(135deg, #ef4444, #b91c1c);
        font-size: 36px;
    }

    .expired-page-card h2 {
        margin: 0 0 12px;
        color: #1e293b;
        font-weight: 700;
    }

    .expired-page-card p {
        color: #64748b;
        line-height: 1.7;
    }

    .expired-page-meta {
        margin: 25px auto;
        padding: 16px 20px;
        max-width: 430px;
        text-align: left;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .expired-page-meta strong {
        color: #1e293b;
    }
</style>

<div class="expired-page-card">
    <div class="expired-page-icon"><i class="fa fa-exclamation-triangle"></i></div>
    <h2>Masa Berlangganan Telah Berakhir</h2>
    <p>
        Akses untuk <strong><?= htmlspecialchars($expiredCabangName, ENT_QUOTES, 'UTF-8') ?></strong>
        sedang tidak aktif. Silakan lakukan pembayaran perpanjangan selama 1 tahun untuk melanjutkan penggunaan dashboard.
    </p>

    <div class="expired-page-meta">
        <div><strong>Cabang:</strong> <?= htmlspecialchars($expiredCabangName, ENT_QUOTES, 'UTF-8') ?></div>
        <div><strong>Berlaku sampai:</strong> <?= htmlspecialchars($expiredDateDisplay, ENT_QUOTES, 'UTF-8') ?></div>
    </div>

    <p>Metode pembayaran QRIS tersedia melalui Midtrans.</p>
    <a href="#Payment" data-toggle="modal" class="open-Payment btn btn-primary btn-lg btn-rounded">
        <i class="fa fa-credit-card"></i> Perpanjang dengan Midtrans
    </a>
</div>
