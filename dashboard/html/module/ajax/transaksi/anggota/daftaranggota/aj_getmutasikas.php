<?php
require_once("../../../../../module/connection/conn.php");


if (isset($_POST['id'])) {
    $ANGGOTA_ID = $_POST['id'];
    $CABANG_KEY = $_POST['cabang'];

    $getKas = GetQuery("SELECT k.*,d.DAERAH_DESKRIPSI,c.CABANG_DESKRIPSI,a.ANGGOTA_ID,a.ANGGOTA_NAMA,t.TINGKATAN_NAMA,t.TINGKATAN_SEBUTAN,a2.ANGGOTA_NAMA INPUT_BY,DATE_FORMAT(k.KAS_TANGGAL, '%d %M %Y') FKAS_TANGGAL, DATE_FORMAT(k.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE,
    CASE
        WHEN k.KAS_JUMLAH < 0 THEN CONCAT('(', FORMAT(ABS(k.KAS_JUMLAH), 0), ')')
        ELSE FORMAT(k.KAS_JUMLAH, 0)
    END AS FKAS_JUMLAH,
    CASE 
        WHEN k.KAS_DK = 'D' THEN 'Debit'
        ELSE 'Kredit' 
    END AS KAS_DK_DES,
    CASE
        WHEN k.KAS_DK = 'D' THEN 'color: green;'
        ELSE 'color: red;' 
    END AS KAS_COLOR
    FROM t_kas k
    LEFT JOIN m_anggota a ON k.ANGGOTA_ID = a.ANGGOTA_ID AND k.CABANG_KEY = a.CABANG_KEY AND a.ANGGOTA_STATUS = 0
    LEFT JOIN m_anggota a2 ON k.INPUT_BY = a2.ANGGOTA_ID AND a2.ANGGOTA_STATUS = 0
    LEFT JOIN m_cabang c ON a.CABANG_KEY = c.CABANG_KEY
    LEFT JOIN m_daerah d ON c.DAERAH_KEY = d.DAERAH_KEY
    LEFT JOIN m_tingkatan t ON a.TINGKATAN_ID = t.TINGKATAN_ID
    WHERE k.DELETION_STATUS = 0 AND a.DELETION_STATUS=0 AND k.ANGGOTA_ID = '$ANGGOTA_ID' AND k.CABANG_KEY = '$CABANG_KEY'
    ORDER BY k.KAS_ID");

    while ($rowKas = $getKas->fetch(PDO::FETCH_ASSOC)) {
        extract($rowKas);
        
        $getSaldo = GetQuery("SELECT 
            CASE
                WHEN SUM(KAS_JUMLAH) < 0 THEN CONCAT('(', FORMAT(ABS(SUM(KAS_JUMLAH)), 0), ')')
                ELSE FORMAT(SUM(KAS_JUMLAH), 0)
            END AS FKAS_SALDO
        FROM 
            t_kas
        WHERE 
            DELETION_STATUS = 0 
            AND ANGGOTA_ID = '$ANGGOTA_ID' 
            AND CABANG_KEY = '$CABANG_KEY'
            AND KAS_ID <= '$KAS_ID' 
            AND KAS_JENIS = '$KAS_JENIS';
        ");

        while ($rowSaldo = $getSaldo->fetch(PDO::FETCH_ASSOC)) {
            extract($rowSaldo);
        }

        ?>
        <tr>
            <td align="center">
                <a href="assets/print/transaksi/kas/print_kas.php?id=<?= encodeIdToBase64($KAS_ID); ?>" target="_blank" style="color: #00b1e1;"><i class="fa-solid fa-print"></i> Cetak</a>
            </td>
            <td><?= $KAS_ID; ?></td>
            <td align="center"><?= $KAS_JENIS; ?></td>
            <td align="center"><?= $FKAS_TANGGAL; ?></td>
            <td align="center"><?= $KAS_DK_DES; ?></td>
            <td><?= $KAS_DESKRIPSI; ?></td>
            <td align="right" style="<?= $KAS_COLOR; ?>"><?= $FKAS_JUMLAH; ?></td>
            <td align="right"><?= $FKAS_SALDO; ?></td>
            <td><?= $INPUT_BY; ?></td>
            <td><?= $INPUT_DATE; ?></td>
        </tr>
        <?php
    }
}
?>