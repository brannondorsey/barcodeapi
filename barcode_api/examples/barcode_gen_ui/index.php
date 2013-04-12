<?php 

@ob_start();

$bdata_matrix	= "123";
$bdata_pdf		= "123";
$bdata			= "123";
$height			= "50";
$scale			= "2";
$bgcolor		= "#FFFFFF";
$color			= "#000000";
$file			= "";
$folder			= "";
$type			= "png";

if(isset($_POST['Genrate'])) {
	
	// Type of encoding var | Wich barcode is wanted ?
	$encode			= $_POST['encode'];
	
	
	////////////////////////
	// In case of QR-Code //
	////////////////////////
	$qrdata_type	= $_POST['qrdata_type'];			// What type of QR-Code

	$qr_btext_text	= $_POST['qr_btext_text'];		// CASE:TEXT 	- Bulk TExt
	
	$qr_link_link	= $_POST['qr_link_link'];		// CASE:LINK 	- Url
	
	$qr_sms_phone	= $_POST['qr_sms_phone'];		// CASE:SMS 	- Phone Number to send sms to
	$qr_sms_msg		= $_POST['qr_sms_msg'];			// CASE:SMS 	- Message to send
	
	$qr_phone_phone	= $_POST['qr_phone_phone'];		// CASE:PHONE 	- Phone number to call
	
	$qr_vc_N		= $_POST['qr_vc_N'];			// CASE:VCard 	- Name of contact
	$qr_vc_C		= $_POST['qr_vc_C'];			// CASE:VCard 	- Company of contact
	$qr_vc_J		= $_POST['qr_vc_J'];			// CASE:VCard 	- Job Title of contact
	$qr_vc_W		= $_POST['qr_vc_W'];			// CASE:VCard 	- Work phone number
	$qr_vc_H		= $_POST['qr_vc_H'];			// CASE:VCard 	- Home phone number
	$qr_vc_AA		= $_POST['qr_vc_AA'];			// CASE:VCard 	- Home Address
	$qr_vc_ACI		= $_POST['qr_vc_ACI'];			// CASE:VCard 	- Home City
	$qr_vc_AP		= $_POST['qr_vc_AP'];			// CASE:VCard 	- Home Postcode
	$qr_vc_ACO		= $_POST['qr_vc_ACO'];			// CASE:VCard 	- Home Country
	$qr_vc_E		= $_POST['qr_vc_E'];			// CASE:VCard 	- Email address of contact
	$qr_vc_U		= $_POST['qr_vc_U'];			// CASE:VCard 	- Web page of contact
	
	$qr_mec_N		= $_POST['qr_mec_N'];			// CASE:meCard 	- Name of contact
	$qr_mec_P		= $_POST['qr_mec_P'];			// CASE:meCard 	- Phone number
	$qr_mec_E		= $_POST['qr_mec_E'];			// CASE:meCard 	- Email address
	$qr_mec_U		= $_POST['qr_mec_U'];			// CASE:meCard 	- Web page of contact
	
	$qr_email_add	= $_POST['qr_email_add'];		// CASE:EMAIL	- Destination Email Address
	$qr_email_sub	= $_POST['qr_email_sub'];		// CASE:WIFI	- Email subject
	$qr_email_msg	= $_POST['qr_email_msg'];		// CASE:EMAIL	- Email body
	
	$qr_wifi_ssid	= $_POST['qr_wifi_ssid'];		// CASE:WIFI	- Network name (SSID)
	$qr_wifi_type	= $_POST['qr_wifi_type'];		// CASE:WIFI	- Network type (WEP, WPA, OPEN)
	$qr_wifi_pass	= $_POST['qr_wifi_pass'];		// CASE:WIFI	- Network password
	
	$qr_geo_lat		= $_POST['qr_geo_lat'];			// CASE:GEO		- Latidude
	$qr_geo_lon		= $_POST['qr_geo_lon'];			// CASE:GEO		- Longitude
	
	
	
	////////////////////////
	// In case of 2D Code //
	////////////////////////
	$bdata_matrix	= $_POST['bdata_matrix'];		// Barcode data
	$bdata_pdf		= $_POST['bdata_pdf'];			// Barcode data
	
	
	
	////////////////////////
	// In case of 1D Code //
	////////////////////////
	$bdata			= $_POST['bdata'];				// Barcode data
		
	
	////////////////////////
	//     Comon stuff    //
	////////////////////////
	$height				= $_POST['height'];			// Height in pixels (will be scaled with next var)
	$scale				= $_POST['scale'];			// Scale of image. So total height of image will be ($scale * $height)
	$bgcolor			= $_POST['bgcolor'];		// Background color
	$color				= $_POST['color'];			// Foregroud color
	$file				= $_POST['file'];			// Filename (optional)
	$folder				= $_POST['folder'];			// Folder (optional)
	$type				= $_POST['type'];			// Type of file name (JPG, PNG, GIF)
	
	
	
	if (!empty($_POST['file']) and empty($_POST['folder'])) {
		include("barcode/barcode.processor.php");
	}
	
	
} else {
	
	$encode 		= "QRCODE";
	$qrdata_type 	= "text";
	$qr_btext_text	= "";
	$qr_link_link	= "";
	$qr_sms_phone	= "";
	$qr_sms_msg		= "";
	$qr_phone_phone	= "";
	$qr_vc_N		= "";
	$qr_vc_C		= "";
	$qr_vc_J		= "";
	$qr_vc_W		= "";
	$qr_vc_H		= "";
	$qr_vc_AA		= "";
	$qr_vc_ACI		= "";
	$qr_vc_AP		= "";
	$qr_vc_ACO		= "";
	$qr_vc_E		= "";
	$qr_vc_U		= "";
	$qr_mec_N		= "";
	$qr_mec_P		= "";
	$qr_mec_E		= "";
	$qr_mec_U		= "";
	$qr_email_add	= "";
	$qr_email_sub	= "";
	$qr_email_msg	= "";
	$qr_wifi_ssid	= "";
	$qr_wifi_type	= "";
	$qr_wifi_pass	= "";
	$qr_geo_lat		= "";
	$qr_geo_lon		= "";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Ultimate Barcode Generator - Generator Form</title>
		<link type="text/css" href="assets/css/style_generator.css"  rel="stylesheet" />
		<link type="text/css" href="assets/css/style.css"  rel="stylesheet" />
		<link type="text/css" href="assets/css/formalize.css"  rel="stylesheet" />
		<link type="text/css" href="assets/css/colorpicker.css"  rel="stylesheet" />
		<script type="text/javascript" src="assets/js/jquery.1.6.4.js" ></script>
		<script type="text/javascript" src="assets/js/jquery.formalize.min.js" ></script>
		<script type="text/javascript" src="assets/js/jquery.wheelcolorpicker.min.js"></script>
		<script type="text/javascript" src="assets/js/ubg_form_engine.js" ></script>
	</head>

	<body>
		
		<div class="header">
			<div class="logo">
				<img src="assets/img/logo.png" />
			</div>
			<br clear="all" />
		</div>
		
		<div class="wrapper">
			<div class="content_gen">
				<div class="form_generator_container">
					<form id="form_generator" action="" method="post">
						
						<label for="encode">Select Encoding</label>
						<select name="encode" id="encode" class="encode">
							<option value="QRCODE" <?php 		if ($encode == 'QRCODE') 		{ echo 'selected'; } ?>>QR-CODE</option>
							<option value="DATAMATRIX" <?php	if ($encode == 'DATAMATRIX')	{ echo 'selected'; } ?>>DataMatrix</option>
							<option value="PDF417" <?php 		if ($encode == 'PDF417') 		{ echo 'selected'; } ?>>PDF417</option>
							<option value="UPC-A" <?php 		if ($encode == 'UPC-A') 		{ echo 'selected'; } ?>>UPC-A</option>
							<option value="UPC-E" <?php 		if ($encode == 'UPC-E') 		{ echo 'selected'; } ?>>UPC-E</option>
							<option value="EAN-8" <?php 		if ($encode == 'EAN-8') 		{ echo 'selected'; } ?>>EAN-8</option>
							<option value="EAN-13" <?php 		if ($encode == 'EAN-13') 		{ echo 'selected'; } ?>>EAN-13</option>
							<option value="CODE39" <?php 		if ($encode == 'CODE39') 		{ echo 'selected'; } ?>>CODE39</option>
							<option value="CODE93" <?php 		if ($encode == 'CODE93') 		{ echo 'selected'; } ?>>CODE93</option>
							<option value="CODE128" <?php 		if ($encode == 'CODE128') 		{ echo 'selected'; } ?>>CODE128</option>
							<option value="POSTNET" <?php 		if ($encode == 'POSTNET') 		{ echo 'selected'; } ?>>POSTNET</option>
							<option value="CODABAR" <?php 		if ($encode == 'CODABAR') 		{ echo 'selected'; } ?>>CODABAR</option>
							<option value="ISBN" <?php 			if ($encode == 'ISBN') 			{ echo 'selected'; } ?>>ISBN</option>
						</select> <small class="encode">Select the barcode type here.</small>
						<br clear="all" />
						
						<hr />
						
						<div class="QR_only">
							<label for="qrdata_type">QR Data Type</label>
							<select name="qrdata_type" id="qrdata_type" class="qrdata_type">
								<option value="text" <?php 		if ($qrdata_type == 'text') 	{ echo 'selected'; } ?>>Bulk Text</option>
								<option value="link" <?php 		if ($qrdata_type == 'link') 	{ echo 'selected'; } ?>>Link</option>
								<option value="sms" <?php 		if ($qrdata_type == 'sms') 		{ echo 'selected'; } ?>>SMS</option>
								<option value="email" <?php 	if ($qrdata_type == 'email') 	{ echo 'selected'; } ?>>Email</option>
								<option value="phone" <?php 	if ($qrdata_type == 'phone') 	{ echo 'selected'; } ?>>Phone Number</option>
								<option value="vcard" <?php 	if ($qrdata_type == 'vcard') 	{ echo 'selected'; } ?>>Contact VCard</option>
								<option value="mecard" <?php 	if ($qrdata_type == 'mecard') 	{ echo 'selected'; } ?>>Contact meCard</option>
								<option value="wifi" <?php 		if ($qrdata_type == 'wifi') 	{ echo 'selected'; } ?>>Wifi Network</option>
								<option value="geo" <?php 		if ($qrdata_type == 'geo') 		{ echo 'selected'; } ?>>Geolocation</option>
							</select> <small class="qrdata_type">Select the QRCode meta info here.</small>
							<br clear="all" />
							
							<div class="qr_text">
								<h4>QR Bulk Text</h4>
								<label class="qr_label" for="qr_btext_text">Bulk Text</label>
								<textarea name="qr_btext_text" id="qr_btext_text" class="qr_btext_text" ><?php echo $qr_btext_text; ?></textarea>
								<br clear="all" />
							</div>
							
							<div class="qr_link">
								<h4>QR Link</h4>
								<label class="qr_label" for="qr_link_link">Link</label>
								<input name="qr_link_link" id="qr_link_link" class="qr_link_link" value="<?php echo $qr_link_link; ?>" />
								<br clear="all" />
							</div>
							
							<div class="qr_sms">
								<h4>QR SMS</h4>
								<label class="qr_label" for="qr_sms_phone">Phone</label>
								<input name="qr_sms_phone" id="qr_sms_phone" class="qr_sms_phone" value="<?php echo $qr_sms_phone; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_sms_msg">SMS</label>
								<textarea name="qr_sms_msg" id="qr_sms_msg" class="qr_sms_msg" ><?php echo $qr_sms_msg; ?></textarea>
								<br clear="all" />
							</div>
							
							<div class="qr_phone">
								<h4>QR Phone Number</h4>
								<label class="qr_label" for="qr_phone_phone">Phone Number</label>
								<input name="qr_phone_phone" id="qr_phone_phone" class="qr_phone_phone" value="<?php echo $qr_phone_phone; ?>" />
								<br clear="all" />
							</div>
							
							<div class="qr_vcard">
								<h4>QR VCard</h4>
								<label class="qr_label" for="qr_vc_N">Name</label>
								<input name="qr_vc_N" id="qr_vc_N" class="qr_vc_N" value="<?php echo $qr_vc_N; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_vc_C">Company</label>
								<input name="qr_vc_C" id="qr_vc_C" class="qr_vc_C" value="<?php echo $qr_vc_C; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_vc_J">Job</label>
								<input name="qr_vc_J" id="qr_vc_J" class="qr_vc_J" value="<?php echo $qr_vc_J; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_vc_W">Work Phone</label>
								<input name="qr_vc_W" id="qr_vc_W" class="qr_vc_W" value="<?php echo $qr_vc_W; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_vc_H">Home Phone</label>
								<input name="qr_vc_H" id="qr_vc_H" class="qr_vc_H" value="<?php echo $qr_vc_H; ?>" />
								<br clear="all" />
								
								<label class="qr_label" for="qr_vc_AA">Home Address</label>
								<input name="qr_vc_AA" id="qr_vc_AA" class="qr_vc_AA" value="<?php echo $qr_vc_AA; ?>" />
								<br clear="all" />
								
								<label class="qr_label" for="qr_vc_ACI">Home City</label>
								<input name="qr_vc_ACI" id="qr_vc_ACI" class="qr_vc_ACI" value="<?php echo $qr_vc_ACI; ?>" />
								<br clear="all" />
								
								<label class="qr_label" for="qr_vc_AP">Home Postcode</label>
								<input name="qr_vc_AP" id="qr_vc_AP" class="qr_vc_AP" value="<?php echo $qr_vc_AP; ?>" />
								<br clear="all" />
								
								<label class="qr_label" for="qr_vc_ACO">Home Country</label>
								<input name="qr_vc_ACO" id="qr_vc_ACO" class="qr_vc_ACO" value="<?php echo $qr_vc_ACO; ?>" />
								<br clear="all" />
								
								<label class="qr_label" for="qr_vc_E">Email</label>
								<input name="qr_vc_E" id="qr_vc_E" class="qr_vc_E" value="<?php echo $qr_vc_E; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_vc_U">Url</label>
								<input name="qr_vc_U" id="qr_vc_U" class="qr_vc_U" value="<?php echo $qr_vc_U; ?>" />
								<br clear="all" />
							</div>
							
							<div class="qr_mecard">
								<h4>QR meCard</h4>
								<label class="qr_label" for="qr_mec_N">Name</label>
								<input name="qr_mec_N" id="qr_mec_N" class="qr_mec_N" value="<?php echo $qr_mec_N; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_mec_P">Phone</label>
								<input name="qr_mec_P" id="qr_mec_P" class="qr_mec_P" value="<?php echo $qr_mec_P; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_mec_E">Email</label>
								<input name="qr_mec_E" id="qr_mec_E" class="qr_mec_E" value="<?php echo $qr_mec_E; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_mec_U">Url</label>
								<input name="qr_mec_U" id="qr_mec_U" class="qr_mec_U" value="<?php echo $qr_mec_U; ?>" />
								<br clear="all" />
							</div>
							
							<div class="qr_email">
								<h4>QR Email</h4>
								<label class="qr_label" for="qr_email_add">Email address</label>
								<input name="qr_email_add" id="qr_email_add" class="qr_email_add" value="<?php echo $qr_email_add; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_email_sub">Subject</label>
								<input name="qr_email_sub" id="qr_email_sub" class="qr_email_sub" value="<?php echo $qr_email_sub; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_email_msg">Message</label>
								<textarea name="qr_email_msg" id="qr_email_msg" class="qr_email_msg" ><?php echo $qr_email_msg; ?></textarea>
								<br clear="all" />
							</div>
							
							<div class="qr_wifi">
								<h4>QR Wifi Network</h4>
								<label class="qr_label" for="qr_wifi_ssid">SSID</label>
								<input name="qr_wifi_ssid" id="qr_wifi_ssid" class="qr_wifi_ssid" value="<?php echo $qr_wifi_ssid; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_wifi_type">Type</label>
								<select name="qr_wifi_type" id="qr_wifi_type" class="qr_wifi_type">
									<option value="wep">WEP</option>
									<option value="wpa">WPA</option>
									<option value="open">OPEN</option>
								</select>
								<br clear="all" />
								<label class="qr_label" for="qr_wifi_pass">Password</label>
								<input name="qr_wifi_pass" id="qr_wifi_pass" class="qr_wifi_pass" value="<?php echo $qr_wifi_pass; ?>" />
								<br clear="all" />
							</div>
							
							<div class="qr_geo">
								<h4>QR Geo Location</h4>
								<label class="qr_label" for="qr_geo_lat">Latitude</label>
								<input name="qr_geo_lat" id="qr_geo_lat" class="qr_geo_lat" value="<?php echo $qr_geo_lat; ?>" />
								<br clear="all" />
								<label class="qr_label" for="qr_geo_lon">Longitude</label>
								<input name="qr_geo_lon" id="qr_geo_lon" class="qr_geo_lon" value="<?php echo $qr_geo_lon; ?>" />
								<br clear="all" />
							</div>
							
						</div>
						
						
						
						<div class="MATRIX_only">
							<label for="bdata_matrix">DataMatrix Data</label>
							<textarea name="bdata_matrix" id="bdata_matrix" class="bdata_matrix" ><?php echo $bdata_matrix; ?></textarea>
							<br clear="all" />
						</div>
						
						<div class="PDF417_only">
							<label for="bdata_pdf">PDF417 Data</label>
							<textarea name="bdata_pdf" id="bdata_pdf" class="bdata_pdf" ><?php echo $bdata_pdf; ?></textarea>
							<br clear="all" />
						</div>
						
						
						
						
						
						<div class="BARCODE_only">
							<label for="bdata">Barcode Data</label>
							<input name="bdata" id="bdata" class="bdata" value="<?php echo $bdata; ?>" />
							<br clear="all" />
						</div>
						
						<hr />
						
						<label for="height">Barcode Height</label>
						<input name="height" id="height" class="height" value="<?php echo $height; ?>" /> <small class="height">For QRCode with and height, set here a value</small>
						<br clear="all" />
						
						<label for="scale">Scale</label>
						<input name="scale" id="scale" class="scale" value="<?php echo $scale; ?>" /> <small class="scale">and set a multiplier here. (50 * 2 = 100x100px).</small>
						<br clear="all" />
						
						<label for="bgcolor">Background Color</label>
						<input name="bgcolor" id="bgcolor" class="bgcolor" value="<?php echo $bgcolor; ?>" /> <small class="bgcolor">Set a background color here.</small>
						<br clear="all" />
						
						<label for="color">Bars Color</label>
						<input name="color" id="color" class="color" value="<?php echo $color; ?>" /> <small class="color">Set the foreground color here.</small>
						<br clear="all" />
						
						<label for="file">File Name</label>
						<input name="file" id="file" class="file" value="<?php echo $file; ?>" />
						<select name="type" id="type" class="type">
							<option value="png">PNG</option>
							<option value="gif">GIF</option>
							<option value="jpg">JPEG</option>
						</select> <small class="file">Filename will trigger a download.</small>
						<br clear="all" />
						
						<label for="folder">Folder</label>
						<input name="folder" id="folder" class="folder" value="<?php echo $folder; ?>" /> <small class="folder">Filename will save the filename to disk. Disabled!</small>
						<br clear="all" />
						
						<input type="submit" name="Genrate" class="Genrate" id="Genrate" value="Create Barcode">
					</form>
				</div>
				<br clear="all" />
			</div>
			<br clear="all" />
		</div>
		<div class="result_container">
			<?php
			if(isset($_POST['Genrate'])) {
				if(empty($_POST['file'])) {
					$qstr = "";
					foreach($_POST as $key=>$value)
						$qstr .= $key."=".urlencode($value)."&";
					echo "<img src='barcode/barcode.processor.php?$qstr'>";
				} else if (!empty($_POST['file']) and !empty($_POST['folder'])) {
					include("barcode/barcode.processor.php");
					echo $file_path."<br/><img src='".$file_path."'>";
				}
			}
			?>
		</div>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-11489287-2']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
	</body>
</html>
