<?php
	require("barcode/barcode.class.php");
	$bar	= new BARCODE();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Ultimate Barcode Generator - API Examples</title>
		<link type="text/css" href="assets/css/style.css"  rel="stylesheet" />
		<link type="text/css" href="assets/css/snippet.css"  rel="stylesheet" />
		<script type="text/javascript" src="assets/js/jquery.1.6.4.js" ></script>
		<script type="text/javascript" src="assets/js/snippet.js" ></script>
		<script type="text/javascript" src="assets/js/query.float.js" ></script>
		<script>
			$(document).ready(function() {
				$("pre.example1").snippet("php",{style:"emacs",transparent:false,box:"5",boxColor:"#aaa",boxFill:"#e4e4e4", menu:false,showNum:false});
				$("pre.example2").snippet("php",{style:"emacs",transparent:false,box:"4",boxColor:"#aaa",boxFill:"#e4e4e4", menu:false,showNum:false});
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
				<a href="_all_1dcodes.php">All 1D-Codes</a> | 
				<a class="selected" href="_other_examples.php">Other examples</a>
			</div>
		</div>
		
		<div class="wrapper">
			
			
			<div class="example">
			This is a QR-Code for type <strong>text</strong> specifying the size wanted.<br/>
			You can notice on line <strong>5</strong> that we have two extra parameters at the end. Right after <strong>$qr_values</strong> we have <strong>100, 2</strong> wich combined will make the height (and also width for QRCodes) of our request. It's always one * the other. In this case we have <strong>100, 2</strong> wich is <strong>100 * 2</strong> wich will make it 200px height (and width for QRCodes because they are always squares)
<pre class="example1">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= 'Hello world!';
$barcode_link	= $bar-&gt;QRCode_link('text', $qr_values, 100, 2);
?&gt;
&lt;img src='&lt;?php echo $barcode_link; ?&gt;' /&gt;
</pre>
			<?php
				// Text
				$qr_values[0] = "Hello world!";
				echo "<img src='".$bar->QRCode_link("text", $qr_values, 100, 2)."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			
			<div class="example">
			This is a DataMatrix specifying the size and colors wanted.<br/>
			Here we set all the optional parameters. After "text" we set the height, the width, margin and finally colors.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$barcode_link = $bar-&gt;DataMatrix_link('text', $qr_values, 150, 250, 20, "#ff6600", "#ffffff");
?&gt;
&lt;img src='&lt;?php echo $barcode_link; ?&gt;' /&gt;
</pre>
			<?php
				// Text
				$qr_values[0] = "Hello world!";
				echo "<img src='".$bar->DataMatrix_link("text", 150, 250, 20, "#ff6600", "#ffffff")."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			
			
			
			<div class="example">
			This is a QR-Code for type <strong>text</strong> specifying the size as above and also <strong>colours</strong> and  <strong>error correction level</strong>.<br/>
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$qr_values[0]	= 'Hello world!';
$barcode_link	= $bar-&gt;QRCode_link('text', $qr_values, 100, 2, "#cccccc", "#dd3300", "Q");
?&gt;
&lt;img src='&lt;?php echo $barcode_link; ?&gt;' /&gt;
</pre>
			<?php
				// Text
				$qr_values[0] = "Hello world!";
				echo "<img src='".$bar->QRCode_link("text", $qr_values, 100, 2, "#cccccc", "#dd3300", "Q")."' />";
			?>
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			
			<div class="example">
			This is a BarCode for type <strong>UPC-A</strong> specifying size and colors.<br/>
			You can see, as the example above we are setting two extra parameters <strong>60, 2</strong> wich will make tha barcode height of 120px (60 * 2). Also, right after we are setting the size, we have two new extra parameters <strong>"#ff0000", "#0000ff"</strong> wich are the hex colors for background color and bars color in that specific order. <strong>NOTE</strong> that we cannot set the colors without setting the size. If you want to set colors you need to pass the size parameters as well in that order.
<pre class="example2">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$link = $bar-&gt;BarCode_link(&quot;UPC-A&quot;, &quot;12&quot;, 60, 2, "#ff0000", "#ffffff");
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
			
			<!-- UPC-A -->
			<?php
			$link = $bar->BarCode_link("UPC-A", "12", 60, 2, "#ff0000", "#ffffff");
			?>
			<img src='<?php echo $link; ?>' />
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			<div class="example">
			This is a BarCode for type <strong>UPC-A</strong> specifying size and colors and also with data hidden.<br/>
			You can see, as the example above we are setting two extra parameters <strong>60, 2</strong> wich will make tha barcode height of 120px (60 * 2). Also, right after we are setting the size, we have two new extra parameters <strong>"#ff0000", "#0000ff"</strong> wich are the hex colors for background color and bars color in that specific order and then finally we set to 0 (hide data). <strong>NOTE</strong> that we cannot set the colors without setting the size. If you want to set colors you need to pass the size parameters as well in that order.
<pre class="phpCode">
&lt;?php
require("barcode/barcode.class.php");
$bar = new BARCODE();
$link = $bar-&gt;BarCode_link(&quot;UPC-A&quot;, &quot;12&quot;, 60, 2, "#ff0000", "#ffffff", 0);
?&gt;
&lt;img src='&lt;?php echo $link; ?&gt;' /&gt;
</pre>
			
			<!-- UPC-A -->
			<?php
			$link = $bar->BarCode_link("UPC-A", "12", 60, 2, "#ff0000", "#ffffff", 0);
			?>
			<img src='<?php echo $link; ?>' />
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			
			
			<div class="example">
			To generate a forced download link, we use <strong>BarCode_dl</strong> and then we have the first two parameter exactly the same as prior examples, defining what barcode type and it's data, but the next two parameters are new. We will need the file name as a string and the extension (jpg, png, gif). As you can see in the second example below, after this two new parameters we do have all the other optional ones, for size and colors. The same happens for QRCodes, where we use <strong>QRCode_dl</strong>, again with a new parameter for the filename BUT NO parameter for filetype. <strong>QRCode_dl</strong> also accepts the optional parameters for size.
<pre class="phpCode">
&lt;?php
require(&quot;barcode/barcode.class.php&quot;);
$bar = new BARCODE();
$link_one 	 	= $bar-&gt;BarCode_dl(&quot;UPC-A&quot;, &quot;12&quot;, &quot;filename_one&quot;, &quot;jpg&quot;);
$link_two 		 = $bar-&gt;BarCode_dl(&quot;UPC-A&quot;, &quot;12&quot;, &quot;filename_two&quot;, &quot;png&quot;, 60, 2, &quot;#ff0000&quot;, &quot;#0000ff&quot;);
$qr_values[0]	  = 'Hello world!';
$link_three		= $bar-&gt;QRCode_dl(&quot;text&quot;, $qr_values, &quot;filename_three&quot;);
?&gt;
&lt;a href='&lt;?php echo $link_one; ?&gt;'&gt;Download file ONE&lt;/a&gt;&lt;br/&gt;
&lt;a href='&lt;?php echo $link_two; ?&gt;'&gt;Download file TWO&lt;/a&gt;
&lt;a href='&lt;?php echo $link_three; ?&gt;'&gt;Download file THREE&lt;/a&gt;
</pre>
			
			<!-- UPC-A -->
			<?php
			$link_one 		= $bar->BarCode_dl("UPC-A", "12", "filename_one", "jpg");
			$link_two 		= $bar->BarCode_dl("UPC-A", "12", "filename_two", "png", 60, 2, "#ff0000", "#0000ff");
			
			$qr_values[0]	= 'Hello world!';
			$link_three		= $bar->QRCode_dl("text", $qr_values, "filename_three");
			?>
			<br/>
			<br/>
			<br/>
			<a class="example3_link" href='<?php echo $link_one; ?>'>Download file ONE</a><br/>
			<a class="example3_link" href='<?php echo $link_two; ?>'>Download file TWO</a>
			<a class="example3_link" href='<?php echo $link_three; ?>'>Download file THREE</a>
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			<div class="example">
			To last we provide a function to save a barcode directly to your filesystem. With <strong>BarCode_save</strong> have the third parameter as the filename and the forth parameter as the directory to save. So this directory <strong>MUST BE relative</strong> to the current script calling the function, in this specific case is <strong>(_other_examples.php) location</strong>. In our example below we have set the directory to <strong>./</strong> wich means to save in current directory. Also note that directories are not created (although you can easy allow directory creation with simple hack to code). For the sake of exampleness, the script sets a var <strong>$_SESSION["_CREATED_FILE_"]</strong> with the last saved image URL, so you can access it as we show in the below examples.
<pre class="phpCode">
&lt;?php
require(&quot;barcode/barcode.class.php&quot;);
$bar = new BARCODE();
$bar-&gt;BarCode_save(&quot;UPC-A&quot;, &quot;12&quot;, &quot;filename_one&quot;, &quot;./&quot;, &quot;jpg&quot;);
?&gt;
&lt;img src='&lt;?php echo $_SESSION["_CREATED_FILE_"]; ?&gt;' /&gt;
&lt;small&gt;&lt;?php echo $_SESSION["_CREATED_FILE_"]; ?&gt;&lt;/small&gt;
</pre>
			
			<!-- UPC-A -->
			<?php
			$bar->BarCode_save("UPC-A", "12", "filename_one", "./", "jpg");
			?>
			<img src='<?php echo $_SESSION["_CREATED_FILE_"]; ?>' />
			<small class="example4"><?php echo $_SESSION["_CREATED_FILE_"]; ?></small>
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			
			
			
			
			<div class="example">
			The same as above but for QRCode. Notice filenames are the same, but above is JPG and this QRCode is PNG<br/>(no choice for QRCodes, must be PNG)
<pre class="phpCode">
&lt;?php
require(&quot;barcode/barcode.class.php&quot;);
$bar = new BARCODE();
$qr_values[0]	= 'Hello world!';
$bar-&gt;QRCode_save(&quot;text&quot;, $qr_values, &quot;filename_one&quot;, &quot;./&quot;);
?&gt;
&lt;img src='&lt;?php echo $_SESSION[&quot;_CREATED_FILE_&quot;]; ?&gt;' /&gt;
&lt;small&gt;&lt;?php echo $_SESSION[&quot;_CREATED_FILE_&quot;]; ?&gt;&lt;/small&gt;
</pre>
			
			<!-- UPC-A -->
			<?php
			$qr_values[0]	= 'Hello world!';
			$bar->QRCode_save("text", $qr_values, "filename_one", "./");
			?>
			<img src='<?php echo $_SESSION["_CREATED_FILE_"]; ?>' /><br/><br/><br/><br/><br/><br/>
			<small class="example4"><?php echo $_SESSION["_CREATED_FILE_"]; ?></small>
		
			<br clear="all" />
			</div>
			<br clear="all" />
			<br/>
			
			
			

			
			
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
