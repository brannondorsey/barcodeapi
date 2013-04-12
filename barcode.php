<!DOCTYPE html>
<html>
<?php
    require("mainfiles/barcode.class.php");
    $bar = new BARCODE();
    $upc = $_GET['code'];
    $height = $_GET['height'];
    $width = $_GET['width'];
    $color = $_GET['color'];
    // Simple use with only the four mandatory parameters. File will be saved in scripts folder
    $bar->BarCode_save("UPC-A", $upc, $upc, "images/", "png", $height, $width, "#FFFFFF", "#".$color);
    echo "<img src='".$_SESSION["_CREATED_FILE_"]."' />";
?>
</html>