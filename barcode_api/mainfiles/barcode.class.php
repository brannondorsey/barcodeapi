<?php
/**
* This class is for generating barcodes in different encoding symbologies. 
* It supports EAN-13, EAN-8, UPC-A, UPC-E, ISBN, 2 of 5 Symbologies(std,ind,interleaved), postnet, codabar, code128, code39, code93, DataMatrix and PDF147 symbologies.
* This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*
* @author 	Eduardo Pereira
* @date		07/09/2011
* @version	3.0 (16/11/2011)
* @website	http://www.voindo.eu/UltimateBarcodeGenerator
*
* @requirements PHP with GD2 library support. 
*
* @package barcode.class
* 
*/

class BARCODE {
	
	
	/* We start creating the global necessary variables */
	var $_encode;
	var $_error;
	var $_width;
	var $_height;
	var $_scale;
	var $_color;
	var $_font;
	var $_bgcolor;
	var $_format;
	var $_n2w;
	var $qr_data_files;
	var $_PROCESSOR_LOCATION_;
	private $data;
	
	/* Reseting system variables   */
	/* DO NOT CHANGE ANYTHING HERE */
	private $version_mx				= 40;
	private $type					= 'bin';
	private $level					= 'L';
	var 	$ECLevel				= "L";
	private $value					= '';
	private $length					= 0;
	private $version				= 0;
	private $size					= 0;
	private $qr_size				= 0;
	private $data_bit 				= array();
	private $data_val 				= array();
	private $data_word 				= array();
	private $data_cur 				= 0;
	private $data_num 				= 0;
	private $data_bits				= 0;
	private $max_data_bit			= 0;
	private $max_data_word			= 0;
	private $max_word				= 0;
	private $ec						= 0;
	private $matrix 				= array();
	private $matrix_remain 			= 0; 
	private $matrix_x_array			= array();
	private $matrix_y_array			= array();
	private $mask_array				= array();
	private $format_information_x1	= array();
	private $format_information_y1	= array();
	private $format_information_x2	= array();
	private $format_information_y2	= array();
	private $rs_block_order			= array();
	private $rs_ecc_codewords		= 0;
	private $byte_num				= 0;
	private $final					= array();
	var 	$margin					= true;
	var 	$margin_size			= 10;
	private $disable_border			= false;
	
	
	/**
	* Function constructor: Sets the initial vars
	*
	*/
	function BARCODE($encoding="EAN-13") {
		
		// IMPORTANT!
		// We are defining here where is de barcode.processor.php file
		// Normaly should be located together with barcode.class.php and
		// in a specific folder ONE LEVEL UP from code calling the API.
		// According to your needs, you can set the $this->_PROCESSOR_LOCATION_
		// to a specific relative or absolute path to the file barcode.processor.php
		//
		// IF YOU USE THE CLASS IN THE SAME FOLDER AS THE CALLING SCRIPT (not the processor, the calling script) 
		// YOU NEED TO SET THE _PROCESSOR_LOCATION_ to = ""
		//
		// All the rest it should automatically detects the folder if ANY LEVEL BELOW.
		//
		// IF IN FOLDER OF LEVEL ABOVE, SET _PROCESSOR_LOCATION_ ABSOLUTE PATH
		
		if ( !defined('__DIR__') ) define('__DIR__', dirname(__FILE__));
		$this->_PROCESSOR_LOCATION_ = basename(__DIR__)."/";
		
		////////////////////////////////////////
		// CONFIGURE YOUR QR_DATA FOLDER HERE //
		////////////////////////////////////////
		$this->qr_data_files = __DIR__ ."/qr_data";
		
		
		
		if(!function_exists("imagecreate")) {
			die("This class needs GD library support.");
			return false;
		}

		$this->_error	= "";
		$this->_scale	= 2;
		$this->_width	= 0;
		$this->_height	= 0;
		$this->_n2w		= 2;
		$this->_height	= 60;
		$this->_format	= 'png';
		
		if (file_exists(dirname($_SERVER["SCRIPT_FILENAME"])."/"."arialbd.ttf")) {
			$this->_font = dirname($_SERVER["SCRIPT_FILENAME"])."/"."arialbd.ttf";
		} else {
			$this->_font = $this->_PROCESSOR_LOCATION_."/"."arialbd.ttf";
		}
		
		if (isset($_SERVER['WINDIR']) && file_exists($_SERVER['WINDIR']))
			$this->_font = $_SERVER['WINDIR']."\Fonts\arialbd.ttf";
		
		$this->setSymblogy($encoding);
		$this->setHexColor("#000000","#FFFFFF");
	}
	
	
	/**
	* Function setFont:
	* 
	* - Sets the font needed for barcodes.
	* - Searches for Windows system and sets according
	*
	* @param string 	font 		The name of the font
	* @param boolean 	autolocate 	If system should look for it on reserved folders
	*
	*/
	function setFont($font, $autolocate = false) {
		$this->_font = $font;
		if($autolocate) {
			$this->_font = $this->_PROCESSOR_LOCATION_."/".$font.".ttf";
		
			if (isset($_SERVER['WINDIR']) && file_exists($_SERVER['WINDIR']))
				$this->_font = $_SERVER['WINDIR']."\Fonts\\".$font.".ttf";
		}
	}
	
	/**
	* Function setSymblogy:
	* - Sets the font needed for barcodes.
	* - Searches for Windows system and sets according
	*
	* @param string		encoding 	The codebar type to set
	*
	*/
	function setSymblogy($encoding = "EAN-13") {
		$this->_encode = strtoupper($encoding);
	}
	
	/**
	* Function setHexColor:
	* - Sets the two color necesseray for .
	* - Searches for Windows system and sets according
	*
	* @param string		color 		The foreground color to set (In hexadecimal values)
	* @param string		bgcolor 	The Background color to set (In hexadecimal values)
	*
	*/
	function setHexColor($color, $bgcolor) {
		$this->setColor(hexdec(substr($color, 1, 2)), hexdec(substr($color, 3, 2)), hexdec(substr($color, 5, 2)));
		$this->setBGColor(hexdec(substr($bgcolor, 1, 2)), hexdec(substr($bgcolor, 3, 2)), hexdec(substr($bgcolor, 5, 2)));
	}
	
	/**
	* Function setColor:
	* - Sets the foreground color
	*
	* @param string		red 		The red value
	* @param string		green 		The green value
	* @param string		blue 		The blue value
	*
	*/
	function setColor($red, $green, $blue) {
		$this->_color = array($red, $green, $blue);
	}
	
	/**
	* Function setBGColor:
	* - Sets the background color
	*
	* @param string		red 		The red value
	* @param string		green 		The green value
	* @param string		blue 		The blue value
	*
	*/
	function setBGColor($red, $green, $blue) {
		$this->_bgcolor = array($red, $green, $blue);
	}
	
	/**
	* Function setScale:
	* Sets the barcode scale value, used to multiply with HEIGHT
	* and the width, depending of how many bars the code will generate
	*
	* @param integer	scale 		The value of the scale
	*
	*/
	function setScale($scale) {
		$this->_scale = $scale;
	}
	
	/**
	* Function setFormat:
	* Sets the resulting file format (JPG, PNG, GIF)
	*
	* @param string		format 		File format (JPG, PNG, GIF)
	*
	*/
	function setFormat($format) {
		$this->_format = strtolower($format);
	}
	
	/**
	* Function setHeight:
	* Sets the height of barcode image (multiplied by scale)
	*
	* @param integer	height	height in pixels
	*
	*/
	function setHeight($height) {
		$this->_height = $height;
	}
	
	/**
	* Function setNarrow2Wide:
	* Sets the space between the narrow and wide bars
	* of the generated barcode
	*
	* @param integer 	n2w 	space in pixels
	*
	*/
	function setNarrow2Wide($n2w) {
		if($n2w<2)
			$n2w=3;
		$this->_n2w = $n2w;
	}
	
	/**
	* Function error:
	* Outputs an error message as image or as message
	*
	* @param boolean 	either if output is as image or not
	*
	* @return image 	Return the error message in text or image via header
	*
	*/
	function error($asimg = false) {
		if(empty($this->_error))
			return "";
		if(!$asimg)
			return $this->_error;

		header("Content-type: image/png");
		$im		= imagecreate(250, 100);
		$color 	= imagecolorallocate($im, 255, 255, 255);
		$color 	= imagecolorallocate($im, 0, 0, 0);
		imagettftext($im, 10, 0, 5, 50, $color, $this->_font, wordwrap($this->_error, 30, "\n"));
		imagepng($im);
		imagedestroy($im);
	}
	
	/**
	* Function BarCode_link:
	* Outputs a string for the barcode generation
	*
	* @param string	 	The type of barcode generating
	* @param string	 	The data to encode in barcode
	* @param integer 	The desired height of barcode
	* @param integer 	The scale (multiplier) for height and also the width scale
	* @param string	 	The hexadecimal code for backgroundcolor
	* @param string	 	The hexadecimal code for foregroundcolor
	*
	* @return string The link to generate the requested barcode on the fly
	*
	*/
	function BarCode_link($encoding, $bardata, $height = 50, $scale = 2, $bgcolor = "#FFFFFF", $barcolor = "#000000") {
		return $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=$encoding&bdata=".urlencode($bardata)."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder=&type=png&Genrate=Create+Barcode";
	}
	
	/**
	* Function BarCode_dl:
	* Outputs a string for the barcode generation and forced download
	*
	* @param string 	The type of barcode generating
	* @param string 	The data to encode in barcode
	* @param string 	The name for desired filename
	* @param string 	The type of file (jpg, png, gif)
	* @param integer 	The desired height of barcode
	* @param integer 	The scale (multiplier) for height and also the width scale
	* @param string 	The hexadecimal code for backgroundcolor
	* @param string 	The hexadecimal code for foregroundcolor
	*
	* @return string 	The link to generate the requested barcode and force download
	*
	**/
	function BarCode_dl($encoding, $bardata, $file, $type = "png", $height = 50, $scale = 2, $bgcolor = "#FFFFFF", $barcolor = "#000000") {
		return $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=$encoding&bdata=".urlencode($bardata)."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder=&type=$type&Genrate=Create+Barcode";
	}
	
	/**
	* Function BarCode_save:
	* Sets appropriate values, creates de desired barcode and saves it to
	* the specified folder. The folder should be relative to the scripts own folder
	* you can also use for example (./) to write the file in to the actual scripts folder
	*
	* @param string	 	The type of barcode generating
	* @param string	 	The data to encode in barcode
	* @param string	 	The name for desired filename
	* @param string	 	The desired folder to save the file (should be relative to script folder)
	* @param string	 	The type of file (jpg, png, gif)
	* @param integer 	The desired height of barcode
	* @param integer 	The scale (multiplier) for height and also the width scale
	* @param string	 	The hexadecimal code for backgroundcolor
	* @param string	 	The hexadecimal code for foregroundcolor
	*
	*/
	function BarCode_save($encoding, $bardata, $file, $folder, $type = "png", $height = 50, $scale = 2, $bgcolor = "#FFFFFF", $barcolor = "#000000") {
		$this->setSymblogy($encoding);
		$this->setHeight($height);
		$this->setScale($scale);
		$this->setHexColor($barcolor, $bgcolor);
		$this->genBarCode($bardata, $type, $file, $folder);
	}
	
	/**
	* Function QRCode_link:
	* Outputs a string for the qrcode generation
	*
	* @param string	 	The type of content of qrcode generating
	* @param array	 	The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param integer 	The desired height of qrcode. Because is a square is also the width.
	* @param integer 	The scale (multiplier) for height and also the width
	* @param string 	Background color
	* @param string 	Foreground color
	* @param string 	Error Correction Level (E, C, L, H)
	* @param boolean 	If QRCode should have margin or not
	*
	*/
	function QRCode_link($qrdata_type, $data, $height = 50, $scale = 2, $bgcolor = "#FFFFFF", $barcolor = "#000000", $ECLevel = "L", $margin = true) {
		switch ($qrdata_type) {
			case "link":
				return  $this->_PROCESSOR_LOCATION_ ."barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_link_link=".urlencode($data[0])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "sms":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_sms_phone=".urlencode($data[0])."&qr_sms_msg=".urlencode($data[1])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "phone":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_phone_phone=".urlencode($data[0])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "vcard":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_vc_N=".urlencode($data[0])."&qr_vc_C=".urlencode($data[1])."&qr_vc_J=".urlencode($data[2])."&qr_vc_W=".urlencode($data[3])."&qr_vc_H=".urlencode($data[4])."&qr_vc_AA=".urlencode($data[5])."&qr_vc_ACI=".urlencode($data[6])."&qr_vc_AP=".urlencode($data[7])."&qr_vc_ACO=".urlencode($data[8])."&qr_vc_E=".urlencode($data[9])."&qr_vc_U=".urlencode($data[10])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "mecard":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_mec_N=".urlencode($data[0])."&qr_mec_P=".urlencode($data[1])."&qr_mec_E=".urlencode($data[2])."&qr_mec_U=".urlencode($data[3])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "email":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_email_add=".urlencode($data[0])."&qr_email_sub=".urlencode($data[1])."&qr_email_msg=".urlencode($data[2])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "wifi":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_wifi_ssid=".urlencode($data[0])."&qr_wifi_type=".urlencode($data[1])."&qr_wifi_pass=".urlencode($data[2])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "geo":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_geo_lat=".urlencode($data[0])."&qr_geo_lon=".urlencode($data[1])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			default:
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_btext_text=".urlencode($data[0])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder&type=png&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
		}
	}
	
	/**
	* Function QRCode_dl:
	* Outputs a string for the qrcode generation and forced download
	*
	* @param string		The type of content of qrcode generating
	* @param array		The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param string	 	The name for desired filename
	* @param string	 	The name format of file (JPG, ONG or GIF)
	* @param integer 	The desired height of qrcode. Because is a square is also the width.
	* @param integer 	The scale (multiplier) for height and also the width
	* @param string 	Background color
	* @param string 	Foreground color
	* @param string 	Error Correction Level (E, C, L, H)
	* @param boolean 	If QRCode should have margin or not
	*
	*/
	function QRCode_dl($qrdata_type, $data, $file, $type = "png", $height = 50, $scale = 2, $bgcolor = "#FFFFFF", $barcolor = "#000000", $ECLevel = "L", $margin = true) {
		switch ($qrdata_type) {
			case "link":
				return  $this->_PROCESSOR_LOCATION_ ."barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_link_link=".urlencode($data[0])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "sms":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_sms_phone=".urlencode($data[0])."&qr_sms_msg=".urlencode($data[1])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "phone":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_phone_phone=".urlencode($data[0])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "vcard":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_vc_N=".urlencode($data[0])."&qr_vc_C=".urlencode($data[1])."&qr_vc_J=".urlencode($data[2])."&qr_vc_W=".urlencode($data[3])."&qr_vc_H=".urlencode($data[4])."&qr_vc_AA=".urlencode($data[5])."&qr_vc_ACI=".urlencode($data[6])."&qr_vc_AP=".urlencode($data[7])."&qr_vc_ACO=".urlencode($data[8])."&qr_vc_E=".urlencode($data[9])."&qr_vc_U=".urlencode($data[10])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "mecard":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_mec_N=".urlencode($data[0])."&qr_mec_P=".urlencode($data[1])."&qr_mec_E=".urlencode($data[2])."&qr_mec_U=".urlencode($data[3])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "email":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_email_add=".urlencode($data[0])."&qr_email_sub=".urlencode($data[1])."&qr_email_msg=".urlencode($data[2])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "wifi":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_wifi_ssid=".urlencode($data[0])."&qr_wifi_type=".urlencode($data[1])."&qr_wifi_pass=".urlencode($data[2])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			case "geo":
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_geo_lat=".urlencode($data[0])."&qr_geo_lon=".urlencode($data[1])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
				break;
				
			default:
				return  $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=QRCODE&bdata=&qrdata_type=$qrdata_type&qr_btext_text=".urlencode($data[0])."&height=$height&scale=$scale&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder&type=$type&Genrate=Create+Barcode&ECLevel=$ECLevel&margin=$margin";
		}
	}
	
	/**
	* Function QRCode_save:
	* Sets appropriate values, creates de desired qrcode and saves it to
	* the specified folder. The folder should be relative to the scripts own folder
	* you can also use for example (./) to write the file in to the actual scripts folder
	*
	* @param string		The type of content of qrcode generating
	* @param array	 	The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param string	 	The name for desired filename
	* @param string	 	The desired folder to save the file (should be relative to script folder)
	* @param integer 	The desired height of qrcode. Because is a square is also the width.
	* @param integer 	The scale (multiplier) for height and also the width
	* @param string 	Background color
	* @param string 	Foreground color
	* @param string 	Error Correction Level (E, C, L, H)
	* @param boolean 	If QRCode should have margin or not
	*
	*/
	function QRCode_save($qrdata_type, $data, $file, $folder, $type = "png", $height = 50, $scale = 2, $bgcolor = "#FFFFFF", $barcolor = "#000000", $ECLevel = "L", $margin = true) {
		switch ($qrdata_type) {
			case "link":
				$this->qr_link($data[0]);
				break;
				
			case "sms":
				$this->qr_sms($data[0], $data[1]);
				break;
				
			case "phone":
				$this->qr_phone_number($data[0]);
				break;
				
			case "vcard":
				$this->qr_vcard($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10]);
				break;
				
			case "mecard":
				$this->qr_mecard($data[0], $data[1], $data[2], $data[3]);
				break;
				
			case "email":
				$this->qr_email($data[0], $data[1], $data[2]);
				break;
				
			case "wifi":
				$this->qr_wifi($data[0], $data[1], $data[2]);
				break;
				
			case "geo":
				$this->qr_sms($data[0], $data[1]);
				break;
				
			case "text":
				$this->qr_text($data[0]);
				break;
		}
		
		$this->setSymblogy("QRCODE");
		$this->setHeight($height);
		$this->setScale($scale);
		$this->setHexColor($barcolor, $bgcolor);
		$this->ECLevel 	= $ECLevel;
		$this->margin 	= $margin;
		$this->genBarCode("", $type, $file, $folder);
	}
	
	
	/**
	* Function DataMatrix_link:
	* Outputs a string for the DATAMATRIX code generation
	*
	* @param array	 	The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param integer 	The desired height of the code in pixels.
	* @param integer 	The desired width of the code in pixels.
	* @param integer 	The desired margin of the image in pixels.
	* @param string 	Background color.
	* @param string 	Foreground color.
	*
	*/
	function DataMatrix_link($bardata, $height = 100, $width = 100, $margin = 10, $bgcolor = "#FFFFFF", $barcolor = "#000000") {
		return $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=DATAMATRIX&bdata=".urlencode($bardata)."&height=$height&scale=$width&margin=$margin&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=&folder=&type=png&Genrate=Create+Barcode";
	}
	
	/**
	* Function DataMatrix_dl:
	* Outputs a string for the DATAMATRIX generation and forced download
	*
	* @param array		The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param string	 	The name for desired filename
	* @param integer 	The desired height of code in pixels.
	* @param integer 	The desired width of code in pixels.
	* @param integer 	The desired margin of code in pixels.
	* @param string 	Background color
	* @param string 	Foreground color
	*
	*/
	function DataMatrix_dl($bardata, $file, $height = 100, $width = 100, $margin = 10, $bgcolor = "#FFFFFF", $barcolor = "#000000") {
		return $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=DATAMATRIX&bdata=".urlencode($bardata)."&height=$height&scale=$width&margin=$margin&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&file=$file&folder=&type=png&Genrate=Create+Barcode";
	}
	
	/**
	* Function DataMatrix_save:
	* Sets appropriate values, creates de desired DATAMATRIX and saves it to
	* the specified folder. The folder should be relative to the scripts own folder
	* you can also use for example (./) to write the file in to the actual scripts folder
	*
	* @param array	 	The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param string	 	The name for desired filename
	* @param string	 	The desired folder to save the file (should be relative to script folder)
	* @param integer 	The desired height of code in pixels.
	* @param integer 	The desired width of code in pixels.
	* @param integer 	The desired margin of code in pixels.
	* @param string 	Background color
	* @param string 	Foreground color
	*
	*/
	function DataMatrix_save($bardata, $file, $folder, $height = 100, $width = 100, $margin = 10, $bgcolor = "#FFFFFF", $barcolor = "#000000") {
		$this->setSymblogy("DATAMATRIX");
		$this->setHeight($height);
		$this->setScale($width);
		$this->margin_size = $margin;
		$this->setHexColor($barcolor, $bgcolor);
		$this->genBarCode($bardata, "png", $file, $folder);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	* Function PDF417_link:
	* Outputs a string for the PDF417 code generation
	*
	* @param array	 	The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param integer 	The desired height of the code in pixels.
	* @param integer 	The desired width of the code in pixels.
	* @param integer 	The desired margin of the image in pixels.
	* @param string 	Background color.
	* @param string 	Foreground color.
	* @param string 	Error Correction Level (from 1 to 8) (-1 is auto)
	*
	*/
	function PDF417_link($bardata, $height = 50, $width = 2, $margin = 10, $bgcolor = "#FFFFFF", $barcolor = "#000000", $ECLevel = -1) {
		return $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=PDF417&bdata=".urlencode($bardata)."&height=$height&scale=$width&margin=$margin&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&ECLevel=$ECLevel&file=&folder=&type=png&Genrate=Create+Barcode";
	}
	
	/**
	* Function PDF417_dl:
	* Outputs a string for the PDF417 generation and forced download
	*
	* @param array		The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param string	 	The name for desired filename
	* @param integer 	The desired height of code in pixels.
	* @param integer 	The desired width of code in pixels.
	* @param integer 	The desired margin of code in pixels.
	* @param string 	Background color
	* @param string 	Foreground color
	* @param string 	Error Correction Level (from 1 to 8) (-1 is auto)
	*
	*/
	function PDF417_dl($bardata, $file, $height = 100, $width = 100, $margin = 10, $bgcolor = "#FFFFFF", $barcolor = "#000000", $ECLevel = -1) {
		return $this->_PROCESSOR_LOCATION_ . "barcode.processor.php?encode=PDF417&bdata=".urlencode($bardata)."&height=$height&scale=$width&margin=$margin&bgcolor=".urlencode($bgcolor)."&color=".urlencode($barcolor)."&ECLevel=$ECLevel&file=$file&folder=&type=png&Genrate=Create+Barcode";
	}
	
	/**
	* Function PDF417_save:
	* Sets appropriate values, creates de desired PDF417 and saves it to
	* the specified folder. The folder should be relative to the scripts own folder
	* you can also use for example (./) to write the file in to the actual scripts folder
	*
	* @param array	 	The data to encode. Mutant array, to hold different values for diferent types of qrcode
	* @param string	 	The name for desired filename
	* @param string	 	The desired folder to save the file (should be relative to script folder)
	* @param integer 	The desired height of code in pixels.
	* @param integer 	The desired width of code in pixels.
	* @param integer 	The desired margin of code in pixels.
	* @param string 	Background color
	* @param string 	Foreground color
	* @param string 	Error Correction Level (from 1 to 8) (-1 is auto)
	*
	*/
	function PDF417_save($bardata, $file, $folder, $height = 100, $width = 100, $margin = 10, $bgcolor = "#FFFFFF", $barcolor = "#000000", $ECLevel = -1) {
		$this->setSymblogy("PDF417");
		$this->setHeight($height);
		$this->setScale($width);
		$this->margin_size = $margin;
		$this->setHexColor($barcolor, $bgcolor);
		$this->genBarCode($bardata, "png", $file, $folder);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	* Function genBarCode:
	* Main function to star barcode generation. For QRcodes the first parameter is not used,
	* because for QRCode the data is set before genBarCode() is called. For all other barcodes,
	* some validation is made, and then the specific barcode function is called.
	*
	* @param string	 	The content/data of barcode generating
	* @param string	 	Type of filetype (JPG, PNG, GIF)
	* @param string	 	The name for desired filename
	* @param string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function genBarCode($barnumber, $format = "png", $file = "", $folder = "") {
		$this->setFormat($format);
		
		// CODE QR-CODE
		if($this->_encode == "QRCODE") {
			$this->_qrBarcode($this->_scale, $file, $folder, $format, $this->ECLevel, $this->margin);
			
		// CODE UPC-A
		} else if($this->_encode == "UPC-A") {
			if(strlen($barnumber) > 12) {
				$this->_error = "Barcode number must be less than 13 characters.";
				return false;
			} else if (preg_match('/[^0-9]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE UPC-A. Only digits [0-9]";
				return false;
			}
			$this->_eanBarcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE UPC-E
		} else if($this->_encode == "UPC-E") {
			if(strlen($barnumber) > 6) {
				$this->_error = "Barcode number must be less than 7 characters.";
				return false;
			} else if (preg_match('/[^0-9]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE UPC-E. Only digits [0-9]";
				return false;
			}
			$this->_upceBarcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE EAN-8
		} else if($this->_encode == "EAN-8") {
			if(strlen($barnumber) > 8) {
				$this->_error = "Barcode number must be less than 8 characters.";
				return false;
			} else if (preg_match('/[^0-9]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE EAN-8. Only digits [0-9]";
				return false;
			}
			$this->_ean8Barcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE EAN-13
		} else if($this->_encode == "EAN-13") {
			if(strlen($barnumber) > 13) {
				$this->_error = "Barcode number must be less than 13 characters.";
				return false;
			} else if (preg_match('/[^0-9]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE EAN-13. Only digits [0-9]";
				return false;
			}
			$this->_eanBarcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE 39
		} else if($this->_encode == "CODE39") { 
			if (preg_match('/[^A-Z0-9\-.$\/+% ]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE 39. Only [A-Z] [0-9] [-. $/+%]";
				return false;
			}
			$this->_c39Barcode($barnumber, $this->_scale, $file, false, $folder);
			
		// CODE 93
		} else if($this->_encode == "CODE93") { 
			if (preg_match('/[^A-Z0-9\-.$\/+% ]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE 93. Only [A-Z] [0-9] [-. $/+%]";
				return false;
			}
			$this->_c93Barcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE 128
		} else if($this->_encode == "CODE128") { 
			if (preg_match('/[^a-zA-Z0-9\-.$\/+% !"#&\',()\*:;<=>?@\[\]\^_{}~\\\`]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE 128B. Only [a-z] [A-Z] [0-9] [!\"#$%&'()*+,-./:;<=>?@[]\^_`{}]";
				return false;
			}
			$this->_c128Barcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE ISBN
		} else if($this->_encode == "ISBN") {
			if(strlen($barnumber) > 13 || strlen($barnumber) < 12) {
				$this->_error = "Barcode number must have 12 characters.";
				return false;
			} else  if (preg_match('/[^0-9]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE ISBN. Only digits [0-9]";
				return false;
			} else if(substr($barnumber, 0, 3) != "978") {
				$this->_error = "Not an ISBN barcode number. Must be start with 978";
				return false;
			}
			$this->_eanBarcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE POSTNET
		} else if($this->_encode == "POSTNET") {
			if (preg_match('/[^0-9]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE POSTNET. Only digits [0-9]";
				return false;
			}
			$this->_postBarcode($barnumber, $this->_scale, $file, $folder);
			
		// CODE CODABAR
		} else if($this->_encode == "CODABAR") {
			if (preg_match('/[^0-9\-$:\/.+]/', $barnumber)) {
				$this->_error = "Invalid chars for CODE CODABAR. Only digits [0-9] and [-$:/.+]";
				return false;
			}
			$this->_codaBarcode($barnumber, $this->_scale, $file, $folder);
		
		
		// CODE DATAMATRIX
		} else if($this->_encode == "DATAMATRIX") {
			$this->_generate_DATAMATRIX($barnumber, $this->_scale, $this->_height, $this->margin_size, $file, $folder);
		
		
		
		// CODE PDF417
		} else if($this->_encode == "PDF417") {
			$this->_generate_PDF417($barnumber, $this->_scale, $this->_height, $this->margin_size, $file, $folder, $this->ECLevel);
		}
		
	}
	
	/**
	* Function _c93Encode:
	* Starts encoding in CODE93 wich has the following structure:
	* - A start character, represented by an asterisk (*) character.
	* - Any number of the allowed characters (0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%$%/+*).
	* - The "C" and "K" checksum digits calculated as described below in the comments and encoded using the table below. 
	* - A stop character, which is a second asterisk (*) character. 
	*
	* @param 	string	 The content/data of barcode generating
	*
	* @return 	string 	Returns binary of already encoded data into barcode
	*
	*/
	function _c93Encode($barnumber) {
		
		// Characters table and binary values
		$encTable	= array("0" => "100010100",
							"1" => "101001000",
							"2" => "101000100",
							"3" => "101000010",
							"4" => "100101000",
							"5" => "100100100",
							"6" => "100100010",
							"7" => "101010000",
							"8" => "100010010",
							"9" => "100001010",
							"A" => "110101000",
							"B" => "110100100",
							"C" => "110100010",
							"D" => "110010100",
							"E" => "110010010",
							"F" => "110001010",
							"G" => "101101000",
							"H" => "101100100",
							"I" => "101100010",
							"J" => "100110100",
							"K" => "100011010",
							"L" => "101011000",
							"M" => "101001100",
							"N" => "101000110",
							"O" => "100101100",
							"P" => "100010110",
							"Q" => "110110100",
							"R" => "110110010",
							"S" => "110101100",
							"T" => "110100110",
							"U" => "110010110",
							"V" => "110011010",
							"W" => "101101100",
							"X" => "101100110",
							"Y" => "100110110",
							"Z" => "100111010",
							"-" => "100101110",
							"." => "111010100",
							" " => "111010010",
							"$" => "111001010",
							"/" => "101101110",
							"+" => "101110110",
							"%" => "110101110",
							"$" => "100100110",
							"%" => "111011010",
							"/" => "111010110",
							"+" => "100110010",
							"*" => "101011110"
						);

		$mfcStr		= "";
		
		$arr_key=array_keys($encTable);
		
		// Calculating C And K
		for($j=0; $j<2; $j++) {
			$sum = 0;
			for($i=strlen($barnumber); $i>0; $i--) {
				$num = $barnumber[strlen($barnumber) - $i];
				if(preg_match("/[A-Z]+/", $num))
					$num = ord($num) - 55;
				else if($num == '-')
					$num = 36;
				else if($num == '.')
					$num = 37;
				else if($num == ' ')
					$num = 38;
				else if($num == '$')
					$num = 39;
				else if($num == '/')
					$num = 40;
				else if($num == '+')
					$num = 41;
				else if($num == '%')
					$num = 42;
				else if($num == '*')
					$num = 43;
					
				$sum += $i * $num;	
			}
			$barnumber .= trim($arr_key[(int)($sum % 47)]);
		}
		
		// Terminating barcode
		$barnumber = "*".$barnumber."*";
		
		// Converting to binary
		for($i=0; $i<strlen($barnumber); $i++) {
			$mfcStr .= $encTable[$barnumber[$i]];
		}
		$mfcStr .= '1';
		
		// Returns binary barcode
		return $mfcStr;
	}
	
	
	/**
	* Function _c93Barcode:
	* Asks for _c93Encode to get binary of encoded data, and the starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _c93Barcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// To avoid output error on lowercase, we converto to upper
		$barnumber 	= strtoupper($barnumber);
		
		// Asks for encoded data
		$bars 		= $this->_c93Encode($barnumber);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scale if too low or negative
		if ($scale < 1) 
			$scale = 2;
		
		// Sets total height
		$total_y 	= (double)$scale * $this->_height + 10 * $scale;
		
		// Sets margin around barcode
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
			
		// Count total width
		$xpos 		= 0;
		$xpos 		= $scale * strlen($bars) + 2 * $scale * 10; 
		
		// Sets total width (according to barcode lenght)
		$total_x 	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'] + $scale * 10;
		
		// Sets final height (also space for btoom text)
		$height		= floor($total_y - ($scale * 20));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im			= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);

		for($i=0;$i<strlen($bars);$i++) {
			$h		= $height;
			$val	= $bars[$i];

			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'],$xpos+$scale-1, $h, $bar_color);
				
			$xpos	+= $scale;
		}
		
		// Ads text to image
		$font_arr	= imagettfbbox($scale * 10, 0, $this->_font, $barnumber);
		$x			= floor($total_x - (int)$font_arr[0] - (int)$font_arr[2] + $scale * 10) / 2;	
		imagettftext($im, $scale * 10, 0, $x, $height2, $bar_color, $this->_font, $barnumber);

		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	
	/**
	* Function _c39Encode:
	* Starts encoding in CODE39 wich has the following structure:
	* - A start character, represented by an asterisk (*) character.
	* - Any number of the allowed characters (0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%$%/+*).
	* - An optional checksum digit (@todo final implement on API) calculated is described below in the comments and encoded using the table below. 
	* - A stop character, which is a second asterisk (*) character. 
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	boolean	 	Either use or not the checksum digit
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _c39Encode($barnumber, $checkdigit = false) {
		
		$encTable = array(	"0" => "NNNWWNWNN",
							"1" => "WNNWNNNNW",
							"2" => "NNWWNNNNW",
							"3" => "WNWWNNNNN",
							"4" => "NNNWWNNNW",
							"5" => "WNNWWNNNN",
							"6" => "NNWWWNNNN",
							"7" => "NNNWNNWNW",
							"8" => "WNNWNNWNN",
							"9" => "NNWWNNWNN",
							"A" => "NNWWNNWNN",
							"B" => "NNWNNWNNW",
							"C" => "WNWNNWNNN",
							"D" => "NNNNWWNNW",
							"E" => "WNNNWWNNN",
							"F" => "NNWNWWNNN",
							"G" => "NNNNNWWNW",
							"H" => "WNNNNWWNN",
							"I" => "NNWNNWWNN",
							"J" => "NNNNWWWNN",
							"K" => "WNNNNNNWW",
							"L" => "NNWNNNNWW",
							"M" => "WNWNNNNWN",
							"N" => "NNNNWNNWW",
							"O" => "WNNNWNNWN",
							"P" => "NNWNWNNWN",
							"Q" => "NNNNNNWWW",
							"R" => "WNNNNNWWN",
							"S" => "NNWNNNWWN",
							"T" => "NNNNWNWWN",
							"U" => "WWNNNNNNW",
							"V" => "NWWNNNNNW",
							"W" => "WWWNNNNNN",
							"X" => "NWNNWNNNW",
							"Y" => "WWNNWNNNN",
							"Z" => "NWWNWNNNN",
							"-" => "NWNNNNWNW",
							"." => "WWNNNNWNN",
							" " => "NWWNNNWNN",
							"$" => "NWNWNWNNN",
							"/" => "NWNWNNNWN",
							"+" => "NWNNNWNWN",
							"%" => "NNNWNWNWN",
							"*" => "NWNNWNWNN"
						);

		$mfcStr		= "";
		$widebar	= str_pad("", $this->_n2w, "1", STR_PAD_LEFT);
		$widespc	= str_pad("", $this->_n2w, "0", STR_PAD_LEFT);
		
		// Calculating checksum digit ?
		if($checkdigit == true) {
			$arr_key = array_keys($encTable);
			for($i=0; $i<strlen($barnumber); $i++) {
				$num = $barnumber[$i];
				if(preg_match("/[A-Z]+/", $num))
					$num = ord($num) - 55;
				else if($num == '-')
					$num = 36;
				else if($num == '.')
					$num = 37;
				else if($num == ' ')
					$num = 38;
				else if($num == '$')
					$num = 39;
				else if($num=='/')
					$num = 40;
				else if($num == '+')
					$num = 41;
				else if($num == '%')
					$num = 42;
				else if($num == '*')
					$num = 43;
				$sum += $num;	
			}	
			$barnumber .= trim($arr_key[(int)($sum % 43)]);
		}
		
		// Terminating barcode
		$barnumber = "*".$barnumber."*";
		
		// Converting barcode
		for($i=0; $i<strlen($barnumber); $i++) {
			$tmp = $encTable[$barnumber[$i]];
			$bar = true;
			for($j=0; $j<strlen($tmp); $j++) {
				if($tmp[$j] == 'N' && $bar)
					$mfcStr .= '1';
				else if($tmp[$j] == 'N' && !$bar)
					$mfcStr .= '0';
				else if($tmp[$j] == 'W' && $bar)
					$mfcStr .= $widebar;
				else if($tmp[$j] == 'W' && !$bar)
					$mfcStr .= $widespc;
				$bar = !$bar;
			}
			$mfcStr .= '0';
		}
		
		// Returns converted barcode
		return $mfcStr;
	}
	
	/**
	* Function _c39Barcode:
	* Asks for _c39Encode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	boolean	 	Either use or not the checksum digit
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _c39Barcode($barnumber, $scale = 1, $file = "", $checkdigit = false, $folder = "") {
		
		// Ask for encoded data
		$bars = $this->_c39Encode($barnumber, $checkdigit);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scales
		if ($scale < 1) $scale = 2;
		
		// Sets total height
		$total_y 	= (double)$scale * $this->_height + 10 * $scale;
		
		// Sets margin around barcode
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// Count total width
		$xpos 		= 0;
		$xpos 		= $scale * strlen($bars) + 2 * $scale * 10; 

		// Sets total width (according to barcode lenght)
		$total_x	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'] + $scale * 10;
		
		// Sets final height (also space for btoom text)
		$height		= floor($total_y - ($scale * 20));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im 		= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		for($i=0; $i<strlen($bars); $i++) {
			$h 		= $height;
			$val 	= $bars[$i];

			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos + $scale-1, $h, $bar_color);
			$xpos += $scale;
		}
		
		// Adds text to image
		$font_arr 	= imagettfbbox ($scale * 10, 0, $this->_font, $barnumber);
		$x			= floor($total_x - (int)$font_arr[0] - (int)$font_arr[2] + $scale * 10) / 2;	
		imagettftext($im, $scale * 10, 0, $x, $height2, $bar_color, $this->_font, "* ".$barnumber." *");
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	/**
	* Function _c128Encode:
	* Starts encoding in CODE39 wich has the following structure (Bear in mind we will use CODE128-B):
	* - A start character, represented by a special character.
	* - Any number of the allowed characters (0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"#$%&'()*+,-/:;<=>?@[]\^_`{}).
	* - A checksum character calculated is described below in the comments and encoded using the table below. 
	* - A stop character, which is a special character. 
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	boolean	 	Either use or not the checksum digit
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _c128Encode($barnumber, $useKeys) {
		
		$encTable	= array("11011001100","11001101100","11001100110","10010011000","10010001100","10001001100","10011001000","10011000100","10001100100","11001001000","11001000100","11000100100","10110011100","10011011100","10011001110","10111001100","10011101100","10011100110","11001110010","11001011100","11001001110","11011100100","11001110100","11101101110","11101001100","11100101100","11100100110","11101100100","11100110100","11100110010","11011011000","11011000110","11000110110","10100011000","10001011000","10001000110","10110001000","10001101000","10001100010","11010001000","11000101000","11000100010","10110111000","10110001110","10001101110","10111011000","10111000110","10001110110","11101110110","11010001110","11000101110","11011101000","11011100010","11011101110","11101011000","11101000110","11100010110","11101101000","11101100010","11100011010","11101111010","11001000010","11110001010","10100110000","10100001100","10010110000","10010000110","10000101100","10000100110","10110010000","10110000100","10011010000","10011000010","10000110100","10000110010","11000010010","11001010000","11110111010","11000010100","10001111010","10100111100","10010111100","10010011110","10111100100","10011110100","10011110010","11110100100","11110010100","11110010010","11011011110","11011110110","11110110110","10101111000","10100011110","10001011110","10111101000","10111100010","11110101000","11110100010","10111011110","10111101110","11101011110","11110101110","11010000100","11010010000","11010011100","11000111010");
		
		// The start characters (we will use B)
		$start		= array("A" => "11010000100", "B" => "11010010000", "C" => "11010011100");
		$stop		= "11000111010";

		$sum		= 0;
		$mfcStr		= "";
		
		// Starts calculating checksum character (We will use B)
		if($useKeys == 'C') {
			for($i=0; $i<strlen($barnumber); $i+=2) {
				$val = substr($barnumber, $i, 2);
				if(is_int($val))
					$sum += ($i + 1) * (int)($val);
				else if($barnumber == chr(129))
					$sum += ($i + 1) * 100;
				else if($barnumber == chr(130))
					$sum += ($i + 1) * 101;
				$mfcStr .= $encTable[$val];
			}
			
		} else {
			for($i=0; $i<strlen($barnumber); $i++) { 
				$num = ord($barnumber[$i]);
				if($num >= 32 && $num <= 126)
					$num = ord($barnumber[$i]) - 32;
				else if($num == 128)
					$num = 99;
				else if($num == 129)
					$num = 100;
				else if($num == 130)
					$num = 101;
				else if($num < 32 && $useKeys == 'A')
					$num = $num + 64;
				
				$sum 	+= ($i + 1) * $num;
				$mfcStr .= $encTable[$num];
			}
		}

		if($useKeys == 'A')
			$check = ($sum + 103) % 103;
		if($useKeys == 'B')
			$check = ($sum + 104) % 103;
		if($useKeys == 'C')
			$check = ($sum + 105) % 103;
		
		// Return encoded data
		return $start[$useKeys].$mfcStr.$encTable[$check].$stop."11";
	}
	
	/**
	* Function _c128Barcode:
	* Asks for _c128Encode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _c128Barcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// We set CODE128-B
		$useKeys = "B";
		
		// Asks for encoded data
		$bars = $this->_c128Encode($barnumber, $useKeys);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fizes scales
		if ($scale < 1) $scale = 2;
		
		// Calculates total height
		$total_y 	= (double)$scale * $this->_height + 10 * $scale;
		
		// Creates margin around barcode image
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// count total width
		$xpos 		= 0;
		$xpos 		= $scale * strlen($bars) + 2 * $scale * 10; 

		// Sets total width (according to barcode lenght)
		$total_x 	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'] + $scale * 10;
		
		// Sets final height (also space for bottom text)
		$height		= floor($total_y - ($scale * 20));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im			= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		for($i=0; $i<strlen($bars); $i++) {
			$h 		= $height;
			$val 	= strtoupper($bars[$i]);

			if($val==1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos + $scale - 1, $h, $bar_color);
			$xpos += $scale;
		}
		
		// Adds text to barcode image
		$font_arr	= imagettfbbox($scale * 10, 0, $this->_font, $barnumber);
		$x			= floor($total_x - (int)$font_arr[0] - (int)$font_arr[2] + $scale * 10) / 2;	
		imagettftext($im, $scale * 10, 0, $x, $height2, $bar_color, $this->_font, $barnumber);
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	/**
	* Function _codaEncode:
	* Starts encoding in CODABAR wich has the following structure:
	* - A start character, represented by one of four choices (A, B, C or D) encoded from the table below
	* - An Inter-character gap space
	* - Any number of the allowed characters (0123456789-$:/.+), with a inter-character space between characters
	* - A stop character from 4 choices: A, B, C and D.
	*
	* @param 	string	 	The content/data of barcode generating
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _codaEncode($barnumber) {
		$encTable	= array("0000011", "0000110", "0001001", "1100000", "0010010", "1000010", "0100001", "0100100", "0110000", "1001000");
		$chrTable	= array("-" => "0001100", "$" => "0011000", ":" => "1000101", "/" => "1010001", "." => "1010100", "+" => "0011111", "A" => "0011010", "B" => "0001011", "C" => "0101001", "D" => "0001110");

		$mfcStr		= "";
		
		$widebar	= str_pad("", $this->_n2w, "1", STR_PAD_LEFT);
		$widespc	= str_pad("", $this->_n2w, "0", STR_PAD_LEFT);
		
		// Encoding data
		for($i=0; $i<strlen($barnumber); $i++) {
			if(preg_match("/[0-9]+/", $barnumber[$i]))
				$tmp = $encTable[(int)$barnumber[$i]];
			else
				$tmp = $chrTable[strtoupper(trim($barnumber[$i]))];

			$bar = true;
			
			for($j=0; $j<strlen($tmp); $j++) {
				if($tmp[$j] == '0' && $bar)
					$mfcStr .= '1';
				else if($tmp[$j] == '0' && !$bar)
					$mfcStr .= '0';
				else if($tmp[$j] == '1' && $bar)
					$mfcStr .= $widebar;
				else if($tmp[$j] == '1' && !$bar)
					$mfcStr .= $widespc;

				$bar = !$bar;
			}
			$mfcStr .= '0';
		}
		
		// Return encoded data
		return $mfcStr;
	}
	
	/**
	* Function _codaBarcode:
	* Asks for _codaEncode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _codaBarcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// Asks for encoded data (above)
		$bars = $this->_codaEncode($barnumber);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scales
		if ($scale < 1) $scale = 2;
		
		// Calculates total height
		$total_y 	= (double)$scale * $this->_height;
		
		// Creates margin around barcode image
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// count total width
		$xpos		= 0;
		$xpos		= $scale * strlen($bars); 
		
		// Sets total width (according to barcode lenght)
		$total_x	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'];
		
		// Sets final height (also space for bottom text)
		$height		= floor($total_y - ($scale * 10));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im			= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);

		for($i=0; $i<strlen($bars); $i++) {
			$h 		= $height;
			$val 	= strtoupper($bars[$i]);
			
			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos+$scale-1, $h, $bar_color);
			$xpos += $scale;
		}
		
		// Adds text to barcode image
		$x	= ($total_x - strlen($bars)) / 2;	
		imagettftext($im, $scale * 6, 0, $x, $height2, $bar_color, $this->_font, $barnumber);
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}

		imagedestroy($im);
	}
	
	
	/**
	* Function _postEncode:
	* Starts encoding in POSTNET wich has the following structure:
	* - A start bar, represented by a single 1
	* - 5, 9 or 11 digits (0123456789) encoded with the table below
	* - Check digit, encoded using encoding table below
	* - A stop bar, again encoded as a single 1
	*
	* @param 	string	 	The content/data of barcode generating
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _postEncode($barnumber) {
		$encTable	= array("11000", "00011", "00101", "00110", "01001", "01010", "01100", "10001", "10010", "10100");
		$sum		= 0;
		$encstr		= "";
		
		// Encoding data
		for($i=0; $i<strlen($barnumber); $i++) {
			$sum	+= (int)$barnumber[$i];
			$encstr	.= $encTable[(int)$barnumber[$i]];
		}
		
		// Creating check digit
		if($sum % 10 != 0)
			$check = (int)(10 - ($sum % 10));
		
		$encstr	.= $encTable[$check];
		$encstr	= "1".$encstr."1";
		
		// Return encoded data
		return $encstr;
	}
	
	/**
	* Function _postBarcode:
	* Asks for _postEncode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _postBarcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// Extra validation missed on top of class, to check if we have 5, 9 or 11 digits
		if(strlen($barnumber) == 5 || strlen($barnumber) == 9 || strlen($barnumber) == 11) {
			
		} else {
			$this->_error = "Not a valid postnet number. Must be 5, 9 or 11 digits.";
			return false;
		}
		
		// Asks for encoded data (above)
		$bars = $this->_postEncode($barnumber);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scales
		if ($scale < 1) $scale = 2;
		
		// Calculates total height
		$total_y 	= (double)$scale * $this->_height;
		
		// Creates margin around barcode image
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// count total width
		$xpos 		= 0;
		$xpos 		= $scale * strlen($bars) * 2; 

		// Sets total width (according to barcode lenght)
		$total_x 	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'];
		
		// Sets final height (also space for bottom text)
		$height		= floor($total_y - ($scale * 10));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im 		= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		for($i=0; $i<strlen($bars); $i++) {
			$val	= strtoupper($bars[$i]);
			$h		= $total_y-$space['bottom'];

			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos + $scale - 1, $height2, $bar_color);
			else
				imagefilledrectangle($im, $xpos, floor($height2 / 1.5), $xpos + $scale - 1, $height2, $bar_color);
			$xpos += 2 * $scale;
		}
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	/**
	* Function _upceEncode:
	* Starts encoding in UPC-E wich has the following structure:
	* - Start guard bars, always with a pattern bar+space+bar.
	* - Maximun of 6 digits (0123456789)
	* - Checkdigit
	* - Stop guard bars, always with a pattern bar+space+bar.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	string	 	The digit for encriptation
	* @param 	string	 	The checkdigit
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _upceEncode($barnumber, $encbit, $checkdigit) {
		
		$leftOdd	= array("0001101", "0011001", "0010011", "0111101", "0100011", "0110001", "0101111", "0111011", "0110111", "0001011");
		$leftEven	= array("0100111", "0110011", "0011011", "0100001", "0011101", "0111001", "0000101", "0010001", "0001001", "0010111");
		
		$encTable0	= array("EEEOOO", "EEOEOO", "EEOOEO", "EEOOOE", "EOEEOO", "EOOEEO", "EOOOEE", "EOEOEO", "EOEOOE", "EOOEOE");
		$encTable1	= array("OOOEEE", "OOEOEE", "OOEEOE", "OOEEEO", "OEOOEE", "OEEOOE", "OEEEOO", "OEOEOE", "OEOEEO", "OEEOEO");
		
		$guards		= array("bab", "ababa", "b");


		if ($encbit == 0)
			$encTable = $encTable0;
		else if ($encbit == 1)
			$encTable = $encTable1;
		else {
			$this->_error = "Not an UPC-E barcode number";	
			return false;
		}
		
		$mfcStr		= "";
		$prodStr	= "";
		$encTable[$checkdigit];
		
		// Encoding data
		for($i=0; $i<strlen($barnumber); $i++) {
			$num	= (int)$barnumber[$i];
			$even	= (substr($encTable[$checkdigit], $i, 1) == 'E');
			if(!$even)
				$mfcStr .= $leftOdd[$num];
			else
				$mfcStr .= $leftEven[$num];
		}
		
		// Return encoded data
		return $guards[0].$mfcStr.$guards[1].$guards[2];
	}
	
	/**
	* Function _upceBarcode:
	* Asks for _upceEncode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _upceBarcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// Arranges barnumber, encryption digit and checkdigit
		$barnumber	= $this->_checkDigit($barnumber, 7);
		$encbit		= $barnumber[0];
		$checkdigit = $barnumber[7];
		$barnumber	= substr($barnumber, 1, 6);
		
		// Asks for encoded data (above)
		$bars	= $this->_upceEncode($barnumber, $encbit, $checkdigit);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scales
		if ($scale < 1) $scale = 2;
		
		// Calculates total height
		$total_y 	= (double)$scale * $this->_height;
		
		// Creates margin around barcode image
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// Count total width
		$xpos		= 0;
		$xpos		= $scale * strlen($bars) + $scale * 12; 
		
		// Sets total width (according to barcode lenght)
		$total_x	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'] + ($scale * 6);
		
		// Sets final height (also space for bottom text)
		$height		= floor($total_y - ($scale * 10));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im			= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		for($i=0; $i<strlen($bars); $i++) {
			$h		= $height;
			$val	= strtoupper($bars[$i]);
			if(preg_match("/[a-z]/i", $val)) {
				$val 	= ord($val) - 65;
				$h		= $height2;
			}
			
			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos + $scale - 1, $h, $bar_color);
			$xpos += $scale;
		}
		
		// Adds text to barcode image
		imagettftext($im, $scale * 6, 0, $space['left'], $height, $bar_color, $this->_font, $encbit);
		$x	= $space['left'] + $scale * strlen($barnumber) + $scale * 6;	
		imagettftext($im, $scale * 6, 0, $x, $height2, $bar_color, $this->_font, $barnumber);
		$x	= $total_x - $space['left'] - $scale * 6;
		imagettftext($im, $scale * 6, 0, $x, $height, $bar_color, $this->_font, $checkdigit);
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	
	/**
	* Function _checkDigit:
	* Tries to go trought codebar data and creates a checksum value for EAN-8 compatbible barcodes
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	*
	* @return 	string 		Returns barnumber and checksum digit
	*
	*/
	function _checkDigit($barnumber, $number) {
		
		// The checksum working variable starts at zero
		$csumTotal = 0;

		// If the source message string is less than the required characters long, we fill zeros
		if(strlen($barnumber) < $number) {
			$barnumber = str_pad($barnumber, $number, "0", STR_PAD_LEFT);  
		}

		// Calculate the checksum value for the message
		for($i=0; $i<strlen($barnumber); $i++) {
			if($i % 2 == 0)
				$csumTotal = $csumTotal + (3 * intval($barnumber[$i]));
			else
				$csumTotal = $csumTotal + intval($barnumber[$i]);
		}
		
		// Calculate the checksum digit
		if($csumTotal % 10 == 0)
			$checksumDigit = '';
		else
			$checksumDigit = 10 - ($csumTotal % 10);
		
		// Returns barnumber and checksum digit
		return $barnumber.$checksumDigit;
	}
	
	
	/**
	* Function _ean8Encode:
	* Starts encoding in EAN-8 wich has the following structure:
	* - Start guard bars, always with a pattern bar+space+bar (101)
	* - Two number system characters, encoded as left-hand odd-parity characters 
	* - First two message characters, encoded as left-hand odd-parity characters.
	* - Center guard bars, encoded as 01010.
	* - Last three message characters, encoded as right-hand characters. 
	* - Check digit, encoded as right-hand character. 
	* - Right-hand guard bars, always with a pattern bar+space+bar encoded as 101
	*
	* @param 	string	 	The content/data of barcode generating
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _ean8Encode($barnumber) {
		
		$leftOdd	= array("0001101", "0011001", "0010011", "0111101", "0100011", "0110001", "0101111", "0111011", "0110111", "0001011");
		$leftEven	= array("0100111", "0110011", "0011011", "0100001", "0011101", "0111001", "0000101", "0010001", "0001001", "0010111");
		$rightAll	= array("1110010", "1100110", "1101100", "1000010", "1011100", "1001110", "1010000", "1000100", "1001000", "1110100");
		$encTable	= array("000000", "001011", "001101", "001110", "010011", "011001", "011100", "010101", "010110", "011010");
		
		$guards		= array("bab", "ababa", "bab");
		
		$mfcStr		= "";
		$prodStr	= "";
		
		// Encoding data
		for ($i=0; $i<strlen($barnumber); $i++) {
			$num = (int)$barnumber[$i];
			if($i < 4)  {
				$mfcStr .= $leftOdd[$num];
			} else if ($i >= 4) {
				$prodStr .= $rightAll[$num];
			}
		}
		
		// Return encoded data
		return $guards[0].$mfcStr.$guards[1].$prodStr.$guards[2];
	}
	
	/**
	* Function _ean8Barcode:
	* Asks for _ean8Encode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string		folder 		The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _ean8Barcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// Asks checkdigit
		$barnumber	= $this->_checkDigit($barnumber, 7);
		
		// Asks for encoded data (above)
		$bars		= $this->_ean8Encode($barnumber);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scales
		if ($scale < 1) $scale = 2;
		
		// Calculates total height
		$total_y 	= (double)$scale * $this->_height;
		
		// Creates margin around barcode image
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// Count total width
		$xpos 		= 0;
		$xpos		= $scale * strlen($bars); 
		
		// Sets total width (according to barcode lenght)
		$total_x	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'];
		
		// Sets final height (also space for bottom text)
		$height		= floor($total_y - ($scale * 10));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im			= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		for($i=0; $i<strlen($bars); $i++) {
			$h		= $height;
			$val	= strtoupper($bars[$i]);
			if(preg_match("/[a-z]/i", $val)) {
				$val	= ord($val) - 65;
				$h		= $height2;
			}
			
			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos + $scale - 1, $h, $bar_color);
			$xpos += $scale;
		}
		
		// Adds text to barcode image
		$str	= substr($barnumber, 0, 4);
		$x		= $space['left'] + $scale * strlen($barnumber);	
		imagettftext($im, $scale * 6, 0, $x, $height2, $bar_color, $this->_font, $str);
		
		$str	= substr($barnumber, 4, 4);
		$x		= $space['left'] + $scale*strlen($bars) / 1.65;
		imagettftext($im, $scale * 6, 0, $x, $height2, $bar_color, $this->_font, $str);
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	/**
	* Function _ean13CheckDigit:
	* Tries to go trought codebar data and creates a checksum value for EAN-13 compatbible barcodes
	*
	* @param 	string	 	The content/data of barcode generating
	*
	* @return 	string 		Returns barnumber and checksum digit
	*
	*/
	function _ean13CheckDigit($barnumber) {
		
		// The checksum working variable starts at zero
		$csumTotal = 0;

		// If the source message string is less than 12 characters long, we make it 12 characters
		if(strlen($barnumber) <= 12 ) {
			$barnumber = str_pad($barnumber, 13, "0", STR_PAD_LEFT);  
		}
		
		// Calculate the checksum value for the message
		for($i=0; $i<strlen($barnumber); $i++)  {
			if($i % 2 == 0 )
				$csumTotal = $csumTotal + intval($barnumber[$i]);
			else
				$csumTotal = $csumTotal + (3 * intval($barnumber[$i]));
		}
		
		// Calculate the checksum digit
		if($csumTotal % 10 == 0)
			$checksumDigit = '';
		else
			$checksumDigit = 10 - ($csumTotal % 10);
		
		// Returns barnumber and checksum digit
		return $barnumber.$checksumDigit;
	}
	
	
	/**
	* Function _eanEncode:
	* Starts encoding in EAN-13 wich has the following structure:
	* - Start guard bars, always with a pattern bar+space+bar encoded as 101
	* - The second character of the number system code, encoded as described below
	* - The five characters of the manufacturer code, encoded as described below
	* - Center guard pattern, encoded as 01010
	* - The five characters of the product code, encoded as right-hand characters, described below
	* - Check digit, encoded as a right-hand character, described below
	* - Right-hand guard bars, or end sentinel, encoded as 101
	*
	* @param 	string	 	The content/data of barcode generating
	*
	* @return 	string 		Returns binary of already encoded data into barcode
	*
	*/
	function _eanEncode($barnumber) {
		
		$leftOdd	= array("0001101", "0011001", "0010011", "0111101", "0100011", "0110001", "0101111", "0111011", "0110111", "0001011");
		$leftEven	= array("0100111", "0110011", "0011011", "0100001", "0011101", "0111001", "0000101", "0010001", "0001001", "0010111");
		$rightAll	= array("1110010", "1100110", "1101100", "1000010", "1011100", "1001110", "1010000", "1000100", "1001000", "1110100");
		$encTable	= array("000000", "001011", "001101", "001110", "010011", "011001", "011100", "010101", "010110", "011010");
		$guards		= array("bab", "ababa", "bab");
		
		$mfcStr		= "";
		$prodStr	= "";
		
		$encbit		= $barnumber[0];
		
		// Encoding data
		for($i=1; $i<strlen($barnumber); $i++) {
			$num = (int)$barnumber[$i];
			if($i < 7)  {
				$even = (substr($encTable[$encbit], $i - 1, 1) == 1);
				if(!$even)
					$mfcStr .= $leftOdd[$num];
				else
					$mfcStr .= $leftEven[$num];
			} else if($i >= 7) {
				$prodStr .= $rightAll[$num];
			}
		}
		
		// Return encoded data
		return $guards[0].$mfcStr.$guards[1].$prodStr.$guards[2];
	}
	
	/**
	* Function _eanBarcode:
	* Asks for _eanEncode to get encoded data, and then starts creating
	* the image of the requested barcode.
	*
	* @param 	string	 	The content/data of barcode generating
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	function _eanBarcode($barnumber, $scale = 1, $file = "", $folder = "") {
		
		// Asks checkdigit
		$barnumber	= $this->_ean13CheckDigit($barnumber);
		
		// Asks for encoded data (above)
		$bars		= $this->_eanEncode($barnumber);
		
		// If we have a file value, output will be a browser header image
		if(empty($file))
			header("Content-type: image/".$this->_format);
		
		// Fixes scales
		if($scale < 1) $scale = 2;
		
		// Calculates total height
		$total_y 	= (double)$scale * $this->_height;
		
		// Creates margin around barcode image
		$space 		= array('top' => 2 * $scale, 'bottom' => 2 * $scale, 'left' => 2 * $scale, 'right' => 2 * $scale);
		
		// Count total width
		$xpos		= 0;
		$xpos		= $scale * (114); 
		
		// Sets total width (according to barcode lenght)
		$total_x	= $xpos + $space['left'] + $space['right'];
		$xpos		= $space['left'] + ($scale * 6);
		
		// Sets final height (also space for bottom text)
		$height		= floor($total_y - ($scale * 10));
		$height2	= floor($total_y - $space['bottom']);
		
		// Starts creating image
		$im			= imagecreatetruecolor($total_x, $total_y);
		$bg_color 	= imagecolorallocate($im, $this->_bgcolor[0], $this->_bgcolor[1], $this->_bgcolor[2]);
		imagefilledrectangle($im, 0, 0, $total_x, $total_y, $bg_color); 
		$bar_color 	= imagecolorallocate($im, $this->_color[0], $this->_color[1], $this->_color[2]);
		
		for($i=0; $i<strlen($bars); $i++) {
			$h		= $height;
			$val	= strtoupper($bars[$i]);
			if(preg_match("/[a-z]/i",$val)) {
				$val	= ord($val) - 65;
				$h		= $height2;
			}
			if($this->_encode == "UPC-A" && ($i < 10 || $i > strlen($bars) - 13))
				$h = $height2;
				
			if($val == 1)
				imagefilledrectangle($im, $xpos, $space['top'], $xpos + $scale - 1, $h, $bar_color);
				
			$xpos += $scale;
		}
		
		// Adds text to barcode image
		if($this->_encode == "UPC-A")
			$str = substr($barnumber, 1, 1);
		else
			$str = substr($barnumber, 0, 1);
			
		imagettftext($im, $scale * 6, 0, $space['left'], $height, $bar_color, $this->_font, $str);
		
		if($this->_encode == "UPC-A")
			$str = substr($barnumber, 2, 5);
		else
			$str = substr($barnumber, 1, 6);
		
		$x = $space['left'] + $scale * strlen($barnumber) + $scale * 6;	
		imagettftext($im, $scale * 6, 0, $x, $height2, $bar_color, $this->_font, $str);
		
		if($this->_encode == "UPC-A")
			$str = substr($barnumber, 7, 5);
		else
			$str = substr($barnumber, 7, 6);
			
		$x = $space['left'] + $scale * strlen($bars) / 1.65 + $scale * 6;
		imagettftext($im, $scale * 6, 0, $x, $height2, $bar_color, $this->_font, $str);
		
		if($this->_encode == "UPC-A") {
			$str	= substr($barnumber, 12, 1);
			$x		= $total_x - $space['left'] - $scale * 6;
			imagettftext($im, $scale * 6, 0, $x, $height, $bar_color, $this->_font, $str);
		}
		
		// Outputs according to options:
		// - Filetype (png, jpg, gif)
		// - Show on screen, force download or saves to filesystem
		if($this->_format == "png") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagepng($im);
			} else if (!empty($folder)) {
				imagepng($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagepng($im);
			}
		}
		
		if($this->_format == "gif") {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagegif($im);
			} else if (!empty($folder)) {
				imagegif($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagegif($im);
			}
		}
		
		if($this->_format=="jpg" || $this->_format == "jpeg" ) {
			if(!empty($file) and (empty($folder))) {
				header('Content-Description: File Transfer');
				header('Content-Type: image/'.$this->_format.'');
				header('Content-Disposition: attachment; filename='.$file.'.'.$this->_format.'');
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				imagejpeg($im);
			} else if (!empty($folder)) {
				imagejpeg($im, $folder.$file.".".$this->_format);
				$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$this->_format;
			} else {
				imagejpeg($im);
			}
		}
		
		imagedestroy($im);
	}
	
	
	
	
	
	/**
	* Function _qrBarcode:
	* Determines what kind of request is made for QRCode and calls the adequate function
	*
	* @param 	integer	 	The scale (multiplier) for height and also the width
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	private function _qrBarcode($scale, $file, $folder, $format, $ECLevel = "L", $margin = true) {
		
		// File is passed but no folder? 	> Forced Download
		if (($file != "") and ($folder == "")) {
			$this->_generate_download_qrimage($scale * $this->_height, $file, $format, $ECLevel, $margin);
		
		// File and folder are passed? 		> Forced Save file
		} else if (($file != "") and ($folder != "")) {
			$this->_generate_save_qrimage($scale * $this->_height, $file, $folder, $format, $ECLevel, $margin);
		
		// No File nor folder  passed? 		> Just a show of image
		} else {
			$this->_generate_qrimage($scale * $this->_height, $ECLevel, $margin);
		}
	}
	
	/**
	* Function qr_text:
	* Sets data type TEXT for QRCode generation
	*
	* @param 	string 	The text to encode
	*
	*/
	public function qr_text($text){
		$this->data = $text;
    }
	
    /**
	* Function qr_link:
	* Sets data type LINK for QRCode generation
	*
	* @param 	string 	The url to encode
	*
	*/
    public function qr_link($url){
        if (preg_match('/^http:\/\//', $url) || preg_match('/^https:\/\//', $url))  {
            $this->data = $url;
        } else {
            $this->data = "http://".$url;
        }
    }
    
	/**
	* Function qr_sms:
	* Sets data type SMS for QRCode generation
	*
	* @param 	string 	The phone number
	* @param 	string 	The text message
	*
	*/
    public function qr_sms($phone, $text){
        $this->data = "SMSTO:".$phone.":".$text;
    }
	
    /**
	* Function qr_phone_number:
	* Sets data type PHONE for QRCode generation
	*
	* @param 	string 	The phone number
	*
	*/
    public function qr_phone_number($phone){
        $this->data = "TEL:".$phone;
    }
    
    /**
	* Function qr_vcard:
	* Sets data type VCARD for QRCode generation
	* Note that linebreaks are necessary, and NO SPACES or TABS are allowed
	*
	* @param 	string 	Contact's name
	* @param 	string 	Contact's company
	* @param 	string	Contact's Job Title
	* @param 	string	Contact's work phone number
	* @param 	string	Contact's home phone number
	* @param 	string	Contact's address
	* @param 	string	Contact's city
	* @param 	string	Contact's postal code
	* @param 	string	Contact's country
	* @param 	string	Contact's email address
	* @param 	string	Contact's website URL
	*
	*/
    public function qr_vcard($name, $company, $job_title, $phone_W, $phone_H, $add_add, $add_city, $add_pc, $add_country, $email, $url){
        $this->data = "BEGIN:VCARD
VERSION:3.0
N:".$name."
FN:".$name."
ORG:".$company."
TITLE:".$job_title."
TEL;TYPE=WORK,VOICE:".$phone_W."
TEL;TYPE=HOME,VOICE:".$phone_H."
ADR;TYPE=HOME:;;".$add_add.";".$add_city.";;".$add_pc.";".$add_country."
LABEL;TYPE=WORK:".$add_add."\n".$add_city.", ".$add_pc."\n".$add_country."
EMAIL;TYPE=PREF,INTERNET:".$email."
URL:".$url."
END:VCARD
";
    }
	
	/**
	* Function qr_mecard:
	* Sets data type MECARD for QRCode generation
	*
	* @param 	string	Contact's name
	* @param 	string	Contact's phone number
	* @param 	string	Contact's email address
	* @param 	string	Contact's website URL
	*
	*/
    public function qr_mecard($name, $phone, $email, $url){
        $this->data = "MECARD:N:".$name.";URL:".$url.";TEL:".$phone.";EMAIL:".$email.";;";
    }
    
    /**
	* Function qr_email:
	* Sets data type EMAIL for QRCode generation
	*
	* @param 	string	Destination email address
	* @param 	string	Email subject
	* @param 	string	Email body
	*
	*/
    public function qr_email($email, $subject, $message){
        $this->data = "MATMSG:TO:".$email.";SUB:".$subject.";BODY:".$message.";;";
    }
    
    /**
	* Function qr_wifi:
	* Sets data type WIFI for QRCode generation
	*
	* @param 	string	Network Name (SSID)
	* @param 	string	Network type (WEP, WPA or OPEN)
	* @param 	string	Network password
	*
	*/
    public function qr_wifi($ssid, $type, $pass){
        $this->data = "WIFI:S:".$ssid.";T:".$type.";P".$pass.";;";
    }
	
	/**
	* Function qr_geo:
	* Sets data type GEO for QRCode generation
	*
	* @param 	string	Latitude coordinates
	* @param 	string	Longitude coordinates
	* @param 	integer	Height (ZOOM)
	*
	*/
	public function qr_geo($lat, $lon, $height = 100){
		$this->data = "GEO:".$lat.",".$lon.",".$height;
	}
    
    /**
	* Function _generate_qrimage:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and shows it on screen with header image
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	*
	*/
    public function _generate_qrimage($size = 150, $EC_level = 'L', $margin = true){
		header("Content-type: image/".$this->_format);
		$output_image = $this->_generate_QRCODE($size, $EC_level, $margin);
		ImagePng($output_image);
    }
	
	/**
	* Function _generate_download_qrimage:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and force image download
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	* @param 	string	Desired filename for the download
	*
	*/
    public function _generate_download_qrimage($size = 150, $file = "qrcode", $format, $EC_level = 'L', $margin = true){
        
		header('Content-Description: File Transfer');
        header('Content-Type: image/'.$format);
        header('Content-Disposition: attachment; filename='.$file.'.'.$format);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
		
		$output_image = $this->_generate_QRCODE($size, $EC_level, $margin);
		
		if ($format == "jpg") {
			imagejpeg($output_image, null, 100);
		} else if ($format == "gif") {
			imagegif($output_image);
		} else {
			imagepng($output_image);
		}
		
		
    }
	
	/**
	* Function _generate_save_qrimage:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and saves it in filesystem
	* Also sets a SESSION var for easy retrieval of recently saved image URL
	* Note that script does not create folders. TWEAK THIS FUNCTION TO ALLOW FOLDER CREATION
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	* @param 	string	Desired filename for the download
	* @param 	string	Desired location to save image (always relative to script root, also script does not create folders)
	*
	*/
    public function _generate_save_qrimage($size = 150, $file, $folder, $format, $EC_level = 'L', $margin = true){
		
		$output_image = $this->_generate_QRCODE($size, $EC_level, $margin);
		
		if(file_exists($folder.$file.".".$format))
			unlink($folder.$file.".".$format);
		
		imagejpeg($output_image, $folder.$file.".".$format);
		$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$format;
		
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	* Function _generate_QRCODE:
	* Creation of QRCODE
	* Tweaked class from original of Laurent MINGUET
	*
	*/
    public function _generate_QRCODE($w = 150, $ECLevel = 'L', $margin = true){
		
		if ($margin == true) {
			$this->disable_border = false;
		} else {
			$this->disable_border = true;
		}
		
		if (!in_array($ECLevel, array('L', 'M', 'Q', 'H'))) {
			$this->_error = "There is no QRCode link value!";
			$this->error(true);
			die();
		}
		
		$this->length = strlen($this->data);
		if (!$this->length) {
			$this->ERROR('No data?');
			$this->_error = "No data?";
			$this->error(true);
			die();
		}

		$this->level = $ECLevel;
		$this->value = &$value;
		
		$this->data_bit = array();
		$this->data_val = array();
		$this->data_cur = 0;
		$this->data_bits= 0;
		
		$this->value = $this->data;
		
		$this->encodeQR();
		$this->loadECC();
		$this->makeECC();
		$this->makeMatrix();
		
		if ($this->disable_border) {
			$s_min = 4;
			$s_max = $this->qr_size-4;
		} else {
			$s_min = 0;
			$s_max = $this->qr_size;
		}
		$size = $w;
		$s = $size/($s_max-$s_min);
		 
		// rectangle de fond
		$im = imagecreatetruecolor($size, $size);
		$c_case = imagecolorallocate($im,$this->_color[0],$this->_color[1],$this->_color[2]);
		$c_back = imagecolorallocate($im,$this->_bgcolor[0],$this->_bgcolor[1],$this->_bgcolor[2]);
		imagefilledrectangle($im,0,0,$size,$size,$c_back);
	 
		for($j=$s_min; $j<$s_max; $j++)
			for($i=$s_min; $i<$s_max; $i++)
				if ($this->final[$i + $j*$this->qr_size+1])
					imagefilledrectangle($im,($i-$s_min)*$s,($j-$s_min)*$s,($i-$s_min+1)*$s-1,($j-$s_min+1)*$s-1,$c_case);
		
		
		return $im;
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * Return final qrcode size
	 *
	 * @return	int	size of qrcode
	 */
	public function getQrSize() {
		if ($this->disable_border)
			return $this->qr_size-8;	
		else
			return $this->qr_size;	
	}
	
	public function disableBorder() {
		$this->disable_border = true;
	}
	
	private function addData($val, $bit, $next = true) {
		$this->data_val[$this->data_cur] = $val;
		$this->data_bit[$this->data_cur] = $bit;
		if ($next) {
			$this->data_cur++;
			return $this->data_cur-1;
		} else {
			return $this->data_cur;
		}
	}
	
	private function encodeQR() {
		// data conversion
		if (preg_match('/[^0-9]/',$this->value)) {
			if (preg_match('/[^0-9A-Z \$\*\%\+\-\.\/\:]/',$this->value)) {
				// type : bin
				$this->type = 'bin';
				$this->addData(4, 4);
				
				$this->data_num = $this->addData($this->length, 8); /* #version 1-9 */
				$data_num_correction=array(0,0,0,0,0,0,0,0,0,0,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8,8);
				
				// data
				for ($i=0; $i<$this->length; $i++)
					$this->addData(ord(substr($this->value, $i, 1)), 8);
			} else {
				// type : alphanum	
				$this->type = 'alphanum';
				$this->addData(2, 4);
				
				$this->data_num = $this->addData($this->length, 9); /* #version 1-9 */
				$data_num_correction=array(0,0,0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,4,4,4,4,4,4,4,4,4,4,4,4,4,4);
				
				// data
				$an_hash=array(
					'0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,
					'A'=>10,'B'=>11,'C'=>12,'D'=>13,'E'=>14,'F'=>15,'G'=>16,'H'=>17,'I'=>18,'J'=>19,'K'=>20,'L'=>21,'M'=>22,
					'N'=>23,'O'=>24,'P'=>25,'Q'=>26,'R'=>27,'S'=>28,'T'=>29,'U'=>30,'V'=>31,'W'=>32,'X'=>33,'Y'=>34,'Z'=>35,
					' '=>36,'$'=>37,'%'=>38,'*'=>39,'+'=>40,'-'=>41,'.'=>42,'/'=>43,':'=>44);
				
				for ($i=0; $i<$this->length; $i++) {
					if (($i %2)==0)
						$this->addData($an_hash[substr($this->value,$i,1)], 6, false);
					else
						$this->addData($this->data_val[$this->data_cur]*45+$an_hash[substr($this->value,$i,1)], 11, true);
				}
				unset($an_hash);
				
				if (isset($this->data_bit[$this->data_cur]))
					$this->data_cur++;
			}
		} else {
			// type : num	
			$this->type = 'num';
			$this->addData(1, 4);
			
			$this->data_num = $this->addData($this->length, 10); /* #version 1-9 */
			$data_num_correction=array(0,0,0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,4,4,4,4,4,4,4,4,4,4,4,4,4,4);
			
			// data
			for ($i=0; $i<$this->length; $i++) {
				if (($i % 3)==0)
					$this->addData(substr($this->value,$i,1), 4, false);
				else if (($i % 3)==1)
					$this->addData($this->data_val[$this->data_cur]*10+substr($this->value,$i,1), 7, false);
				else
					$this->addData($this->data_val[$this->data_cur]*10+substr($this->value,$i,1), 10);
			}
			
			if (isset($this->data_bit[$this->data_cur]))
				$this->data_cur++;
				
		}
		
		// calculate bit number
		$this->data_bits=0;
		foreach($this->data_bit as $bit)
			$this->data_bits+= $bit;
			
		// code ECC
		$ec_hash = array('L'=>1, 'M'=>0, 'Q'=>3, 'H'=>2);
		$this->ec = $ec_hash[$this->level];
		
		// bit limit table
		$max_bits = array(
		0,128,224,352,512,688,864,992,1232,1456,1728,2032,2320,2672,2920,3320,3624,4056,4504,5016,5352,
		5712,6256,6880,7312,8000,8496,9024,9544,10136,10984,11640,12328,13048,13800,14496,15312,15936,16816,17728,18672,
		
		152,272,440,640,864,1088,1248,1552,1856,2192,2592,2960,3424,3688,4184,4712,5176,5768,6360,6888,
		7456,8048,8752,9392,10208,10960,11744,12248,13048,13880,14744,15640,16568,17528,18448,19472,20528,21616,22496,23648,
		
		72,128,208,288,368,480,528,688,800,976,1120,1264,1440,1576,1784,2024,2264,2504,2728,3080,
		3248,3536,3712,4112,4304,4768,5024,5288,5608,5960,6344,6760,7208,7688,7888,8432,8768,9136,9776,10208,
		
		104,176,272,384,496,608,704,880,1056,1232,1440,1648,1952,2088,2360,2600,2936,3176,3560,3880,
		4096,4544,4912,5312,5744,6032,6464,6968,7288,7880,8264,8920,9368,9848,10288,10832,11408,12016,12656,13328
		);
		
		// Determine qrcode version
		$this->version=1; 
		$i=1+40*$this->ec;
		$j=$i+39;
		while ($i<=$j) {
			if ($max_bits[$i]>=$this->data_bits+$data_num_correction[$this->version]) {
				$this->max_data_bit=$max_bits[$i];
				break;
			}
			$i++;
			$this->version++;
		}
		
		// verifies max version
		if ($this->version>$this->version_mx) {
			$this->ERROR('Too large version!');
			$this->_error = "Too large version!";
			$this->error(true);
			die();
		}
		
			
		// correct number of bit in value
		$this->data_bits+=$data_num_correction[$this->version];
		$this->data_bit[$this->data_num]+=$data_num_correction[$this->version];
		$this->max_data_word = ($this->max_data_bit/8);
		
		// maximun number of words
		$max_words_array=array(0,26,44,70,100,134,172,196,242,292,346,404,466,532,581,655,733,815,901,991,1085,1156,
						1258,1364,1474,1588,1706,1828,1921,2051,2185,2323,2465,2611,2761,2876,3034,3196,3362,3532,3706);
		$this->max_word = $max_words_array[$this->version];
		$this->size		= 17 + 4*$this->version;
		
		unset($max_bits);
		unset($data_num_correction);
		unset($max_words_array);
		unset($ec_hash);
		
		// terminator
		if ($this->data_bits<=$this->max_data_bit-4)
			$this->addData(0, 4);
		elseif ($this->data_bits<$this->max_data_bit)
			$this->addData(0, $this->max_data_bit-$this->data_bits);
		elseif ($this->data_bits>$this->max_data_bit)
			$this->ERROR('Overflow error');
			
		$this->data_word = array();
		$this->data_word[0] = 0;
		$nb_word = 0;			

		$remaining_bit=8;
		for($i=0; $i<$this->data_cur; $i++) {
			$buffer_val=$this->data_val[$i];
			$buffer_bit=$this->data_bit[$i];

			$flag = true;
			while ($flag) {
				if ($remaining_bit>$buffer_bit) {
					$this->data_word[$nb_word]=((@$this->data_word[$nb_word]<<$buffer_bit) | $buffer_val);
					$remaining_bit-=$buffer_bit;
					$flag=false;
				} else {
					$buffer_bit-=$remaining_bit;
					$this->data_word[$nb_word]=((@$this->data_word[$nb_word] << $remaining_bit) | ($buffer_val >> $buffer_bit));
					$nb_word++;
					
					if ($buffer_bit==0)
						$flag=false;
					else
						$buffer_val= ($buffer_val & ((1 << $buffer_bit)-1) );

					if ($nb_word<$this->max_data_word-1)
						$this->data_word[$nb_word]=0;
					$remaining_bit=8;
				}
			}
		}
		
		// completion du dernier mot si incomplet
		if ($remaining_bit<8)
			$this->data_word[$nb_word]=$this->data_word[$nb_word] << $remaining_bit;
		else
			$nb_word--;

		// remplissage du reste
		if ($nb_word<$this->max_data_word-1) {
			$flag=true;
			while ($nb_word<$this->max_data_word-1) {
				$nb_word++;
				if ($flag)
					$this->data_word[$nb_word]=236;
				else
					$this->data_word[$nb_word]=17;
				$flag=!$flag;
			}
		}
	}
	
	private function loadECC() {
		$matrix_remain_bit=array(0,0,7,7,7,7,7,0,0,0,0,0,0,0,3,3,3,3,3,3,3,4,4,4,4,4,4,4,3,3,3,3,3,3,3,0,0,0,0,0,0);
		$this->matrix_remain = $matrix_remain_bit[$this->version];
		unset($matrix_remain_bit);
		
		// lecture du fichier : data file of geometry & mask for version V ,ecc level N			
		$this->byte_num = $this->matrix_remain+ 8*$this->max_word;
		$filename = $this->qr_data_files . "/qrv".$this->version."_".$this->ec.".dat";
		$fp1 = fopen ($filename, "rb");
			$this->matrix_x_array			= unpack("C*", fread($fp1,$this->byte_num));
			$this->matrix_y_array			= unpack("C*", fread($fp1,$this->byte_num));
			$this->mask_array				= unpack("C*", fread($fp1,$this->byte_num));
			$this->format_information_x2	= unpack("C*", fread($fp1,15));
			$this->format_information_y2	= unpack("C*", fread($fp1,15));
			$this->rs_ecc_codewords			= ord(fread($fp1,1));
			$this->rs_block_order			= unpack("C*", fread($fp1,128));
		fclose($fp1);
		$this->format_information_x1 = array(0,1,2,3,4,5,7,8,8,8,8,8,8,8,8);
		$this->format_information_y1 = array(8,8,8,8,8,8,8,8,7,5,4,3,2,1,0);

	}
	
	private function makeECC() {
		// lecture du fichier : data file of caluclatin tables for RS encoding
		$rs_cal_table_array = array();
		$filename = $this->qr_data_files . "/rsc".$this->rs_ecc_codewords.".dat";
		$fp0 = fopen ($filename, "rb");
		for($i=0; $i<256; $i++)
			$rs_cal_table_array[$i]=fread ($fp0,$this->rs_ecc_codewords);
		fclose ($fp0);	

		$max_data_codewords = count($this->data_word);

		// preparation
		$j=0;
		$rs_block_number=0;
		$rs_temp[0]="";
		for($i=0; $i<$max_data_codewords; $i++) {
			$rs_temp[$rs_block_number].=chr($this->data_word[$i]);
			$j++;
			if ($j>=$this->rs_block_order[$rs_block_number+1]-$this->rs_ecc_codewords) {
				$j=0;
				$rs_block_number++;
				$rs_temp[$rs_block_number]="";
			}
		}

		// make
		$rs_block_order_num=count($this->rs_block_order);
		
		for($rs_block_number=0; $rs_block_number<$rs_block_order_num; $rs_block_number++) {
			$rs_codewords=$this->rs_block_order[$rs_block_number+1];
			$rs_data_codewords=$rs_codewords-$this->rs_ecc_codewords;

			$rstemp=$rs_temp[$rs_block_number].str_repeat(chr(0),$this->rs_ecc_codewords);
			$padding_data=str_repeat(chr(0),$rs_data_codewords);

			$j=$rs_data_codewords;
			while($j>0) {
				$first=ord(substr($rstemp,0,1));

				if ($first)
				{
					$left_chr=substr($rstemp,1);
					$cal=$rs_cal_table_array[$first].$padding_data;
					$rstemp=$left_chr ^ $cal;
				}
				else
					$rstemp=substr($rstemp,1);
				$j--;
			}
			
			$this->data_word=array_merge($this->data_word,unpack("C*",$rstemp));
		}
	}
	
	private function makeMatrix() {
		// preparation
		$this->matrix = array_fill(0, $this->size, array_fill(0, $this->size, 0));
		
		// mettre les words
		for($i=0; $i<$this->max_word; $i++) {
			$word = $this->data_word[$i];
			for($j=8; $j>0; $j--) {
				$bit_pos = ($i<<3) + $j;
				$this->matrix[ $this->matrix_x_array[$bit_pos] ][ $this->matrix_y_array[$bit_pos] ] = ((255*($word & 1)) ^ $this->mask_array[$bit_pos] ); 
				$word = $word >> 1;
			}
		}
		
		for($k=$this->matrix_remain; $k>0; $k--) {
			$bit_pos = $k + ( $this->max_word <<3);
			$this->matrix[ $this->matrix_x_array[$bit_pos] ][ $this->matrix_y_array[$bit_pos] ] = ( 255 ^ $this->mask_array[$bit_pos] );
		}
		
		// mask select
		$min_demerit_score=0;
		$hor_master="";
		$ver_master="";
		$k=0;
		while($k<$this->size) {
			$l=0;
			while($l<$this->size)
			{
				$hor_master=$hor_master.chr($this->matrix[$l][$k]);
				$ver_master=$ver_master.chr($this->matrix[$k][$l]);
				$l++;
			}
			$k++;
		}
		
		$i=0;
		$all_matrix=$this->size * $this->size;
		 
		while ($i<8) {
			$demerit_n1=0;
			$ptn_temp=array();
			$bit= 1<< $i;
			$bit_r=(~$bit)&255;
			$bit_mask=str_repeat(chr($bit),$all_matrix);
			$hor = $hor_master & $bit_mask;
			$ver = $ver_master & $bit_mask;

			$ver_shift1=$ver.str_repeat(chr(170),$this->size);
			$ver_shift2=str_repeat(chr(170),$this->size).$ver;
			$ver_shift1_0=$ver.str_repeat(chr(0),$this->size);
			$ver_shift2_0=str_repeat(chr(0),$this->size).$ver;
			$ver_or=chunk_split(~($ver_shift1 | $ver_shift2),$this->size,chr(170));
			$ver_and=chunk_split(~($ver_shift1_0 & $ver_shift2_0),$this->size,chr(170));

			$hor=chunk_split(~$hor,$this->size,chr(170));
			$ver=chunk_split(~$ver,$this->size,chr(170));
			$hor=$hor.chr(170).$ver;

			$n1_search="/".str_repeat(chr(255),5)."+|".str_repeat(chr($bit_r),5)."+/";
			$n3_search=chr($bit_r).chr(255).chr($bit_r).chr($bit_r).chr($bit_r).chr(255).chr($bit_r);

			$demerit_n3=substr_count($hor,$n3_search)*40;
			$demerit_n4=floor(abs(( (100* (substr_count($ver,chr($bit_r))/($this->byte_num)) )-50)/5))*10;

			$n2_search1="/".chr($bit_r).chr($bit_r)."+/";
			$n2_search2="/".chr(255).chr(255)."+/";
			$demerit_n2=0;
			preg_match_all($n2_search1,$ver_and,$ptn_temp);
			foreach($ptn_temp[0] as $str_temp) {
				$demerit_n2+=(strlen($str_temp)-1);
			}
			$ptn_temp=array();
			preg_match_all($n2_search2,$ver_or,$ptn_temp);
			foreach($ptn_temp[0] as $str_temp) {
				$demerit_n2+=(strlen($str_temp)-1);
			}
			$demerit_n2*=3;
			
			$ptn_temp=array();
			
			preg_match_all($n1_search,$hor,$ptn_temp);
			foreach($ptn_temp[0] as $str_temp) {
				$demerit_n1+=(strlen($str_temp)-2);
			}	
			$demerit_score=$demerit_n1+$demerit_n2+$demerit_n3+$demerit_n4;

			if ($demerit_score<=$min_demerit_score || $i==0) {
				$mask_number=$i;
				$min_demerit_score=$demerit_score;
			}

			$i++;
		}

		$mask_content=1 << $mask_number;
		
		$format_information_value=(($this->ec << 3) | $mask_number);
		$format_information_array=array("101010000010010","101000100100101",
		"101111001111100","101101101001011","100010111111001","100000011001110",
		"100111110010111","100101010100000","111011111000100","111001011110011",
		"111110110101010","111100010011101","110011000101111","110001100011000",
		"110110001000001","110100101110110","001011010001001","001001110111110",
		"001110011100111","001100111010000","000011101100010","000001001010101",
		"000110100001100","000100000111011","011010101011111","011000001101000",
		"011111100110001","011101000000110","010010010110100","010000110000011",
		"010111011011010","010101111101101");

		for($i=0; $i<15; $i++) {
			$content=substr($format_information_array[$format_information_value],$i,1);
			
			$this->matrix[$this->format_information_x1[$i]][$this->format_information_y1[$i]]=$content * 255;
			$this->matrix[$this->format_information_x2[$i+1]][$this->format_information_y2[$i+1]]=$content * 255;
		}
	
		$this->final = unpack("C*", file_get_contents(dirname(__FILE__).'/qr_data/modele'.$this->version.'.dat'));
		$this->qr_size = $this->size+8;
		
		for($x=0; $x<$this->size; $x++) {
			for($y=0; $y<$this->size; $y++) {
				if ($this->matrix[$x][$y] & $mask_content)
					$this->final[($x+4) + ($y+4)*$this->qr_size+1] = true; 
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	* Function _generate_DATAMATRIX:
	* Starts generaing the datamatrix code and the tries to find
	* what kind of request is made and calls the adequate function
	*
	* @param 	string	 	The data to encode
	* @param 	integer	 	With of image in pixels
	* @param 	integer	 	Height of image in pixels
	* @param 	integer	 	Size of margin in pixels
	* @param 	string	 	The name for desired filename
	* @param 	string	 	The desired folder to save the file (should be relative to script folder)
	*
	*/
	public function _generate_DATAMATRIX($barnumber, $width = 100, $height = 100, $margin = 10, $file = "", $folder = "") {
		
		require_once(dirname(__FILE__).'/2dbarcodes.php');
		
		$barcodeobj = new TCPDF2DBarcode($barnumber, $this->_encode);
		$png = $barcodeobj->getBarcodePNG($width, $height, $margin, $this->_color, $this->_bgcolor);
		
		// File is passed but no folder? 	> Forced Download
		if (($file != "") and ($folder == "")) {
			$this->_generate_download_DATAMATRIXimage($png, $file);
		
		// File and folder are passed? 		> Forced Save file
		} else if (($file != "") and ($folder != "")) {
			$this->_generate_save_DATAMATRIXimage($png, $folder, $file, "png");
		
		// No File nor folder  passed? 		> Just a show of image
		} else {
			$this->_generate_DATAMATRIXimage($png);
		}
		
		
	}
	
	
	
	/**
	* Function _generate_DATAMATRIXimage:
	* Final function for DataMatrix generation.
	* Will output it on screen with header image
	*
	* @param 	object	The image to output
	*
	*/
    public function _generate_DATAMATRIXimage($png){
		if ($png === false) {
			$this->_error = "DATAMATRIX Code has some errors. Probably too much info.";
		} else {
			header("Content-type: image/png");
			imagepng($png);
		}
    }
	
	/**
	* Function _generate_download_qrimage:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and force image download
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	* @param 	string	Desired filename for the download
	*
	*/
    public function _generate_download_DATAMATRIXimage($png, $file = "datamatrix"){
        
		header('Content-Description: File Transfer');
        header('Content-Type: image/jpg');
        header('Content-Disposition: attachment; filename='.$file.'.png');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
		
		if ($png === false) {
			$this->_error = "DATAMATRIX Code has some errors. Probably too much info.";
		} else {
			imagepng($png);
		}
		
		
    }
	
	/**
	* Function _generate_save_qrimage:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and saves it in filesystem
	* Also sets a SESSION var for easy retrieval of recently saved image URL
	* Note that script does not create folders. TWEAK THIS FUNCTION TO ALLOW FOLDER CREATION
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	* @param 	string	Desired filename for the download
	* @param 	string	Desired location to save image (always relative to script root, also script does not create folders)
	*
	*/
    public function _generate_save_DATAMATRIXimage($png, $folder, $file = "datamatrix", $format = "png"){
		
		if(file_exists($folder.$file.".".$format))
			unlink($folder.$file.".".$format);
		
		imagepng($png, $folder.$file.".".$format);
		$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$format;
		
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function _generate_PDF417($barnumber, $width = 100, $height = 100, $margin = 10, $file = "", $folder = "", $ECLevel = -1) {
		
		require_once(dirname(__FILE__).'/2dbarcodes.php');
		
		$barcodeobj = new TCPDF2DBarcode($barnumber, $this->_encode, $ECLevel);
		$png = $barcodeobj->getBarcodePNG($width, $height, $margin, $this->_color, $this->_bgcolor);
		
		
		
		
		// File is passed but no folder? 	> Forced Download
		if (($file != "") and ($folder == "")) {
			$this->_generate_download_PDF417image($png, $file);
		
		// File and folder are passed? 		> Forced Save file
		} else if (($file != "") and ($folder != "")) {
			$this->_generate_save_PDF417image($png, $folder, $file, "png");
		
		// No File nor folder  passed? 		> Just a show of image
		} else {
			$this->_generate_PDF417image($png);
		}
		
	}
	
	
	
	/**
	* Function _generate_PDF417image:
	* Final function for DataMatrix generation.
	* Will output it on screen with header image
	*
	* @param 	object	The image to output
	*
	*/
    public function _generate_PDF417image($png){
		if ($png === false) {
			$this->_error = "DATAMATRIX Code has some errors. Probably too much info.";
		} else {
			header("Content-type: image/png");
			imagepng($png);
		}
    }
	
	/**
	* Function _generate_download_PDF417image:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and force image download
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	* @param 	string	Desired filename for the download
	*
	*/
    public function _generate_download_PDF417image($png, $file = "datamatrix"){
        
		header('Content-Description: File Transfer');
        header('Content-Type: image/jpg');
        header('Content-Disposition: attachment; filename='.$file.'.png');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
		
		if ($png === false) {
			$this->_error = "DATAMATRIX Code has some errors. Probably too much info.";
		} else {
			imagepng($png);
		}
		
		
    }
	
	/**
	* Function _generate_save_PDF417image:
	* Final function for QRCode generation.
	* Will asks Google chart api for image and saves it in filesystem
	* Also sets a SESSION var for easy retrieval of recently saved image URL
	* Note that script does not create folders. TWEAK THIS FUNCTION TO ALLOW FOLDER CREATION
	*
	* @param 	integer	Size in pixels (both X and Y because is a square)
	* @param 	string	Error correction level (always set to L)
	* @param 	integer	Margin around qrcode (aparently is not working properly)
	* @param 	string	Desired filename for the download
	* @param 	string	Desired location to save image (always relative to script root, also script does not create folders)
	*
	*/
    public function _generate_save_PDF417image($png, $folder, $file = "datamatrix", $format = "png"){
		
		if(file_exists($folder.$file.".".$format))
			unlink($folder.$file.".".$format);
		
		imagepng($png, $folder.$file.".".$format);
		$_SESSION["_CREATED_FILE_"] = "http://" . $_SERVER['HTTP_HOST']  . $_SERVER['PHP_SELF']."/../".$folder.$file.".".$format;
		
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

?>