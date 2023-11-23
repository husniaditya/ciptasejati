<?php
$maps = $_POST["maps"];
?>
    <div class="col-md-12">
        <div class="form-group">
        <label for="EVENT_MAP">Google Maps</span></label><br>
        <iframe src="<?= $maps; ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div> 
    </div>