<?php
/**
 * Aktivasi Cabang Backend Handler
 * Business logic for branch activation
 */

class CabangActivation {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Check if cabang has active subscription
     */
    public function hasActiveSubscription($cabangId) {
        $sql = "SELECT * FROM t_cabang_activation 
                WHERE cabang_key = :cabang_id 
                AND status = 'active' 
                AND end_date > NOW()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cabang_id', $cabangId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Get activation status for a cabang
     */
    public function getActivationStatus($cabangId) {
        $sql = "SELECT * FROM t_cabang_activation 
                WHERE cabang_key = :cabang_id 
                ORDER BY created_at DESC 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cabang_id', $cabangId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Activate cabang after successful payment
     */
    public function activateAfterPayment($transactionId) {
        // Get transaction details
        $sql = "SELECT * FROM t_payment WHERE transaction_id = :transaction_id AND status = 'success'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':transaction_id', $transactionId);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            return false;
        }

        // Check if this is an activation order
        if (strpos($transaction['order_id'], 'ORD-ACT-') !== 0) {
            return false;
        }

        $cabangId = $transaction['cabang_key'];
        
        // Set subscription period (1 year)
        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime('+1 year'));

        // Check if activation record exists
        $checkSql = "SELECT id FROM t_cabang_activation WHERE cabang_key = :cabang_id";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bindParam(':cabang_id', $cabangId);
        $checkStmt->execute();
        $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $sql = "UPDATE t_cabang_activation 
                    SET activation_type = 'subscription',
                        start_date = :start_date,
                        end_date = :end_date,
                        status = 'active',
                        transaction_id = :transaction_id,
                        updated_at = NOW()
                    WHERE cabang_key = :cabang_id";
        } else {
            $sql = "INSERT INTO t_cabang_activation 
                    (cabang_key, activation_type, start_date, end_date, status, transaction_id, created_at)
                    VALUES (:cabang_id, 'subscription', :start_date, :end_date, 'active', :transaction_id, NOW())";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cabang_id', $cabangId);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':transaction_id', $transactionId);

        return $stmt->execute();
    }

    /**
     * Check and expire trial/subscriptions that have ended
     */
    public function checkExpiredActivations() {
        $sql = "UPDATE t_cabang_activation 
                SET status = 'expired' 
                WHERE status = 'active' 
                AND end_date < NOW()";
        
        return $this->db->exec($sql);
    }
}
?>
