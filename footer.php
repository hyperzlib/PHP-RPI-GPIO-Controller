</div>
<div class="modal fade" id="login">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">登录</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" id="loginform">
				  <fieldset>
					<div class="form-group">
					  <label for="inputUsername" class="col-md-2 control-label">用户名</label>

					  <div class="col-md-10">
						<input type="emain" class="form-control" id="inputUsername" name="username" placeholder="用户名">
					  </div>
					</div>
					<div class="form-group">
					  <label for="inputPassword" class="col-md-2 control-label">密码</label>

					  <div class="col-md-10">
						<input type="password" class="form-control" id="inputPassword" name="password" placeholder="密码">
					  </div>
					</div>
					<div class="form-group" style="margin-top: 0;">
					  <div class="col-md-offset-2 col-md-10">
						<div class="checkbox">
						  <label>
							<input type="checkbox" name="autologin" value="1"> 保持登录
						  </label>
						</div>
					  </div>
					</div>
				  </fieldset>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="clear">清空</button>
				<button type="submit" class="btn btn-primary" id="submit">提交</button>
			</div>
		</div>
	</div>
</div>
<script src="dist/js/ripples.min.js"></script>
<script src="dist/js/material.min.js"></script>
<script src="dist/js/snackbar.min.js"></script>
<script src="dist/js/jquery.nouislider.min.js"></script>
<script>
  $(function () {
    $.material.init();
    /*$(".shor").noUiSlider({
      start: 40,
      connect: "lower",
      range: {
        min: 0,
        max: 100
      }
    });

    $(".svert").noUiSlider({
      orientation: "vertical",
      start: 40,
      connect: "lower",
      range: {
        min: 0,
        max: 100
      }
    });*/
  });
$('#login #submit').click(function(e){
	e.preventDefault();
	$(this).addClass({disabled:'disabled'});
	$.post('api.php?mode=login', $('#loginform').serialize(), function(data){
		if(data.status == 0){
			$('#login .close').click();
			message('success', '登录成功');
			setTimeout(function(){ //计时
				ajaxreload();
			}, 3000);
		} else if(data.status == 1){
			message('warning', '登录失败', data.message);
		}
	}, 'json');
	$(this).removeClass('disabled');
});

$('#login #clear').click(function(){
	$('#loginform').find('input[type!=checkbox]').each(function(){
		$(this).val('');
	});
});
</script>
</body>
</html>