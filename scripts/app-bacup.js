(function ($) {
	'use strict';

	window.app = {
		name: 'FTrack',
		version: '1.1.0',
		color: {
			'primary': '#0cc2aa',
			'accent': '#a88add',
			'warn': '#fcc100',
			'info': '#6887ff',
			'success': '#6cc788',
			'warning': '#f77a99',
			'danger': '#f44455',
			'white': '#ffffff',
			'light': '#f1f2f3',
			'dark': '#2e3e4e',
			'black': '#2a2b3c'
		},
		setting: {
			theme: {
				primary: 'primary',
				accent: 'accent',
				warn: 'warn'
			},
			color: {
				primary:'#0cc2aa',
				accent:'#a88add',
				warn:'#fcc100'
			},
			folded: false,
			boxed: false,
			container: false,
			themeID: 1,
			bg: ''
		}
	};

	var setting = 'jqStorage-'+app.name+'-Setting',
					storage = $.localStorage;

	if( storage.isEmpty(setting) ) {
		storage.set(setting, app.setting);
	} else {
		app.setting = storage.get(setting);
	}

	var v = window.location.search.substring(1).split('&');
	for (var i = 0; i < v.length; i++) {
		var n = v[i].split('=');
		app.setting[n[0]] = (n[1] == "true" || n[1]== "false") ? (n[1] == "true") : n[1];
		storage.set(setting, app.setting);
	}

	$(".modal-profile").on('click', function(e) {
		alert('a');
		$('#frm_profile').modal('show');
	});

	$("#submitProfile").on('click', function(e) {
		e.preventDefault();
		var form = $('#submitProfile').serialize();

		$.ajax({
			type		: 'POST',
			url			: BASE_URL+'master/submit_profile',
			data		: form,
			dataType	: 'json',
			success		: function (data) {
				if (data.msg == 'success') {
					swal({title:"Success", text:"Successfully update", timer:2000, type:"success", showConfirmButton:false});
				} else {
					swal({title:"Failed", text:"Failed to update", timer:2000, type:"error", showConfirmButton:false});
				}
			}
		}).then(function () {
			$('#frm_profile').modal('hide');
		});
	});

	$(window).load(function() {
		setTimeout(function() {
			$('body').addClass('loaded');
		});
	});

	function init(){
		$('body').uiInclude();
	}
	init();
})(jQuery);
