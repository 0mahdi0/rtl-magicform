function magicform_inArray(needle, haystack) {
	var length = haystack.length;
	for(var i = 0; i < length; i++) {
		if(haystack[i] == needle) return true;
	}
	return false;
}

(function ($) {
	'use strict';


	/**
	 * When the DOM is ready
	 */
	$(function () {

		/**
		* Remove errors when key press at input
		*/
		$(".magicform input, .magicform textarea,.magicform select").on("keyup change", function () {
			var formGroup = $(this).closest(".mf-form-group");
			formGroup.removeClass("mf-has-error");
			formGroup.find(".mf-error").html("").hide();
		});


		/**
		 * Material Inputs
		 **/
		$.each($(".mf-material-container input, .mf-material-container textarea"), function (k, v) {
			if ($(v).val()) {
				$(this).closest(".mf-material-container").addClass("mf-active");
			}
		});

		/**
		 * Material Label placement
		 */
		$(".mf-material-container input,.mf-material-container textarea").on("keyup change", function () {
			var val = $(this).val();
			if (val != "") {
				$(this).closest(".mf-material-container").addClass("mf-active");
			} else {
				$(this).closest(".mf-material-container").removeClass("mf-active");
			}
		});

		/**
		 * Password View icon click
		 */
		$(".mf-view-icon-btn").on("click", function (e) {
			var input = $(this).closest(".mf-form-group").find("input");
			$(this).find("i").toggleClass("fa-eye fa-eye-slash");
			input.attr("type", input.attr("type") == "password" ? "text" : "password");
		});

		/**
		 * Range Slider
		 */
		$(".mf-custom-range").on("change", function () {
			var formGroup = $(this).closest(".mf-form-group");
			formGroup.find(".mf-value").text($(this).val());
		});

		/**
		 * Other input
		 */
		$(".mf-other-input").on("focus", function () {
			var id = $(this).attr('id');
			$('#' + id).prop('checked', true);
		});

		/**
		 * Datepicker
		 */

		$('.mf-datepicker').on("click",function () {
			var datePicker = $(this);
			datePicker.airdatepicker({
				timepicker: datePicker.attr('data-timepicker'),
				language: datePicker.attr('data-language'),
				autoClose: true,
				dateFormat: datePicker.attr('date-format'),
				timeFormat: "hh:ii",
				minDate: !datePicker.attr('min-date') ? false : new Date(datePicker.attr('min-date')),
				maxDate: !datePicker.attr('max-date') ? false : new Date(datePicker.attr('max-date')),
				onSelect: function (fd, d, picker) {
					// Do nothing if selection was cleared
					if (!d) return;
					var el = document.getElementById("date-" + datePicker.attr('id'))
					el.value = fd
					var event = new Event('change');
					el.dispatchEvent(event);
				}
			})
		});

		/**
		 * Custom File Upload
		 */
		$('.mf-custom-file-input').on('change', function () {
			var fileList = $(this).get(0).files;
			var label = $(this).next()
			if (fileList.length > 0) {
				var fileName = $(this).get(0).files[0].name;
				label.text(fileName);
			} else {
				label.text(label.attr("data-placeholder"));
			}
		});

		/**
		 * Signature Field
		 */
		var signaturePads = [];
		$.each($(".mf-signature"), function (k, v) {
			var el = $(v);
			var canvas = el.get(0);
			var signaturePad = new SignaturePad(canvas, {
				penColor: el.attr("pencolor"),
				onEnd: function () {
					var elem = document.querySelector("input[name='" + el.attr('target') + "']");
					elem.value = signaturePad.toDataURL();
					var event = new Event('change');
					elem.dispatchEvent(event);
				}
			});
			signaturePads.push({ id: el.attr("id"), signature: signaturePad })
		});


		/**
		 * Signature Clear
		 */
		$('.mf-remove-signature').on("click", function () {
			var id = $(this).attr("id");
			var signatureId = id.replace(/.[^_]+$/, '')
			var obj = signaturePads.find(item => item.id == id)
			obj.signature.clear();
			$("#"+signatureId).val("");
			$( "#"+signatureId ).trigger( "change" );
		});

		var idArray = []
		$.each($(".mf-termsofuse"), function (k, v) {
			var termOfUseValue = $(v).text();
            var regex = /\{([^{}]+)\}/g
            var termOfUseVariable = termOfUseValue.match(regex);
            if(Array.isArray(termOfUseVariable)){
				for(var i=0; i<termOfUseVariable.length;i++){
					var id = termOfUseVariable[i].split("|")[1].replace("}","");
					
					if(idArray.indexOf("#"+id) === -1) {
						var selector = "";
						var htmlElement = "";
						if(id.search("radioButton") !== -1 || id.search("checkBox") !== -1 || id.search("thumbnailSelector") !== -1){
							idArray.push("[name='"+id+"_value']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_value' ></label>"
						}
						else if(id.search("email")!==-1){
							idArray.push("[name='"+id+"_email']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_email' ></label>"
						}
						else if(id.search("password")!==-1){
							idArray.push("[name='"+id+"_password']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_password' ></label>"
						}
						else if(id.search("name")!==-1){
							idArray.push("[name='"+id+"_title']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_title' ></label>"
							idArray.push("[name='"+id+"_name']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_name' ></label>"
							idArray.push("[name='"+id+"_middle']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_middle' ></label>"
							idArray.push("[name='"+id+"_surname']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_surname' ></label>"
						}
						else if(id.search("address")!==-1){
							idArray.push("[name='"+id+"_address1']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_address1' ></label>"
							idArray.push("[name='"+id+"_address2']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_address2' ></label>"
							idArray.push("[name='"+id+"_city']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_city' ></label>"
							idArray.push("[name='"+id+"_state']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_state' ></label>"
							idArray.push("[name='"+id+"_zip']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_zip' ></label>"
							idArray.push("[name='"+id+"_country']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_country' ></label>"
						}
						else if(id.search("phone")!==-1){
							idArray.push("[name='"+id+"_phoneCode']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_phoneCode' ></label>"
							idArray.push("[name='"+id+"_phone']")
							htmlElement += "<label id='mf-terms-element' class='terms_"+ id +"_phone' ></label>"
						}else{
							selector = "[name='"+id+"']";
							idArray.push(selector)
							htmlElement += "<label class='terms_"+ id +"' id='mf-terms-element' ></label>"
						}
					}
					
					var replace = $(v).html().replace(termOfUseVariable[i], htmlElement);
					$(v).html(replace);
				}
			}
		})
		
		if(idArray.length > 0){
			var ids = idArray.join(",")
			$(ids).on("change input", function(){
				var id = $(this).attr('name')
				if(id.search("signature") !== -1){
					$( ".terms_"+id ).html( "<img src='"+ $(this).val() +"'>" );
				}else if(id.search("checkBox")!== -1 || id.search("thumbnailSelector")!== -1){
					var checked = []
					$.each($("input[name='"+id+"']:checked"), function(){
						checked.push($(this).val());
					});
					$( ".terms_"+id ).text( checked.join() );
				}else{
					$(".terms_"+id).text(" " + $(this).val() + " ");
				}
			})
		}	
	});
})(jQuery);