<?php
require_once './module/connection/conn.php';

if (!isset($_SESSION['LOGINIDUS_CS'])) {
    header('Location: index.php');
    exit;
}

$expiredCabang = $_SESSION['LOGINCAB_CS'] ?? '';
if ($expiredCabang === '') {
    header('Location: dashboard.php');
    exit;
}

$expiryStmt = $db1->prepare(
    "SELECT EXPIRY_DATE
     FROM m_cabang
     WHERE CONVERT(CABANG_KEY USING utf8mb4) COLLATE utf8mb4_general_ci
         = CONVERT(:cabang_key USING utf8mb4) COLLATE utf8mb4_general_ci
     LIMIT 1"
);
$expiryStmt->execute([':cabang_key' => $expiredCabang]);
$expiredCabangData = $expiryStmt->fetch(PDO::FETCH_ASSOC);

if ($expiredCabangData && !empty($expiredCabangData['EXPIRY_DATE']) && strtotime($expiredCabangData['EXPIRY_DATE']) >= strtotime(date('Y-m-d'))) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html class="backend">
    <head>
        <?php include 'module/head.php'; ?>
    </head>
    <body>
        <header id="header" class="navbar">
            <?php include 'module/header.php'; ?>
        </header>

        <aside class="sidebar sidebar-left sidebar-menu">
            <?php include 'module/sidebar.php'; ?>
        </aside>

        <section id="main" role="main">
            <div class="container-fluid">
                <div class="page-header page-header-block">
                    <div class="page-header-section">
                        <h4 class="title semibold">
                            <span class="figure"><i class="fa fa-exclamation-triangle"></i></span>
                            Langganan Expired
                        </h4>
                    </div>
                </div>

                <?php include 'module/component/expired/v_expired.php'; ?>
            </div>
        </section>

        <footer id="footer">
            <?php include 'module/footer.php'; ?>
        </footer>

        <?php include 'module/js.php'; ?>
    </body>
</html>
