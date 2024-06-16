<?php
require_once ("./module/connection/conn.php");
$getMaintenance = GetQuery("SELECT * FROM p_param WHERE KATEGORI = 'isMaintenance'");
while ($mt = $getMaintenance->fetch(PDO::FETCH_ASSOC)) {
    extract($mt);
}
$getLogo = GetQuery("SELECT PROFIL_LOGO FROM c_profil");
while ($logoCS = $getLogo->fetch(PDO::FETCH_ASSOC)) {
    extract($logoCS);
}
$getSocial = GetQuery("SELECT * FROM c_mediasosial WHERE DELETION_STATUS = 0");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pemeliharaan</title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="../stylesheet/fontawesome-free/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('https://cdn0-production-images-kly.akamaized.net/peBTVTtV2gJNrp8VAAEwUphQSt0=/640x360/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/1511326/original/016255100_1487375130-Martial-arts-21.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container img {
            width: 150px;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #C70039;
        }
        p {
            margin: 0 0 20px;
            font-size: 16px;
        }
        .countdown {
            font-size: 20px;
            color: #C70039;
        }
        .contact-info {
            margin-top: 20px;
        }
        .contact-info a {
            color: #007BFF;
            text-decoration: none;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .social-icons a {
            margin: 0 10px;
            color: #C70039;
            font-size: 24px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?= $PROFIL_LOGO; ?>" alt="Ikon Pemeliharaan">
        <h1>Kami akan segera kembali!</h1>
        <p>Maaf atas ketidaknyamanannya, saat ini kami sedang melakukan pemeliharaan. Kami akan segera online kembali!</p>
        <div class="countdown">
            <a id="countdownLink" href="#">Perkiraan Waktu Tersisa: <span id="time">00:00:00</span></a>
        </div>
        <div class="contact-info">
            <p>Perlu menghubungi kami? Hubungi kami di: <br> 
            <a href="mailto:admincs@ciptasejatiindonesia.com">admincs@ciptasejatiindonesia.com</a> <br>
            <a href="mailto:ciptasejatiindonesia@gmail.com">ciptasejatiindonesia@gmail.com</a></p>
        </div>
        <div class="social-icons">
            <?php
            while ($iconCS = $getSocial->fetch(PDO::FETCH_ASSOC)) {
                extract($iconCS);
                ?>
                <a href="<?= $MEDIA_LINK; ?>" target="_blank" rel="noopener noreferrer"><i class="<?= $MEDIA_ICON; ?>"></i></a>
                <?php
            }
            ?>
            <!-- Add more social media icons as needed -->
        </div>
    </div>

    <script>
        // Extract the end time from PHP variable
        const timeString = '<?= $DESK; ?>';
        const endTime = new Date(timeString).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;

            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Adjust the time format based on remaining time
            if (distance <= 0) {
                clearInterval(timer);
                document.getElementById('time').textContent = "00:00:00";
                // Replace the link text with a URL when countdown reaches zero
                document.getElementById('countdownLink').textContent = "Klik disini untuk melanjutkan";
                document.getElementById('countdownLink').href = "index.php"; // Replace with your desired URL
            } else {
                let timeString = '';
                if (hours > 0) {
                    timeString += `${hours} jam, `;
                }
                if (minutes > 0 || hours > 0) {
                    timeString += `${minutes} menit, `;
                }
                timeString += `${seconds} detik`;

                document.getElementById('time').textContent = timeString;
            }
        }

        // Start the countdown timer
        const timer = setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
