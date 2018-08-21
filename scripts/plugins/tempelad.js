'use strict';
socket.on('broadcast-request', function(data) {
	$.ajax({
		type		: 'get',
		url 		: BASE_URL+'api/requestCar',
		datatype 	: 'json',
		success		: function(result) {
			if(result != 0) {
				$(".ta-request").html('<b class="label rounded primary pos-rlt text-sm m-r-xs">'+result+'</b>');
			}
		}
	});

	$.ajax({
		type		: 'get',
		url 		: BASE_URL+'api/requestWithdraw',
		datatype 	: 'json',
		success		: function(result) {
			if(result != 0) {
				console.log('a');
				$(".ta-withdraw").html('<b class="label rounded primary pos-rlt text-sm m-r-xs">'+result+'</b>');
			}
		}
	});
});