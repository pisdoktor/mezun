/* Created by jankoatwarpspeed.com */

(function($) {
	$.fn.formToWizard = function(options) {
		options = $.extend({  
			submitButton: "" 
		}, options); 
		
		var element = this;

		var steps = $(element).find("fieldset");
		var count = steps.size();
		var submitButtonName = "#" + options.submitButton;
		$(submitButtonName).hide();

		// 2
		$(element).before("<ul id='steps'></ul>");

		steps.each(function(i) {
			$(this).wrap("<div id='step" + i + "'></div>");
			$(this).append("<div id='step" + i + "commands' class='btn-group'></div>");
			

			// 2
			var name = $(this).find("legend").html();
			
			$("#steps").append("<li id='stepDesc" + i + "'>Aşama " + (i + 1) + "<span>" + name + "</span></li>");
			
			//ilk aşama
			if (i == 0) {
				createNextButton(i);
				selectStep(i);
			}
			//son aşama
			else if (i == count - 1) {
				$("#step" + i).hide();
				createPrevButton(i);
			}
			//ortadaki aşamalar
			else {
				$("#step" + i).hide();
				createPrevButton(i);
				createNextButton(i);
			}
		});

		function createPrevButton(i) {
			var stepName = "step" + i;
			$("#" + stepName + "commands").append("<a class='btn btn-default btn-sm' href='#' id='" + stepName + "Önceki' class='prev'>< Önceki</a>");

			$("#" + stepName + "Önceki").bind("click", function(e) {
				$("#" + stepName).hide();
				$("#step" + (i - 1)).show();
				$(submitButtonName).hide();
				selectStep(i - 1);
			});
		}

		function createNextButton(i) {
			var stepName = "step" + i;
			$("#" + stepName + "commands").append("<a class='btn btn-default btn-sm' href='#' id='" + stepName + "Sonraki' class='next'>Sonraki ></a>");

			$("#" + stepName + "Sonraki").bind("click", function(e) {
				$("#" + stepName).hide();
				$("#step" + (i + 1)).show();
				if (i + 2 == count) {
					$(submitButtonName).show();
				}
				selectStep(i + 1);
			});
		}

		function selectStep(i) {
			$("#steps li").removeClass("current");
			$("#stepDesc" + i).addClass("current");
		}

	}
})(jQuery); 