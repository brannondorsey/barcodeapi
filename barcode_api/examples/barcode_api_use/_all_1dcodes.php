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
		<script type="text/javascript" src="assets/js/query.float.js" ></script>
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
				<a href="_all_qrcodes.php">All Qr-Codes</a> | 
				<a class="selected" href="_all_1dcodes.php">All 1D-Codes</a> | 
				<a href="_other_examples.php">Other examples</a>
			</div>
		</div>
		
		<div class="wrapper">
			
			<h1>API Examples - All 1D BarCodes</h1>
			<h2 class="newversion">Datamatrix Codes and PDF417 Codes</h2>
			<br/>
			
			<div class="example">
			<h3>This is a <strong>DataMatrix</strong> code.</h3>
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar          = new BARCODE();
$barcode_link = $bar-&gt;DataMatrix_link('Test 123');
?&gt;
&lt;img src='&lt;?php echo $barcode_link; ?&gt;' /&gt;
</pre>
			<?php
				echo "<img src='".$bar->DataMatrix_link("Test 123")."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			<div class="example">
			This is a <strong>DataMatrix</strong> code.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar          = new BARCODE();
$barcode_link = $bar-&gt;PDF417_link('Test 123');
?&gt;
&lt;img src='&lt;?php echo $barcode_link; ?&gt;' /&gt;
</pre>
			<?php
				echo "<img src='".$bar->PDF417_link("Test 123")."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
			
			
			
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

