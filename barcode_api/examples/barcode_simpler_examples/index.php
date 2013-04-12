
<?php

// Remenber if you move the class to the same foder as the calling script you need to set it
// Check line #44 of barcode.class.php for more details
require("barcode/barcode.class.php");
$bar	= new BARCODE();

?>



<html style="text-align: center;">
	
	
	<!-- Set all vars and use a var inside the html -->
	<?php
	$qr_values[0] 	= '555687945';
	$qr_values[1] 	= 'Hello this is a test text message';
	$qrcode_link 	= $bar->QRCode_link("sms", $qr_values)
	?>
	<img src='<?php echo $qrcode_link;?>' />
	<hr />
	
	
	<!-- Set all vars and use a var inside the html -->
	<?php
	$qr_values[0] 	= 'Hello world!';
	$qrcode_link 	= $bar->QRCode_link('text', $qr_values, 100, 2);
	?>
	<img src='<?php echo $qrcode_link;?>' />
	<hr />
	
	
	<!-- Use it straight in the html -->
	<img src='<?php echo $bar->BarCode_link("UPC-A", "12", 50, 1, "#000000", "#ff6600"); ?>' />
	<hr />
	
	
	<!-- Use it straight in the html -->
	<a href='<?php echo $bar->BarCode_dl("UPC-A", "12", "UPC-A_filename", "jpg"); ?>'>Download UPC-A JPG</a><br/>
	<hr/>
	
	
	<!-- Use it straight in the html, although for qrcodes the array needs to be filled first -->
	<?php
	$qr_values[0] 	= 'Hello world!';
	?>
	<a href='<?php echo $bar->QRCode_dl('text', $qr_values, "qrcode_filename"); ?>'>Download QRCODE</a><br/>
	<hr/>
	
	
	
</html>