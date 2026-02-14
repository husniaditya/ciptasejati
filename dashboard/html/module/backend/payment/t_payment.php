<?php
/**
 * Payment Gateway - Database Transaction Handler
 * 
 * This file contains:
 * 1. Functions to insert successful transactions
 * 2. Helper functions for payment processing
 */

// Include database connection
require_once __DIR__ . '/../../connection/conn.php';

class PaymentTransaction {

    private $db;
    private $table = 't_payment';
    private $logTable = 't_payment_logs';
    private $masterTable = 'm_payment';

    /**
     * Constructor
     */
    public function __construct() {
        global $db1;
        $this->db = $db1;
    }

    /**
     * Generate unique transaction ID
     * @return string
     */
    public function generateTransactionId() {
        return 'TRX' . date('YmdHis') . rand(1000, 9999);
    }

    /**
     * Get payment method by code
     * 
     * @param string $paymentCode Payment method code
     * @return array|null
     */
    public function getPaymentMethod($paymentCode) {
        $sql = "SELECT * FROM {$this->masterTable} WHERE payment_code = :payment_code AND is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':payment_code' => $paymentCode]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all active payment methods
     * 
     * @param string|null $category Filter by category
     * @return array
     */
    public function getActivePaymentMethods($category = null) {
        $sql = "SELECT * FROM {$this->masterTable} WHERE is_active = 1";
        $params = [];
        
        if ($category) {
            $sql .= " AND payment_category = :category";
            $params[':category'] = $category;
        }
        
        $sql .= " ORDER BY sort_order ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment methods grouped by category
     * 
     * @return array
     */
    public function getPaymentMethodsByCategory() {
        $methods = $this->getActivePaymentMethods();
        $grouped = [];
        
        foreach ($methods as $method) {
            $category = $method['payment_category'];
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $method;
        }
        
        return $grouped;
    }

    /**
     * Create new payment transaction
     * 
     * @param array $data Transaction data
     * @return array Result with success status and transaction ID
     */
    public function createTransaction($data) {
        try {
            $transactionId = $this->generateTransactionId();
            
            // Get payment method from master table
            $paymentMethod = $this->getPaymentMethod($data['payment_method']);
            
            if (!$paymentMethod) {
                return [
                    'success' => false,
                    'message' => 'Payment method not found or inactive'
                ];
            }
            
            // Calculate total amount with fee from master table
            $amount = floatval($data['amount']);
            $feeAmount = $this->calculateFeeFromMaster($amount, $paymentMethod);
            $totalAmount = $amount + $feeAmount;
            
            // Set expiry time from master table
            $expiryHours = intval($paymentMethod['expiry_hours'] ?? 24);
            
            // Get expired_at value directly from MySQL to avoid any timezone issues
            $stmtExpiry = $this->db->query("SELECT NOW() as created_at, DATE_ADD(NOW(), INTERVAL {$expiryHours} HOUR) as expired_at");
            $times = $stmtExpiry->fetch(PDO::FETCH_ASSOC);
            $createdAt = $times['created_at'];
            $expiredAt = $times['expired_at'];
            
            $sql = "INSERT INTO {$this->table} (
                        transaction_id,
                        order_id,
                        user_id,
                        cabang_key,
                        payment_id,
                        amount,
                        fee_amount,
                        total_amount,
                        status,
                        payment_data,
                        expired_at,
                        ip_address,
                        user_agent,
                        created_at,
                        created_by
                    ) VALUES (
                        :transaction_id,
                        :order_id,
                        :user_id,
                        :cabang_key,
                        :payment_id,
                        :amount,
                        :fee_amount,
                        :total_amount,
                        'pending',
                        :payment_data,
                        :expired_at,
                        :ip_address,
                        :user_agent,
                        :created_at,
                        :created_by
                    )";

            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':transaction_id' => $transactionId,
                ':order_id' => $data['order_id'],
                ':user_id' => $data['user_id'] ?? null,
                ':cabang_key' => $_SESSION["LOGINCAB_CS"] ?? null,
                ':payment_id' => $paymentMethod['id'],
                ':amount' => $amount,
                ':fee_amount' => $feeAmount,
                ':total_amount' => $totalAmount,
                ':payment_data' => json_encode($data['payment_data'] ?? []),
                ':expired_at' => $expiredAt,
                ':ip_address' => $this->getClientIp(),
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                ':created_at' => $createdAt,
                ':created_by' => $data['user_id'] ?? null
            ]);

            $insertedId = $this->db->lastInsertId();

            // Log the transaction creation
            $this->logAction($insertedId, $transactionId, 'create', null, 'pending', $data);

            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'payment_id' => $paymentMethod['id'],
                'payment_name' => $paymentMethod['payment_name'],
                'payment_category' => $paymentMethod['payment_category'],
                'amount' => $amount,
                'fee_amount' => $feeAmount,
                'total_amount' => $totalAmount,
                'expired_at' => $expiredAt,
                'created_at' => $createdAt
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to create transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Calculate fee from master payment method
     * 
     * @param float $amount Base amount
     * @param array $paymentMethod Payment method data from m_payment
     * @return float Fee amount
     */
    private function calculateFeeFromMaster($amount, $paymentMethod) {
        $feeType = $paymentMethod['fee_type'];
        $feeValue = floatval($paymentMethod['fee_value']);
        
        if ($feeType === 'fixed') {
            return $feeValue;
        } else {
            return round($amount * ($feeValue / 100), 2);
        }
    }

    /**
     * Update transaction status for successful payment
     * 
     * @param string $transactionId Transaction ID
     * @param array $callbackData Callback data from payment gateway
     * @return array Result
     */
    public function insertSuccessfulTransaction($transactionId, $callbackData = []) {
        try {
            // Get current transaction
            $transaction = $this->getTransaction($transactionId);
            
            if (!$transaction) {
                return ['success' => false, 'message' => 'Transaction not found'];
            }

            $oldStatus = $transaction['status'];
            $newStatus = 'success';

            // Update transaction status
            $sql = "UPDATE {$this->table} SET
                        status = :status,
                        callback_data = :callback_data,
                        gateway_transaction_id = :gateway_transaction_id,
                        gateway_reference = :gateway_reference,
                        paid_at = NOW(),
                        updated_at = NOW(),
                        updated_by = :updated_by
                    WHERE transaction_id = :transaction_id";

            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':status' => $newStatus,
                ':callback_data' => json_encode($callbackData),
                ':gateway_transaction_id' => $callbackData['gateway_transaction_id'] ?? null,
                ':gateway_reference' => $callbackData['gateway_reference'] ?? null,
                ':updated_by' => $transaction['user_id'],
                ':transaction_id' => $transactionId
            ]);

            // Log the status change
            $this->logAction($transaction['id'], $transactionId, 'status_change', $oldStatus, $newStatus, $callbackData);

            // Update related order
            $this->updateOrderStatus($transaction['order_id'], $transaction['amount']);

            return [
                'success' => true,
                'message' => 'Transaction updated successfully',
                'transaction_id' => $transactionId,
                'status' => $newStatus
            ];

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Failed to update transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mark transaction as failed
     * 
     * @param string $transactionId Transaction ID
     * @param string $reason Failure reason
     * @return array Result
     */
    public function markTransactionFailed($transactionId, $reason = '') {
        try {
            $transaction = $this->getTransaction($transactionId);
            
            if (!$transaction) {
                return ['success' => false, 'message' => 'Transaction not found'];
            }

            $oldStatus = $transaction['status'];

            $sql = "UPDATE {$this->table} SET
                        status = 'failed',
                        notes = :notes,
                        updated_at = NOW()
                    WHERE transaction_id = :transaction_id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':notes' => $reason,
                ':transaction_id' => $transactionId
            ]);

            $this->logAction($transaction['id'], $transactionId, 'status_change', $oldStatus, 'failed', ['reason' => $reason]);

            return ['success' => true, 'message' => 'Transaction marked as failed'];

        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Mark expired transactions
     * This should be run periodically via cron job
     * 
     * @return int Number of transactions marked as expired
     */
    public function markExpiredTransactions() {
        try {
            $sql = "UPDATE {$this->table} SET
                        status = 'expired',
                        updated_at = NOW()
                    WHERE status = 'pending'
                    AND expired_at < NOW()";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->rowCount();

        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Get transaction by ID
     * 
     * @param string $transactionId Transaction ID
     * @return array|null
     */
    public function getTransaction($transactionId) {
        $sql = "SELECT t.*, m.payment_code, m.payment_name, m.payment_category 
                FROM {$this->table} t
                INNER JOIN {$this->masterTable} m ON t.payment_id = m.id
                WHERE t.transaction_id = :transaction_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':transaction_id' => $transactionId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get transactions by order ID
     * 
     * @param string $orderId Order ID
     * @return array
     */
    public function getTransactionsByOrder($orderId) {
        $sql = "SELECT t.*, m.payment_code, m.payment_name, m.payment_category 
                FROM {$this->table} t
                INNER JOIN {$this->masterTable} m ON t.payment_id = m.id
                WHERE t.order_id = :order_id 
                ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get payment history for a user
     * 
     * @param int $userId User ID
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getPaymentHistory($userId, $limit = 10, $offset = 0) {
        $sql = "SELECT t.*, m.payment_code, m.payment_name, m.payment_category 
                FROM {$this->table} t
                INNER JOIN {$this->masterTable} m ON t.payment_id = m.id
                WHERE t.user_id = :user_id 
                ORDER BY t.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calculate transaction fee based on payment method
     * @deprecated Use calculateFeeFromMaster instead
     * 
     * @param float $amount Base amount
     * @param string $method Payment method code
     * @return float Fee amount
     */
    private function calculateFee($amount, $method) {
        $paymentMethod = $this->getPaymentMethod($method);
        
        if ($paymentMethod) {
            return $this->calculateFeeFromMaster($amount, $paymentMethod);
        }
        
        return 0;
    }

    /**
     * Update order status after successful payment
     * 
     * @param string $orderId Order ID
     * @param float $paidAmount Amount paid
     */
    private function updateOrderStatus($orderId, $paidAmount) {
        try {
            // Update your orders table here
            // This is a sample - adjust according to your actual orders table structure
            $sql = "UPDATE orders SET 
                        payment_status = 'paid',
                        paid_amount = paid_amount + :paid_amount,
                        paid_at = NOW(),
                        updated_at = NOW()
                    WHERE order_id = :order_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':paid_amount' => $paidAmount,
                ':order_id' => $orderId
            ]);

        } catch (PDOException $e) {
            // Log error but don't throw - payment is already successful
            error_log('Failed to update order status: ' . $e->getMessage());
        }
    }

    /**
     * Log payment action for audit trail
     * 
     * @param int $paymentTransactionId Payment transaction ID (t_payment.id)
     * @param string $transactionId Transaction ID
     * @param string $action Action performed
     * @param string|null $oldStatus Old status
     * @param string|null $newStatus New status
     * @param array $data Additional data
     */
    private function logAction($paymentTransactionId, $transactionId, $action, $oldStatus, $newStatus, $data = []) {
        try {
            $sql = "INSERT INTO {$this->logTable} (
                        payment_transaction_id,
                        transaction_id,
                        cabang_key,
                        action,
                        old_status,
                        new_status,
                        request_data,
                        ip_address,
                        user_agent,
                        created_at
                    ) VALUES (
                        :payment_transaction_id,
                        :transaction_id,
                        :cabang_key,
                        :action,
                        :old_status,
                        :new_status,
                        :request_data,
                        :ip_address,
                        :user_agent,
                        NOW()
                    )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':payment_transaction_id' => $paymentTransactionId,
                ':transaction_id' => $transactionId,
                ':cabang_key' => $_SESSION["LOGINCAB_CS"] ?? null,
                ':action' => $action,
                ':old_status' => $oldStatus,
                ':new_status' => $newStatus,
                ':request_data' => json_encode($data),
                ':ip_address' => $this->getClientIp(),
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);

        } catch (PDOException $e) {
            // Log error but don't throw
            error_log('Failed to log payment action: ' . $e->getMessage());
        }
    }

    /**
     * Get client IP address
     * 
     * @return string
     */
    private function getClientIp() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                   'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                return trim($ip);
            }
        }
        
        return '0.0.0.0';
    }

    /**
     * Get payment statistics
     * 
     * @param string $startDate Start date (Y-m-d)
     * @param string $endDate End date (Y-m-d)
     * @return array
     */
    public function getPaymentStatistics($startDate, $endDate) {
        $sql = "SELECT 
                    m.payment_category,
                    m.payment_code,
                    m.payment_name,
                    t.status,
                    COUNT(*) as transaction_count,
                    SUM(t.amount) as total_amount,
                    SUM(t.fee_amount) as total_fee
                FROM {$this->table} t
                INNER JOIN {$this->masterTable} m ON t.payment_id = m.id
                WHERE DATE(t.created_at) BETWEEN :start_date AND :end_date
                GROUP BY m.payment_category, m.payment_code, m.payment_name, t.status
                ORDER BY m.payment_category, m.payment_code, t.status";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment method in master table
     * 
     * @param int $id Payment method ID
     * @param array $data Update data
     * @return array Result
     */
    public function updatePaymentMethod($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = [
                'payment_name', 'payment_description', 'logo_url',
                'fee_type', 'fee_value', 'min_amount', 'max_amount',
                'expiry_hours', 'account_number', 'account_name',
                'bank_code', 'gateway_code', 'sort_order', 'is_active'
            ];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return ['success' => false, 'message' => 'No fields to update'];
            }
            
            $params[':updated_by'] = $data['updated_by'] ?? null;
            
            $sql = "UPDATE {$this->masterTable} SET " . implode(', ', $fields) . ", updated_by = :updated_by, updated_at = NOW() WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true, 'message' => 'Payment method updated successfully'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Add new payment method to master table
     * 
     * @param array $data Payment method data
     * @return array Result
     */
    public function addPaymentMethod($data) {
        try {
            $sql = "INSERT INTO {$this->masterTable} (
                        payment_code,
                        payment_name,
                        payment_category,
                        payment_description,
                        logo_url,
                        fee_type,
                        fee_value,
                        min_amount,
                        max_amount,
                        expiry_hours,
                        account_number,
                        account_name,
                        bank_code,
                        gateway_code,
                        sort_order,
                        is_active,
                        created_at,
                        created_by
                    ) VALUES (
                        :payment_code,
                        :payment_name,
                        :payment_category,
                        :payment_description,
                        :logo_url,
                        :fee_type,
                        :fee_value,
                        :min_amount,
                        :max_amount,
                        :expiry_hours,
                        :account_number,
                        :account_name,
                        :bank_code,
                        :gateway_code,
                        :sort_order,
                        :is_active,
                        NOW(),
                        :created_by
                    )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':payment_code' => $data['payment_code'],
                ':payment_name' => $data['payment_name'],
                ':payment_category' => $data['payment_category'],
                ':payment_description' => $data['payment_description'] ?? null,
                ':logo_url' => $data['logo_url'] ?? null,
                ':fee_type' => $data['fee_type'] ?? 'fixed',
                ':fee_value' => $data['fee_value'] ?? 0,
                ':min_amount' => $data['min_amount'] ?? 0,
                ':max_amount' => $data['max_amount'] ?? 999999999,
                ':expiry_hours' => $data['expiry_hours'] ?? 24,
                ':account_number' => $data['account_number'] ?? null,
                ':account_name' => $data['account_name'] ?? null,
                ':bank_code' => $data['bank_code'] ?? null,
                ':gateway_code' => $data['gateway_code'] ?? null,
                ':sort_order' => $data['sort_order'] ?? 0,
                ':is_active' => $data['is_active'] ?? 1,
                ':created_by' => $data['created_by'] ?? null
            ]);
            
            return [
                'success' => true,
                'message' => 'Payment method added successfully',
                'id' => $this->db->lastInsertId()
            ];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

// ============================================================================
// USAGE EXAMPLES
// ============================================================================

/*
// Example 1: Get available payment methods grouped by category
$payment = new PaymentTransaction();
$methods = $payment->getPaymentMethodsByCategory();

foreach ($methods as $category => $categoryMethods) {
    echo "Category: {$category}\n";
    foreach ($categoryMethods as $method) {
        echo "  - {$method['payment_name']} ({$method['payment_code']})\n";
    }
}

// Example 2: Create a new transaction (now uses payment_id from m_payment)
$payment = new PaymentTransaction();

$result = $payment->createTransaction([
    'order_id' => 'ORD-2024-001',
    'user_id' => 123,
    'amount' => 500000,
    'payment_method' => 'va_bca', // This will lookup m_payment by payment_code
    'payment_data' => []
]);

if ($result['success']) {
    echo "Transaction created: " . $result['transaction_id'];
    echo "Payment Method: " . $result['payment_name'];
    echo "Fee: " . $result['fee_amount'];
}

// Example 3: Mark transaction as successful (called from payment gateway callback)
$payment = new PaymentTransaction();

$result = $payment->insertSuccessfulTransaction('TRX20241215123456789', [
    'gateway_transaction_id' => 'GW-12345',
    'gateway_reference' => 'REF-67890',
    'paid_amount' => 500000,
    'paid_at' => '2024-12-15 10:30:00'
]);

if ($result['success']) {
    echo "Payment successful!";
}

// Example 4: Get payment history with payment method details
$payment = new PaymentTransaction();
$history = $payment->getPaymentHistory(123, 10, 0);

foreach ($history as $transaction) {
    echo $transaction['transaction_id'] . ' - ' . $transaction['payment_name'] . ' - ' . $transaction['status'] . "\n";
}

// Example 5: Add new payment method to master table
$payment = new PaymentTransaction();
$result = $payment->addPaymentMethod([
    'payment_code' => 'bank_bsi',
    'payment_name' => 'Bank BSI',
    'payment_category' => 'transfer_bank',
    'fee_type' => 'fixed',
    'fee_value' => 0,
    'account_number' => '1234567890',
    'account_name' => 'PT Cipta Sejati',
    'sort_order' => 5,
    'is_active' => 1,
    'created_by' => 1
]);

// Example 6: Update payment method fee
$payment = new PaymentTransaction();
$result = $payment->updatePaymentMethod(5, [
    'fee_value' => 5000,
    'is_active' => 1,
    'updated_by' => 1
]);

// Example 7: Mark expired transactions (run via cron)
$payment = new PaymentTransaction();
$expiredCount = $payment->markExpiredTransactions();
echo "Marked {$expiredCount} transactions as expired";

// Example 8: Get payment statistics
$payment = new PaymentTransaction();
$stats = $payment->getPaymentStatistics('2024-12-01', '2024-12-31');

foreach ($stats as $stat) {
    echo "{$stat['payment_name']} ({$stat['status']}): {$stat['transaction_count']} transactions, Rp {$stat['total_amount']}\n";
}
*/
?>