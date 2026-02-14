
<div id="Payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="Payment-form" class="form form-horizontal form-striped" action="" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Detail Pembayaran Sewa</h3>
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
                            <input type="hidden" id="payment_amount" name="amount" value="">
                            
                            <!-- Payment Amount Display -->
                            <div class="alert alert-info text-center">
                                <h4>Total Pembayaran: <strong id="display_payment_amount">Rp 0</strong></h4>
                            </div>

                            <!-- Payment Categories Accordion -->
                            <div class="panel-group" id="payment-accordion">
                                <!-- Transfer Bank -->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#payment-accordion" href="#collapse-transfer-bank">
                                                <i class="fa fa-university"></i> Transfer Bank
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse-transfer-bank" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div class="row">
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="bank_bca" data-category="transfer_bank">
                                                    <img src="assets/payment/bca.svg" alt="BCA" class="img-responsive">
                                                    <span>Bank BCA</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="bank_bni" data-category="transfer_bank">
                                                    <img src="assets/payment/bni.svg" alt="BNI" class="img-responsive">
                                                    <span>Bank BNI</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="bank_bri" data-category="transfer_bank">
                                                    <img src="assets/payment/bri.svg" alt="BRI" class="img-responsive">
                                                    <span>Bank BRI</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="bank_mandiri" data-category="transfer_bank">
                                                    <img src="assets/payment/mandiri.svg" alt="Mandiri" class="img-responsive">
                                                    <span>Bank Mandiri</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Virtual Account -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#payment-accordion" href="#collapse-virtual-account">
                                            <i class="fa fa-barcode"></i> Virtual Account
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-virtual-account" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="va_bca" data-category="virtual_account">
                                                    <img src="assets/payment/bca.svg" alt="VA BCA" class="img-responsive">
                                                    <span>VA BCA</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="va_bni" data-category="virtual_account">
                                                    <img src="assets/payment/bni.svg" alt="VA BNI" class="img-responsive">
                                                    <span>VA BNI</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="va_bri" data-category="virtual_account">
                                                    <img src="assets/payment/bri.svg" alt="VA BRI" class="img-responsive">
                                                    <span>VA BRI</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="va_mandiri" data-category="virtual_account">
                                                    <img src="assets/payment/mandiri.svg" alt="VA Mandiri" class="img-responsive">
                                                    <span>VA Mandiri</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="va_permata" data-category="virtual_account">
                                                    <img src="assets/payment/permata.svg" alt="VA Permata" class="img-responsive">
                                                    <span>VA Permata</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- E-Wallet -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#payment-accordion" href="#collapse-ewallet">
                                            <i class="fa fa-mobile"></i> E-Wallet
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-ewallet" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="gopay" data-category="ewallet">
                                                    <img src="assets/payment/gopay.svg" alt="GoPay" class="img-responsive">
                                                    <span>GoPay</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="ovo" data-category="ewallet">
                                                    <img src="assets/payment/ovo.svg" alt="OVO" class="img-responsive">
                                                    <span>OVO</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="dana" data-category="ewallet">
                                                    <img src="assets/payment/dana.svg" alt="DANA" class="img-responsive">
                                                    <span>DANA</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="shopeepay" data-category="ewallet">
                                                    <img src="assets/payment/shopeepay.svg" alt="ShopeePay" class="img-responsive">
                                                    <span>ShopeePay</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="linkaja" data-category="ewallet">
                                                    <img src="assets/payment/linkaja.svg" alt="LinkAja" class="img-responsive">
                                                    <span>LinkAja</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- QRIS -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#payment-accordion" href="#collapse-qris">
                                            <i class="fa fa-qrcode"></i> QRIS
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse-qris" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 mb-3">
                                                <div class="payment-option" data-method="qris" data-category="qris">
                                                    <img src="assets/payment/qris.svg" alt="QRIS" class="img-responsive">
                                                    <span>QRIS</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div><!-- End payment-accordion -->

                            <!-- Payment Instructions -->
                            <div id="payment-instructions" class="alert alert-warning" style="display: none;">
                                <h5><i class="fa fa-info-circle"></i> Instruksi Pembayaran</h5>
                                <div id="payment-instruction-content"></div>
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
                        <span class="fa fa-check"></span> Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
</attachment>
