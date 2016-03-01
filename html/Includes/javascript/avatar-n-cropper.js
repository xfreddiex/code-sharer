$(document).ready(function(){

	var croppBox = null;
	var cropp;

	$("#img-edit-button").click(function(){
		$("#img-form-wrapper").slideToggle();
	});

	function readURL(input) {
        if(input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                cropp.croppie('bind', {
					url: e.target.result,
				});
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $('input[name="avatar-input"]').change(function(){
        
		var width = $("#avatar250").innerWidth();
		var height = $("#avatar250").innerHeight();

        $("#avatar-wrapper .img-group").hide();

        if(!croppBox){
	        croppBox = $("#croppbox");
			cropp = croppBox.croppie({
				viewport: {
					width: width,
					height: height
				},
				boundary: {
					width: width,
					height: height
				}
			});
		}
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