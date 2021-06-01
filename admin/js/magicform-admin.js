(function ($) {
	'use strict';

	/**
	 * Json result format
	 * 
	 * @param string type 
	 * @param string text 
	 */
	var resultFormat = function (type, text) {
		var icon = null;
		switch (type) {
			case "success":
				icon = "fa-check-circle";
				break;
			case "info":
				icon = "fa-info-circle";
				break;
			case "warning":
				icon = "fa-exclamation-triangle";
			case "error":
				icon = "fa-exclamation-circle";
			default:
		}
		return '<div class="alert alert-' + type + ' alert-dismissible show" role="alert"> <i class="fas ' + icon + '" ></i> ' + text + ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}

	/**
	 * Copy input to memory
	 * @param string id 
	 */
	var copyInput = function (id) {
		var copyText = document.getElementById(id);
		copyText.select();
		copyText.setSelectionRange(0, 99999)
		document.execCommand("copy");
	}

	function GoBackWithRefresh() {
		if ('referrer' in document) {
			window.location = document.referrer;
			/* OR */
			//location.replace(document.referrer);
		} else {
			window.history.back();
		}
	}

	$(function () {

		$(".mf-submission-back-btn").on("click",function(){
			GoBackWithRefresh();
		});

		/**
		 * Click at submission row
		 */
		$(".mf-submission td").not(".magicform_checkbox_column").on("click", function () {
			var id = $(this).closest("tr").attr("data-id");
			window.location = "?page=magicform_submission&subpage=detail&id=" + id;
		});

		/**
		 * Plugin Activate
		 */
		$(".mf-activate-btn").on("click", function () {
			var code = $("#magicform_purchase_code").val().trim();
			var domain = window.location.hostname;
			var btn = $(this);
			$(".mf-activate-result").html("");
			btn.find("i").addClass("fa-spin fa-spinner").removeClass("fa-thumbs-up");
			$.post(magicFormSettings.ajaxUrl, "action=magicform_verify&code=" + code + "&domain=" + domain, function (result) {
				btn.find("i").removeClass("fa-spin fa-spinner").addClass("fa-thumbs-up");
				if (result.success) {
					$(".mf-activate-result").html(resultFormat("success", result.data));
					setTimeout(function () {
						location.reload();
					}, 3000);
				} else {
					$(".mf-activate-result").html(resultFormat("warning", result.data));
				}
			}, 'json');
		});

		/**
		 * Plugin Deactivate
		*/
		$(".mf-deactivate-btn").on("click", function () {
			var btn = $(this);
			btn.find("i").addClass("fa-spin fa-spinner").removeClass("fa-trash");
			$.post(magicFormSettings.ajaxUrl, "action=magicform_deactivate", function (result) {
				btn.find("i").removeClass("fa-spin fa-spinner").addClass("fa-trash");
				if (result.success) {
					$(".mf-activate-result").html(resultFormat("success", result.data));
					setTimeout(function () {
						location.reload();
					}, 3000);
				} else {
					$(".mf-activate-result").html(resultFormat("warning", result.data));
				}
			}, 'json');
		});


		/**
		 * Send Feedback
		 */
		$(".mf-send-feedback").on("click", function () {
			var btn = $(this);
			btn.find("i").addClass("fas fa-spin fa-spinner").removeClass("far fa-check-circle");
			$.post(magicFormSettings.ajaxUrl, $("form[name='mf-feedback-form']").serialize() + "&action=magicform_send_feedback", function (result) {
				btn.find("i").removeClass("fas fa-spin fa-spinner").addClass("far fa-check-circle");
				if (result.success) {
					$(".mf-result").html(resultFormat("success", result.data));
				} else {
					$(".mf-result").html(resultFormat("warning", result.data));
				}
			}, 'json');
		});

		/**
		 * Create Form
		 */
		var templateId = "t0";
		$(".mf-create-form-btn").on("click", function () {
			var name = $("input[name='form_name']").val();
			if (name.trim() == "") {
				alert("Form name is required!");
				return;
			}
			var action = "magicform_save_form"
			if (templateId != "t0")
				action = "magicform_import_form"

			$.post(magicFormSettings.ajaxUrl, "action=" + action + "&name=" + name + "&templateId=" + templateId, function (result) {
				if (result.success) {
					window.location = ("?page=magicform_forms&subpage=create&id=" + result.data.id);
				} else { 
					$('#mf-createform-modal').modal('hide')
					$('#mf-purchase-warning-modal').modal('show')
				}
			}, 'json');
		});

		/**
		 * Choose Template
		*/
		$(".mf-template").on("click", function () {
			templateId = $(this).attr('data-id');
		})

		/**
		 * Archive Form Open Modal
		 */
		$(".mf-remove-form-btn").on("click", function () {
			var action = $(this).attr("id");

			var modal = $("#removeModal").modal("show");
			var itemName = $(this).closest(".mf-card").attr("data-name");

			if(action == "magicform_delete_form"){
				$(".mf-delete-message").css("display","block")
				$(".modal-title").text('پاک کردن فرم')
				$(".mf-delete-form-btn").html("<i class='fas fa-trash-alt'></i> حذف")
				modal.find("b.formName").text(""+itemName);
			}else 
				modal.find("b.formName").text(""+itemName);
				
			var formId = $(this).closest(".mf-card").attr("data-id");
			modal.find("input[name='action']").val(action);
			modal.find("input[name='formId']").val(formId);
		});

		/**
		 * Archive Form Btn Click
		 */
		$(".mf-delete-form-btn").on("click", function () {
			
			var formId = $("#removeModal input[name='formId']").val();
 			var action = $("#removeModal input[name='action']").val();
		
			jQuery.post(magicFormSettings.ajaxUrl, "action="+ action +"&form_id=" + formId, function (result) {
				if (result.success) {
					window.location = "?page=magicform_forms&active=1";
				}
			}, 'json');
		});

		/**
		 * Shortcode Open Modal 
		 */
		$(".mf-dropdown-shortcode-btn").on("click", function () {
			var formId = $(this).closest(".mf-card").attr("data-id");
			var modal = $("#mf-shortcode-modal").modal("show");
			modal.find("#mf-shortcode-input1").val('[magicform id=' + formId + ' type="inline"]');
			modal.find("#mf-shortcode-input2").val('echo do_shortcode("[magicform id=' + formId + ' type="inline"]");');
		});

		/**
		 * Shortcode copy
		 */
		$("#mf-copy-shortcode1, #mf-copy-shortcode2").on("click", function () {
			copyInput($(this).attr("data-href"));
			$(this).html('<i class="fas fa-copy"></i>کپی شد!');
		});

		/**
		 * Clone Form
		 */
		$(".mf-dropdown-clone-btn").on("click", function () {
			var formId = $(this).closest(".mf-card").attr("data-id");
			$.post(magicFormSettings.ajaxUrl,
				"action=magicform_clone_form&form_id=" + formId,
				function (result) {
					if (result.success) {
						window.location.href = "?page=magicform_forms&active=1"
					} else { }
				}, 'json');
		});

		/**
		 * Open Form for edit
		 */
		$(".mf-card[data-link]").on("click", function (e) {
			if (!e.target.classList.contains("mf-card-action") && !e.target.classList.contains("dropdown-item")) {
				var id = $(this).attr("data-id");
				window.location = "?page=magicform_forms&subpage=create&id=" + id;
			}
		});

		/**
		 * Goto Submission Detail
		 */
		$(".mf-submission td").not(".magicform_checkbox_column").on("click", function () {
			var id = $(this).closest("tr").attr("data-id");
			window.location = "?page=magicform_submissions&subpage=detail&id=" + id;
		});

		/**
		 * Submission multi selected actions
		 */
		$(".mf-multi-action").on("change", function () {
			var val = $(this).val();
			if (val != "") {
				//Check checkbox count
				var selectedCheckboxes = $(".magicform_checkbox_column input:checked");
				if (selectedCheckboxes.length == 0) {
					alert("Please select a submission to do this action.");
					$(this).val("");
					return false;
				}
				switch (val) {
					case "delete":
						$("#deleteModal").modal("show").find("b.submissionCount").html(selectedCheckboxes.length);
						break;
				}
			}
		});

		/**
		 * Select/Deselect all
		 */
		$("input[type='checkbox'][data-id='all']").on("change", function () {
			$(".magicform_checkbox_column input").prop("checked", $(this).prop("checked"));
		});

		/**
		 * Delete submissions
		 */
		$("#mf-delete-submission-btn").on("click", function () {
			var selectedCheckboxes = $(".magicform_checkbox_column input:checked");
			var ids = [];
			$.each(selectedCheckboxes, function (k, v) {
				ids.push($(v).attr("data-id"));
			})
			$.post(magicFormSettings.ajaxUrl, "action=magicform_delete_submissions&ids=" + ids.join(","), function (result) {
				if (result.success) {
					location.reload();
				}
			}, 'json');
		});

		/**
		 * Select email system
		 */
		$(".mf-select-email-system").on("click", function (e) {
			var emailSystem = $(this).val();
			if (emailSystem == "smtp") {
				$('#mf-sendgrid').css("display", "none");
				$('#mf-smtp').css("display", 'block');
				$('#mf-mailgun').css("display", "none");
			} else if (emailSystem == "sendgrid") {
				$('#mf-sendgrid').css("display", "block");
				$('#mf-smtp').css("display", 'none');
				$('#mf-mailgun').css("display", "none");
			} else if(emailSystem == "mailgun") {
				$('#mf-mailgun').css("display", "block");
				$('#mf-smtp').css("display", 'none');
				$('#mf-sendgrid').css("display", "none");
			}
		});

		/**
		 * Custom File Upload
		 */
		$('.custom-file-input').on('change', function () {
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
		 * Mail Test 
		*/

		$(".send-to-test").on("click", function() {
			var btn = $(this);
			var email = $("input[name='send_to']").val()
			btn.find("i").addClass("fas fa-spin fa-spinner").removeClass("far fa-check-circle");

			$.post(magicFormSettings.ajaxUrl,"&action=magicform_smtp_test" + "&email="+email, function (result) {
				btn.find("i").removeClass("fas fa-spin fa-spinner").addClass("far fa-check-circle");
				if (result.success) {
					$(".mf-mailtest-result").html(resultFormat("success", result.data));
				} else {
					$(".mf-mailtest-result").html(resultFormat("warning", result.data));
				}
			}, 'json');
		})

		/**
		 * Settings save form
		 */
		$(".mf-btn-settings-save").on("click", function () {
			var btn = $(this);
			var formName = $(this).attr("form-name");
			var form = $("form[name='" + formName + "']")
			postRequest(form.serialize(), form.attr('action-name'), btn);
		})

		/**
		 * Make Request
		 * 
		 * @param object formData 
		 * @param string action 
		 * @param object btn 
		 */
		function postRequest(formData, action, btn) {
			btn.find("i").addClass("fas fa-spin fa-spinner").removeClass("far fa-check-circle");
			$.post(magicFormSettings.ajaxUrl, formData + "&action=" + action, function (result) {
				btn.find("i").removeClass("fas fa-spin fa-spinner").addClass("far fa-check-circle");
				if (result.success) {
					$(".mf-result").html(resultFormat("success", result.data));
				} else {
					$(".mf-result").html(resultFormat("warning", result.data));
				}
			}, 'json');
		}

		/**
		 * Filter Forms
		 */
		$(".mf-forms-search input").on("keydown", function (e) {
			if (e.keyCode == 13) {
				var val = $(this).val();
				var query = window.location.search.substring(1);
				query = query.replace(/(&q=)(.*)/, '');
				window.location = "?" + query + '&q=' + val;
			}
		});

		/**
		 * Import Form 
		*/
		$(".mf-import-form-btn").on("click", function () {
			var name = $("#import_form_name").val();
			var fileData = $("input[type='file']")[0];
			var formData = new FormData();

			if (name.trim() == "") {
				alert(magicFormSettings.lang['You have to add form name']);
				return;
			}

			if (fileData.files.length < 1) {
				alert(magicFormSettings.lang['You have to select file']);
				return;
			}

			formData.append("upload_file", fileData.files[0]);
			formData.append("name", name)
			formData.append("action", "magicform_form_import");

			$.ajax({
				url: magicFormSettings.ajaxUrl,
				data: formData,
				type: "POST",
				contentType: false,
				processData: false,
				dataType: "json",
				success: function (result) {
					window.location = ("?page=magicform_forms&subpage=create&id=" + result.data.id);
				},
				error: function (response) {
					alert(response.responseText);
				}
			})
		});
	});
})(jQuery);
