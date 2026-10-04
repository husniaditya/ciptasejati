/**
 * Pembayaran (Payment Methods) Management JavaScript
 * Handles DataTable initialization, CRUD operations for m_payment
 */

// DataTable instance
var paymentTable;

/**
 * Initialize Payment DataTable with server-side processing
 */
function callPaymentTable() {
    paymentTable = $('#payment-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'module/ajax/admin/pembayaran/aj_tablepembayaran_ssp.php',
            type: 'POST',
            data: function(d) {
                // Add custom filters
                d.PAYMENT_CODE = $('#filter_payment_code').val() || '';
                d.PAYMENT_NAME = $('#filter_payment_name').val() || '';
                d.PAYMENT_CATEGORY = $('#filter_payment_category').val() || '';
                d.IS_ACTIVE = $('#filter_is_active').val() || '';
            }
        },
        columns: [
            { data: 0, orderable: false, className: 'text-center' }, // Action
            { data: 1 }, // Kode
            { data: 2 }, // Nama
            { data: 3 }, // Kategori
            { data: 4 }, // Biaya
            { data: 5 }, // No. Rekening
            { data: 6 }, // Atas Nama
            { data: 7, className: 'text-center' }, // Status
            { data: 8, className: 'text-center' }  // Urutan
        ],
        order: [[8, 'asc']], // Default sort by sort_order
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        language: {
            processing: "Memproses...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            loadingRecords: "Memuat...",
            zeroRecords: "Tidak ada data yang ditemukan",
            emptyTable: "Tidak ada data tersedia",
            paginate: {
                first: "Pertama",
                previous: "Sebelumnya",
                next: "Selanjutnya",
                last: "Terakhir"
            }
        },
        dom: 'Bfrtlip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ],
        scrollX: true,
        scrollY: '450px'
    });
}

/**
 * Reload DataTable
 */
function reloadPaymentTable() {
    if (paymentTable) {
        paymentTable.ajax.reload(null, false);
    }
}

/**
 * Handle Add Payment Form
 */
function handleAddPaymentForm() {
    $('#AddPayment-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        formData.append('savepayment', 'save');

        $.ajax({
            type: 'POST',
            url: 'module/backend/admin/pembayaran/t_pembayaran.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'Success') {
                    UpdateNotification('Metode pembayaran berhasil ditambahkan!');
                    $('#AddPayment').modal('hide');
                    $('#AddPayment-form')[0].reset();
                    reloadPaymentTable();
                } else {
                    FailedNotification(response);
                }
            },
            error: function(xhr, status, error) {
                FailedNotification('Terjadi kesalahan: ' + error);
            }
        });
    });
}

/**
 * Handle Edit Payment Form
 */
function handleEditPaymentForm() {
    $('#EditPayment-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        formData.append('updatepayment', 'update');

        $.ajax({
            type: 'POST',
            url: 'module/backend/admin/pembayaran/t_pembayaran.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'Success') {
                    UpdateNotification('Metode pembayaran berhasil diperbarui!');
                    $('#EditPayment').modal('hide');
                    reloadPaymentTable();
                } else {
                    FailedNotification(response);
                }
            },
            error: function(xhr, status, error) {
                FailedNotification('Terjadi kesalahan: ' + error);
            }
        });
    });
}

/**
 * Load payment data for editing
 */
function loadPaymentData(paymentId) {
    $.ajax({
        url: 'module/backend/admin/pembayaran/t_pembayaran.php',
        method: 'POST',
        data: { getpayment: 'get', id: paymentId },
        dataType: 'json',
        success: function(data) {
            if (data && !data.error) {
                $('#edit_id').val(data.id);
                $('#edit_payment_code').val(data.payment_code);
                $('#edit_payment_name').val(data.payment_name);
                $('#edit_payment_category').val(data.payment_category);
                $('#edit_logo_url').val(data.logo_url || '');
                $('#edit_fee_type').val(data.fee_type);
                $('#edit_fee_value').val(data.fee_value);
                $('#edit_expiry_hours').val(data.expiry_hours);
                $('#edit_account_number').val(data.account_number);
                $('#edit_account_name').val(data.account_name);
                $('#edit_bank_code').val(data.bank_code);
                $('#edit_min_amount').val(data.min_amount);
                $('#edit_max_amount').val(data.max_amount);
                $('#edit_sort_order').val(data.sort_order);
                $('#edit_payment_description').val(data.payment_description);
                $('#edit_gateway_code').val(data.gateway_code);
                $('#edit_is_active').val(data.is_active);
                
                // Show existing logo preview
                if (data.logo_url) {
                    $('#edit_logo_preview_img').attr('src', data.logo_url);
                    $('#edit_logo_preview').show();
                } else {
                    $('#edit_logo_preview').hide();
                }
                
                // Clear file input
                $('#edit_logo_file').val('');
            } else {
                FailedNotification(data.error || 'Gagal memuat data');
            }
        },
        error: function(error) {
            console.error('Error fetching data:', error);
            FailedNotification('Gagal memuat data');
        }
    });
}

/**
 * Delete payment method
 */
function deletePayment(paymentId) {
    swal({
        title: "Konfirmasi Hapus",
        text: "Apakah anda yakin untuk menghapus metode pembayaran ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        closeOnConfirm: false
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'POST',
                url: 'module/backend/admin/pembayaran/t_pembayaran.php',
                data: {
                    id: paymentId,
                    EVENT_ACTION: 'delete'
                },
                success: function(response) {
                    if (response === 'Success') {
                        swal("Terhapus!", "Metode pembayaran berhasil dihapus.", "success");
                        reloadPaymentTable();
                    } else {
                        swal("Gagal!", response, "error");
                    }
                },
                error: function(xhr, status, error) {
                    swal("Error!", "Terjadi kesalahan: " + error, "error");
                }
            });
        }
    });
}

/**
 * Toggle payment status
 */
function togglePaymentStatus(paymentId, currentStatus) {
    var newStatus = currentStatus == 1 ? 0 : 1;
    var statusText = newStatus == 1 ? 'mengaktifkan' : 'menonaktifkan';

    swal({
        title: "Konfirmasi",
        text: "Apakah anda yakin untuk " + statusText + " metode pembayaran ini?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        closeOnConfirm: false
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'POST',
                url: 'module/backend/admin/pembayaran/t_pembayaran.php',
                data: {
                    togglestatus: 'toggle',
                    id: paymentId,
                    status: newStatus
                },
                success: function(response) {
                    if (response === 'Success') {
                        swal("Berhasil!", "Status berhasil diperbarui.", "success");
                        reloadPaymentTable();
                    } else {
                        swal("Gagal!", response, "error");
                    }
                },
                error: function(xhr, status, error) {
                    swal("Error!", "Terjadi kesalahan: " + error, "error");
                }
            });
        }
    });
}

/**
 * Apply filters
 */
function applyPaymentFilters() {
    reloadPaymentTable();
}

/**
 * Reset filters
 */
function resetPaymentFilters() {
    $('#filter_payment_code').val('');
    $('#filter_payment_name').val('');
    $('#filter_payment_category').val('');
    $('#filter_is_active').val('');
    reloadPaymentTable();
}

/**
 * Clear Add form when modal is opened
 */
function clearAddPaymentForm() {
    $('#AddPayment-form')[0].reset();
    $('#payment_code').val('');
    $('#payment_name').val('');
    $('#payment_category').val('');
    $('#fee_type').val('fixed');
    $('#fee_value').val('0');
    $('#expiry_hours').val('24');
    $('#min_amount').val('0');
    $('#max_amount').val('999999999');
    $('#sort_order').val('0');
    $('#is_active').val('1');
    // Clear logo preview
    $('#logo_file').val('');
    $('#logo_preview').hide();
    $('#logo_preview_img').attr('src', '');
}

/**
 * Preview logo before upload
 * @param {HTMLInputElement} input - File input element
 * @param {string} mode - 'add' or 'edit'
 */
function previewLogo(input, mode) {
    var previewContainer = mode === 'add' ? '#logo_preview' : '#edit_logo_preview';
    var previewImg = mode === 'add' ? '#logo_preview_img' : '#edit_logo_preview_img';
    
    if (input.files && input.files[0]) {
        var file = input.files[0];
        
        // Validate file type
        var allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
        if (!allowedTypes.includes(file.type)) {
            FailedNotification('Format file tidak didukung. Gunakan PNG, JPG, GIF, WEBP, atau SVG.');
            input.value = '';
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            FailedNotification('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            return;
        }
        
        var reader = new FileReader();
        reader.onload = function(e) {
            $(previewImg).attr('src', e.target.result);
            $(previewContainer).show();
        };
        reader.readAsDataURL(file);
    }
}

/**
 * Clear logo preview
 * @param {string} mode - 'add' or 'edit'
 */
function clearLogoPreview(mode) {
    if (mode === 'add') {
        $('#logo_file').val('');
        $('#logo_preview').hide();
        $('#logo_preview_img').attr('src', '');
    } else {
        $('#edit_logo_file').val('');
        $('#edit_logo_url').val(''); // Clear hidden URL to remove logo on save
        $('#edit_logo_preview').hide();
        $('#edit_logo_preview_img').attr('src', '');
    }
}

// Document Ready
$(document).ready(function() {
    // Initialize DataTable
    callPaymentTable();

    // Handle form submissions
    handleAddPaymentForm();
    handleEditPaymentForm();

    // Open Edit Modal - Load data
    $(document).on('click', '.open-EditPayment', function() {
        var paymentId = $(this).data('id');
        loadPaymentData(paymentId);
    });

    // Clear form when Add modal is opened
    $(document).on('click', '.open-AddPayment', function() {
        clearAddPaymentForm();
    });

    // Filter button click
    $(document).on('click', '#btn-filter-payment', function() {
        applyPaymentFilters();
    });

    // Reset filter button click
    $(document).on('click', '#btn-reset-filter', function() {
        resetPaymentFilters();
    });

    // Enter key in filter inputs
    $(document).on('keypress', '.filter-input', function(e) {
        if (e.which === 13) {
            applyPaymentFilters();
        }
    });
    
    // Logo file change handlers for preview
    $(document).on('change', '#logo_file', function() {
        previewLogo(this, 'add');
    });
    
    $(document).on('change', '#edit_logo_file', function() {
        previewLogo(this, 'edit');
    });
});
