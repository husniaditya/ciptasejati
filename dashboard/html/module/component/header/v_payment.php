<?php
require_once __DIR__ . '/../../backend/payment/midtrans.php';

$paymentAmount = 0;
$paymentParam = GetQuery("SELECT CODE FROM p_param WHERE KATEGORI = 'PAYMENT' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($paymentParam && is_numeric($paymentParam['CODE'])) {
    $paymentAmount = (float) $paymentParam['CODE'];
}
$paymentAmountDisplay = 'Rp ' . number_format($paymentAmount, 0, ',', '.');
$paymentExpiryMinutes = max(1, (int)($_ENV['MIDTRANS_PAYMENT_EXPIRY_MINUTES'] ?? 15));
?>
<script type="text/javascript" src="<?= htmlspecialchars(MidtransClient::snapScriptUrl(), ENT_QUOTES, 'UTF-8') ?>" data-client-key="<?= htmlspecialchars(MidtransClient::clientKey(), ENT_QUOTES, 'UTF-8') ?>"></script>
<script type="text/javascript">var PAYMENT_AMOUNT = <?= json_encode($paymentAmount) ?>;</script>
<style>
    #Payment .modal-dialog {
        width: 96%;
        max-width: 1200px;
    }

    #Payment #midtrans-snap-container {
        width: 100%;
        min-height: 680px;
        padding: 0;
        overflow: hidden;
        background: #fff;
    }

    #Payment #midtrans-snap-container iframe {
        display: block;
        width: 100% !important;
        min-height: 680px !important;
        height: 680px !important;
        border: 0;
    }

    #Payment .payment-terms {
        margin-top: 24px;
        overflow: hidden;
        border: 1px solid #c7d2fe;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 22px rgba(67, 56, 202, .08);
    }

    #Payment .payment-terms-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        color: #312e81;
        background: linear-gradient(135deg, #eef2ff, #faf5ff);
        border-bottom: 1px solid #e0e7ff;
    }

    #Payment .payment-terms-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    #Payment .payment-terms-title i {
        margin-right: 8px;
    }

    #Payment .payment-terms-badge {
        padding: 5px 10px;
        border-radius: 20px;
        color: #4338ca;
        background: #fff;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    #Payment .payment-terms-body {
        padding: 18px 20px 20px;
    }

    #Payment .payment-terms-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 18px;
    }

    #Payment .payment-terms-fact {
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
    }

    #Payment .payment-terms-fact small {
        display: block;
        margin-bottom: 4px;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    #Payment .payment-terms-fact strong {
        color: #1e293b;
        font-size: 14px;
    }

    #Payment .payment-terms-list {
        margin: 0;
        padding: 0;
        list-style: none;
        color: #475569;
        line-height: 1.6;
    }

    #Payment .payment-terms-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
    }

    #Payment .payment-terms-list li:last-child {
        margin-bottom: 0;
    }

    #Payment .payment-terms-number {
        flex: 0 0 22px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        color: #fff;
        background: #6366f1;
        font-size: 12px;
        font-weight: 700;
        line-height: 22px;
        text-align: center;
    }

    #Payment .payment-terms-notice {
        margin-top: 18px;
        padding: 12px 14px;
        border-left: 4px solid #f59e0b;
        border-radius: 6px;
        color: #92400e;
        background: #fffbeb;
        font-size: 13px;
    }

    @media (max-width: 600px) {
        #Payment .payment-terms-summary {
            grid-template-columns: 1fr;
        }

        #Payment .payment-terms-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
<div id="Payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="Payment-form" class="form form-horizontal form-striped" action="" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Pembayaran Sewa</h3>
                </div>
                <div class="modal-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab-payment-method" aria-controls="tab-payment-method" role="tab" data-toggle="tab">
                                <i class="fa fa-credit-card"></i> Metode Pembayaran
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tab-payment-history" aria-controls="tab-payment-history" role="tab" data-toggle="tab">
                                <i class="fa fa-history"></i> Riwayat Pembayaran
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" style="padding-top: 20px;">
                        <!-- Payment Method Tab -->
                        <div role="tabpanel" class="tab-pane fade in active" id="tab-payment-method">
                            <input type="hidden" id="payment_order_id" name="order_id" value="">
                            <input type="hidden" id="payment_amount" name="amount" value="<?= htmlspecialchars((string) $paymentAmount, ENT_QUOTES, 'UTF-8') ?>">
                            
                            <!-- Payment Amount Display -->
                            <div class="alert alert-info text-center">
                                <h4>Total Pembayaran: <strong id="display_payment_amount"><?= htmlspecialchars($paymentAmountDisplay, ENT_QUOTES, 'UTF-8') ?></strong></h4>
                            </div>

                            <!-- Midtrans Snap is embedded here after a token is created. -->
                            <div id="midtrans-snap-container" class="well" style="display: none; min-height: 420px; margin-bottom: 20px;"></div>

                            <div id="midtrans-payment-message" class="alert alert-info text-center">
                                <i class="fa fa-lock"></i>
                                Pembayaran diproses dengan aman melalui Midtrans.
                                Saat ini hanya Midtrans yang tersedia. Metode pembayaran lainnya akan ditambahkan segera.
                            </div>

                            <div class="payment-option selected" id="midtrans-payment-option" data-method="midtrans" data-category="gateway" style="max-width: 280px; margin: 0 auto 20px;">
                                <i class="fa fa-credit-card" style="font-size: 42px; color: #667eea; margin-bottom: 10px;"></i>
                                <span>Midtrans</span>
                                <small style="margin-top: 6px; color: #64748b;">Bayar <?= htmlspecialchars($paymentAmountDisplay, ENT_QUOTES, 'UTF-8') ?></small>
                            </div>

                            <!-- Payment Instructions -->
                            <div id="payment-instructions" class="alert alert-warning">
                                <h5><i class="fa fa-qrcode"></i> Instruksi Pembayaran QRIS</h5>
                                <div id="payment-instruction-content">
                                    <ol>
                                        <li>Klik <strong>Bayar dengan Midtrans</strong>.</li>
                                        <li>Pilih metode <strong>QRIS</strong> di halaman Midtrans.</li>
                                        <li>Buka aplikasi e-wallet atau mobile banking yang mendukung QRIS.</li>
                                        <li>Scan QRIS yang ditampilkan dan pastikan nominal pembayaran sesuai.</li>
                                        <li>Konfirmasi pembayaran, lalu tunggu status berhasil.</li>
                                    </ol>
                                </div>
                            </div>

                            <div class="payment-terms">
                                <div class="payment-terms-header">
                                    <h5 class="payment-terms-title"><i class="fa fa-file-text-o"></i> Syarat dan Ketentuan Pembayaran</h5>
                                    <span class="payment-terms-badge">Penting dibaca</span>
                                </div>
                                <div class="payment-terms-body">
                                    <div class="payment-terms-summary">
                                        <div class="payment-terms-fact">
                                            <small>Masa berlaku</small>
                                            <strong>1 Tahun</strong>
                                        </div>
                                        <div class="payment-terms-fact">
                                            <small>Metode</small>
                                            <strong>QRIS via Midtrans</strong>
                                        </div>
                                        <div class="payment-terms-fact">
                                            <small>Batas pembayaran</small>
                                            <strong><?= htmlspecialchars((string)$paymentExpiryMinutes, ENT_QUOTES, 'UTF-8') ?> Menit</strong>
                                        </div>
                                    </div>

                                    <ol class="payment-terms-list">
                                        <li><span class="payment-terms-number">1</span><span>Paket berlaku selama <strong>1 tahun (12 bulan)</strong> sejak pembayaran berhasil dikonfirmasi.</span></li>
                                        <li><span class="payment-terms-number">2</span><span>Setelah berhasil, masa berlaku cabang akan diperpanjang selama 1 tahun.</span></li>
                                        <li><span class="payment-terms-number">3</span><span>Jika cabang masih aktif, perpanjangan ditambahkan dari tanggal kedaluwarsa saat ini. Jika sudah expired, masa berlaku dihitung sejak pembayaran berhasil.</span></li>
                                        <li><span class="payment-terms-number">4</span><span>Pembayaran dilakukan melalui Midtrans dengan metode QRIS.</span></li>
                                        <li><span class="payment-terms-number">5</span><span>Pembayaran yang berhasil bersifat final dan tidak dapat dikembalikan.</span></li>
                                    </ol>

                                    <div class="payment-terms-notice">
                                        <i class="fa fa-info-circle"></i>
                                        Dengan melanjutkan pembayaran, Anda menyetujui seluruh syarat dan ketentuan di atas.
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Display -->
                            <div id="qr-code-container" class="text-center" style="display: none;">
                                <img id="qr-code-image" src="" alt="QR Code" class="img-responsive" style="max-width: 250px; margin: 0 auto;">
                                <p class="text-muted mt-2">Scan QR Code di atas untuk melakukan pembayaran</p>
                            </div>
                        </div>

                        <!-- Payment History Tab -->
                        <div role="tabpanel" class="tab-pane fade" id="tab-payment-history">
                            <div id="payment-history-loading" class="text-center" style="display: none;">
                                <i class="fa fa-spinner fa-spin fa-3x"></i>
                                <p>Memuat riwayat pembayaran...</p>
                            </div>
                            <div id="payment-history-content">
                                <table class="table table-striped table-bordered table-hover" id="table-payment-history" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>ID Transaksi</th>
                                            <th>Metode</th>
                                            <th>Jumlah</th>
                                            <th>Tgl Kadaluarsa</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via DataTables AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal">
                        <span class="ico-cancel"></span> Tutup
                    </button>
                    <button type="button" id="btn-process-payment" class="btn btn-primary mb5 btn-rounded" style="display: none;">
                        <span class="fa fa-credit-card"></span> Klik untuk bayar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</attachment>
