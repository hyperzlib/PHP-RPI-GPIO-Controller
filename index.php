<?php
/*
 *
 *  _____  _____   _____  _____ 
 * |  __ \|  __ \ / ____|/ ____|
 * | |__) | |__) | |  __| |     
 * |  ___/|  _  /| | |_ | |     
 * | |    | | \ \| |__| | |____ 
 * |_|    |_|  \_\\_____|\_____|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author hyperzlib
 * @github https://github.com/hyperzlib/PHP-RPI-GPIO-Controller/
 * @email mcleague@126.com
 *
 *
*/

$title = '主页';
include('header.php');
if(!isset($_SESSION['username'])){
?>
<div class="col-md-12">
<div class="alert alert-dismissible alert-primary">
  <h4>提示</h4>

  <p>请先登录</p>
</div>
</div>
<?php
} else {
?>
<div class="jumbotron col-md-8">
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>模式</th>
				<th style="width: 40%;">状态</th>
			</tr>
		</thead>
		<tbody>
			<?php
			for($i=0;$i<30;$i++){
				system("gpio mode ".$i." out");
				$val_array[$i] = trim(shell_exec("gpio read ".$i));
			}
			$i =0;
			for ($i = 0; $i < 30; $i++) {
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td>输出</td>
				<td>
					<div class="togglebutton">
						<label>
							<input type="checkbox" <?php echo ($val_array[$i] == 1)?'checked="checked"':'';?> onclick="toggleGPIO(<?php echo $i;?>)" class="switch-<?php echo $i;?>">
						</label>
					</div>
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>
<div class="col-md-4">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">系统状态</h3>
		</div>
		<div class="panel-body" id="sysinfo">
			<div class="message">
			</div>
			<p>cpu使用率</p>
			<div class="progress cpuuse">
				<div class="progress-bar progress-bar-info" style="width: 20%"></div>
			</div>
			<p>内存使用率</p>
			<div class="progress ramuse">
				<div class="progress-bar progress-bar-success" style="width: 20%"></div>
			</div>
			<p>磁盘使用率</p>
			<div class="progress diskuse">
				<div class="progress-bar progress-bar-primary" style="width: 20%"></div>
			</div>
		</div>
	</div>
</div>
<script>
var round = 0;
var threadSysinfo;
threadSysinfo = setInterval(function(){
	$.get('api.php?mode=sysinfo&round='+Date.parse(new Date()), function(data){
		if(data.status == 0){
			$('#sysinfo .message').html(data.message);
			$('#sysinfo .cpuuse .progress-bar').css({width:data.cpuuse+'%'});
			$('#sysinfo .ramuse .progress-bar').css({width:data.ramuse+'%'});
			$('#sysinfo .diskuse .progress-bar').css({width:data.diskuse+'%'});
		}
	}, 'json');
}, 2000);
</script>
<?php
}
include('footer.php');
?>