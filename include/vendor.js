$(document).ready( function() {
	$('.buttonclose a').on('click', function() {
		$(this).parent().parent().fadeOut(1000);
		$(this).off('click');
		return false;
	});
	
	
	$('body').jpreLoader({
		splashID: "preload#main",
		loaderVPos: '70%',
		autoClose: false,
		closeBtnText: "Let's Begin!",
		splashFunction: function() {  
			//passing Splash Screen script to jPreLoader
			$('header').css({
				"margin-top": -225
			});
			$('panel').hide();
			$('footer').hide();
			$('navigator').hide();
		}
	}, function() {	//callback function
		$('header').animate({
			"margin-top": 0
		},200,function(){
			$('navigator').fadeIn(500);	
			$('panel').fadeIn(500);	
			$('footer').fadeIn(500);					
		});

		$('#preload#main').fadeOut(800);
	});
	
});