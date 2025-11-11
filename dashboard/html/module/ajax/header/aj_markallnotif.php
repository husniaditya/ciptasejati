<?php
require_once ("../../connection/conn.php");

header('Content-Type: application/json');

$USER_ID = $_SESSION["LOGINIDUS_CS"] ?? '';
if (!$USER_ID) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Mark all unread notifications for this user as read
    GetQuery("UPDATE t_notifikasi SET READ_STATUS = 1 WHERE NOTIFIKASI_USERID = '$USER_ID' AND READ_STATUS = 0");
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
