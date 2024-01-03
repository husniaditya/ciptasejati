<?php
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/../../../../vendor/autoload.php';

$dotenvPath = __DIR__ . '/../../../../dashboard/html/';
$dotenv = Dotenv\Dotenv::createImmutable($dotenvPath);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

try {
    $db1 = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $db1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    date_default_timezone_set("Asia/Jakarta");
	ini_set('max_execution_time', 300); //300 seconds = 5 minutes
	ini_set('upload_max_filesize', '10M');
    session_start();
	

    $inactivityTimeout = 900;

    if (isset($_SESSION['LOGINIDUS_CS']) && isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > $inactivityTimeout) {
            unset($_SESSION["LOGINIDUS_CS"]);
            session_destroy();
            ?><script>alert('Sesi berakhir, silahkan login kembali');</script><?php
            ?><script>document.location.href='index.php';</script><?php
        }
    }

    $_SESSION['last_activity'] = time();

    $YEAR = date("Y");
    $MONTH = date("m");
    $DATE = date("d");

    function GetQuery($query)
    {
        global $db1;
        $result = $db1->prepare($query);
        $result->execute();
        return $result;
    }

    function createKode($namaTabel, $namaKolom, $awalan, $jumlahAngka)
    {
        global $db1, $YEAR, $MONTH;
        $angkaAkhir = 0;
        $stmt = $db1->query("select max(right($namaKolom,$jumlahAngka)) as akhir from $namaTabel where substr($namaKolom, 5, 6) = '$YEAR$MONTH'");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($row["akhir"])) {
                $angkaAkhir = intval($row["akhir"]);
            }
        }
        $angkaAkhir = $angkaAkhir + 1;
        return $awalan . substr("00000000" . $angkaAkhir, -1 * $jumlahAngka);
    }

    function autoInc($namaTabel, $namaKolom, $jumlahAngka)
    {
        global $db1;
        $angkaAkhir = 0;
        $stmt = $db1->query("select max(right($namaKolom,$jumlahAngka)) as akhir from $namaTabel");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($row["akhir"])) {
                $angkaAkhir = intval($row["akhir"]);
            }
        }
        $angkaAkhir = $angkaAkhir + 1;
        return substr("00000000" . $angkaAkhir, -1 * $jumlahAngka);
    }

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function sendEmail($toAddress, $toName, $ccAddresses, $ccNames, $subject, $body, $attachmentPath = null, $attachmentName = null)
{
    try {
        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();            // Send using SMTP
        $mail->Host       = $_ENV['mailHost']; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;    // Enable SMTP authentication
        $mail->Username   = $_ENV['mailUsername']; // SMTP username
        $mail->Password   = $_ENV['mailPassword']; // SMTP password
        $mail->SMTPSecure = 'ssl';   // Enable implicit TLS encryption
        $mail->Port       = 465;      // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        // Recipients
        $mail->setFrom('no-reply@ciptasejatiindonesia.com', 'No-Reply Cipta Sejati');
        $mail->addAddress($toAddress, $toName);   // Add a recipient

        // Add CC recipients
        if (!empty($ccAddresses) && is_array($ccAddresses)) {
            foreach ($ccAddresses as $key => $ccAddress) {
                $ccName = isset($ccNames[$key]) ? $ccNames[$key] : '';
                $mail->addCC($ccAddress, $ccName);
            }
        }

        // Attachments
        if ($attachmentPath !== null) {
            $mail->addAttachment($attachmentPath, $attachmentName); // Add attachments
        }

        // Content
        $mail->isHTML(true);       // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();

        // Return success or error message
        return "Email sent successfully to $toAddress";
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// $ssh_host = 'IP HOST';
// $ssh_username = 'SSH USERNAME';
// $ssh_password = 'SSH PASSWORD';


// $mysql_host = 'MYSQL HOST';
// $mysql_port = MYSQL PORT;
// $mysql_database = 'MYSQL DATABASE';
// $mysql_username = 'MYSQL USERNAME';
// $mysql_password = 'MYSQL PASSWORD';

// // Create an SSH connection
// $connection = ssh2_connect($ssh_host);

// if (ssh2_auth_password($connection, $ssh_username, $ssh_password)) {
// 	echo "SSH connection established.<br>";

// 	// Create an SSH tunnel
// 	$tunnel = ssh2_tunnel($connection, $mysql_host, $mysql_port);

// 	if ($tunnel) {
// 		echo "SSH tunnel established.<br>";

// 		// Now, you can establish a PDO connection to the MySQL server through the SSH tunnel
// 		try {
// 			$pdo = new PDO("mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_database", $mysql_username, $mysql_password);
// 			echo "PDO connection established through SSH tunnel.<br>";

// 			// Perform database operations here

// 		} catch (PDOException $e) {
// 			die("PDO connection failed: " . $e->getMessage());
// 		}
// 	} else {
// 		die("SSH tunnel connection failed.");
// 	}
// } else {
// 	die("SSH authentication failed.");
// }
?>