function message(type, title, txt) {
	setTimeout(function() {
		toastr.options = {
			closeButton: true,
			progressBar: true,
			showMethod: 'fadeIn',
			hideMethod: 'fadeOut',
			timeOut: 10000,
		};
		if (type == 'success') toastr.success(txt, title);
		if (type == 'info') toastr.info(txt, title);
		if (type == 'warning') toastr.warning(txt, title);
		if (type == 'error') toastr.error(txt, title);
		if (type == 'danger') toastr.error(txt, title);
	},0);
}

function referrer(){
	window.location.href = window.location.href;
}

function logout(){
	$.get('api.php?mode=login&logout', function(data){
		if(data == 'true'){
			message('success', '注销成功');
			ajaxreload();
		} else {
			message('warning', '注销失败');
		}
	}, 'text');
}

function ajaxreload(){
	$('body').load(window.location.href);
}

function toggleGPIO(id){
	$.post('api.php?mode=gpio', {id:id}, function(data){
		if(data.status == 0){
			if(data.mode == 0){ //开关为on
				$('.switch-'+id)[0].checked = true;
			} else if(data.mode == 1){ //开关为off
				$('.switch-'+id)[0].checked = false;
			}
		} else {
			return false;
		}
	}, 'json');
}