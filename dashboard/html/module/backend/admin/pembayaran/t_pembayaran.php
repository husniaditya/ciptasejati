<?php
/**
 * Pembayaran (Payment Methods) Backend Handler
 * Handles CRUD operations for m_payment table
 */

require_once ("../../../../module/connection/conn.php");

$USER_ID = $_SESSION["LOGINIDUS_CS"];

// Upload directory for payment logos
define('PAYMENT_LOGO_DIR', $_SERVER['DOCUMENT_ROOT'] . '/dashboard/html/assets/payment/');
define('PAYMENT_LOGO_URL', 'assets/payment/');

/**
 * Handle logo upload and convert to SVG
 * @param array $file - $_FILES array element
 * @param string $paymentCode - Payment code for filename
 * @return string|null - Logo URL or null on failure
 */
function handleLogoUpload($file, $paymentCode) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK || empty($file['tmp_name'])) {
        return null;
    }

    // Create directory if not exists
    if (!is_dir(PAYMENT_LOGO_DIR)) {
        mkdir(PAYMENT_LOGO_DIR, 0755, true);
    }

    // Get file info
    $tmpName = $file['tmp_name'];
    $originalName = $file['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    // Generate filename
    $filename = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $paymentCode)) . '.svg';
    $filepath = PAYMENT_LOGO_DIR . $filename;

    // Check if already SVG
    if ($extension === 'svg') {
        // Validate SVG content
        $svgContent = file_get_contents($tmpName);
        if (strpos($svgContent, '<svg') !== false) {
            // Clean and save SVG
            file_put_contents($filepath, $svgContent);
            return PAYMENT_LOGO_URL . $filename;
        }
        return null;
    }

    // For raster images (PNG, JPG, GIF, WEBP), convert to SVG with embedded image
    $allowedTypes = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
    if (!in_array($extension, $allowedTypes)) {
        return null;
    }

    // Get image info
    $imageInfo = getimagesize($tmpName);
    if ($imageInfo === false) {
        return null;
    }

    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $mimeType = $imageInfo['mime'];

    // Read and encode image to base64
    $imageData = file_get_contents($tmpName);
    $base64 = base64_encode($imageData);

    // Create SVG with embedded image
    $svg = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $svg .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" ';
    $svg .= 'width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">' . "\n";
    $svg .= '  <image width="' . $width . '" height="' . $height . '" ';
    $svg .= 'xlink:href="data:' . $mimeType . ';base64,' . $base64 . '"/>' . "\n";
    $svg .= '</svg>';

    // Save SVG file
    if (file_put_contents($filepath, $svg)) {
        return PAYMENT_LOGO_URL . $filename;
    }

    return null;
}

/**
 * Delete old logo file
 * @param string $logoUrl - Logo URL to delete
 */
function deleteOldLogo($logoUrl) {
    if (empty($logoUrl)) return;
    
    // Only delete if it's in our payment folder
    if (strpos($logoUrl, PAYMENT_LOGO_URL) === 0) {
        $filename = str_replace(PAYMENT_LOGO_URL, '', $logoUrl);
        $filepath = PAYMENT_LOGO_DIR . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
}

/**
 * Save new payment method
 */
if (isset($_POST["savepayment"])) {
    try {
        // Required fields
        $PAYMENT_CODE = trim($_POST["payment_code"] ?? '');
        $PAYMENT_NAME = trim($_POST["payment_name"] ?? '');
        $PAYMENT_CATEGORY = trim($_POST["payment_category"] ?? '');
        $FEE_TYPE = trim($_POST["fee_type"] ?? 'fixed');
        $FEE_VALUE = floatval($_POST["fee_value"] ?? 0);

        // Validate required fields
        if (empty($PAYMENT_CODE) || empty($PAYMENT_NAME) || empty($PAYMENT_CATEGORY)) {
            echo "Kode, Nama, dan Kategori wajib diisi";
            exit;
        }

        // Check if payment_code already exists
        $checkResult = GetQuery("SELECT id FROM m_payment WHERE payment_code = '$PAYMENT_CODE'");
        if ($checkResult->fetch()) {
            echo "Kode pembayaran sudah digunakan";
            exit;
        }

        // Handle logo upload
        $LOGO_URL = '';
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $uploadedLogo = handleLogoUpload($_FILES['logo_file'], $PAYMENT_CODE);
            if ($uploadedLogo) {
                $LOGO_URL = $uploadedLogo;
            }
        }

        // Optional fields
        $PAYMENT_DESCRIPTION = trim($_POST["payment_description"] ?? '');
        $MIN_AMOUNT = floatval($_POST["min_amount"] ?? 0);
        $MAX_AMOUNT = floatval($_POST["max_amount"] ?? 999999999);
        $EXPIRY_HOURS = intval($_POST["expiry_hours"] ?? 24);
        $ACCOUNT_NUMBER = trim($_POST["account_number"] ?? '');
        $ACCOUNT_NAME = trim($_POST["account_name"] ?? '');
        $BANK_CODE = trim($_POST["bank_code"] ?? '');
        $GATEWAY_CODE = trim($_POST["gateway_code"] ?? '');
        $SORT_ORDER = intval($_POST["sort_order"] ?? 0);
        $IS_ACTIVE = intval($_POST["is_active"] ?? 1);

        // Handle null values
        $PAYMENT_DESCRIPTION = empty($PAYMENT_DESCRIPTION) ? "NULL" : "'" . addslashes($PAYMENT_DESCRIPTION) . "'";
        $LOGO_URL_SQL = empty($LOGO_URL) ? "NULL" : "'" . addslashes($LOGO_URL) . "'";
        $ACCOUNT_NUMBER = empty($ACCOUNT_NUMBER) ? "NULL" : "'" . addslashes($ACCOUNT_NUMBER) . "'";
        $ACCOUNT_NAME = empty($ACCOUNT_NAME) ? "NULL" : "'" . addslashes($ACCOUNT_NAME) . "'";
        $BANK_CODE = empty($BANK_CODE) ? "NULL" : "'" . addslashes($BANK_CODE) . "'";
        $GATEWAY_CODE = empty($GATEWAY_CODE) ? "NULL" : "'" . addslashes($GATEWAY_CODE) . "'";

        $sql = "INSERT INTO m_payment (
            payment_code, payment_name, payment_category, payment_description,
            logo_url, fee_type, fee_value, min_amount, max_amount, expiry_hours,
            account_number, account_name, bank_code, gateway_code,
            sort_order, is_active, created_at, created_by
        ) VALUES (
            '$PAYMENT_CODE', '$PAYMENT_NAME', '$PAYMENT_CATEGORY', $PAYMENT_DESCRIPTION,
            $LOGO_URL_SQL, '$FEE_TYPE', $FEE_VALUE, $MIN_AMOUNT, $MAX_AMOUNT, $EXPIRY_HOURS,
            $ACCOUNT_NUMBER, $ACCOUNT_NAME, $BANK_CODE, $GATEWAY_CODE,
            $SORT_ORDER, $IS_ACTIVE, NOW(), '$USER_ID'
        )";

        GetQuery($sql);
        echo "Success";

    } catch (Exception $e) {
        echo "Caught Exception: " . $e->getMessage();
    }
}

/**
 * Update existing payment method
 */
if (isset($_POST["updatepayment"])) {
    try {
        $ID = intval($_POST["id"] ?? 0);
        
        if ($ID <= 0) {
            echo "ID tidak valid";
            exit;
        }

        // Required fields
        $PAYMENT_CODE = trim($_POST["payment_code"] ?? '');
        $PAYMENT_NAME = trim($_POST["payment_name"] ?? '');
        $PAYMENT_CATEGORY = trim($_POST["payment_category"] ?? '');
        $FEE_TYPE = trim($_POST["fee_type"] ?? 'fixed');
        $FEE_VALUE = floatval($_POST["fee_value"] ?? 0);

        // Validate required fields
        if (empty($PAYMENT_CODE) || empty($PAYMENT_NAME) || empty($PAYMENT_CATEGORY)) {
            echo "Kode, Nama, dan Kategori wajib diisi";
            exit;
        }

        // Check if payment_code already exists for different record
        $checkResult = GetQuery("SELECT id FROM m_payment WHERE payment_code = '$PAYMENT_CODE' AND id != $ID");
        if ($checkResult->fetch()) {
            echo "Kode pembayaran sudah digunakan";
            exit;
        }

        // Get current logo URL
        $currentResult = GetQuery("SELECT logo_url FROM m_payment WHERE id = $ID");
        $currentData = $currentResult->fetch(PDO::FETCH_ASSOC);
        $CURRENT_LOGO = $currentData['logo_url'] ?? '';

        // Handle logo upload
        $LOGO_URL = trim($_POST["logo_url"] ?? ''); // Keep existing if no new upload
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $uploadedLogo = handleLogoUpload($_FILES['logo_file'], $PAYMENT_CODE);
            if ($uploadedLogo) {
                // Delete old logo if different
                if (!empty($CURRENT_LOGO) && $CURRENT_LOGO !== $uploadedLogo) {
                    deleteOldLogo($CURRENT_LOGO);
                }
                $LOGO_URL = $uploadedLogo;
            }
        } elseif (empty($LOGO_URL)) {
            // Keep current logo if no new upload and no URL provided
            $LOGO_URL = $CURRENT_LOGO;
        }

        // Optional fields
        $PAYMENT_DESCRIPTION = trim($_POST["payment_description"] ?? '');
        $MIN_AMOUNT = floatval($_POST["min_amount"] ?? 0);
        $MAX_AMOUNT = floatval($_POST["max_amount"] ?? 999999999);
        $EXPIRY_HOURS = intval($_POST["expiry_hours"] ?? 24);
        $ACCOUNT_NUMBER = trim($_POST["account_number"] ?? '');
        $ACCOUNT_NAME = trim($_POST["account_name"] ?? '');
        $BANK_CODE = trim($_POST["bank_code"] ?? '');
        $GATEWAY_CODE = trim($_POST["gateway_code"] ?? '');
        $SORT_ORDER = intval($_POST["sort_order"] ?? 0);
        $IS_ACTIVE = intval($_POST["is_active"] ?? 1);

        // Handle null values
        $PAYMENT_DESCRIPTION = empty($PAYMENT_DESCRIPTION) ? "NULL" : "'" . addslashes($PAYMENT_DESCRIPTION) . "'";
        $LOGO_URL_SQL = empty($LOGO_URL) ? "NULL" : "'" . addslashes($LOGO_URL) . "'";
        $ACCOUNT_NUMBER = empty($ACCOUNT_NUMBER) ? "NULL" : "'" . addslashes($ACCOUNT_NUMBER) . "'";
        $ACCOUNT_NAME = empty($ACCOUNT_NAME) ? "NULL" : "'" . addslashes($ACCOUNT_NAME) . "'";
        $BANK_CODE = empty($BANK_CODE) ? "NULL" : "'" . addslashes($BANK_CODE) . "'";
        $GATEWAY_CODE = empty($GATEWAY_CODE) ? "NULL" : "'" . addslashes($GATEWAY_CODE) . "'";

        $sql = "UPDATE m_payment SET
            payment_code = '$PAYMENT_CODE',
            payment_name = '$PAYMENT_NAME',
            payment_category = '$PAYMENT_CATEGORY',
            payment_description = $PAYMENT_DESCRIPTION,
            logo_url = $LOGO_URL_SQL,
            fee_type = '$FEE_TYPE',
            fee_value = $FEE_VALUE,
            min_amount = $MIN_AMOUNT,
            max_amount = $MAX_AMOUNT,
            expiry_hours = $EXPIRY_HOURS,
            account_number = $ACCOUNT_NUMBER,
            account_name = $ACCOUNT_NAME,
            bank_code = $BANK_CODE,
            gateway_code = $GATEWAY_CODE,
            sort_order = $SORT_ORDER,
            is_active = $IS_ACTIVE,
            updated_at = NOW(),
            updated_by = '$USER_ID'
        WHERE id = $ID";

        GetQuery($sql);
        echo "Success";

    } catch (Exception $e) {
        echo "Caught Exception: " . $e->getMessage();
    }
}

/**
 * Delete payment method (soft or hard delete)
 */
if (isset($_POST["EVENT_ACTION"])) {
    try {
        $ID = intval($_POST["id"] ?? 0);

        if ($ID <= 0) {
            echo "ID tidak valid";
            exit;
        }

        // Check if payment is being used in transactions
        $checkResult = GetQuery("SELECT COUNT(*) as count FROM t_payment WHERE payment_id = $ID");
        $row = $checkResult->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            echo "Metode pembayaran tidak dapat dihapus karena sudah digunakan dalam transaksi. Silakan nonaktifkan saja.";
            exit;
        }

        // Hard delete since not used in any transaction
        GetQuery("DELETE FROM m_payment WHERE id = $ID");
        echo "Success";

    } catch (Exception $e) {
        echo "Caught Exception: " . $e->getMessage();
    }
}

/**
 * Get single payment data for edit
 */
if (isset($_POST["getpayment"])) {
    try {
        $ID = intval($_POST["id"] ?? 0);

        if ($ID <= 0) {
            echo json_encode(['error' => 'ID tidak valid']);
            exit;
        }

        $result = GetQuery("SELECT * FROM m_payment WHERE id = $ID LIMIT 1");
        $payment = $result->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            header('Content-Type: application/json');
            echo json_encode($payment);
        } else {
            echo json_encode(['error' => 'Data tidak ditemukan']);
        }

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

/**
 * Toggle payment status (active/inactive)
 */
if (isset($_POST["togglestatus"])) {
    try {
        $ID = intval($_POST["id"] ?? 0);
        $STATUS = intval($_POST["status"] ?? 0);

        if ($ID <= 0) {
            echo "ID tidak valid";
            exit;
        }

        GetQuery("UPDATE m_payment SET is_active = $STATUS, updated_at = NOW(), updated_by = '$USER_ID' WHERE id = $ID");
        echo "Success";

    } catch (Exception $e) {
        echo "Caught Exception: " . $e->getMessage();
    }
}
?>
