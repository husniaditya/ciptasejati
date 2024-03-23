<?php
require_once("../../../../module/connection/conn.php");

$getBlog = GetQuery("SELECT c.*, CASE WHEN c.DELETION_STATUS = 0 THEN 'Aktif' ELSE 'Tidak Aktif' END BLOG_STATUS, DATE_FORMAT(c.INPUT_DATE, '%d %M %Y %H:%i') INPUT_DATE, a.ANGGOTA_NAMA INPUT_BY
FROM c_blog c
LEFT JOIN m_anggota a ON c.INPUT_BY = a.ANGGOTA_ID");

foreach ($getBlog as $rowBlog) {
    extract($rowBlog);
    ?>
    <tr>
        <td align="center">
            <form id="eventoption-form-<?= $BLOG_ID; ?>" method="post" class="form">
                <div class="btn-group" style="margin-bottom:5px;">
                    <button type="button" class="btn btn-primary btn-outline btn-rounded mb5 dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a data-toggle="modal" href="#ViewBlog" class="open-ViewBlog" style="color:#222222;" data-id="<?= $BLOG_ID; ?>"><i class="fa-solid fa-magnifying-glass"></i> Lihat</a></li>
                        <li><a data-toggle="modal" href="#EditBlog" class="open-EditBlog" style="color:#00a5d2;" data-id="<?= $BLOG_ID; ?>"><span class="ico-edit"></span> Ubah</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="deleteblog('<?= $BLOG_ID;?>','delete')" style="color:firebrick;"><i class="fa-regular fa-trash-can"></i> Hapus</a></li>
                    </ul>
                </div>
            </form>
        </td>
        <td align="center"><img src="<?= $BLOG_IMAGE; ?>" alt="" style="text-align: center;overflow: hidden;position: relative;height: 100; width:100"></td>
        <td><?= $BLOG_TITLE; ?></td>
        <td><?= substr_replace($BLOG_MESSAGE, '...', 100); ?></td>
        <td align="center"><?= $BLOG_STATUS; ?></td>
        <td align="center"><?= $INPUT_BY; ?></td>
        <td align="center"><?= $INPUT_DATE; ?></td>
    </tr>
    <?php
}
?>