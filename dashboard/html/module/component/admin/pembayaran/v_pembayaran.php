<?php
$USER_ID = $_SESSION["LOGINIDUS_CS"];

// Category options for dropdown
$categoryOptions = [
    'transfer_bank' => 'Transfer Bank',
    'virtual_account' => 'Virtual Account',
    'ewallet' => 'E-Wallet',
    'qris' => 'QRIS',
    'credit_card' => 'Credit Card',
    'other' => 'Lainnya'
];

// Fee type options
$feeTypeOptions = [
    'fixed' => 'Fixed (Nominal)',
    'percentage' => 'Percentage (%)'
];
?>

<!-- START row -->
<div class="row">
    <div class="col-lg-12">
        <a data-toggle="modal" title="Add this item" class="open-AddPayment btn btn-inverse btn-outline mb5 btn-rounded" href="#AddPayment"><i class="ico-plus2"></i> Tambah Metode Pembayaran</a>
    </div>
</div>
<br>
<!--/ END row -->

<!-- START row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default" id="demo">
            <div class="panel-heading">
                <h3 class="panel-title">Tabel Metode Pembayaran</h3>
            </div>
            <table class="table table-striped table-bordered" id="payment-table">
                <thead>
                    <tr>
                        <th width="100"></th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Biaya</th>
                        <th>No. Rekening</th>
                        <th>Atas Nama</th>
                        <th>Status</th>
                        <th>Urutan</th>
                    </tr>
                </thead>
                <tbody id="paymentdata">
                    <!-- Data loaded via server-side DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>
<!--/ END row -->

<!-- Add Payment Modal -->
<div id="AddPayment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="AddPayment-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Tambah Metode Pembayaran</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_code">Kode Pembayaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="payment_code" name="payment_code" placeholder="contoh: bank_bca, va_bni, gopay" data-parsley-required>
                                <small class="text-muted">Gunakan huruf kecil dan underscore</small>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_name">Nama Pembayaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="payment_name" name="payment_name" placeholder="contoh: Bank BCA, VA BNI, GoPay" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_category">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control" required id="payment_category" name="payment_category" data-parsley-required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categoryOptions as $key => $label): ?>
                                    <option value="<?= $key; ?>"><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logo_file">Upload Logo</label>
                                <input type="file" class="form-control" id="logo_file" name="logo_file" accept="image/*,.svg">
                                <small class="text-muted">Format: PNG, JPG, SVG (akan dikonversi ke SVG)</small>
                                <div id="logo_preview" class="mt-2" style="display:none;">
                                    <img id="logo_preview_img" src="" alt="Preview" style="max-height: 50px; margin-top: 10px;">
                                    <button type="button" class="btn btn-xs btn-danger" onclick="clearLogoPreview('add')"><i class="fa fa-times"></i></button>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fee_type">Tipe Biaya <span class="text-danger">*</span></label>
                                <select class="form-control" required id="fee_type" name="fee_type" data-parsley-required>
                                    <?php foreach ($feeTypeOptions as $key => $label): ?>
                                    <option value="<?= $key; ?>"><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fee_value">Nilai Biaya <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" required id="fee_value" name="fee_value" value="0" data-parsley-required>
                                <small class="text-muted">Nominal atau persentase</small>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expiry_hours">Masa Berlaku (Jam)</label>
                                <input type="number" class="form-control" id="expiry_hours" name="expiry_hours" value="24">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="account_number">Nomor Rekening</label>
                                <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Untuk transfer bank">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="account_name">Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Atas nama">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bank_code">Kode Bank</label>
                                <input type="text" class="form-control" id="bank_code" name="bank_code" placeholder="Untuk VA, contoh: 70012">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="min_amount">Minimal Transaksi</label>
                                <input type="number" class="form-control" id="min_amount" name="min_amount" value="0">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="max_amount">Maksimal Transaksi</label>
                                <input type="number" class="form-control" id="max_amount" name="max_amount" value="999999999">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sort_order">Urutan Tampil</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="payment_description">Deskripsi / Instruksi</label>
                                <textarea class="form-control" id="payment_description" name="payment_description" rows="3" placeholder="Instruksi pembayaran untuk ditampilkan ke user"></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gateway_code">Kode Gateway</label>
                                <input type="text" class="form-control" id="gateway_code" name="gateway_code" placeholder="Kode dari payment gateway">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="savePayment" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Edit Payment Modal -->
<div id="EditPayment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="EditPayment-form" method="post" class="form" data-parsley-validate>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="semibold modal-title text-inverse">Ubah Metode Pembayaran</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_payment_code">Kode Pembayaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="edit_payment_code" name="payment_code" data-parsley-required>
                                <small class="text-muted">Gunakan huruf kecil dan underscore</small>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_payment_name">Nama Pembayaran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required id="edit_payment_name" name="payment_name" data-parsley-required>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_payment_category">Kategori <span class="text-danger">*</span></label>
                                <select class="form-control" required id="edit_payment_category" name="payment_category" data-parsley-required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categoryOptions as $key => $label): ?>
                                    <option value="<?= $key; ?>"><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_logo_file">Upload Logo</label>
                                <input type="file" class="form-control" id="edit_logo_file" name="logo_file" accept="image/*,.svg">
                                <small class="text-muted">Format: PNG, JPG, SVG (akan dikonversi ke SVG)</small>
                                <input type="hidden" id="edit_logo_url" name="logo_url" value="">
                                <div id="edit_logo_preview" class="mt-2" style="display:none;">
                                    <img id="edit_logo_preview_img" src="" alt="Preview" style="max-height: 50px; margin-top: 10px;">
                                    <button type="button" class="btn btn-xs btn-danger" onclick="clearLogoPreview('edit')"><i class="fa fa-times"></i></button>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_fee_type">Tipe Biaya <span class="text-danger">*</span></label>
                                <select class="form-control" required id="edit_fee_type" name="fee_type" data-parsley-required>
                                    <?php foreach ($feeTypeOptions as $key => $label): ?>
                                    <option value="<?= $key; ?>"><?= $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_fee_value">Nilai Biaya <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" required id="edit_fee_value" name="fee_value" data-parsley-required>
                                <small class="text-muted">Nominal atau persentase</small>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_expiry_hours">Masa Berlaku (Jam)</label>
                                <input type="number" class="form-control" id="edit_expiry_hours" name="expiry_hours">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_account_number">Nomor Rekening</label>
                                <input type="text" class="form-control" id="edit_account_number" name="account_number">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_account_name">Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" id="edit_account_name" name="account_name">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_bank_code">Kode Bank</label>
                                <input type="text" class="form-control" id="edit_bank_code" name="bank_code">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_min_amount">Minimal Transaksi</label>
                                <input type="number" class="form-control" id="edit_min_amount" name="min_amount">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_max_amount">Maksimal Transaksi</label>
                                <input type="number" class="form-control" id="edit_max_amount" name="max_amount">
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_sort_order">Urutan Tampil</label>
                                <input type="number" class="form-control" id="edit_sort_order" name="sort_order">
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_payment_description">Deskripsi / Instruksi</label>
                                <textarea class="form-control" id="edit_payment_description" name="payment_description" rows="3"></textarea>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_gateway_code">Kode Gateway</label>
                                <input type="text" class="form-control" id="edit_gateway_code" name="gateway_code">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_is_active">Status</label>
                                <select class="form-control" id="edit_is_active" name="is_active">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-outline mb5 btn-rounded" data-dismiss="modal"><span class="ico-cancel"></span> Tutup</button>
                    <button type="submit" name="submit" id="updatePayment" class="submit btn btn-primary btn-outline mb5 btn-rounded"><span class="ico-save"></span> Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Include Pembayaran JS -->
<script src="module/javascript/component/admin/pembayaran/pembayaran.js"></script>
