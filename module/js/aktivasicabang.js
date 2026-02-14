/**
 * Aktivasi Cabang Module
 * Handles branch activation with trial/subscription options
 */

var AktivasiCabang = (function() {
    'use strict';

    // Configuration
    var config = {
        ajaxUrl: 'module/ajax/aj_aktivasicabang.php',
        subscriptionPrice: (typeof SUBSCRIPTION_PRICE !== 'undefined') ? SUBSCRIPTION_PRICE : 499999,
        pollingInterval: 10000 // Check payment status every 10 seconds
    };

    // State
    var state = {
        selectedCabang: null,
        selectedCabangName: null,
        selectedPackage: null,
        selectedPaymentMethod: null,
        selectedPaymentCategory: null,
        selectedPaymentName: null,
        selectedPaymentFee: null,
        currentStep: 'selection', // 'selection' or 'confirmation'
        termsAccepted: false,
        paymentMethods: null, // Cache for loaded payment methods
        currentOrderId: null, // Current order ID for status polling
        pollingTimer: null // Timer for auto status check
    };

    /**
     * Initialize module
     */
    function init() {
        loadCabangList();
        loadPaymentMethods();
        bindEvents();
        console.log('Aktivasi Cabang initialized');
    }

    /**
     * Bind event handlers
     */
    function bindEvents() {
        // Package selection
        $(document).on('click', '.package-card', function() {
            selectPackage($(this));
        });

        // Payment method selection
        $(document).on('click', '#aktivasiCabangModal .payment-option', function() {
            selectPaymentMethod($(this));
        });

        // Cabang selection change
        $(document).on('change', '#selectCabang', function() {
            state.selectedCabang = $(this).val();
            state.selectedCabangName = $(this).find('option:selected').text();
            validateForm();
        });

        // Terms checkbox
        $(document).on('change', '#acceptTerms', function() {
            state.termsAccepted = $(this).is(':checked');
            validateForm();
        });

        // Toggle Terms & Conditions content
        $(document).on('click', '#toggleTerms', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#termsContentWrapper').toggleClass('show');
        });

        // Aktivasi button click
        $(document).on('click', '#btnAktivasi', function() {
            if (state.currentStep === 'selection') {
                showConfirmation();
            } else {
                processActivation();
            }
        });

        // Back/Cancel button
        $('#btnBatal').on('click', function(e) {
            if (state.currentStep === 'confirmation') {
                // Go back to selection step
                e.preventDefault();
                e.stopPropagation();
                goBackToSelection();
            } else {
                // Close modal
                $('#aktivasiCabangModal').modal('hide');
            }
        });

        // Modal shown - initialize Select2 here
        $('#aktivasiCabangModal').on('shown.bs.modal', function(e) {
            // Disable Bootstrap 5's focus enforcement which blocks Select2 search
            var modal = bootstrap.Modal.getInstance(this);
            if (modal && modal._focustrap) {
                modal._focustrap.deactivate();
            }
            initSelect2();
        });

        // Modal reset on close
        $('#aktivasiCabangModal').on('hidden.bs.modal', function() {
            resetForm();
        });
        
        // Stop polling when payment result modal is closed
        $('#paymentResultModal').on('hidden.bs.modal', function() {
            stopStatusPolling();
        });

        // Copy button in payment result
        $(document).on('click', '.btn-copy-number', function() {
            var textToCopy = $(this).data('copy');
            copyToClipboard(textToCopy);
        });

        // Go to Dashboard button
        $(document).on('click', '#btnGoToDashboard', function() {
            var cabangId = $(this).data('cabang-id');
            $('#paymentStatusModal').modal('hide');
            // Redirect to dashboard or login
            window.location.href = 'dashboard/?cabang=' + cabangId;
        });

        // Retry Payment button
        $(document).on('click', '#btnRetryPayment', function() {
            $('#paymentStatusModal').modal('hide');
            // Re-open aktivasi modal
            setTimeout(function() {
                $('#aktivasiCabangModal').modal('show');
            }, 300);
        });

        // Check status button in payment result
        $(document).on('click', '.btn-check-status', function() {
            var orderId = $(this).data('order-id');
            checkPaymentStatus(orderId);
        });
    }

    /**
     * Load cabang list from server
     */
    function loadCabangList() {
        console.log('Loading cabang list from:', config.ajaxUrl);
        
        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: { action: 'get_cabang_list' },
            dataType: 'json',
            success: function(response) {
                console.log('Cabang list response:', response);
                
                if (response.success && response.data) {
                    var $select = $('#selectCabang');
                    $select.find('option:not(:first)').remove();
                    
                    $.each(response.data, function(i, cabang) {
                        $select.append(
                            $('<option>', {
                                value: cabang.id,
                                text: cabang.name
                            })
                        );
                    });
                    
                    console.log('Loaded', response.data.length, 'cabang options');
                } else {
                    console.error('Failed to load cabang:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to load cabang list:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }

    /**
     * Load payment methods from server
     */
    function loadPaymentMethods() {
        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: { action: 'get_payment_methods' },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    state.paymentMethods = response.data;
                    renderPaymentMethods(response.data);
                } else {
                    $('#paymentLoading').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Gagal memuat metode pembayaran</span>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to load payment methods:', error);
                $('#paymentLoading').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Gagal memuat metode pembayaran</span>');
            }
        });
    }

    /**
     * Render payment methods to accordion
     */
    function renderPaymentMethods(data) {
        var $container = $('#paymentAccordion');
        $container.empty();

        var isFirst = true;
        $.each(data, function(categoryKey, category) {
            var collapseId = 'collapse' + categoryKey.replace(/_/g, '');
            var showClass = isFirst ? 'show' : '';
            var collapsedClass = isFirst ? '' : 'collapsed';

            var html = '<div class="payment-category">';
            html += '<div class="category-header ' + collapsedClass + '" data-bs-toggle="collapse" data-bs-target="#' + collapseId + '" data-bs-parent="#paymentAccordion">';
            html += '<div class="category-title">';
            html += '<i class="fas ' + category.icon + '"></i>';
            html += '<span>' + category.name + '</span>';
            html += '</div>';
            html += '<i class="fas fa-chevron-down category-arrow"></i>';
            html += '</div>';
            html += '<div class="collapse ' + showClass + '" id="' + collapseId + '" data-bs-parent="#paymentAccordion">';
            html += '<div class="payment-options-grid">';

            $.each(category.methods, function(i, method) {
                var feeInfo = '';
                if (parseFloat(method.fee_value) > 0) {
                    if (method.fee_type === 'fixed') {
                        feeInfo = ' (+' + formatCurrency(method.fee_value) + ')';
                    } else {
                        feeInfo = ' (+' + method.fee_value + '%)';
                    }
                }

                html += '<div class="payment-option" data-method="' + method.payment_code + '" data-category="' + method.payment_category + '" data-fee-type="' + method.fee_type + '" data-fee-value="' + method.fee_value + '">';
                html += '<img src="' + method.logo_url + '" alt="' + method.payment_name + '" onerror="this.src=\'dashboard/html/assets/payment/default.svg\'">';
                html += '<span>' + method.payment_name + '</span>';
                if (feeInfo) {
                    html += '<small class="fee-info">' + feeInfo + '</small>';
                }
                html += '</div>';
            });

            html += '</div>';
            html += '</div>';
            html += '</div>';

            $container.append(html);
            isFirst = false;
        });
    }

    /**
     * Initialize Select2 for cabang dropdown
     */
    function initSelect2() {
        if ($('#selectCabang').data('select2')) {
            $('#selectCabang').select2('destroy');
        }
        
        $('#selectCabang').select2({
            placeholder: '-- Ketik untuk mencari cabang --',
            allowClear: true,
            dropdownParent: $('#aktivasiCabangModal'),
            width: '100%',
            language: {
                noResults: function() {
                    return 'Cabang tidak ditemukan';
                },
                searching: function() {
                    return 'Mencari...';
                }
            }
        });
        
        // Fix: Bootstrap 5 modal focus trap blocks Select2 search input
        // This removes the enforced focus and allows Select2 to work properly
        $(document).on('select2:open', '#selectCabang', function() {
            // Find and focus the search field
            var searchField = document.querySelector('.select2-search__field');
            if (searchField) {
                searchField.focus();
                
                // Prevent Bootstrap modal from stealing focus
                $(searchField).on('blur.select2fix', function(e) {
                    var $target = $(e.relatedTarget);
                    if ($target.closest('.select2-container').length === 0) {
                        return;
                    }
                    e.stopPropagation();
                    e.preventDefault();
                    this.focus();
                });
            }
        });
        
        // Handle Select2 selection change
        $('#selectCabang').on('select2:select select2:clear', function(e) {
            state.selectedCabang = $(this).val();
            state.selectedCabangName = $(this).find('option:selected').text();
            validateForm();
        });
    }

    /**
     * Select package (trial/subscription)
     */
    function selectPackage($card) {
        // Remove previous selection
        $('.package-card').removeClass('selected');
        $('.package-card input[type="radio"]').prop('checked', false);

        // Add selection to current
        $card.addClass('selected');
        $card.find('input[type="radio"]').prop('checked', true);

        // Update state
        state.selectedPackage = $card.data('package');

        // Show/hide payment section
        if (state.selectedPackage === 'subscription') {
            $('#sectionPayment').slideDown(300);
            $('#btnAktivasiText').text('Bayar & Aktivasi');
        } else {
            $('#sectionPayment').slideUp(300);
            $('#btnAktivasiText').text('Aktivasi Trial');
            // Clear payment selection for trial
            state.selectedPaymentMethod = null;
            state.selectedPaymentCategory = null;
            $('.payment-option').removeClass('selected');
            $('#selectedPaymentInfo').hide();
        }

        validateForm();
    }

    /**
     * Select payment method
     */
    function selectPaymentMethod($option) {
        // Remove previous selection
        $('.payment-option').removeClass('selected');

        // Add selection to current
        $option.addClass('selected');

        // Update state
        state.selectedPaymentMethod = $option.data('method');
        state.selectedPaymentCategory = $option.data('category');
        state.selectedPaymentName = $option.find('span').first().text();
        state.selectedPaymentFee = {
            type: $option.data('fee-type'),
            value: parseFloat($option.data('fee-value')) || 0
        };

        // Update hidden fields
        $('#selectedPaymentMethod').val(state.selectedPaymentMethod);
        $('#selectedPaymentCategory').val(state.selectedPaymentCategory);

        // Show selected payment info
        $('#selectedPaymentName').text(state.selectedPaymentName);
        $('#selectedPaymentInfo').slideDown(200);

        validateForm();
    }

    /**
     * Validate form and enable/disable button
     */
    function validateForm() {
        var isValid = false;

        if (state.currentStep === 'confirmation') {
            // In confirmation step, just need terms accepted
            isValid = state.termsAccepted;
        } else {
            // In selection step
            if (state.selectedCabang && state.selectedPackage) {
                if (state.selectedPackage === 'trial') {
                    isValid = true;
                } else if (state.selectedPackage === 'subscription' && state.selectedPaymentMethod) {
                    isValid = true;
                }
            }
        }

        $('#btnAktivasi').prop('disabled', !isValid);
    }

    /**
     * Show confirmation step
     */
    function showConfirmation() {
        if (state.selectedPackage === 'trial') {
            // For trial, directly process
            processActivation();
            return;
        }

        // Calculate fee
        var subtotal = config.subscriptionPrice;
        var feeAmount = 0;
        
        if (state.selectedPaymentFee && state.selectedPaymentFee.value > 0) {
            if (state.selectedPaymentFee.type === 'fixed') {
                feeAmount = state.selectedPaymentFee.value;
            } else if (state.selectedPaymentFee.type === 'percentage') {
                feeAmount = Math.round(subtotal * state.selectedPaymentFee.value / 100);
            }
        }
        
        var totalAmount = subtotal + feeAmount;

        // Populate confirmation data
        $('#confirmCabangName').text(state.selectedCabangName || '-');
        $('#confirmPackageType').text('Berlangganan (1 Tahun)');
        $('#confirmPaymentMethod').text(state.selectedPaymentName || '-');
        $('#confirmSubtotal').text(formatCurrency(subtotal));
        
        // Show/hide fee row
        if (feeAmount > 0) {
            $('#confirmFee').text(formatCurrency(feeAmount));
            $('#confirmFeeRow').show();
        } else {
            $('#confirmFeeRow').hide();
        }
        
        $('#confirmTotal').text(formatCurrency(totalAmount));

        // Hide selection sections with animation
        $('#sectionPilihCabang, #sectionPilihPaket, #sectionPayment').slideUp(300);
        
        // Show confirmation section
        setTimeout(function() {
            $('#sectionKonfirmasi').slideDown(300);
        }, 300);

        // Update state
        state.currentStep = 'confirmation';
        state.termsAccepted = false;
        $('#acceptTerms').prop('checked', false);

        // Update buttons
        $('#btnBatalText').text('Kembali');
        $('#btnBatal i').removeClass('fa-times').addClass('fa-arrow-left');
        $('#btnBatal').removeClass('btn-cancel').addClass('btn-back');
        $('#btnAktivasiText').text('Proses Pembayaran');
        $('#btnAktivasi i').removeClass('fa-check').addClass('fa-credit-card');
        $('#btnAktivasi').prop('disabled', true);
    }

    /**
     * Go back to selection step
     */
    function goBackToSelection() {
        // Hide confirmation
        $('#sectionKonfirmasi').slideUp(300);

        // Show selection sections
        setTimeout(function() {
            $('#sectionPilihCabang, #sectionPilihPaket').slideDown(300);
            if (state.selectedPackage === 'subscription') {
                $('#sectionPayment').slideDown(300);
            }
        }, 300);

        // Update state
        state.currentStep = 'selection';

        // Update buttons
        $('#btnBatalText').text('Batal');
        $('#btnBatal i').removeClass('fa-arrow-left').addClass('fa-times');
        $('#btnBatal').removeClass('btn-back').addClass('btn-cancel');
        $('#btnAktivasiText').text(state.selectedPackage === 'subscription' ? 'Bayar & Aktivasi' : 'Aktivasi Trial');
        $('#btnAktivasi i').removeClass('fa-credit-card').addClass('fa-check');

        validateForm();
    }

    /**
     * Process activation
     */
    function processActivation() {
        if (!state.selectedCabang || !state.selectedPackage) {
            showNotification('error', 'Silakan lengkapi semua data');
            return;
        }

        if (state.selectedPackage === 'subscription' && !state.selectedPaymentMethod) {
            showNotification('error', 'Silakan pilih metode pembayaran');
            return;
        }

        // Show loading
        showLoading();

        // Get CSRF token
        var csrfToken = $('#csrfToken').val() || '';

        var data = {
            action: 'create_activation',
            csrf_token: csrfToken,
            cabang_id: state.selectedCabang,
            package_type: state.selectedPackage,
            payment_method: state.selectedPaymentMethod || '',
            payment_category: state.selectedPaymentCategory || '',
            amount: state.selectedPackage === 'subscription' ? config.subscriptionPrice : 0
        };

        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    if (state.selectedPackage === 'trial') {
                        // Trial activation success
                        $('#aktivasiCabangModal').modal('hide');
                        showNotification('success', 'Trial berhasil diaktifkan! Silakan login ke dashboard.');
                    } else {
                        // Show payment details
                        showPaymentResult(response.data);
                    }
                } else {
                    showNotification('error', response.message || 'Terjadi kesalahan');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                var errorMsg = 'Gagal memproses aktivasi';
                if (xhr.status === 403) {
                    errorMsg = 'Sesi keamanan tidak valid. Silakan refresh halaman.';
                } else if (xhr.status === 429) {
                    errorMsg = 'Terlalu banyak permintaan. Silakan tunggu sebentar.';
                }
                showNotification('error', errorMsg);
            }
        });
    }

    /**
     * Show payment result modal
     */
    function showPaymentResult(data) {
        var html = '';

        // Transaction info
        html += '<div class="payment-result-section">';
        html += '<div class="result-icon success"><i class="fas fa-check-circle"></i></div>';
        html += '<h5 class="result-title">Transaksi Berhasil Dibuat</h5>';
        html += '<p class="result-transaction-id">' + data.transaction_id + '</p>';
        html += '</div>';

        // Payment details based on category
        if (data.payment_category === 'transfer_bank') {
            html += '<div class="payment-detail-card">';
            html += '<div class="detail-header"><i class="fas fa-university"></i> Informasi Rekening</div>';
            html += '<div class="detail-body">';
            html += '<div class="bank-name">' + data.bank_name + '</div>';
            html += '<div class="account-number-display">';
            html += '<span class="number">' + data.account_number + '</span>';
            html += '<button type="button" class="btn-copy-number" data-copy="' + data.account_number + '"><i class="fas fa-copy"></i></button>';
            html += '</div>';
            html += '<div class="account-name">a.n. ' + data.account_name + '</div>';
            html += '</div>';
            html += '</div>';
        } else if (data.payment_category === 'virtual_account') {
            html += '<div class="payment-detail-card">';
            html += '<div class="detail-header"><i class="fas fa-barcode"></i> Virtual Account</div>';
            html += '<div class="detail-body">';
            html += '<div class="bank-name">' + data.bank_name + '</div>';
            html += '<div class="va-number-display">';
            html += '<span class="number">' + formatVANumber(data.va_number) + '</span>';
            html += '<button type="button" class="btn-copy-number" data-copy="' + data.va_number + '"><i class="fas fa-copy"></i></button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        } else if (data.payment_category === 'qris' && data.qr_code_url) {
            html += '<div class="payment-detail-card text-center">';
            html += '<div class="detail-header"><i class="fas fa-qrcode"></i> Scan QR Code</div>';
            html += '<div class="detail-body">';
            html += '<img src="' + data.qr_code_url + '" alt="QRIS" class="qr-code-img">';
            html += '<p class="qr-instruction">Scan QR Code dengan aplikasi e-wallet Anda</p>';
            html += '</div>';
            html += '</div>';
        } else if (data.payment_category === 'ewallet') {
            html += '<div class="payment-detail-card text-center">';
            html += '<div class="detail-header"><i class="fas fa-wallet"></i> ' + (data.ewallet_name || 'E-Wallet') + '</div>';
            html += '<div class="detail-body">';
            html += '<p class="ewallet-instruction">Klik tombol di bawah untuk melanjutkan pembayaran</p>';
            html += '<a href="' + data.redirect_url + '" target="_blank" class="btn btn-ewallet-pay">';
            html += '<i class="fas fa-external-link-alt"></i> Bayar dengan ' + (data.ewallet_name || 'E-Wallet');
            html += '</a>';
            html += '</div>';
            html += '</div>';
        }

        // Amount info
        html += '<div class="payment-amount-card">';
        html += '<div class="amount-row"><span>Subtotal</span><span>' + formatCurrency(data.amount) + '</span></div>';
        if (data.fee_amount > 0) {
            html += '<div class="amount-row"><span>Biaya Admin</span><span>' + formatCurrency(data.fee_amount) + '</span></div>';
        }
        html += '<div class="amount-row total"><span>Total</span><span>' + formatCurrency(data.total_amount) + '</span></div>';
        html += '</div>';

        // Expiry info
        if (data.expired_at) {
            html += '<div class="payment-expiry-info">';
            html += '<i class="fas fa-clock"></i> Selesaikan pembayaran sebelum <strong>' + data.expired_at + '</strong>';
            html += '</div>';
        }

        // Check status button
        if (data.order_id) {
            html += '<div class="text-center">';
            html += '<button type="button" class="btn-check-status" data-order-id="' + data.order_id + '">';
            html += '<i class="fas fa-sync-alt"></i> Cek Status Pembayaran';
            html += '</button>';
            html += '<p class="auto-check-info"><i class="fas fa-info-circle"></i> Status akan diperbarui otomatis setiap 10 detik</p>';
            html += '</div>';
            
            // Store order ID and start auto-polling
            state.currentOrderId = data.order_id;
            startStatusPolling(data.order_id);
        }

        $('#paymentResultContent').html(html);
        $('#aktivasiCabangModal').modal('hide');
        $('#paymentResultModal').modal('show');
    }

    /**
     * Reset form
     */
    function resetForm() {
        state.selectedCabang = null;
        state.selectedCabangName = null;
        state.selectedPackage = null;
        state.selectedPaymentMethod = null;
        state.selectedPaymentCategory = null;
        state.selectedPaymentName = null;
        state.selectedPaymentFee = null;
        state.currentStep = 'selection';
        state.termsAccepted = false;

        $('#formAktivasiCabang')[0].reset();
        
        // Reset Select2
        if ($('#selectCabang').data('select2')) {
            $('#selectCabang').val(null).trigger('change');
        }
        
        $('.package-card').removeClass('selected');
        $('.payment-option').removeClass('selected');
        $('#sectionPayment').hide();
        $('#sectionKonfirmasi').hide();
        $('#selectedPaymentInfo').hide();
        $('#acceptTerms').prop('checked', false);
        
        // Reset buttons to initial state
        $('#btnBatalText').text('Batal');
        $('#btnBatal i').removeClass('fa-arrow-left').addClass('fa-times');
        $('#btnBatal').removeClass('btn-back').addClass('btn-cancel');
        $('#btnAktivasiText').text('Aktivasi');
        $('#btnAktivasi i').removeClass('fa-credit-card').addClass('fa-check');
        $('#btnAktivasi').prop('disabled', true);
        
        // Show selection sections
        $('#sectionPilihCabang, #sectionPilihPaket').show();
    }

    /**
     * Format currency
     */
    function formatCurrency(amount) {
        return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
    }

    /**
     * Format VA number with spaces
     */
    function formatVANumber(vaNumber) {
        return vaNumber.replace(/(.{4})/g, '$1 ').trim();
    }

    /**
     * Copy to clipboard
     */
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showNotification('success', 'Berhasil disalin!');
            }).catch(function() {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    /**
     * Fallback copy method
     */
    function fallbackCopy(text) {
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val(text).select();
        document.execCommand('copy');
        $temp.remove();
        showNotification('success', 'Berhasil disalin!');
    }

    /**
     * Show loading overlay
     */
    function showLoading() {
        if ($('#aktivasi-loading-overlay').length === 0) {
            $('body').append(
                '<div id="aktivasi-loading-overlay">' +
                '<div class="loading-content">' +
                '<div class="loading-spinner"></div>' +
                '<p>Memproses...</p>' +
                '</div>' +
                '</div>'
            );
        }
        $('#aktivasi-loading-overlay').fadeIn(200);
    }

    /**
     * Hide loading overlay
     */
    function hideLoading() {
        $('#aktivasi-loading-overlay').fadeOut(200);
    }

    /**
     * Show notification
     */
    function showNotification(type, message) {
        // Create toast notification
        var iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        var bgClass = type === 'success' ? 'bg-success' : 'bg-danger';

        var $toast = $(
            '<div class="aktivasi-toast ' + bgClass + '">' +
            '<i class="fas ' + iconClass + '"></i>' +
            '<span>' + message + '</span>' +
            '</div>'
        );

        $('body').append($toast);
        
        setTimeout(function() {
            $toast.addClass('show');
        }, 100);

        setTimeout(function() {
            $toast.removeClass('show');
            setTimeout(function() {
                $toast.remove();
            }, 300);
        }, 3000);
    }

    /**
     * Show payment status modal (success/failed)
     * @param {string} status - 'success' or 'failed'
     * @param {object} data - Payment data
     */
    function showPaymentStatus(status, data) {
        var isSuccess = status === 'success';
        var html = '';

        // Update modal header
        if (isSuccess) {
            $('#paymentStatusIcon').removeClass('failed').addClass('success')
                .html('<i class="fas fa-check-circle"></i>');
            $('#paymentStatusTitle').text('Pembayaran Berhasil');
            $('#paymentStatusSubtitle').text('Terima kasih atas pembayaran Anda');
            $('#paymentStatusHeader').removeClass('status-failed').addClass('status-success');
        } else {
            $('#paymentStatusIcon').removeClass('success').addClass('failed')
                .html('<i class="fas fa-times-circle"></i>');
            $('#paymentStatusTitle').text('Pembayaran Gagal');
            $('#paymentStatusSubtitle').text('Silakan coba kembali');
            $('#paymentStatusHeader').removeClass('status-success').addClass('status-failed');
        }

        // Build content
        html += '<div class="payment-status-section">';
        
        if (isSuccess) {
            // Success content
            html += '<div class="status-icon-large success">';
            html += '<i class="fas fa-check"></i>';
            html += '</div>';
            html += '<h4 class="status-title success">Pembayaran Dikonfirmasi</h4>';
            html += '<p class="status-message">Aktivasi cabang Anda telah berhasil diproses.</p>';
            
            // Transaction details
            html += '<div class="status-detail-card">';
            html += '<div class="detail-row"><span class="label">No. Transaksi</span><span class="value">' + (data.transaction_id || '-') + '</span></div>';
            html += '<div class="detail-row"><span class="label">Cabang</span><span class="value">' + (data.cabang_name || '-') + '</span></div>';
            html += '<div class="detail-row"><span class="label">Paket</span><span class="value">' + (data.package_type || 'Berlangganan 1 Tahun') + '</span></div>';
            html += '<div class="detail-row"><span class="label">Metode Pembayaran</span><span class="value">' + (data.payment_method || '-') + '</span></div>';
            html += '<div class="detail-row"><span class="label">Total Dibayar</span><span class="value text-success fw-bold">' + formatCurrency(data.total_amount || 0) + '</span></div>';
            html += '<div class="detail-row"><span class="label">Tanggal Pembayaran</span><span class="value">' + (data.paid_at || '-') + '</span></div>';
            html += '<div class="detail-row"><span class="label">Berlaku Hingga</span><span class="value">' + (data.valid_until || '-') + '</span></div>';
            html += '</div>';

            // Success info
            html += '<div class="status-info success">';
            html += '<i class="fas fa-info-circle"></i>';
            html += '<span>Cabang Anda sudah aktif dan siap digunakan. Silakan masuk ke dashboard untuk memulai.</span>';
            html += '</div>';

            // Show dashboard button
            $('#btnGoToDashboard').show().data('cabang-id', data.cabang_id);
            $('#btnRetryPayment').hide();
        } else {
            // Failed content
            html += '<div class="status-icon-large failed">';
            html += '<i class="fas fa-times"></i>';
            html += '</div>';
            html += '<h4 class="status-title failed">Pembayaran Tidak Berhasil</h4>';
            html += '<p class="status-message">' + (data.error_message || 'Terjadi kesalahan saat memproses pembayaran Anda.') + '</p>';
            
            // Transaction details
            if (data.transaction_id) {
                html += '<div class="status-detail-card">';
                html += '<div class="detail-row"><span class="label">No. Transaksi</span><span class="value">' + data.transaction_id + '</span></div>';
                html += '<div class="detail-row"><span class="label">Status</span><span class="value text-danger"><i class="fas fa-times-circle"></i> ' + (data.status_text || 'Gagal') + '</span></div>';
                if (data.failed_at) {
                    html += '<div class="detail-row"><span class="label">Waktu</span><span class="value">' + data.failed_at + '</span></div>';
                }
                if (data.error_code) {
                    html += '<div class="detail-row"><span class="label">Kode Error</span><span class="value"><code>' + data.error_code + '</code></span></div>';
                }
                html += '</div>';
            }

            // Failed info with possible reasons
            html += '<div class="status-info failed">';
            html += '<i class="fas fa-exclamation-triangle"></i>';
            html += '<div class="info-content">';
            html += '<strong>Kemungkinan penyebab:</strong>';
            html += '<ul>';
            html += '<li>Saldo tidak mencukupi</li>';
            html += '<li>Koneksi terputus saat proses</li>';
            html += '<li>Batas waktu pembayaran habis</li>';
            html += '<li>Transaksi dibatalkan</li>';
            html += '</ul>';
            html += '</div>';
            html += '</div>';

            // Show retry button
            $('#btnRetryPayment').show().data('order-id', data.order_id);
            $('#btnGoToDashboard').hide();
        }

        html += '</div>';

        $('#paymentStatusContent').html(html);
        
        // Close payment result modal if open
        $('#paymentResultModal').modal('hide');
        
        // Show status modal
        setTimeout(function() {
            $('#paymentStatusModal').modal('show');
        }, 300);
    }

    /**
     * Start auto-polling for payment status
     * @param {string} orderId - Order ID to check
     */
    function startStatusPolling(orderId) {
        // Clear any existing timer
        stopStatusPolling();
        
        // Start new polling timer
        state.pollingTimer = setInterval(function() {
            checkPaymentStatusSilent(orderId);
        }, config.pollingInterval);
        
        console.log('Started payment status polling for order: ' + orderId);
    }
    
    /**
     * Stop auto-polling
     */
    function stopStatusPolling() {
        if (state.pollingTimer) {
            clearInterval(state.pollingTimer);
            state.pollingTimer = null;
            console.log('Stopped payment status polling');
        }
    }
    
    /**
     * Check payment status silently (for auto-polling, no loading indicator)
     * @param {string} orderId - Order ID to check
     */
    function checkPaymentStatusSilent(orderId) {
        var csrfToken = $('#csrfToken').val() || '';
        
        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: { 
                action: 'check_payment_status',
                csrf_token: csrfToken,
                order_id: orderId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var status = response.data.status;
                    
                    if (status === 'paid' || status === 'success' || status === 'settlement') {
                        // Payment successful - stop polling and show success
                        stopStatusPolling();
                        $('#paymentResultModal').modal('hide');
                        showPaymentStatus('success', response.data);
                    } else if (status === 'failed' || status === 'expired' || status === 'cancelled' || status === 'deny') {
                        // Payment failed - stop polling and show failure
                        stopStatusPolling();
                        $('#paymentResultModal').modal('hide');
                        showPaymentStatus('failed', response.data);
                    }
                    // For pending status, continue polling (do nothing)
                }
            },
            error: function(xhr, status, error) {
                console.log('Silent status check failed: ' + error);
                // Continue polling on error
            }
        });
    }

    /**
     * Check payment status from server (manual check with loading)
     * @param {string} orderId - Order ID to check
     */
    function checkPaymentStatus(orderId) {
        showLoading();
        
        var csrfToken = $('#csrfToken').val() || '';

        $.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: { 
                action: 'check_payment_status',
                csrf_token: csrfToken,
                order_id: orderId
            },
            dataType: 'json',
            success: function(response) {
                hideLoading();
                
                if (response.success) {
                    var status = response.data.status;
                    
                    if (status === 'paid' || status === 'success' || status === 'settlement') {
                        showPaymentStatus('success', response.data);
                    } else if (status === 'failed' || status === 'expired' || status === 'cancelled' || status === 'deny') {
                        showPaymentStatus('failed', response.data);
                    } else {
                        // Pending status
                        showNotification('info', 'Pembayaran masih dalam proses. Status: ' + status);
                    }
                } else {
                    showNotification('error', response.message || 'Gagal mengecek status pembayaran');
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                showNotification('error', 'Gagal mengecek status: ' + error);
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        init();
    });

    // Public API
    return {
        init: init,
        loadCabangList: loadCabangList,
        showPaymentStatus: showPaymentStatus,
        checkPaymentStatus: checkPaymentStatus
    };

})();
