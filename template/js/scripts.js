$(document).ready(function() {

	/* mobile menu */

	$(".js-menu_button").click(function() {
		$(this).toggleClass("active");
		$(".js-mobile_menu").toggleClass("active");
	});

	/* code */

	if ($("pre").length) {
		$("pre").addClass("prettyprint");
		PR.prettyPrint();
	}

	/* copy link */

	$(".js-share_button").click(function() {

		var link = $(this).attr("data-link");

		var $textarea = $("<textarea class='share_link'>");
		$("body").append($textarea);
		$textarea.val(link).select();
		document.execCommand("copy");
		$textarea.remove();

		$(this).addClass("active");

	});

	$(".js-share_button").mouseout(function(){
		$(this).removeClass("active");
	});

	/* votes */

	$(".js-vote_button").click(function() {
		var count = parseInt($(this).parent().find(".js-votes_count").text());
		if ($(this).hasClass("js-minus")) {
			$(this).toggleClass("active");
			if ($(this).hasClass("active")) {
				if ($(this).parent().find(".js-plus").hasClass("active")) {
					count = count - 2;
				} else {
					count = count - 1;
				}
			} else {
				count = count + 1;
			}
			$(this).parent().find(".js-plus").removeClass("active");
		}
		if ($(this).hasClass("js-plus")) {
			$(this).toggleClass("active");
			if ($(this).hasClass("active")) {
				if ($(this).parent().find(".js-minus").hasClass("active")) {
					count = count + 2;
				} else {
					count = count + 1;
				}
			} else {
				count = count - 1;
			}
			$(this).parent().find(".js-minus").removeClass("active");
		}
		$(this).parent().find(".js-votes_count").removeClass("green_color");
		$(this).parent().find(".js-votes_count").removeClass("red_color");

		if (count < 0) {
			$(this).parent().find(".js-votes_count").addClass("red_color");
		} else if (count > 0) {
			$(this).parent().find(".js-votes_count").addClass("green_color");
		}
		$(this).parent().find(".js-votes_count").text(count);
	});

});