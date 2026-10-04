/**
 * Payment Gateway Module
 * Handles payment method selection, processing, and history
 */

var PaymentGateway = (function() {
    'use strict';

    // Configuration
    var config = {
        ajaxUrl: 'module/ajax/payment/aj_payment.php',
        historyAjaxUrl: 'module/ajax/payment/aj_payment_history_ssp.php',
        pollingInterval: 5000, // Check payment status every 5 seconds
        maxPollingAttempts: 120 // Max 10 minutes of polling
    };

    // State
    var state = {
        selectedMethod: null,
        selectedCategory: null,
        orderId: null,
        amount: 0,
        transactionId: null,
        pollingTimer: null,
        pollingAttempts: 0,
        historyDataTable: null,
        countdownTimer: null
    };

    /**
     * Initialize payment gateway
     */
    function init() {
        bindEvents();
        console.log('Payment Gateway initialized');
    }

    /**
     * Bind event handlers
     */
    function bindEvents() {
        // Payment method selection
        $(document).on('click', '#Payment .payment-option', function(e) {
            e.preventDefault();
            e.stopPropagation();
            selectPaymentMethod($(this));
        });

        // Copy button click handler (for account number and VA number)
        $(document).on('click', '.bank-info-card .account-number, .va-info-card .va-number', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var textToCopy = $(this).find('span').first().text();
            if (textToCopy) {
                copyToClipboard(textToCopy);
            }
        });

        // Payment history detail button click handler
        $(document).on('click', '.btn-view-payment-detail', function(e) {
            e.preventDefault();
            var transactionId = $(this).data('transaction-id');
            if (transactionId) {
                viewDetail(transactionId);
            }
        });

        // Process payment button
        $(document).on('click', '#btn-process-payment', function(e) {
            e.preventDefault();
            processPayment();
        });

        // Modal open - initialize if opened from menu
        $(document).on('shown.bs.modal', '#Payment', function() {
            // If no order_id is set, show message
            if (!state.orderId || !state.amount) {
                // Set default for testing or show info
                console.log('Payment modal opened without order data');
            }
            
            // Check if Payment History tab is active and load data
            if ($('#tab-payment-history').hasClass('active')) {
                loadPaymentHistory();
                // Adjust columns after modal is visible
                if (state.historyDataTable) {
                    setTimeout(function() {
                        state.historyDataTable.columns.adjust().responsive.recalc();
                    }, 50);
                }
            }
        });

        // Modal close - cleanup
        $(document).on('hidden.bs.modal', '#Payment', function() {
            resetPaymentState();
        });

        // Tab change - use shown.bs.tab to ensure tab is visible before initializing DataTable
        $(document).on('shown.bs.tab', '#Payment a[data-toggle="tab"]', function(e) {
            if ($(e.target).attr('href') === '#tab-payment-history') {
                loadPaymentHistory();
                // Adjust columns after tab is visible (fixes DataTable width issue)
                if (state.historyDataTable) {
                    setTimeout(function() {
                        state.historyDataTable.columns.adjust().responsive.recalc();
                    }, 10);
                }
            }
        });

        // Open Payment link from menu - initialize with order data
        $(document).on('click', '.open-Payment', function() {
            // You can set order data here if needed
            // For now, let's set a sample order for testing
            var testOrderId = 'ORD-' + Date.now();
            var testAmount = 100000; // Sample amount Rp 100.000
            
            state.orderId = testOrderId;
            state.amount = testAmount;
            
            $('#payment_order_id').val(testOrderId);
            $('#payment_amount').val(testAmount);
            $('#display_payment_amount').text(formatCurrency(testAmount));
            
            // Reset selection when opening
            $('.payment-option').removeClass('selected');
            $('#payment-instructions').hide();
            $('#qr-code-container').hide();
            $('#btn-process-payment').hide();
        });
    }

    /**
     * Open payment modal with order details
     * @param {string} orderId - Order ID
     * @param {number} amount - Payment amount
     */
    function openPaymentModal(orderId, amount) {
        state.orderId = orderId;
        state.amount = amount;

        $('#payment_order_id').val(orderId);
        $('#payment_amount').val(amount);
        $('#display_payment_amount').text(formatCurrency(amount));

        // Reset selection
        $('.payment-option').removeClass('selected');
        $('#payment-instructions').hide();
        $('#qr-code-container').hide();
        $('#btn-process-payment').hide();

        $('#Payment').modal('show');
    }

    /**
     * Select payment method
     * @param {jQuery} element - Payment option element
     */
    function selectPaymentMethod(element) {
        // Remove previous selection
        $('.payment-option').removeClass('selected');
        
        // Add selection to current
        element.addClass('selected');

        // Update state
        state.selectedMethod = element.data('method');
        state.selectedCategory = element.data('category');

        // Show process button
        $('#btn-process-payment').show();

        // Show payment instructions
        showPaymentInstructions(state.selectedMethod, state.selectedCategory);

        // Show toast notification for selection
        var methodName = element.find('span').text();
        showInfo('Metode pembayaran ' + methodName + ' dipilih. Klik "Proses Pembayaran" untuk melanjutkan.', 'Metode Dipilih');
    }

    /**
     * Show payment instructions based on method
     * @param {string} method - Payment method
     * @param {string} category - Payment category
     */
    function showPaymentInstructions(method, category) {
        var instructions = getPaymentInstructions(method, category);
        
        $('#payment-instruction-content').html(instructions);
        $('#payment-instructions').show();

        // Hide QR code if not QRIS
        if (category !== 'qris') {
            $('#qr-code-container').hide();
        }
    }

    /**
     * Get payment instructions HTML
     * @param {string} method - Payment method
     * @param {string} category - Payment category
     * @returns {string} Instructions HTML
     */
    function getPaymentInstructions(method, category) {
        var instructions = '';

        switch (category) {
            case 'transfer_bank':
                instructions = getTransferBankInstructions(method);
                break;
            case 'virtual_account':
                instructions = getVirtualAccountInstructions(method);
                break;
            case 'ewallet':
                instructions = getEwalletInstructions(method);
                break;
            case 'qris':
                instructions = getQrisInstructions();
                break;
            default:
                instructions = '<p>Silakan klik tombol Proses Pembayaran untuk melanjutkan.</p>';
        }

        return instructions;
    }

    /**
     * Get transfer bank instructions
     */
    function getTransferBankInstructions(method) {
        var bankNames = {
            'bank_bca': 'BCA',
            'bank_bni': 'BNI',
            'bank_bri': 'BRI',
            'bank_mandiri': 'Mandiri'
        };

        return '<ol>' +
            '<li>Klik tombol "Proses Pembayaran" untuk mendapatkan nomor rekening tujuan</li>' +
            '<li>Transfer ke rekening ' + (bankNames[method] || 'Bank') + ' yang tertera</li>' +
            '<li>Masukkan jumlah transfer sesuai dengan total pembayaran</li>' +
            '<li>Simpan bukti transfer untuk verifikasi</li>' +
            '<li>Pembayaran akan diverifikasi dalam 1x24 jam</li>' +
            '</ol>';
    }

    /**
     * Get virtual account instructions
     */
    function getVirtualAccountInstructions(method) {
        return '<ol>' +
            '<li>Klik tombol "Proses Pembayaran" untuk mendapatkan nomor Virtual Account</li>' +
            '<li>Buka aplikasi Mobile Banking atau ATM</li>' +
            '<li>Pilih menu Transfer > Virtual Account</li>' +
            '<li>Masukkan nomor Virtual Account yang tertera</li>' +
            '<li>Konfirmasi nominal pembayaran</li>' +
            '<li>Pembayaran akan terkonfirmasi otomatis</li>' +
            '</ol>';
    }

    /**
     * Get e-wallet instructions
     */
    function getEwalletInstructions(method) {
        var walletNames = {
            'gopay': 'GoPay',
            'ovo': 'OVO',
            'dana': 'DANA',
            'shopeepay': 'ShopeePay',
            'linkaja': 'LinkAja'
        };

        return '<ol>' +
            '<li>Klik tombol "Proses Pembayaran"</li>' +
            '<li>Anda akan diarahkan ke aplikasi ' + (walletNames[method] || 'E-Wallet') + '</li>' +
            '<li>Konfirmasi pembayaran di aplikasi</li>' +
            '<li>Pembayaran akan terkonfirmasi otomatis</li>' +
            '</ol>';
    }

    /**
     * Get QRIS instructions
     */
    function getQrisInstructions() {
        return '<ol>' +
            '<li>Klik tombol "Proses Pembayaran" untuk menampilkan QR Code</li>' +
            '<li>Buka aplikasi pembayaran yang mendukung QRIS</li>' +
            '<li>Scan QR Code yang ditampilkan</li>' +
            '<li>Konfirmasi pembayaran di aplikasi</li>' +
            '<li>Pembayaran akan terkonfirmasi otomatis</li>' +
            '</ol>';
    }

    /**
     * Process payment
     */
    function processPayment() {
        if (!state.selectedMethod) {
            showNotification('error', 'Silakan pilih metode pembayaran terlebih dahulu');
            return;
        }

        // Show loading
        showLoading();

        // Prepare data
        var data = {
            action: 'create_transaction',
            order_id: state.orderId,
            amount: state.amount,
            payment_method: state.selectedMethod,
            payment_category: state.selectedCategory
        };

        // Send request
        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    state.transactionId = response.data.transaction_id;
                    handlePaymentResponse(response.data);
                } else {
                    showNotification('error', response.message || 'Gagal memproses pembayaran');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                console.error('Payment error:', error);
            }
        });
    }

    /**
     * Handle payment response based on category
     * @param {object} data - Response data
     */
    function handlePaymentResponse(data) {
        // Show success toast for transaction creation
        showSuccess('Transaksi berhasil dibuat! ID: ' + data.transaction_id, 'Transaksi Dibuat');

        switch (state.selectedCategory) {
            case 'transfer_bank':
                showBankTransferDetails(data);
                showInfo('Silakan transfer sesuai instruksi di bawah.', 'Transfer Bank');
                startPaymentPolling(); // Start polling for bank transfer too
                break;
            case 'virtual_account':
                showVirtualAccountDetails(data);
                showInfo('Nomor Virtual Account sudah tersedia. Silakan lakukan pembayaran.', 'Virtual Account');
                startPaymentPolling();
                break;
            case 'ewallet':
                handleEwalletPayment(data);
                showInfo('Silakan selesaikan pembayaran di aplikasi E-Wallet Anda.', 'E-Wallet');
                startPaymentPolling();
                break;
            case 'qris':
                showQrisCode(data);
                showInfo('Scan QR Code dengan aplikasi pembayaran Anda.', 'QRIS');
                startPaymentPolling();
                break;
        }
    }

    /**
     * Show bank transfer details
     */
    function showBankTransferDetails(data) {
        var feeRow = data.fee_amount > 0 ? 
            '<div class="payment-detail-row"><span class="label">Biaya Admin</span><span class="value">' + formatCurrency(data.fee_amount) + '</span></div>' : '';
        
        var html = '<div class="payment-result-container">' +
            // Header with status
            '<div class="payment-status-header success">' +
                '<div class="status-icon"><i class="fa fa-check-circle"></i></div>' +
                '<div class="status-text">' +
                    '<h4>Transaksi Berhasil Dibuat</h4>' +
                    '<p class="transaction-id">' + data.transaction_id + '</p>' +
                '</div>' +
            '</div>' +
            
            // Two column layout
            '<div class="payment-details-grid">' +
                // Left: Bank Details
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-university"></i> Informasi Rekening</div>' +
                    '<div class="section-body">' +
                        '<div class="bank-info-card">' +
                            '<div class="bank-name">' + data.bank_name + '</div>' +
                            '<div class="account-number" data-copy="' + data.account_number + '" style="cursor:pointer;">' +
                                '<span>' + data.account_number + '</span>' +
                                '<button type="button" class="btn-copy" title="Klik untuk menyalin"><i class="fa fa-copy"></i></button>' +
                            '</div>' +
                            '<div class="account-name">a.n. ' + data.account_name + '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                
                // Right: Payment Summary
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-calculator"></i> Rincian Pembayaran</div>' +
                    '<div class="section-body">' +
                        '<div class="payment-detail-row"><span class="label">Subtotal</span><span class="value">' + formatCurrency(data.amount) + '</span></div>' +
                        feeRow +
                        '<div class="payment-detail-row total"><span class="label">Total Transfer</span><span class="value">' + formatCurrency(data.total_amount) + '</span></div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            
            // Countdown Timer
            '<div class="payment-countdown-section">' +
                '<div class="countdown-label"><i class="fa fa-clock-o"></i> Selesaikan pembayaran dalam</div>' +
                '<div class="countdown-timer" id="payment-countdown" data-expired="' + data.expired_at + '"></div>' +
                '<div class="countdown-note">Pembayaran akan diverifikasi dalam 1x24 jam setelah transfer</div>' +
            '</div>' +
        '</div>';

        $('#payment-instruction-content').html(html);
        $('#btn-process-payment').hide();
        startCountdown(data.expired_at);
    }

    /**
     * Show virtual account details
     */
    function showVirtualAccountDetails(data) {
        var feeRow = data.fee_amount > 0 ? 
            '<div class="payment-detail-row"><span class="label">Biaya Admin</span><span class="value">' + formatCurrency(data.fee_amount) + '</span></div>' : '';
        
        var html = '<div class="payment-result-container">' +
            // Header with status
            '<div class="payment-status-header success">' +
                '<div class="status-icon"><i class="fa fa-check-circle"></i></div>' +
                '<div class="status-text">' +
                    '<h4>Virtual Account Dibuat</h4>' +
                    '<p class="transaction-id">' + data.transaction_id + '</p>' +
                '</div>' +
            '</div>' +
            
            // Two column layout
            '<div class="payment-details-grid">' +
                // Left: VA Details
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-barcode"></i> Virtual Account</div>' +
                    '<div class="section-body">' +
                        '<div class="va-info-card">' +
                            '<div class="bank-name">' + (data.bank_name || 'Virtual Account') + '</div>' +
                            '<div class="va-number" data-copy="' + data.va_number + '" style="cursor:pointer;">' +
                                '<span>' + formatVANumber(data.va_number) + '</span>' +
                                '<button type="button" class="btn-copy" title="Klik untuk menyalin"><i class="fa fa-copy"></i></button>' +
                            '</div>' +
                            '<div class="account-name">a.n. ' + (data.account_name || 'PT Cipta Sejati') + '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                
                // Right: Payment Summary
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-calculator"></i> Rincian Pembayaran</div>' +
                    '<div class="section-body">' +
                        '<div class="payment-detail-row"><span class="label">Subtotal</span><span class="value">' + formatCurrency(data.amount) + '</span></div>' +
                        feeRow +
                        '<div class="payment-detail-row total"><span class="label">Total Bayar</span><span class="value">' + formatCurrency(data.total_amount) + '</span></div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            
            // Countdown Timer
            '<div class="payment-countdown-section">' +
                '<div class="countdown-label"><i class="fa fa-clock-o"></i> Selesaikan pembayaran dalam</div>' +
                '<div class="countdown-timer" id="payment-countdown" data-expired="' + data.expired_at + '"></div>' +
                '<div class="payment-waiting"><i class="fa fa-spinner fa-spin"></i> Menunggu pembayaran...</div>' +
            '</div>' +
        '</div>';

        $('#payment-instruction-content').html(html);
        $('#btn-process-payment').hide();
        startCountdown(data.expired_at);
    }

    /**
     * Handle e-wallet payment
     */
    function handleEwalletPayment(data) {
        if (data.redirect_url) {
            // Open e-wallet app/web
            window.open(data.redirect_url, '_blank');
        }

        if (data.deeplink_url) {
            // Try to open mobile app
            window.location.href = data.deeplink_url;
        }

        var feeRow = data.fee_amount > 0 ? 
            '<div class="payment-detail-row"><span class="label">Biaya Admin</span><span class="value">' + formatCurrency(data.fee_amount) + '</span></div>' : '';
        
        var walletName = getWalletName(state.selectedMethod);
        
        var html = '<div class="payment-result-container">' +
            // Header with status
            '<div class="payment-status-header pending">' +
                '<div class="status-icon"><i class="fa fa-mobile"></i></div>' +
                '<div class="status-text">' +
                    '<h4>Menunggu Pembayaran ' + walletName + '</h4>' +
                    '<p class="transaction-id">' + data.transaction_id + '</p>' +
                '</div>' +
            '</div>' +
            
            // Two column layout
            '<div class="payment-details-grid">' +
                // Left: E-Wallet Info
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-mobile"></i> Instruksi Pembayaran</div>' +
                    '<div class="section-body">' +
                        '<div class="ewallet-steps">' +
                            '<div class="step"><span class="step-num">1</span> Buka aplikasi ' + walletName + '</div>' +
                            '<div class="step"><span class="step-num">2</span> Cek notifikasi pembayaran</div>' +
                            '<div class="step"><span class="step-num">3</span> Konfirmasi dan bayar</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                
                // Right: Payment Summary
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-calculator"></i> Rincian Pembayaran</div>' +
                    '<div class="section-body">' +
                        '<div class="payment-detail-row"><span class="label">Subtotal</span><span class="value">' + formatCurrency(data.amount) + '</span></div>' +
                        feeRow +
                        '<div class="payment-detail-row total"><span class="label">Total Bayar</span><span class="value">' + formatCurrency(data.total_amount) + '</span></div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            
            // Countdown Timer
            '<div class="payment-countdown-section">' +
                '<div class="countdown-label"><i class="fa fa-clock-o"></i> Selesaikan pembayaran dalam</div>' +
                '<div class="countdown-timer" id="payment-countdown" data-expired="' + data.expired_at + '"></div>' +
                '<div class="payment-waiting"><i class="fa fa-spinner fa-spin"></i> Menunggu konfirmasi pembayaran...</div>' +
            '</div>' +
        '</div>';

        $('#payment-instruction-content').html(html);
        $('#btn-process-payment').hide();
        startCountdown(data.expired_at);
    }

    /**
     * Show QRIS code
     */
    function showQrisCode(data) {
        var feeRow = data.fee_amount > 0 ? 
            '<div class="payment-detail-row"><span class="label">Biaya Admin</span><span class="value">' + formatCurrency(data.fee_amount) + '</span></div>' : '';
        
        var html = '<div class="payment-result-container">' +
            // Header with status
            '<div class="payment-status-header pending">' +
                '<div class="status-icon"><i class="fa fa-qrcode"></i></div>' +
                '<div class="status-text">' +
                    '<h4>Scan QR Code untuk Bayar</h4>' +
                    '<p class="transaction-id">' + data.transaction_id + '</p>' +
                '</div>' +
            '</div>' +
            
            // Two column layout
            '<div class="payment-details-grid">' +
                // Left: QR Code
                '<div class="payment-section qris-section">' +
                    '<div class="section-header"><i class="fa fa-qrcode"></i> QRIS</div>' +
                    '<div class="section-body text-center">' +
                        '<div class="qris-card">' +
                            '<img src="' + data.qr_code_url + '" alt="QRIS" class="qris-image">' +
                            '<div class="qris-note">Scan dengan aplikasi e-wallet atau mobile banking</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                
                // Right: Payment Summary
                '<div class="payment-section">' +
                    '<div class="section-header"><i class="fa fa-calculator"></i> Rincian Pembayaran</div>' +
                    '<div class="section-body">' +
                        '<div class="payment-detail-row"><span class="label">Subtotal</span><span class="value">' + formatCurrency(data.amount) + '</span></div>' +
                        feeRow +
                        '<div class="payment-detail-row total"><span class="label">Total Bayar</span><span class="value">' + formatCurrency(data.total_amount) + '</span></div>' +
                    '</div>' +
                    '<div class="section-body" style="margin-top: 15px;">' +
                        '<div class="ewallet-steps">' +
                            '<div class="step"><span class="step-num">1</span> Buka aplikasi pembayaran</div>' +
                            '<div class="step"><span class="step-num">2</span> Pilih menu Scan/QRIS</div>' +
                            '<div class="step"><span class="step-num">3</span> Scan QR dan konfirmasi</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            
            // Countdown Timer
            '<div class="payment-countdown-section">' +
                '<div class="countdown-label"><i class="fa fa-clock-o"></i> Selesaikan pembayaran dalam</div>' +
                '<div class="countdown-timer" id="payment-countdown" data-expired="' + data.expired_at + '"></div>' +
                '<div class="payment-waiting"><i class="fa fa-spinner fa-spin"></i> Menunggu pembayaran...</div>' +
            '</div>' +
        '</div>';

        $('#payment-instruction-content').html(html);
        $('#qr-code-container').hide(); // Hide old container, use inline QR
        $('#btn-process-payment').hide();
        startCountdown(data.expired_at);
    }

    /**
     * Start polling for payment status
     */
    function startPaymentPolling() {
        state.pollingAttempts = 0;
        
        if (state.pollingTimer) {
            clearInterval(state.pollingTimer);
        }

        state.pollingTimer = setInterval(function() {
            checkPaymentStatus();
        }, config.pollingInterval);
    }

    /**
     * Check payment status
     */
    function checkPaymentStatus() {
        state.pollingAttempts++;

        if (state.pollingAttempts >= config.maxPollingAttempts) {
            stopPaymentPolling();
            showNotification('warning', 'Waktu pembayaran telah habis');
            return;
        }

        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'check_status',
                transaction_id: state.transactionId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    handlePaymentStatusUpdate(response.data);
                }
            },
            error: function() {
                console.error('Failed to check payment status');
            }
        });
    }

    /**
     * Handle payment status update
     */
    function handlePaymentStatusUpdate(data) {
        console.log('Payment status update:', data.status, data);
        switch (data.status) {
            case 'success':
            case 'settlement':
            case 'capture':
                console.log('Payment SUCCESS detected, calling showPaymentSuccess');
                stopPaymentPolling();
                showPaymentSuccess(data);
                break;
            case 'pending':
                // Continue polling
                break;
            case 'failed':
            case 'expired':
            case 'cancel':
            case 'deny':
            case 'failure':
                console.log('Payment FAILED/EXPIRED detected, calling showPaymentFailed');
                stopPaymentPolling();
                showPaymentFailed(data);
                break;
        }
    }

    /**
     * Show payment success
     */
    function showPaymentSuccess(data) {
        // Stop countdown timer immediately
        if (state.countdownTimer) {
            clearInterval(state.countdownTimer);
            state.countdownTimer = null;
        }

        var html = '<div class="payment-success-container">' +
            // Animated success icon with celebration effect
            '<div class="success-celebration">' +
                '<div class="success-checkmark">' +
                    '<div class="check-icon">' +
                        '<span class="icon-line line-tip"></span>' +
                        '<span class="icon-line line-long"></span>' +
                        '<div class="icon-circle"></div>' +
                        '<div class="icon-fix"></div>' +
                    '</div>' +
                '</div>' +
                '<div class="confetti-container">' +
                    '<div class="confetti"></div><div class="confetti"></div><div class="confetti"></div>' +
                    '<div class="confetti"></div><div class="confetti"></div><div class="confetti"></div>' +
                '</div>' +
            '</div>' +
            
            // Success message
            '<div class="success-message">' +
                '<h2 class="success-title">Pembayaran Berhasil!</h2>' +
                '<p class="success-subtitle">Terima kasih, pembayaran Anda telah kami terima</p>' +
            '</div>' +
            
            // Transaction card
            '<div class="success-card">' +
                '<div class="success-card-header">' +
                    '<div class="transaction-label">No. Transaksi</div>' +
                    '<div class="transaction-id">' + data.transaction_id + '</div>' +
                '</div>' +
                '<div class="success-card-body">' +
                    '<div class="success-detail-row">' +
                        '<span class="detail-label"><i class="fa fa-money"></i> Jumlah Dibayar</span>' +
                        '<span class="detail-value amount">' + formatCurrency(data.total_amount || data.amount) + '</span>' +
                    '</div>' +
                    '<div class="success-detail-row">' +
                        '<span class="detail-label"><i class="fa fa-calendar"></i> Tanggal Bayar</span>' +
                        '<span class="detail-value">' + new Date().toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'}) + '</span>' +
                    '</div>' +
                    '<div class="success-detail-row">' +
                        '<span class="detail-label"><i class="fa fa-check-circle"></i> Status</span>' +
                        '<span class="detail-value"><span class="status-pill success"><i class="fa fa-check"></i> Lunas</span></span>' +
                    '</div>' +
                '</div>' +
                '<div class="success-card-footer">' +
                    '<button type="button" class="btn-success-action btn-print" onclick="PaymentGateway.printInvoice(\'' + data.transaction_id + '\')">' +
                        '<i class="fa fa-print"></i> Cetak Invoice' +
                    '</button>' +
                    '<button type="button" class="btn-success-action btn-download" onclick="PaymentGateway.downloadInvoice(\'' + data.transaction_id + '\')">' +
                        '<i class="fa fa-download"></i> Download PDF' +
                    '</button>' +
                '</div>' +
            '</div>' +
            
            // Additional info
            '<div class="success-info">' +
                '<i class="fa fa-info-circle"></i> ' +
                'Invoice telah dikirim ke email Anda. Simpan bukti pembayaran ini untuk keperluan administrasi.' +
            '</div>' +
        '</div>';

        // Add success styles if not exists
        if (!$('#payment-success-styles').length) {
            var styles = '<style id="payment-success-styles">' +
                '.payment-success-container { text-align: center; padding: 20px; animation: fadeInUp 0.5s ease; }' +
                '@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }' +
                
                // Celebration animation
                '.success-celebration { position: relative; margin-bottom: 20px; }' +
                '.success-checkmark { width: 80px; height: 80px; margin: 0 auto; border-radius: 50%; display: block; stroke-width: 2; stroke: #fff; stroke-miterlimit: 10; box-shadow: inset 0px 0px 0px #10b981; animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both; background: linear-gradient(135deg, #10b981 0%, #059669 100%); }' +
                '@keyframes fill { 100% { box-shadow: inset 0px 0px 0px 40px #10b981; } }' +
                '@keyframes scale { 0%, 100% { transform: none; } 50% { transform: scale3d(1.1, 1.1, 1); } }' +
                
                '.check-icon { width: 80px; height: 80px; position: relative; border-radius: 50%; box-sizing: content-box; }' +
                '.check-icon .icon-line { height: 5px; background-color: #fff; display: block; border-radius: 2px; position: absolute; z-index: 10; }' +
                '.check-icon .line-tip { width: 25px; left: 14px; top: 46px; transform: rotate(45deg); animation: icon-line-tip 0.75s; }' +
                '.check-icon .line-long { width: 47px; right: 8px; top: 38px; transform: rotate(-45deg); animation: icon-line-long 0.75s; }' +
                '@keyframes icon-line-tip { 0% { width: 0; left: 1px; top: 19px; } 54% { width: 0; left: 1px; top: 19px; } 70% { width: 50px; left: -8px; top: 37px; } 84% { width: 17px; left: 21px; top: 48px; } 100% { width: 25px; left: 14px; top: 46px; } }' +
                '@keyframes icon-line-long { 0% { width: 0; right: 46px; top: 54px; } 65% { width: 0; right: 46px; top: 54px; } 84% { width: 55px; right: 0px; top: 35px; } 100% { width: 47px; right: 8px; top: 38px; } }' +
                
                // Confetti
                '.confetti-container { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 200px; height: 100px; overflow: visible; }' +
                '.confetti { position: absolute; width: 10px; height: 10px; top: 40px; animation: confetti-fall 1s ease-out forwards; }' +
                '.confetti:nth-child(1) { left: 20%; background: #f59e0b; animation-delay: 0s; }' +
                '.confetti:nth-child(2) { left: 35%; background: #10b981; animation-delay: 0.1s; border-radius: 50%; }' +
                '.confetti:nth-child(3) { left: 50%; background: #3b82f6; animation-delay: 0.2s; }' +
                '.confetti:nth-child(4) { left: 65%; background: #ef4444; animation-delay: 0.15s; border-radius: 50%; }' +
                '.confetti:nth-child(5) { left: 80%; background: #8b5cf6; animation-delay: 0.25s; }' +
                '.confetti:nth-child(6) { left: 95%; background: #ec4899; animation-delay: 0.05s; border-radius: 50%; }' +
                '@keyframes confetti-fall { 0% { opacity: 1; transform: translateY(0) rotate(0deg); } 100% { opacity: 0; transform: translateY(80px) rotate(360deg); } }' +
                
                // Success message
                '.success-message { margin-bottom: 25px; }' +
                '.success-title { font-size: 28px; font-weight: 700; color: #10b981; margin: 0 0 8px; }' +
                '.success-subtitle { font-size: 14px; color: #6b7280; margin: 0; }' +
                
                // Success card
                '.success-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 20px; text-align: left; }' +
                '.success-card-header { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 20px; border-bottom: 1px dashed #86efac; text-align: center; }' +
                '.transaction-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }' +
                '.transaction-id { font-size: 16px; font-weight: 600; color: #059669; font-family: monospace; }' +
                '.success-card-body { padding: 20px; }' +
                '.success-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }' +
                '.success-detail-row:last-child { border-bottom: none; }' +
                '.detail-label { color: #6b7280; font-size: 14px; }' +
                '.detail-label i { width: 20px; margin-right: 8px; color: #9ca3af; }' +
                '.detail-value { font-weight: 600; color: #1f2937; font-size: 14px; }' +
                '.detail-value.amount { color: #10b981; font-size: 18px; }' +
                '.status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }' +
                '.status-pill.success { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }' +
                
                // Footer buttons
                '.success-card-footer { display: flex; gap: 10px; padding: 15px 20px; background: #f9fafb; border-top: 1px solid #f3f4f6; }' +
                '.btn-success-action { flex: 1; padding: 12px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; }' +
                '.btn-success-action.btn-print { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }' +
                '.btn-success-action.btn-print:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }' +
                '.btn-success-action.btn-download { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }' +
                '.btn-success-action.btn-download:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16,185,129,0.4); }' +
                
                // Info box
                '.success-info { background: linear-gradient(135deg, #eff6ff, #dbeafe); padding: 15px 20px; border-radius: 12px; font-size: 13px; color: #1e40af; display: flex; align-items: center; gap: 10px; }' +
                '.success-info i { font-size: 16px; }' +
            '</style>';
            $('head').append(styles);
        }

        // Replace entire instruction content including countdown
        $('#payment-instruction-content').html(html);
        $('#qr-code-container').hide();
        
        // Also hide the countdown section if it exists separately
        $('.payment-countdown-section').hide();

        showNotification('success', 'Pembayaran berhasil!');

        // Trigger callback if defined
        if (typeof onPaymentSuccess === 'function') {
            onPaymentSuccess(data);
        }

        // Refresh payment history DataTable after 2 seconds (don't close modal, don't reload page)
        setTimeout(function() {
            // Refresh payment history DataTable
            if (state.historyDataTable) {
                state.historyDataTable.ajax.reload(null, false);
            }
        }, 2000);
    }

    /**
     * Show payment failed
     */
    function showPaymentFailed(data) {
        // Stop countdown timer immediately
        if (state.countdownTimer) {
            clearInterval(state.countdownTimer);
            state.countdownTimer = null;
        }

        var html = '<div class="payment-failed-container">' +
            // Animated failed icon with shake effect
            '<div class="failed-animation">' +
                '<div class="failed-circle">' +
                    '<div class="failed-icon">' +
                        '<span class="icon-line line-left"></span>' +
                        '<span class="icon-line line-right"></span>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            
            // Failed message
            '<div class="failed-message">' +
                '<h2 class="failed-title">Pembayaran Gagal</h2>' +
                '<p class="failed-subtitle">' + (data.message || 'Maaf, pembayaran Anda tidak dapat diproses') + '</p>' +
            '</div>' +
            
            // Transaction card
            '<div class="failed-card">' +
                '<div class="failed-card-header">' +
                    '<div class="transaction-label">No. Transaksi</div>' +
                    '<div class="transaction-id">' + (data.transaction_id || '-') + '</div>' +
                '</div>' +
                '<div class="failed-card-body">' +
                    '<div class="failed-detail-row">' +
                        '<span class="detail-label"><i class="fa fa-money"></i> Jumlah</span>' +
                        '<span class="detail-value amount">' + formatCurrency(data.total_amount || data.amount || 0) + '</span>' +
                    '</div>' +
                    '<div class="failed-detail-row">' +
                        '<span class="detail-label"><i class="fa fa-calendar"></i> Tanggal</span>' +
                        '<span class="detail-value">' + new Date().toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'}) + '</span>' +
                    '</div>' +
                    '<div class="failed-detail-row">' +
                        '<span class="detail-label"><i class="fa fa-times-circle"></i> Status</span>' +
                        '<span class="detail-value"><span class="status-pill failed"><i class="fa fa-times"></i> Gagal</span></span>' +
                    '</div>' +
                '</div>' +
                '<div class="failed-card-footer">' +
                    '<button type="button" class="btn-failed-action btn-retry" onclick="$(\'#btn-process-payment\').click()">' +
                        '<i class="fa fa-refresh"></i> Coba Lagi' +
                    '</button>' +
                    '<button type="button" class="btn-failed-action btn-contact" onclick="window.open(\'https://wa.me/6281234567890\', \'_blank\')">' +
                        '<i class="fa fa-whatsapp"></i> Hubungi CS' +
                    '</button>' +
                '</div>' +
            '</div>' +
            
            // Help info
            '<div class="failed-info">' +
                '<i class="fa fa-question-circle"></i> ' +
                'Jika Anda sudah melakukan pembayaran namun status masih gagal, silakan hubungi customer service kami.' +
            '</div>' +
        '</div>';

        // Add failed styles if not exists
        if (!$('#payment-failed-styles').length) {
            var styles = '<style id="payment-failed-styles">' +
                '.payment-failed-container { text-align: center; padding: 20px; animation: fadeInUp 0.5s ease; }' +
                '@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }' +
                
                // Failed animation
                '.failed-animation { position: relative; margin-bottom: 20px; }' +
                '.failed-circle { width: 80px; height: 80px; margin: 0 auto; border-radius: 50%; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); display: flex; align-items: center; justify-content: center; animation: shake 0.5s ease-in-out, scale-fail 0.3s ease-in-out 0.5s both; box-shadow: 0 4px 15px rgba(239,68,68,0.4); }' +
                '@keyframes shake { 0%, 100% { transform: translateX(0); } 10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); } 20%, 40%, 60%, 80% { transform: translateX(5px); } }' +
                '@keyframes scale-fail { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }' +
                
                '.failed-icon { width: 40px; height: 40px; position: relative; }' +
                '.failed-icon .icon-line { height: 5px; background-color: #fff; display: block; border-radius: 2px; position: absolute; }' +
                '.failed-icon .line-left { width: 35px; left: 3px; top: 18px; transform: rotate(45deg); animation: icon-line-fail 0.4s ease-out 0.3s both; }' +
                '.failed-icon .line-right { width: 35px; right: 3px; top: 18px; transform: rotate(-45deg); animation: icon-line-fail 0.4s ease-out 0.5s both; }' +
                '@keyframes icon-line-fail { from { width: 0; } to { width: 35px; } }' +
                
                // Failed message
                '.failed-message { margin-bottom: 25px; }' +
                '.failed-title { font-size: 28px; font-weight: 700; color: #ef4444; margin: 0 0 8px; }' +
                '.failed-subtitle { font-size: 14px; color: #6b7280; margin: 0; max-width: 300px; margin: 0 auto; }' +
                
                // Failed card
                '.failed-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 20px; text-align: left; }' +
                '.failed-card-header { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); padding: 20px; border-bottom: 1px dashed #fca5a5; text-align: center; }' +
                '.failed-card-header .transaction-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }' +
                '.failed-card-header .transaction-id { font-size: 16px; font-weight: 600; color: #dc2626; font-family: monospace; }' +
                '.failed-card-body { padding: 20px; }' +
                '.failed-detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6; }' +
                '.failed-detail-row:last-child { border-bottom: none; }' +
                '.failed-detail-row .detail-label { color: #6b7280; font-size: 14px; }' +
                '.failed-detail-row .detail-label i { width: 20px; margin-right: 8px; color: #9ca3af; }' +
                '.failed-detail-row .detail-value { font-weight: 600; color: #1f2937; font-size: 14px; }' +
                '.failed-detail-row .detail-value.amount { color: #ef4444; font-size: 18px; }' +
                '.status-pill.failed { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }' +
                
                // Footer buttons
                '.failed-card-footer { display: flex; gap: 10px; padding: 15px 20px; background: #f9fafb; border-top: 1px solid #f3f4f6; }' +
                '.btn-failed-action { flex: 1; padding: 12px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; }' +
                '.btn-failed-action.btn-retry { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }' +
                '.btn-failed-action.btn-retry:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }' +
                '.btn-failed-action.btn-contact { background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; }' +
                '.btn-failed-action.btn-contact:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(34,197,94,0.4); }' +
                
                // Info box
                '.failed-info { background: linear-gradient(135deg, #fef2f2, #fee2e2); padding: 15px 20px; border-radius: 12px; font-size: 13px; color: #991b1b; display: flex; align-items: center; gap: 10px; text-align: left; }' +
                '.failed-info i { font-size: 16px; flex-shrink: 0; }' +
            '</style>';
            $('head').append(styles);
        }

        $('#payment-instruction-content').html(html);
        $('#qr-code-container').hide();
        $('.payment-countdown-section').hide();
        $('#btn-process-payment').show();

        showNotification('error', 'Pembayaran gagal');

        // Refresh payment history DataTable after 2 seconds
        setTimeout(function() {
            if (state.historyDataTable) {
                state.historyDataTable.ajax.reload(null, false);
            }
        }, 2000);
    }

    /**
     * Stop payment polling
     */
    function stopPaymentPolling() {
        if (state.pollingTimer) {
            clearInterval(state.pollingTimer);
            state.pollingTimer = null;
        }
    }

    /**
     * Load payment history using DataTables with server-side processing
     */
    function loadPaymentHistory() {
        // If DataTable already exists, just reload
        if (state.historyDataTable) {
            state.historyDataTable.ajax.reload(null, false);
            return;
        }

        // Initialize DataTable with server-side processing
        state.historyDataTable = $('#table-payment-history').DataTable({
            responsive: true,
            order: [[1, 'desc']], // Order by date descending
            dom: 'Bfrtlip',
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: '300px',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            ajax: {
                url: config.historyAjaxUrl,
                type: 'POST',
                data: function(d) {
                    d._ts = Date.now();
                    d.order_id = state.orderId || '';
                }
            },
            columns: [
                { data: 0, orderable: false, width: '40px' },  // No
                { data: 1 },  // Tanggal
                { data: 2 },  // Transaction ID
                { data: 3 },  // Metode
                { data: 4 },  // Jumlah
                { data: 5 },  // Tanggal Kadaluarsa
                { data: 6, orderable: false },  // Status & Countdown (merged)
                { data: 7, orderable: false, width: '80px' }   // Aksi
            ],
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-2x"></i>',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(filter dari _MAX_ total data)',
                zeroRecords: 'Tidak ada data yang ditemukan',
                emptyTable: 'Belum ada riwayat pembayaran',
                paginate: {
                    first: 'Pertama',
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya',
                    last: 'Terakhir'
                }
            },
            drawCallback: function() {
                // Hide loading indicator after draw
                $('#payment-history-loading').hide();
                
                // Initialize countdown timers for pending transactions
                initializeCountdownTimers();
            }
        });
    }

    /**
     * Destroy payment history DataTable (called on modal close)
     */
    function destroyHistoryDataTable() {
        if (state.historyDataTable) {
            state.historyDataTable.destroy();
            state.historyDataTable = null;
        }
    }

    /**
     * Get status badge HTML - Bootstrap 3 label classes
     */
    function getStatusBadge(status) {
        var badges = {
            'pending': '<span class="label label-warning">Menunggu</span>',
            'success': '<span class="label label-success">Berhasil</span>',
            'settlement': '<span class="label label-success">Berhasil</span>',
            'failed': '<span class="label label-danger">Gagal</span>',
            'expired': '<span class="label label-default">Kadaluarsa</span>',
            'cancel': '<span class="label label-default">Dibatalkan</span>'
        };

        return badges[status] || '<span class="label label-default">' + status + '</span>';
    }

    /**
     * View transaction detail
     */
    function viewDetail(transactionId) {
        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'get_detail',
                transaction_id: transactionId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showTransactionDetail(response.data);
                } else {
                    showNotification('error', 'Gagal memuat detail transaksi');
                }
            }
        });
    }

    /**
     * Show transaction detail modal - Modern design
     */
    function showTransactionDetail(data) {
        // Status configuration
        var statusConfig = {
            'pending': { icon: 'fa-clock-o', color: '#f59e0b', bg: '#fef3c7', label: 'Menunggu Pembayaran' },
            'success': { icon: 'fa-check-circle', color: '#10b981', bg: '#d1fae5', label: 'Pembayaran Berhasil' },
            'settlement': { icon: 'fa-check-circle', color: '#10b981', bg: '#d1fae5', label: 'Pembayaran Berhasil' },
            'failed': { icon: 'fa-times-circle', color: '#ef4444', bg: '#fee2e2', label: 'Pembayaran Gagal' },
            'expired': { icon: 'fa-clock-o', color: '#6b7280', bg: '#f3f4f6', label: 'Kadaluarsa' },
            'cancel': { icon: 'fa-ban', color: '#6b7280', bg: '#f3f4f6', label: 'Dibatalkan' }
        };
        
        var status = statusConfig[data.status] || statusConfig['pending'];
        var feeAmount = parseFloat(data.fee_amount) || 0;
        var amount = parseFloat(data.amount) || 0;
        var totalAmount = parseFloat(data.total_amount) || (amount + feeAmount);
        
        var html = '<div class="trx-detail-container">' +
            // Status Header
            '<div class="trx-status-header" style="background: ' + status.bg + '; border-left: 4px solid ' + status.color + ';">' +
                '<div class="trx-status-icon" style="color: ' + status.color + ';"><i class="fa ' + status.icon + ' fa-2x"></i></div>' +
                '<div class="trx-status-info">' +
                    '<div class="trx-status-label" style="color: ' + status.color + ';">' + status.label + '</div>' +
                    '<div class="trx-id">' + data.transaction_id + '</div>' +
                '</div>' +
            '</div>' +
            
            // Main Content
            '<div class="trx-detail-body">' +
                // Order Info Section
                '<div class="trx-section">' +
                    '<div class="trx-section-title"><i class="fa fa-shopping-cart"></i> Informasi Pesanan</div>' +
                    '<div class="trx-row">' +
                        '<span class="trx-label">Order ID</span>' +
                        '<span class="trx-value">' + data.order_id + '</span>' +
                    '</div>' +
                    '<div class="trx-row">' +
                        '<span class="trx-label">Metode Pembayaran</span>' +
                        '<span class="trx-value"><i class="fa fa-credit-card"></i> ' + data.payment_method + '</span>' +
                    '</div>' +
                    '<div class="trx-row">' +
                        '<span class="trx-label">Kategori</span>' +
                        '<span class="trx-value">' + data.payment_category + '</span>' +
                    '</div>' +
                '</div>' +
                
                // Payment Details Section
                '<div class="trx-section">' +
                    '<div class="trx-section-title"><i class="fa fa-calculator"></i> Rincian Pembayaran</div>' +
                    '<div class="trx-row">' +
                        '<span class="trx-label">Subtotal</span>' +
                        '<span class="trx-value">' + formatCurrency(amount) + '</span>' +
                    '</div>' +
                    (feeAmount > 0 ? 
                    '<div class="trx-row">' +
                        '<span class="trx-label">Biaya Admin</span>' +
                        '<span class="trx-value">' + formatCurrency(feeAmount) + '</span>' +
                    '</div>' : '') +
                    '<div class="trx-row trx-total">' +
                        '<span class="trx-label">Total Pembayaran</span>' +
                        '<span class="trx-value">' + formatCurrency(totalAmount) + '</span>' +
                    '</div>' +
                '</div>' +
                
                // Timestamp Section
                '<div class="trx-section trx-timestamps">' +
                    '<div class="trx-time-item">' +
                        '<i class="fa fa-calendar-plus-o"></i>' +
                        '<div>' +
                            '<small>Dibuat</small>' +
                            '<span>' + data.created_at + '</span>' +
                        '</div>' +
                    '</div>' +
                    (data.expired_at ? 
                    '<div class="trx-time-item">' +
                        '<i class="fa fa-calendar-times-o"></i>' +
                        '<div>' +
                            '<small>Kedaluwarsa</small>' +
                            '<span>' + data.expired_at + '</span>' +
                        '</div>' +
                    '</div>' : '') +
                    (data.paid_at ? 
                    '<div class="trx-time-item">' +
                        '<i class="fa fa-calendar-check-o"></i>' +
                        '<div>' +
                            '<small>Dibayar</small>' +
                            '<span>' + data.paid_at + '</span>' +
                        '</div>' +
                    '</div>' : '') +
                '</div>' +
                
                // Print Button Section
                '<div class="trx-section trx-actions">' +
                    '<button type="button" class="btn-print-invoice" onclick="PaymentGateway.printInvoice(\'' + data.transaction_id + '\')">' +
                        '<i class="fa fa-print"></i> Cetak Invoice' +
                    '</button>' +
                    '<button type="button" class="btn-download-invoice" onclick="PaymentGateway.downloadInvoice(\'' + data.transaction_id + '\')">' +
                        '<i class="fa fa-download"></i> Download PDF' +
                    '</button>' +
                '</div>' +
            '</div>' +
        '</div>';

        // Show in SweetAlert modal
        Swal.fire({
            html: html,
            width: 480,
            padding: 0,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'trx-detail-popup',
                closeButton: 'trx-close-btn'
            }
        });
    }

    /**
     * Reset payment state
     */
    function resetPaymentState() {
        stopPaymentPolling();
        
        // Stop countdown timer
        if (state.countdownTimer) {
            clearInterval(state.countdownTimer);
        }
        
        // Don't destroy DataTable - keep it for smoother reopening
        // Just preserve the historyDataTable reference
        var existingDataTable = state.historyDataTable;
        
        state = {
            selectedMethod: null,
            selectedCategory: null,
            orderId: null,
            amount: 0,
            transactionId: null,
            pollingTimer: null,
            pollingAttempts: 0,
            historyDataTable: existingDataTable, // Preserve DataTable
            countdownTimer: null
        };

        // Reset UI
        $('.payment-option').removeClass('selected');
        $('#payment-instructions').hide();
        $('#qr-code-container').hide();
        $('#btn-process-payment').hide();
    }

    /**
     * Copy text to clipboard using modern Clipboard API with fallback
     */
    function copyToClipboard(text) {
        // Remove any spaces from the text
        text = String(text).replace(/\s/g, '');
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            // Modern Clipboard API
            navigator.clipboard.writeText(text).then(function() {
                showNotification('success', 'Berhasil disalin: ' + text);
            }).catch(function(err) {
                console.error('Clipboard API failed:', err);
                fallbackCopyToClipboard(text);
            });
        } else {
            fallbackCopyToClipboard(text);
        }
    }

    /**
     * Fallback copy method for older browsers
     */
    function fallbackCopyToClipboard(text) {
        var textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        textArea.style.top = '0';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            var successful = document.execCommand('copy');
            if (successful) {
                showNotification('success', 'Berhasil disalin: ' + text);
            } else {
                showNotification('error', 'Gagal menyalin ke clipboard');
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            showNotification('error', 'Gagal menyalin ke clipboard');
        }
        
        document.body.removeChild(textArea);
    }

    /**
     * Format currency
     */
    function formatCurrency(amount) {
        return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
    }

    /**
     * Format VA number with spaces for readability
     */
    function formatVANumber(vaNumber) {
        if (!vaNumber) return '';
        // Format: XXXX XXXX XXXX XXXX
        return vaNumber.replace(/(.{4})/g, '$1 ').trim();
    }

    /**
     * Get wallet name
     */
    function getWalletName(method) {
        var walletNames = {
            'gopay': 'GoPay',
            'ovo': 'OVO',
            'dana': 'DANA',
            'shopeepay': 'ShopeePay',
            'linkaja': 'LinkAja'
        };
        return walletNames[method] || 'E-Wallet';
    }

    /**
     * Initialize countdown timers in the payment history table
     */
    function initializeCountdownTimers() {
        $('.countdown-timer').each(function() {
            var $el = $(this);
            var expiredAt = $el.data('expired');
            var transactionId = $el.closest('tr').find('.btn-view-payment-detail').data('transaction-id');
            
            if (!expiredAt) return;
            
            // Skip if already initialized (prevent duplicate intervals)
            if ($el.data('countdown-initialized')) return;
            $el.data('countdown-initialized', true);
            
            var countdownInterval = setInterval(function() {
                var now = new Date().getTime();
                var expiredTime = new Date(expiredAt).getTime();
                var distance = expiredTime - now;
                
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    $el.html('<span class="text-muted"><i class="fa fa-clock-o"></i> Kadaluarsa</span>');
                    
                    // Update status in database via AJAX
                    if (transactionId) {
                        markTransactionExpired(transactionId);
                    }
                    return;
                }
                
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Format as HH:mm:ss (include days in hours if > 0)
                var totalHours = (days * 24) + hours;
                var hh = String(totalHours).padStart(2, '0');
                var mm = String(minutes).padStart(2, '0');
                var ss = String(seconds).padStart(2, '0');
                var timeString = hh + ':' + mm + ':' + ss;
                
                $el.html('<span class="text-warning"><i class="fa fa-clock-o"></i> ' + timeString + '</span>');
            }, 1000);
            
            // Store interval ID so it can be cleared later
            $el.data('countdown-interval', countdownInterval);
        });
    }

    /**
     * Mark a single transaction as expired via AJAX
     * @param {string} transactionId - Transaction ID to mark as expired
     */
    function markTransactionExpired(transactionId) {
        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'mark_expired',
                transaction_id: transactionId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update the status badge in the row
                    var $row = $('[data-transaction-id="' + transactionId + '"]').closest('tr');
                    if ($row.length) {
                        // Find the status column and update it
                        var $statusCell = $row.find('td').eq(7); // Status is column 8 (index 7)
                        $statusCell.html('<span class="label label-default">Kadaluarsa</span>');
                    }
                    console.log('Transaction ' + transactionId + ' marked as expired');
                }
            },
            error: function() {
                console.log('Failed to mark transaction as expired');
            }
        });
    }

    /**
     * Start countdown timer
     */
    function startCountdown(expiredAt) {
        // Clear existing countdown first
        if (state.countdownTimer) {
            clearInterval(state.countdownTimer);
            state.countdownTimer = null;
        }

        // Validate input
        if (!expiredAt || expiredAt === 'null' || expiredAt === 'undefined') {
            console.error('startCountdown: expiredAt is empty or invalid:', expiredAt);
            $('#payment-countdown').html('<span class="text-muted">-</span>');
            return;
        }

        // Convert to string if needed
        expiredAt = String(expiredAt).trim();

        // Parse the expired date (format: dd/mm/yyyy HH:MM or yyyy-mm-dd HH:MM:SS)
        var expiredDate = null;
        
        try {
            if (expiredAt.includes('/')) {
                // Format: dd/mm/yyyy HH:MM or dd/mm/yyyy HH:MM:SS
                var parts = expiredAt.split(' ');
                if (parts.length < 2) {
                    console.error('startCountdown: Invalid date format (missing time):', expiredAt);
                    $('#payment-countdown').html('<span class="text-muted">-</span>');
                    return;
                }
                var dateParts = parts[0].split('/');
                var timeParts = parts[1].split(':');
                
                // Validate parts
                if (dateParts.length < 3 || timeParts.length < 2) {
                    console.error('startCountdown: Invalid date/time parts:', dateParts, timeParts);
                    $('#payment-countdown').html('<span class="text-muted">-</span>');
                    return;
                }

                var day = parseInt(dateParts[0], 10);
                var month = parseInt(dateParts[1], 10) - 1;
                var year = parseInt(dateParts[2], 10);
                var hour = parseInt(timeParts[0], 10);
                var minute = parseInt(timeParts[1], 10);
                var second = timeParts[2] ? parseInt(timeParts[2], 10) : 0;

                // Validate parsed values
                if (isNaN(day) || isNaN(month) || isNaN(year) || isNaN(hour) || isNaN(minute)) {
                    console.error('startCountdown: NaN values in date:', {day, month, year, hour, minute});
                    $('#payment-countdown').html('<span class="text-muted">-</span>');
                    return;
                }

                expiredDate = new Date(year, month, day, hour, minute, second);
            } else if (expiredAt.includes('-')) {
                // Format: yyyy-mm-dd HH:MM:SS (ISO-like)
                expiredDate = new Date(expiredAt.replace(' ', 'T'));
            } else {
                expiredDate = new Date(expiredAt);
            }

            // Check if date is valid
            if (!expiredDate || isNaN(expiredDate.getTime())) {
                console.error('startCountdown: Invalid date result:', expiredAt, expiredDate);
                $('#payment-countdown').html('<span class="text-muted">-</span>');
                return;
            }
        } catch (e) {
            console.error('startCountdown: Error parsing date:', e, expiredAt);
            $('#payment-countdown').html('<span class="text-muted">-</span>');
            return;
        }

        // Store the expiredDate timestamp to avoid recalculation
        var expiredTimestamp = expiredDate.getTime();

        function updateCountdown() {
            var now = Date.now();
            var distance = expiredTimestamp - now;

            if (distance <= 0) {
                clearInterval(state.countdownTimer);
                state.countdownTimer = null;
                $('#payment-countdown').html('<span class="expired-text">Waktu Habis</span>');
                
                // Mark transaction as expired via AJAX
                if (state.currentTransactionId) {
                    markTransactionExpired(state.currentTransactionId);
                }
                return;
            }

            var hours = Math.floor(distance / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Ensure no NaN values
            if (isNaN(hours) || isNaN(minutes) || isNaN(seconds)) {
                console.error('startCountdown: NaN in countdown values');
                clearInterval(state.countdownTimer);
                state.countdownTimer = null;
                $('#payment-countdown').html('<span class="text-muted">-</span>');
                return;
            }

            var html = '<div class="countdown-boxes">' +
                '<div class="countdown-box"><span class="number">' + String(hours).padStart(2, '0') + '</span><span class="unit">JAM</span></div>' +
                '<div class="countdown-separator">:</div>' +
                '<div class="countdown-box"><span class="number">' + String(minutes).padStart(2, '0') + '</span><span class="unit">MENIT</span></div>' +
                '<div class="countdown-separator">:</div>' +
                '<div class="countdown-box"><span class="number">' + String(seconds).padStart(2, '0') + '</span><span class="unit">DETIK</span></div>' +
            '</div>';

            $('#payment-countdown').html(html);
        }

        // Update immediately
        updateCountdown();
        
        // Update every second
        state.countdownTimer = setInterval(updateCountdown, 1000);
    }

    /**
     * Show loading overlay
     */
    function showLoading() {
        if (typeof $.blockUI !== 'undefined') {
            $.blockUI({
                message: '<i class="fa fa-spinner fa-spin fa-3x"></i><br>Memproses...',
                css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                }
            });
        }
    }

    /**
     * Hide loading overlay
     */
    function hideLoading() {
        if (typeof $.unblockUI !== 'undefined') {
            $.unblockUI();
        }
    }

    /**
     * Show notification using Gritter (toast)
     * @param {string} type - Notification type: 'success', 'error', 'info', 'warning'
     * @param {string} message - Notification message
     * @param {string} title - Optional title (defaults based on type)
     */
    function showNotification(type, message, title) {
        // Define notification configs
        var notificationConfig = {
            success: {
                title: title || 'Berhasil',
                image: '../image/notification/success.png',
                class_name: 'gritter-success'
            },
            error: {
                title: title || 'Error',
                image: '../image/notification/failed.png',
                class_name: 'gritter-error'
            },
            info: {
                title: title || 'Informasi',
                image: '../image/notification/info.png',
                class_name: 'gritter-info'
            },
            warning: {
                title: title || 'Perhatian',
                image: '../image/notification/warning.png',
                class_name: 'gritter-warning'
            }
        };

        var config = notificationConfig[type] || notificationConfig.info;

        // Use Gritter if available (primary)
        if (typeof $.gritter !== 'undefined') {
            $.gritter.add({
                title: config.title,
                text: message,
                time: 5000,
                image: config.image,
                class_name: config.class_name,
                before_open: function() {
                    // Limit to 3 notifications at a time
                    if ($('.gritter-item-wrapper').length >= 3) {
                        return false;
                    }
                }
            });
        }
        // Fallback to existing global notification functions
        else if (type === 'success' && typeof SuccessNotification === 'function') {
            SuccessNotification(message);
        }
        else if (type === 'error' && typeof FailedNotification === 'function') {
            FailedNotification(message);
        }
        else if (type === 'info' && typeof infoNotification === 'function') {
            infoNotification(message);
        }
        // Fallback to toastr if available
        else if (typeof toastr !== 'undefined') {
            toastr[type](message, config.title);
        }
        // Fallback to SweetAlert if available
        else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type === 'error' ? 'error' : (type === 'success' ? 'success' : 'info'),
                title: config.title,
                text: message,
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        }
        // Last resort: browser alert
        else {
            alert(config.title + ': ' + message);
        }
    }

    /**
     * Show success notification
     */
    function showSuccess(message, title) {
        showNotification('success', message, title);
    }

    /**
     * Show error notification
     */
    function showError(message, title) {
        showNotification('error', message, title);
    }

    /**
     * Show info notification
     */
    function showInfo(message, title) {
        showNotification('info', message, title);
    }

    /**
     * Show warning notification
     */
    function showWarning(message, title) {
        showNotification('warning', message, title);
    }

    /**
     * Reload payment history DataTable
     */
    function reloadPaymentHistory() {
        if (state.historyDataTable) {
            state.historyDataTable.ajax.reload(null, false);
        }
    }

    /**
     * Print invoice - opens PDF in new tab for printing
     * @param {string} transactionId - Transaction ID
     */
    function printInvoice(transactionId) {
        if (!transactionId) {
            showError('Transaction ID tidak valid', 'Error');
            return;
        }
        
        var printUrl = 'module/backend/payment/t_printpayment.php?trx_id=' + encodeURIComponent(transactionId);
        var printWindow = window.open(printUrl, '_blank');
        
        // Auto-trigger print dialog when PDF loads
        if (printWindow) {
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                }, 500);
            };
        }
        
        showInfo('Membuka invoice untuk dicetak...', 'Cetak Invoice');
    }

    /**
     * Download invoice as PDF
     * @param {string} transactionId - Transaction ID
     */
    function downloadInvoice(transactionId) {
        if (!transactionId) {
            showError('Transaction ID tidak valid', 'Error');
            return;
        }
        
        var downloadUrl = 'module/backend/payment/t_printpayment.php?trx_id=' + encodeURIComponent(transactionId) + '&download=1';
        
        // Create a temporary link and trigger download
        var link = document.createElement('a');
        link.href = downloadUrl;
        link.target = '_blank';
        link.click();
        
        showSuccess('Mengunduh invoice PDF...', 'Download');
    }

    // Initialize on document ready
    $(document).ready(function() {
        init();
    });

    // Public API
    return {
        init: init,
        openPaymentModal: openPaymentModal,
        loadPaymentHistory: loadPaymentHistory,
        reloadPaymentHistory: reloadPaymentHistory,
        viewDetail: viewDetail,
        copyToClipboard: copyToClipboard,
        checkPaymentStatus: checkPaymentStatus,
        printInvoice: printInvoice,
        downloadInvoice: downloadInvoice,
        // Notification helpers
        showSuccess: showSuccess,
        showError: showError,
        showInfo: showInfo,
        showWarning: showWarning
    };

})();

// Global function for loading payment history (called from tab)
function loadPaymentHistory() {
    PaymentGateway.loadPaymentHistory();
}