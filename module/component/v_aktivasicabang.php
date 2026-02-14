<?php
// Get subscription price from p_param
$subsPrice = 499999; // Default value
$subsQuery = GetQuery("SELECT TEXT FROM p_param WHERE KATEGORI = 'SUBS_PRICE' LIMIT 1");
if ($subsRow = $subsQuery->fetch(PDO::FETCH_ASSOC)) {
    $subsPrice = intval($subsRow['TEXT']);
}
$subsPriceFormatted = 'Rp ' . number_format($subsPrice, 0, ',', '.');

// Generate CSRF token if not exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];
?>
<!-- Aktivasi Cabang Modal -->
<div class="modal fade" id="aktivasiCabangModal" aria-labelledby="aktivasiCabangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content aktivasi-modal-content">
            <!-- Modal Header -->
            <div class="modal-header aktivasi-modal-header">
                <div class="header-content">
                    <div class="header-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="header-text">
                        <h4 class="modal-title" id="aktivasiCabangModalLabel">Aktivasi Cabang</h4>
                        <p class="modal-subtitle">Pilih cabang dan paket untuk mengaktifkan layanan</p>
                    </div>
                </div>
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body aktivasi-modal-body">
                <form id="formAktivasiCabang">
                    <!-- Step 1: Pilih Cabang -->
                    <div class="form-section" id="sectionPilihCabang">
                        <div class="section-header">
                            <span class="step-number">1</span>
                            <span class="step-title">Pilih Cabang</span>
                        </div>
                        <div class="form-group">
                            <label for="selectCabang" class="form-label">Nama Cabang</label>
                            <select class="form-select custom-select" id="selectCabang" name="cabang_id" required>
                                <option value="">-- Pilih Cabang --</option>
                            </select>
                            <div class="invalid-feedback">Silakan pilih cabang</div>
                        </div>
                    </div>

                    <!-- Step 2: Pilih Paket -->
                    <div class="form-section" id="sectionPilihPaket">
                        <div class="section-header">
                            <span class="step-number">2</span>
                            <span class="step-title">Pilih Paket</span>
                        </div>
                        <div class="package-options">
                            <div class="package-card" data-package="trial">
                                <div class="package-radio">
                                    <input type="radio" name="package_type" id="packageTrial" value="trial">
                                    <span class="radio-custom"></span>
                                </div>
                                <div class="package-content">
                                    <div class="package-icon trial">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                    <div class="package-info">
                                        <h5 class="package-name">Trial</h5>
                                        <p class="package-desc">Coba gratis selama 14 hari</p>
                                        <div class="package-price">
                                            <span class="price">Gratis</span>
                                        </div>
                                    </div>
                                    <div class="package-badge trial">
                                        <i class="fas fa-clock"></i> 14 Hari
                                    </div>
                                </div>
                            </div>

                            <div class="package-card" data-package="subscription">
                                <div class="package-radio">
                                    <input type="radio" name="package_type" id="packageSubscription" value="subscription">
                                    <span class="radio-custom"></span>
                                </div>
                                <div class="package-content">
                                    <div class="package-icon subscription">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <div class="package-info">
                                        <h5 class="package-name">Berlangganan</h5>
                                        <p class="package-desc">Akses penuh selama 1 tahun</p>
                                        <div class="package-price">
                                            <span class="price" id="subscriptionPrice"><?= $subsPriceFormatted ?></span>
                                            <span class="period">/tahun</span>
                                        </div>
                                    </div>
                                    <div class="package-badge subscription">
                                        <i class="fas fa-star"></i> Premium
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Pilih Metode Pembayaran (Hidden by default) -->
                    <div class="form-section" id="sectionPayment" style="display: none;">
                        <div class="section-header">
                            <span class="step-number">3</span>
                            <span class="step-title">Metode Pembayaran</span>
                        </div>

                        <!-- Payment Amount Display -->
                        <div class="payment-amount-display">
                            <div class="amount-label">Total Pembayaran</div>
                            <div class="amount-value" id="displayTotalAmount"><?= $subsPriceFormatted ?></div>
                        </div>

                        <!-- Payment Methods Accordion -->
                        <div class="payment-methods-container" id="paymentAccordion">
                            <!-- Payment methods will be loaded dynamically -->
                            <div class="payment-loading" id="paymentLoading">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span>Memuat metode pembayaran...</span>
                            </div>
                        </div>

                        <!-- Selected Payment Info -->
                        <div class="selected-payment-info" id="selectedPaymentInfo" style="display: none;">
                            <div class="info-header">
                                <i class="fas fa-check-circle"></i>
                                <span>Metode Pembayaran Dipilih</span>
                            </div>
                            <div class="info-content">
                                <span id="selectedPaymentName">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="csrfToken" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" id="selectedPaymentMethod" name="payment_method" value="">
                    <input type="hidden" id="selectedPaymentCategory" name="payment_category" value="">
                    <input type="hidden" id="subscriptionAmount" name="amount" value="<?= $subsPrice ?>">

                    <!-- Step 4: Konfirmasi Pembayaran (Hidden by default) -->
                    <div class="form-section" id="sectionKonfirmasi" style="display: none;">
                        <div class="section-header">
                            <span class="step-number">4</span>
                            <span class="step-title">Konfirmasi Pembayaran</span>
                        </div>

                        <!-- Transaction Summary -->
                        <div class="confirmation-card">
                            <div class="confirmation-header">
                                <i class="fas fa-receipt"></i>
                                <span>Ringkasan Transaksi</span>
                            </div>
                            <div class="confirmation-body">
                                <div class="summary-row">
                                    <span class="label">Cabang</span>
                                    <span class="value" id="confirmCabangName">-</span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Paket</span>
                                    <span class="value" id="confirmPackageType">Berlangganan (1 Tahun)</span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Metode Pembayaran</span>
                                    <span class="value" id="confirmPaymentMethod">-</span>
                                </div>
                                <div class="summary-divider"></div>
                                <div class="summary-row">
                                    <span class="label">Harga Paket</span>
                                    <span class="value" id="confirmSubtotal"><?= $subsPriceFormatted ?></span>
                                </div>
                                <div class="summary-row" id="confirmFeeRow" style="display: none;">
                                    <span class="label">Biaya Admin</span>
                                    <span class="value" id="confirmFee">Rp 0</span>
                                </div>
                                <div class="summary-row total">
                                    <span class="label">Total Pembayaran</span>
                                    <span class="value" id="confirmTotal"><?= $subsPriceFormatted ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="terms-checkbox">
                            <label class="checkbox-container">
                                <input type="checkbox" id="acceptTerms">
                                <span class="checkmark"></span>
                                <span class="terms-text">Saya menyetujui <a href="javascript:void(0)" id="toggleTerms">Syarat & Ketentuan</a> yang berlaku</span>
                            </label>
                        </div>

                        <!-- Terms Content (Collapsible) -->
                        <div class="terms-content-wrapper" id="termsContentWrapper">
                            <div class="terms-content">
                                <h5>Syarat & Ketentuan Aktivasi Cabang</h5>
                                
                                <h6>1. Ketentuan Umum</h6>
                                <ol>
                                    <li>Layanan aktivasi cabang disediakan oleh Cipta Sejati Indonesia.</li>
                                    <li>Dengan mengaktifkan cabang, pengguna menyetujui semua syarat dan ketentuan yang berlaku.</li>
                                    <li>Pengguna bertanggung jawab atas kebenaran data yang diinput.</li>
                                </ol>

                                <h6>2. Paket Trial</h6>
                                <ol>
                                    <li>Paket trial berlaku selama 14 hari sejak aktivasi.</li>
                                    <li>Paket trial hanya dapat digunakan satu kali per cabang.</li>
                                    <li>Setelah masa trial berakhir, cabang akan dinonaktifkan secara otomatis.</li>
                                </ol>

                                <h6>3. Paket Berlangganan</h6>
                                <ol>
                                    <li>Paket berlangganan berlaku selama 1 (satu) tahun sejak pembayaran berhasil.</li>
                                    <li>Pembayaran bersifat non-refundable (tidak dapat dikembalikan).</li>
                                    <li>Perpanjangan langganan harus dilakukan sebelum masa aktif berakhir.</li>
                                </ol>

                                <h6>4. Pembayaran</h6>
                                <ol>
                                    <li>Pembayaran harus diselesaikan sebelum batas waktu yang ditentukan.</li>
                                    <li>Transaksi yang tidak dibayar akan otomatis dibatalkan.</li>
                                    <li>Biaya admin (jika ada) akan ditambahkan ke total pembayaran.</li>
                                </ol>

                                <h6>5. Hak dan Kewajiban</h6>
                                <ol>
                                    <li>Cipta Sejati Indonesia berhak mengubah syarat dan ketentuan sewaktu-waktu.</li>
                                    <li>Pengguna wajib menjaga kerahasiaan akun dan data cabang.</li>
                                    <li>Penyalahgunaan layanan dapat mengakibatkan penonaktifan cabang.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer aktivasi-modal-footer">
                <button type="button" class="btn btn-cancel" id="btnBatal">
                    <i class="fas fa-times"></i> <span id="btnBatalText">Batal</span>
                </button>
                <button type="button" class="btn btn-aktivasi" id="btnAktivasi" disabled>
                    <i class="fas fa-check"></i> <span id="btnAktivasiText">Aktivasi</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Result Modal -->
<div class="modal fade" id="paymentResultModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content aktivasi-modal-content">
            <div class="modal-header aktivasi-modal-header">
                <div class="header-content">
                    <div class="header-icon success">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="header-text">
                        <h4 class="modal-title">Detail Pembayaran</h4>
                        <p class="modal-subtitle">Selesaikan pembayaran Anda</p>
                    </div>
                </div>
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body aktivasi-modal-body">
                <div id="paymentResultContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer aktivasi-modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Status Modal (Success/Failed) -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content aktivasi-modal-content">
            <div class="modal-header aktivasi-modal-header" id="paymentStatusHeader">
                <div class="header-content">
                    <div class="header-icon" id="paymentStatusIcon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="header-text">
                        <h4 class="modal-title" id="paymentStatusTitle">Status Pembayaran</h4>
                        <p class="modal-subtitle" id="paymentStatusSubtitle">Informasi pembayaran Anda</p>
                    </div>
                </div>
                <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body aktivasi-modal-body">
                <div id="paymentStatusContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer aktivasi-modal-footer" id="paymentStatusFooter">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-aktivasi" id="btnGoToDashboard" style="display: none;">
                    <i class="fas fa-tachometer-alt"></i> Ke Dashboard
                </button>
                <button type="button" class="btn btn-warning-custom" id="btnRetryPayment" style="display: none;">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pass subscription price to JS -->
<script>
    var SUBSCRIPTION_PRICE = <?= $subsPrice ?>;
</script>
