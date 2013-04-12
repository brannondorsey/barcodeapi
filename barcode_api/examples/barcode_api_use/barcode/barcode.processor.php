<?php
/** 
* This is the API processor. Offers GET and POST responses. Also processes some requests to the API
* It does also check for empty values and handles erros. Also handles errors for images request too big.
* Protects some values like ECLevel for correct ones, avoiding generating erros.
*
* @author 	Eduardo Pereira
* @date		24/09/2011
* @version	3.0 (16/11/2011)
* @website	http://www.voindo.eu/UltimateBarcodeGenerator
* 
*/

/**
* Calls and instantiates the class
*/
require("barcode.class.php");
$bar = new BARCODE();

/**
* Check if POST or GET encode type as been passed
* If it was passed then set acoording, else just throws
* a image error. Quite nice hun? :)
*/
if (isset($_REQUEST['encode']) and ($_REQUEST['encode'] != "")) {
	
	/**
	* Converts the POST or GET encode type to uppercase
	* and verifies if is a valid request for code
	*/
	$enc = strtoupper($_REQUEST['encode']);
	if (($enc == "QRCODE") or ($enc == "UPC-A") or ($enc == "UPC-E") or ($enc == "EAN-8") or ($enc == "EAN-13") or ($enc == "CODE39") or ($enc == "CODE93") or ($enc == "CODE128") or ($enc == "POSTNET") or ($enc == "CODABAR") or ($enc == "ISBN") or ($enc == "DATAMATRIX") or ($enc == "PDF417")) {
		
		/**
		* request code valid so assign it
		*/
		$encode	= $enc;
		
		/**
		* Verifies 
		* IF request if for 1D barcode and bdata is set
		* OR request is QRCODE (will check for data later)
		* OR request is DATAMATRIX and bdata_matrix is set
		* OR request is PDF417 and bdata_pdf is set
		*/
		if ((isset($_REQUEST['bdata']) and ($_REQUEST['bdata'] != "") and (($enc != "QRCODE") and ($enc != "DATAMATRIX") and ($enc != "PDF417"))) or ($enc == "QRCODE") or (($enc == "DATAMATRIX") and ((isset($_REQUEST['bdata_matrix'])) and ($_REQUEST['bdata_matrix'] != ""))) or (($enc == "PDF417") and ((isset($_REQUEST['bdata_pdf'])) and ($_REQUEST['bdata_pdf'] != "")))) {
			
			/**
			* Double checks data to encode for 1D barcodes
			* No point here, because we checked above, but ok :P
			* Important is assigning it to the $barnumber variable :)
			*/
			if (isset($_REQUEST['bdata']))
				$barnumber	= $_REQUEST['bdata'];
			else
				$barnumber	= "";
			
			/**
			* Is file type defined? if not set to PNG
			*/
			if (!isset($_REQUEST['type'])) { $type = "png"; } else { $type = $_REQUEST['type']; }
			
			/**
			* Is file name defined? if not set to ""
			*/
			if (!isset($_REQUEST['file'])) { $file = ""; } else { $file = $_REQUEST['file']; }
			
			/**
			* Is folder name defined? if not set to ""
			*/
			if (!isset($_REQUEST['folder'])) { $folder = ""; } else { $folder = $_REQUEST['folder']; }
							
			/**
			* Set encoding type
			*/
			$bar->setSymblogy($encode);
			
			/**
			* Is height defined? if not set to 50
			*/
			if (!isset($_REQUEST['height']) or ($_REQUEST['height'] == ""))
				$bar->setHeight(50);
			else
				$bar->setHeight($_REQUEST['height']);
			
			/**
			* Is scale defined? if not set to 2
			*/
			if (!isset($_REQUEST['scale']) or ($_REQUEST['scale'] == ""))
				$bar->setScale(2);
			else
				$bar->setScale($_REQUEST['scale']);
			
			/**
			* Is colors defined? if not set to #000000 and #ffffff
			*/
			if ((!isset($_REQUEST['color']) or ($_REQUEST['color'] == "")) and (!isset($_REQUEST['bgcolor']) or ($_REQUEST['bgcolor'] == "")))
				$bar->setHexColor("#000000", "#ffffff");
			else
				$bar->setHexColor($_REQUEST['color'],$_REQUEST['bgcolor']);
			
			/**
			* Right so what will we produce, QRCODE ?
			*/
			if ($bar->_encode == "QRCODE") {
				
				/**
				* We start with no errors for now
				*/
				$qr_error = false;
				
				/**
				* If no QRCODE type defined, set to "error"
				*/
				if (!isset($_REQUEST['qrdata_type']) or ($_REQUEST['qrdata_type'] == ""))
					$qrdata_type = "error";
				else
					$qrdata_type = $_REQUEST['qrdata_type'];
					
				
				/**
				* Do we have a ECLevel request ?
				*/
				if (isset($_REQUEST['ECLevel']) and (($_REQUEST['ECLevel'] == "L") or ($_REQUEST['ECLevel'] == "M") or ($_REQUEST['ECLevel'] == "Q") or ($_REQUEST['ECLevel'] == "H")))
					$bar->ECLevel = $_REQUEST['ECLevel'];
				else
					$bar->ECLevel = "L";
					
				
				/**
				* Do we have a margin request ?
				*/
				if (isset($_REQUEST['margin']) and (($_REQUEST['margin'] == true) or ($_REQUEST['margin'] == false)))
					$bar->margin = $_REQUEST['margin'];
				
				
				/**
				* Finally lets check the desired total image size
				* If its too big (bigger than 3000x3000px throw error
				*/
				if (($bar->_height * $bar->_scale) > 3000) {
					$bar->_error = "Image too big to be generated!";
					$bar->error(true);
					$qr_error = true;
				}
				
				/**
				* Let's switch between the QRCODES types. Check for each one if
				* we have enough data to encode. If not enough defined produce an error
				*/
				switch ($qrdata_type) {
					case "link":
						if (isset($_REQUEST['qr_link_link'])) {
							$bar->qr_link($_REQUEST['qr_link_link']);
						} else {
							$bar->_error = "There is no QRCode link value!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "sms":
						if ((isset($_REQUEST['qr_sms_phone'])) and (isset($_REQUEST['qr_sms_msg']))) {
							$bar->qr_sms($_REQUEST['qr_sms_phone'], $_REQUEST['qr_sms_msg']);
						} else {
							$bar->_error = "One of the QRCode sms values missing!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "phone":
						if (isset($_REQUEST['qr_phone_phone'])) {
							$bar->qr_phone_number($_REQUEST['qr_phone_phone']);
						} else {
							$bar->_error = "There is no QRCode phone value!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "vcard":
						if ((isset($_REQUEST['qr_vc_N'])) and (isset($_REQUEST['qr_vc_C'])) and (isset($_REQUEST['qr_vc_J'])) and (isset($_REQUEST['qr_vc_W'])) and (isset($_REQUEST['qr_vc_H'])) and (isset($_REQUEST['qr_vc_AA'])) and (isset($_REQUEST['qr_vc_ACI'])) and (isset($_REQUEST['qr_vc_AP'])) and (isset($_REQUEST['qr_vc_ACO'])) and (isset($_REQUEST['qr_vc_E'])) and (isset($_REQUEST['qr_vc_U']))) {
							$bar->qr_vcard($_REQUEST['qr_vc_N'], $_REQUEST['qr_vc_C'], $_REQUEST['qr_vc_J'], $_REQUEST['qr_vc_W'], $_REQUEST['qr_vc_H'], $_REQUEST['qr_vc_AA'], $_REQUEST['qr_vc_ACI'], $_REQUEST['qr_vc_AP'], $_REQUEST['qr_vc_ACO'], $_REQUEST['qr_vc_E'], $_REQUEST['qr_vc_U']);
						} else {
							$bar->_error = "One of the QRCode vcard values missing!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "mecard":
						if ((isset($_REQUEST['qr_mec_N'])) and (isset($_REQUEST['qr_mec_P'])) and (isset($_REQUEST['qr_mec_E'])) and (isset($_REQUEST['qr_mec_U']))) {
							$bar->qr_mecard($_REQUEST['qr_mec_N'], $_REQUEST['qr_mec_P'], $_REQUEST['qr_mec_E'], $_REQUEST['qr_mec_U']);
						} else {
							$bar->_error = "One of the QRCode mecard values missing!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "email":
						if ((isset($_REQUEST['qr_email_add'])) and (isset($_REQUEST['qr_email_sub'])) and (isset($_REQUEST['qr_email_msg']))) {
							$bar->qr_email($_REQUEST['qr_email_add'], $_REQUEST['qr_email_sub'], $_REQUEST['qr_email_msg']);
						} else {
							$bar->_error = "One of the QRCode email values missing!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "wifi":
						if ((isset($_REQUEST['qr_wifi_ssid'])) and (isset($_REQUEST['qr_wifi_type'])) and (isset($_REQUEST['qr_wifi_pass']))) {
							$bar->qr_wifi($_REQUEST['qr_wifi_ssid'], $_REQUEST['qr_wifi_type'], $_REQUEST['qr_wifi_pass']);
						} else {
							$bar->_error = "One of the QRCode wifi values missing!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "geo":
						if ((isset($_REQUEST['qr_geo_lat'])) and (isset($_REQUEST['qr_geo_lon']))) {
							$bar->qr_geo($_REQUEST['qr_geo_lat'], $_REQUEST['qr_geo_lon']);
						} else {
							$bar->_error = "One of the QRCode geo values missing!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					case "text":
						if (isset($_REQUEST['qr_btext_text'])) {
							$bar->qr_text($_REQUEST['qr_btext_text']);
						} else {
							$bar->_error = "There is no QRCode text value!";
							$bar->error(true);
							$qr_error = true;
						}
						break;
						
					default:
						// QRCode type invalid, produce error
						$bar->_error = "Invalid QRCode type!";
						$bar->error(true);
						$qr_error = true;
						
				}
				
				/**
				* All went well with QRCodes? no errors? So show me the money (the barcode actually :)
				*/
				if ($qr_error === false) {
					$return = $bar->genBarCode($barnumber, $type, $file, $folder);
					if($return == false)
						$bar->error(true);
						
					if ($folder != "") {
						$file_path = $_SESSION["_CREATED_FILE_"];
						if (isset($_REQUEST['AJAX_REQUEST']) and ($_REQUEST['AJAX_REQUEST'] == "1")) {
							echo $file_path;
						}
					}
				}
			
			
			
			/**
			* So what is it? DataMatrix then?
			*/
			} else if ($bar->_encode == "DATAMATRIX") {
				
				/**
				* Double checks data to encode for 1D barcodes
				*/
				if (isset($_REQUEST['bdata_matrix']))
					$barnumber	= $_REQUEST['bdata_matrix'];
				else
					$barnumber	= "";
				
				/**
				* Do we have a margin request ?
				*/
				if (isset($_REQUEST['margin']))
					$bar->margin_size = $_REQUEST['margin'];
				
				/**
				* Finally lets check the desired total image size
				* If its too big (bigger than 3000x3000px throw error
				*/
				if (($bar->_height > 3000) or ($bar->_scale > 3000)) {
					$bar->_error = "Image too big to be generated!";
					$bar->error(true);
					$qr_error = true;
				} else {
					$return = $bar->genBarCode($barnumber, "png", $file, $folder); // Notice, file type for datamatrix MUST BE ALWAYS PNG
					if($return == false)
						$bar->error(true);
						
					if ($folder != "") {
						$file_path = $_SESSION["_CREATED_FILE_"];
						if (isset($_REQUEST['AJAX_REQUEST']) and ($_REQUEST['AJAX_REQUEST'] == "1")) {
							echo $file_path;
						}
					}
				}
				
				
			/**
			* So is it PDF417 then?
			*/
			} else if ($bar->_encode == "PDF417") {
				
				/**
				* Double checks data to encode for 1D barcodes
				*/
				if (isset($_REQUEST['bdata_pdf']))
					$barnumber	= $_REQUEST['bdata_pdf'];
				else
					$barnumber	= "";
				
				/**
				* Do we have a margin request ?
				*/
				if (isset($_REQUEST['margin']))
					$bar->margin_size = $_REQUEST['margin'];
				
				/**
				* Do we have a ECLevel request ?
				* If not reset it to avoid errors betwen QRCOde ECLevel and PDF417 ECLevel
				*/
				if (isset($_REQUEST['ECLevel']) and (($_REQUEST['ECLevel'] == -1) or ($_REQUEST['ECLevel'] == 1) or ($_REQUEST['ECLevel'] == 2) or ($_REQUEST['ECLevel'] == 3) or ($_REQUEST['ECLevel'] == 4) or ($_REQUEST['ECLevel'] == 5) or ($_REQUEST['ECLevel'] == 6) or ($_REQUEST['ECLevel'] == 7) or ($_REQUEST['ECLevel'] == 8)))
					$bar->ECLevel = $_REQUEST['ECLevel'];
				else
					$bar->ECLevel = -1;
				
				/**
				* Finally lets check the desired total image size
				* If its too big (bigger than 3000x3000px throw error
				*/
				if (($bar->_height > 3000) or ($bar->_scale > 3000)) {
					$bar->_error = "Image too big to be generated!";
					$bar->error(true);
					$qr_error = true;
				} else {
					$return = $bar->genBarCode($barnumber, "png", $file, $folder); // Notice, file type for pdf417 MUST BE ALWAYS PNG
					if($return == false)
						$bar->error(true);
						
					if ($folder != "") {
						$file_path = $_SESSION["_CREATED_FILE_"];
						if (isset($_REQUEST['AJAX_REQUEST']) and ($_REQUEST['AJAX_REQUEST'] == "1")) {
							echo $file_path;
						}
					}
				}
				
			
			/**
			* So we're talking about normal BARCODES. Show it then, we've got everything already
			*/
			} else {
				
				/**
				* Double checks and resets the option to show data with barcodes.
				*/
				if ((isset($_REQUEST['showData'])) and ($_REQUEST['showData'] == "0")) {
					$bar->_showData = false;
				} else {
					$bar->_showData = true;
				}
				
				$return = $bar->genBarCode($barnumber, $type, $file, $folder);
				if($return == false)
					$bar->error(true);
					
				if ($folder != "") {
					$file_path = $_SESSION["_CREATED_FILE_"];
					if (isset($_REQUEST['AJAX_REQUEST']) and ($_REQUEST['AJAX_REQUEST'] == "1")) {
						echo $file_path;
					}
				}
			}
		
		/**
		* It's not a QRCode nor a BARCODE with data to encode...
		* Error it!
		*/
		} else {
			$bar->_error = "No data to encode!";
			$bar->error(true);
		}
	
	/**
	* Right so if it's not a QRCODE, UPC-A, UPC-E, EAN-8, EAN-13, CODE39, CODE93, CODE128, POSTNET, CODABAR or ISBN
	* what are you looking for it's not meant to be made with this class. Sorry ;)
	*/
	} else {
		$bar->_error = $_REQUEST['encode']." is invalid request!";
		$bar->error(true);
	}

/**
* Come on... talk to me... Say something for me to produce.
*/
} else {
	$bar->_error = "No request?";
	$bar->error(true);
}
?>