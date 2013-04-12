<?php
	require("barcode/barcode.class.php");
	$bar	= new BARCODE();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Ultimate Barcode Generator - API Examples - All QRCODES</title>
		<link type="text/css" href="assets/css/style.css"  rel="stylesheet" />
		<link type="text/css" href="assets/css/snippet.css"  rel="stylesheet" />
		<script type="text/javascript" src="assets/js/jquery.1.6.4.js" ></script>
		<script type="text/javascript" src="assets/js/snippet.js" ></script>
		<script>
			$(document).ready(function() {
				$("pre.phpCode").snippet("php",{style:"emacs",transparent:false,menu:false,showNum:false});
			});
		</script>
	</head>

	<body>
		
		<div class="header">
			<div class="logo">
				<img src="assets/img/logo.png" />
			</div>
			<div class="menu">
				<a href="index.php">Overview</a> | 
				<a href="_all_barcodes.php">All barcodes</a> | 
				<a class="selected" href="_all_qrcodes.php">All Qr-Codes</a> | 
				<a href="_all_1dcodes.php">All 1D-Codes</a> | 
				<a href="_other_examples.php">Other examples</a>
			</div>
		</div>
		
		<div class="wrapper">
			
			<h2>API Examples - All QRCODES</h2>
			<br/>
			
			<div class="example">
			This is a QR-Code for type <strong>text</strong>. The array variable <strong>qr_values</strong> holds only one value.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= 'Hello world!';
$barcode_link	= $bar-&gt;QRCode_link('text', $qr_values);
?&gt;
&lt;img src='&lt;?php echo $barcode_link; ?&gt;' /&gt;
</pre>
			<?php
				// Text
				$qr_values[0] = "Hello world!";
				echo "<img src='".$bar->QRCode_link("text", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>link</strong>. The array variable <strong>qr_values</strong> holds only one value.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= "www.google.com";
$barcode_link	= $bar->QRCode_link("link", $qr_values);
?&gt;
&lt;img src='&lt;?php echo $code_link; ?&gt;' /&gt;
</pre>
			<?php
				// Link
				$qr_values[0] = "www.google.com";
				echo "<img src='".$bar->QRCode_link("link", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>sms</strong>. The array variable <strong>qr_values</strong> holds two values.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= '555687945';
$qr_values[1]	= 'Hello this is a test text message';
$barcode_link	= $bar-&gt;QRCode_link(&quot;sms&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// sms
				$qr_values[0] = "555687945";
				$qr_values[1] = "Hello this is a test text message";
				echo "<img src='".$bar->QRCode_link("sms", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>phone</strong>. The array variable <strong>qr_values</strong> holds only one value.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= '555687945';
$barcode_link	= $bar-&gt;QRCode_link(&quot;phone&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// Phone
				$qr_values[0] = "555687945";
				echo "<img src='".$bar->QRCode_link("phone", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>vcard</strong>. The array variable <strong>qr_values</strong> holds 11 values.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= &quot;Contact name&quot;;		// Name of contact
$qr_values[1]	= &quot;Comapny name&quot;;		// Company of contact
$qr_values[2]	= &quot;CEO&quot;;				 // Job Title of contact
$qr_values[3]	= &quot;555666777&quot;;		   // Work phone number
$qr_values[4]	= &quot;555687945&quot;;		   // Home phone number
$qr_values[5]	= &quot;The address&quot;;		 // Home Address
$qr_values[6]	= &quot;City&quot;;				// Home City
$qr_values[7]	= &quot;postcode&quot;;			// Home Postcode
$qr_values[8]	= &quot;Country&quot;;			 // Home Country
$qr_values[9]	= &quot;email@email.com&quot;;	 // Email address of contact
$qr_values[10]   = &quot;www.google.com&quot;;	  // Web page of contact
$barcode_link	= $bar-&gt;QRCode_link(&quot;vcard&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// vCard
				$qr_values[0] = "Contact name";		// Name of contact
				$qr_values[1] = "Comapny name";		// Company of contact
				$qr_values[2] = "CEO";				// Job Title of contact
				$qr_values[3] = "555666777";			// Work phone number
				$qr_values[4] = "555687945";			// Home phone number
				$qr_values[5] = "The address";		// Home Address
				$qr_values[6] = "City";				// Home City
				$qr_values[7] = "postcode";			// Home Postcode
				$qr_values[8] = "Country";			// Home Country
				$qr_values[9] = "email@email.com";	// Email address of contact
				$qr_values[10] = "www.google.com";	// Web page of contact
				echo "<img src='".$bar->QRCode_link("vcard", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>meCard</strong>. The array variable <strong>qr_values</strong> holds 4 values.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0] = "Contact name";	   // Contact's name
$qr_values[1] = "999999999";		  // Contact's phone number
$qr_values[2] = "email@email.com";	// Contact's email
$qr_values[3] = "www.google.com";	 // Contact's web page
$barcode_link	= $bar-&gt;QRCode_link(&quot;mecard&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// meCard
				$qr_values[0] = "Contact name";			// Contact's name
				$qr_values[1] = "999999999";			// Contact's phone number
				$qr_values[2] = "email@email.com";		// Contact's email
				$qr_values[3] = "www.google.com";		// Contact's web page
				echo "<img src='".$bar->QRCode_link("mecard", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>email</strong>. The array variable <strong>qr_values</strong> holds 3 values.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0] = "destination@email.com";
$qr_values[1] = "Email Subject";
$qr_values[2] = "Hello this is a test email content to be sent";
$barcode_link = $bar-&gt;QRCode_link(&quot;email&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// Email
				$qr_values[0] = "destination@email.com";
				$qr_values[1] = "Email Subject";
				$qr_values[2] = "Hello this is a test email content to be sent";
				echo "<img src='".$bar->QRCode_link("email", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>wifi</strong>. The array variable <strong>qr_values</strong> holds 3 values.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0] = "test_wifi";		// Network Name (SSID)
$qr_values[1] = "WEP";			  // Network type (WEP; WPA; OPEN)
$qr_values[2] = "password";		 // Network password
$barcode_link = $bar-&gt;QRCode_link(&quot;wifi&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// Wifi
				$qr_values[0] = "test_wifi";		// Network Name (SSID)
				$qr_values[1] = "WEP";				// Network type (WEP; WPA; OPEN)
				$qr_values[2] = "password";			// Network password
				echo "<img src='".$bar->QRCode_link("wifi", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>geo</strong> used for Geo-Localization.<br/>
			The array variable <strong>qr_values</strong> holds 2 values.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0] = "38.52775596312173";	   // Latitude
$qr_values[1] = "-8.8824462890625";		// Longitude
$barcode_link = $bar-&gt;QRCode_link(&quot;geo&quot;, $qr_values)
?&gt;
&lt;img src='&lt;?php echo $barcode_link;?&gt;' /&gt;
</pre>
			<?php
				// GEO
				$qr_values[0] = "38.52775596312173";	// Latitude
				$qr_values[1] = "-8.8824462890625";		// Longitude
				echo "<img src='".$bar->QRCode_link("geo", $qr_values)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			
		</div>
	
	
	</body>
</html>

