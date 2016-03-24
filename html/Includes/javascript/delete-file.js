$(document).ready(function(){
	
	bindDeleteButtons();

	function bindDeleteButtons(){
			$('button[name="delete-file"]').click(function(){
			var url = "/pack/" + $(this).attr("pack") + "/" + $(this).attr("file") + "/delete";
			$.ajax({
				type: 'GET',
				url: url,
				dataType : "json",
				context: $(this),
				success: function(response){
					$("#files-list").load("/pack/" + this.attr("pack") + "/files-list", function(){
			           bindDeleteButtons(); 
			        });
				}
			});
		});
	}
});