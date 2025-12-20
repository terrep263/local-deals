!function(e) {
	"use strict";
	var t = e(window);
	t.on("load", function() {
		var a = e(document),
			s = e("html, body");
		e(".loader-container").delay("333").fadeOut("slow"), 
		    a.on("click", ".sub-menu-toggler", function() {
			return e(this).closest("li").siblings().removeClass("active").find(".off-canvas-sub-menu").slideUp(200), e(this).closest("li").toggleClass("active").find(".off-canvas-sub-menu").slideToggle(200), !1
		}), a.on("click", ".side-menu-open", function() {
			e(".off-canvas").addClass("active")
		}), a.on("click", ".off-canvas-close", function() {
			e(".off-canvas, .side-user-panel").removeClass("active")
		}), a.on("click", ".side-user-menu-open", function() {
			e(".side-user-panel").addClass("active")
		}), t.on("scroll", function() {
			var a = e(".main-nav-header");
			t.scrollTop() ? a.addClass("fixed-top") : a.removeClass("fixed-top");
			var s = e("#back-to-top");
			t.scrollTop() > 300 ? s.addClass("show-back-to-top") : s.removeClass("show-back-to-top"), e(".skillbar").each(function() {
				e(this).find(".skillbar-bar").animate({
					width: e(this).attr("data-percent")
				}, 6e3)
			})
		}), a.on("click", "#back-to-top", function() {
			return s.animate({
				scrollTop: 0
			}, 1000), !1
		});
		
		// Card Carousel
		var n = e('[data-fancybox="preview-video"]');
		n.length && n.fancybox();
		var o = e(".card-carousel");
		o.length && o.owlCarousel({
			loop: false,
			items: 3,
			nav: true,
			dots: false,
			smartSpeed: 700,
			autoplay: true,
			navText: ['<i class="fal fa-angle-left"></i>', '<i class="fal fa-angle-right"></i>'],
			margin: 0,
		    responsive: {
				0: {
					items: 1
				},
				767: {
					items: 2
				},
				992: {
					items: 3
				}
			}
		});
		
		// Header Dropdown User Menu
		$('.dropdown-item').click(function(e){
			e.preventDefault();
			$('.select_season').text(($(this).text()));
		});
		var clicked=false;
		$('.user-menu').on('click', function(e) {
		  if(!clicked){
			$('.user-menu ul').css({'opacity':'1','visibility':'visible'});
			clicked=true;  
		  }else{
			$('.user-menu ul').css({'opacity':'0','visibility':'hidden'});
			clicked=false; 
		  }      
		});
		$("body").click(function(e){
		 if(e.target.className!=='content-user' && e.target.className!=='user-name' && e.target.id!=="userArrow" && e.target.id!=="login-user-pic" ){
			$('.user-menu ul').css({'opacity':'0','visibility':'hidden'});
			clicked=false; 
		 }
		});
		
		// Gallery Carousel
		var r = e(".gallery-carousel");
		r.length && r.owlCarousel({
			loop: !0,
			items: 1,
			nav: !0,
			dots: !1,
			smartSpeed: 700,
			autoplay: !1,
			navText: ['<i class="fal fa-angle-left"></i>', '<i class="fal fa-angle-right"></i>']
		}), e(".qtyDec, .qtyInc").on("click", function() {
			var t = e(this),
				a = t.parent().find(".qtyInput").val();
			if (t.hasClass("qtyInc")) var s = parseFloat(a) + 1;
			else s = a > 0 ? parseFloat(a) - 1 : 0;
			t.parent().find(".qtyInput").val(s)
		});
		
		// Select Picker
		var c = e(".counter");
		c.length && c.counterUp({
			delay: 20,
			time: 2e3
		}), e('[data-toggle="tooltip"]').tooltip();
		var d = e(".select-picker");
		d.length && d.selectpicker();
		var u = e(".date-picker");
		u.length && u.daterangepicker({
			opens: "left",
			singleDatePicker: !0,
			autoApply: !0,
			locale: {
				format: "DD-MM-YYYY"
			}
		});
		
		// Lazy
		var p = e(".lazy");
		p.length && p.Lazy({
			effect: "fadeIn"
		}), 
		e(".rating-item-progress").css("width", function() {
			return e(this).attr("aria-valuenow") + "%"
		});
		
		// Review Gallery
		var f = e('[data-fancybox="review-gallery"]');
		f.length && f.fancybox({
			thumbs: {
				autoStart: !0
			}
		});
		
		var v = e('[data-fancybox="review-gallery-two"]');
		v.length && v.fancybox({
			thumbs: {
				autoStart: !0
			}
		}), e(".toggle-password").on("click", function() {
			e(this).toggleClass("active");
			var t = e(".password-field");
			"password" === t.attr("type") ? t.attr("type", "text") : t.attr("type", "password")
		});
		
		// Tags Input
		var g = e(".tags-input");
		g.length && g.tagsinput({
			maxTags: 5,
			tagClass: "badge badge-info"
		}), e(".payment-method-label input[type='radio']").on("change", function() {
			e(this).parent().parent().addClass("active"), e(this).parent().parent().siblings().removeClass("active")
		});		
	})
}(jQuery);