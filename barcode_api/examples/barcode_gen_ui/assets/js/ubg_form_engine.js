$(document).ready(function() {
	
	if ($('#encode').val() == "QRCODE") {
		$('.QR_only').show();
		$('.MATRIX_only').hide();
		$('.PDF417_only').hide();
		$('.BARCODE_only').hide();
		$('#type').attr("disabled", true);
		
	} else if ($('#encode').val() == "DATAMATRIX") {
		$('.QR_only').hide();
		$('.MATRIX_only').show();
		$('.PDF417_only').hide();
		$('.BARCODE_only').hide();
		$('#type').attr("disabled", true);
	
	} else if ($('#encode').val() == "PDF417") {
		$('.QR_only').hide();
		$('.MATRIX_only').hide();
		$('.PDF417_only').show();
		$('.BARCODE_only').hide();
		$('#type').attr("disabled", true);
		
	} else {
		$('.QR_only').hide();
		$('.MATRIX_only').hide();
		$('.PDF417_only').hide();
		$('.BARCODE_only').show();
		$('#type').attr("disabled", false);
	}
	
	
	if ($('#qrdata_type').val() == "link") {
		$('.qr_text').hide();
		$('.qr_link').show();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "sms") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').show();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "email") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').show();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "phone") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').show();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "vcard") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').show();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "mecard") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').show();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "wifi") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').show();
		$('.qr_geo').hide();
		
	} else if ($('#qrdata_type').val() == "geo") {
		$('.qr_text').hide();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').show();
		
	} else {
		$('.qr_text').show();
		$('.qr_link').hide();
		$('.qr_sms').hide();
		$('.qr_phone').hide();
		$('.qr_vcard').hide();
		$('.qr_mecard').hide();
		$('.qr_email').hide();
		$('.qr_wifi').hide();
		$('.qr_geo').hide();
	}
	
	$('#encode').change(function() {
		if ($('#encode').val() == "QRCODE") {
			$('.QR_only').show();
			$('.MATRIX_only').hide();
			$('.PDF417_only').hide();
			$('.BARCODE_only').hide();
			$('#type').attr("disabled", true);
			
			$('input#height').val("50");
			$('input#scale').val("2");
			$("small.height").hide().html("For QRCode with and height, set here a value in").fadeIn("slow");
			$("small.scale").hide().html("pixels and set a multiplier here. (50 * 2 = 100px).").fadeIn("slow");
			
		} else if ($('#encode').val() == "DATAMATRIX") {
			$('.QR_only').hide();
			$('.MATRIX_only').show();
			$('.PDF417_only').hide();
			$('.BARCODE_only').hide();
			$('#type').attr("disabled", true);
			
			$('input#height').val("100");
			$('input#scale').val("100");
			$("small.height").hide().html("For DataMatrix, set height in Pixels here.").fadeIn("slow");
			$("small.scale").hide().html("For DataMatrix, set width in Pixels here.").fadeIn("slow");
		
		} else if ($('#encode').val() == "PDF417") {
			$('.QR_only').hide();
			$('.MATRIX_only').hide();
			$('.PDF417_only').show();
			$('.BARCODE_only').hide();
			$('#type').attr("disabled", true);
			
			$('input#height').val("100");
			$('input#scale').val("250");
			$("small.height").hide().html("For PDF417, set height in Pixels here.").fadeIn("slow");
			$("small.scale").hide().html("For PDF417, set width in Pixels here.").fadeIn("slow");
			
		} else {
			$('.QR_only').hide();
			$('.MATRIX_only').hide();
			$('.PDF417_only').hide();
			$('.BARCODE_only').show();
			$('#type').attr("disabled", false);
			
			$('input#height').val("50");
			$('input#scale').val("2");
			$("small.height").hide().html("1D Codes, set height (width is variable) here in").fadeIn("slow");
			$("small.scale").hide().html("pixels and set a multiplier here. (50 * 2 = 100px).").fadeIn("slow");
		}
	});
	
	$('#qrdata_type').change(function() {
		if ($('#qrdata_type').val() == "text") {
			$('.qr_text').show();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "link") {
			$('.qr_text').hide();
			$('.qr_link').show();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "sms") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').show();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "phone") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').show();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "vcard") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').show();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "mecard") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').show();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "email") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').show();
			$('.qr_wifi').hide();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "wifi") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').show();
			$('.qr_geo').hide();
		}
		
		if ($('#qrdata_type').val() == "geo") {
			$('.qr_text').hide();
			$('.qr_link').hide();
			$('.qr_sms').hide();
			$('.qr_phone').hide();
			$('.qr_vcard').hide();
			$('.qr_mecard').hide();
			$('.qr_email').hide();
			$('.qr_wifi').hide();
			$('.qr_geo').show();
		}
		
	});
	
	$('#bgcolor').wheelColorPicker({ dir: 'assets/img', format: 'css', userinput: true, preview: false });
	$('#color').wheelColorPicker({ dir: 'assets/img', format: 'css', userinput: true, preview: false });
	
	$('#form_generator').submit(function(e) {
		e.preventDefault();
		$(".result_container").html("<img src='assets/img/loading.gif'>");
		
		if ($('#file').val() == "") {
			$.post("barcode/barcode.processor.php", $("#form_generator").serialize(), function(data) {
				$(".result_container").html("<img src='barcode/barcode.processor.php?"+$("#form_generator").serialize()+"'>");
			});
		} else if (($('#file').val() != "") && ($('#folder').val() == "")) {
			$(".result_container").html("");
			window.location = "barcode/barcode.processor.php?"+$("#form_generator").serialize();
		} else {
			//$(".result_container").html("");
			$.post("barcode/barcode.processor.php?AJAX_REQUEST=1", $("#form_generator").serialize(), function(data) {
				var rand = Math.floor(Math.random()*99999);
				$(".result_container").html("<pre>"+data+"</pre><img src='"+data+"?rand="+rand.toString()+"'>");
			});
		}
		return false;
	});
	
});