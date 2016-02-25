$(document).ready(function(){
 	$(".img-group").mouseover(function(){
		$(".img-button").show();
	});
	$(".img-group").mouseout(function(){
		$(".img-button").hide();
	});

	var croppBox = null;
	var cropp;

	$("#img-edit-button").click(function(){
		$("#img-form-wrapper").slideDown();
	});

	function readURL(input) {
        if(input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                cropp.croppie('bind', {
					url: e.target.result,
					points: [0,0,250,250]
				});
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $('input[name="avatar-input"]').change(function(){
        $("#avatar-wrapper .img-group").hide();

        if(!croppBox){
	        croppBox = $("#croppbox");
			cropp = croppBox.croppie({
				viewport: {
					width: 250,
					height: 250,
				},
				boundary: {
					width: 250,
					height: 250
				}
			});
		}

		var avatarPath = $("#avatar250").attr("src");
		
		cropp.croppie('bind', {
			url: avatarPath,
			points: [0,0,250,250]
		});

		readURL(this);
	});

    var done = false;
	
	$("#upload-img-form").submit(function(event){
		if(!done){
			event.preventDefault();
			cropp.croppie("result", {type : "canvas"}).then(function(resp){
				$('<input>').attr({'type' : 'hidden', 'name' : 'newAvatar', 'value' : resp}).appendTo('#upload-img-form');
	    		done = true;
	    		$("#upload-img-form").submit();
			});
		}
	});
});