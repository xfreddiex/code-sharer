$(document).ready(function(){

	$('.search-wrapper .pagination button').click(function(){
		window.location.href = '/search?q='+$("#results").attr("q")+'&search='+$("#results").attr("search")+'&page='+$(this).val();
	});


});