<?php
$video = $_POST["video"];
?>
<div class="row">
    <div class="col-md-12">
        <iframe width="560" height="300" src="<?= $video; ?>" frameborder="0" allowfullscreen></iframe>
    </div>
</div>