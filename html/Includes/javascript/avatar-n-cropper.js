$(document).ready(function(){
 	$(".img-wrapper").mouseover(function(){
		$(".img-wrapper button").show();
	});
	$(".img-wrapper").mouseout(function(){
		$(".img-wrapper button").hide();
	});

	$(".img-wrapper button").click(function(){
		var cropp = $("#img-box").croppie({
			viewport: {
				width: 250,
				height: 250,
			},
			boundary: {
				width: 280,
				height: 280
			}
		});

		var avatarPath = $("#avatar250").attr("src");
		
		cropp.croppie('bind', {
			url: avatarPath,
			points: [0,0,250,250]
		});
	});
});