<?php
	require("barcode/barcode.class.php");
	$bar	= new BARCODE();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Ultimate Barcode Generator - API Examples - All BARCODES</title>
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
				<a class="selected" href="_all_barcodes.php">All barcodes</a> | 
				<a href="_all_qrcodes.php">All Qr-Codes</a> | 
				<a href="_all_1dcodes.php">All 1D-Codes</a> | 
				<a href="_other_examples.php">Other examples</a>
			</div>
		</div>
		
		<div class="wrapper">
			
			<h2>API Examples - All BARCODES</h2>
			<br/>
			
			
			
			<div class="example">
			This is a BarCode for type <strong>UPC-A</strong>.<br/>
			This barcode must be LESS than 13 characters from ONLY 0-9 characters.
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;UPC-A&quot;, &quot;123456789012&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
			
			<!-- UPC-A -->
			<?php
			$link = $bar->BarCode_link("UPC-A", "123456789012");
			?>
			<img src='<?php echo $link; ?>' />
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
			This is a BarCode for type <strong>UPC-E</strong>.<br/>
			This barcode must be LESS than 7 characters from ONLY 0-9 characters.
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;UPC-E&quot;, &quot;654321&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
			
			<!-- UPC-E -->
			<?php
			$link = $bar->BarCode_link("UPC-E", "654321");
			?>
			<img src='<?php echo $link; ?>' />
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>EAN-8</strong>.<br/>
			This barcode must be LESS than 9 characters from ONLY 0-9 characters.
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;EAN-8&quot;, &quot;12345678&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- EAN-8 -->
				<?php
				$link = $bar->BarCode_link("EAN-8", "12345678");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>EAN-13</strong>.<br/>
			This barcode must be LESS than 14 characters from ONLY 0-9 characters.
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;EAN-13&quot;, &quot;1234567890123&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- EAN-13 -->
				<?php
				$link = $bar->BarCode_link("EAN-13", "1234567890123");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>CODE39</strong>.<br/>
				This barcode HAS NOT a character limit and accepts any<br/>
				characters from [A-Z], [0-9] and [-. $/+%]
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;CODE39&quot;, &quot;ABC12%&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- CODE39 -->
				<?php
				$link = $bar->BarCode_link("CODE39", "ABC12%");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>CODE93</strong>.<br/>
				This barcode HAS NOT a character limit and accepts any<br/>
				characters from [A-Z], [0-9] and [-. $/+%]
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;CODE93&quot;, &quot;ABC12%&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- CODE93 -->
				<?php
				$link = $bar->BarCode_link("CODE93", "ABC12%");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>CODE128</strong>.<br/>
				This barcode HAS NOT a character limit and accepts any<br/>
				characters from [a-z], [A-Z], [0-9] and [!"#$%&'()*+,-/:;<=>?@[]\^_`{}]
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;CODE128&quot;, &quot;Ab12.#@&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- CODE128 -->
				<?php
				$link = $bar->BarCode_link("CODE128", "Ab12.#@");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>POSTNET</strong>.<br/>
			This barcode must be 5, 9 or 11 characters from ONLY 0-9 characters.
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;POSTNET&quot;, &quot;12345678901&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- POSTNET -->
				<?php
				$link = $bar->BarCode_link("POSTNET", "12345678901");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>CODABAR</strong>.<br/>
			This barcode HAS NO LIMIT of characters from ONLY 0-9 characters.
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;CODABAR&quot;, &quot;12345678901&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- CODABAR -->
				<?php
				$link = $bar->BarCode_link("CODABAR", "1234567890");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
				This is a BarCode for type <strong>ISBN</strong>.<br/>
			This barcode MUST BE 11 characters long from ONLY 0-9 characters<br/>
			and it MUST start with the number 978
<pre class="phpCode">
&lt;?php
	$link = $bar-&gt;BarCode_link(&quot;ISBN&quot;, &quot;984567890123&quot;);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
				
				<!-- ISBN -->
				<?php
				$link = $bar->BarCode_link("ISBN", "9784567890123");
				?>
				<img src='<?php echo $link; ?>' />
			
				<br clear="all" />
			</div>
			<br clear="all" />
			
			
			
			
		
			
			
			
			
			
			
			
		</div>
	
	
	</body>
</html>

