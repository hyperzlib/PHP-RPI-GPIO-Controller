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
session_start();
if($_GET['mode']){
	switch($_GET['mode']){
		case 'sysinfo':
			sysinfo();
			break;
		case 'gpio':
			gpio();
			break;
		case 'login':
			login();
			break;
		default:
			header('Location: index.php');
	}
}

function login(){
	if(!isset($_SESSION['username'])){
		if(!isset($_POST['username'])){
			echo json_encode(['status' => 1, 'message' => '请完整填写信息']);
		} else {
			$users = include('config/users.php');
			$flag = false;
			foreach($users as $user){
				if($user['username'] == $_POST['username'] && $user['password'] == $_POST['password']){
					$flag = true;
					break;
				}
			}
			if($flag){
				$_SESSION['username'] = $_POST['username'];
				if(isset($_POST['autologin'])){
					session_set_cookie_params(365*24*60*60);
				}
				echo json_encode(['status' => 0]);
			} else {
				echo json_encode(['status' => 1, 'message' => '用户名或密码错误']);
			}
		}
	} elseif(isset($_GET['logout'])){
		unset($_SESSION['username']);
		echo 'true';
	}
}

function gpio(){
	require_once('lib/function.php');
	if(isset($_POST['id']) && isset($_SESSION['username'])){
		$id = $_POST['id'];
		$status = shell_exec('gpio read '.$id);
		if(trim($status) == '0'){
			echo root_exec('gpio write '.$id.' 1');
		} elseif($status == '1'){
			echo root_exec('gpio write '.$id.' 0');
		}
		echo json_encode(['status' => 0, 'mode' => intval($status)]);
	} else {
		echo json_encode(['status' => 1]);
	}
}

function sysinfo(){
	$ret = array();
	$ret['status'] = 0;
	$ret['message'] = 'test';
	if(!preg_match('/win/', strtolower(PHP_OS))){
		$top = shell_exec('top -n 1 -b');
		$top = explode("\n",$top);
		$arr = array();
		foreach($top as $one){
			if(preg_match('/:/',$one)){
				$tmp = explode(':',$one);
				$arr[trim($tmp[0])] = explode(',',$tmp[1]);
			}
		}
		$ret['cpuuse'] = round(floatval(str_replace('%us','',trim($arr['%Cpu(s)'][0]))), 1);
		$ret['ramall'] = intval(str_replace('k total','',trim($arr['KiB Mem'][0])));
		$ret['ramused'] = intval(str_replace('k used','',trim($arr['KiB Mem'][1])));
		$ret['ramfree'] = intval(str_replace('k free','',trim($arr['KiB Mem'][2])));
		$ret['ramuse'] = round(($ret['ramused']/$ret['ramall'])*100, 1);
		$cpu = file_get_contents('/proc/cpuinfo');
		$cpulist = array();
		preg_match('/model name[ ]{0,100}.*/',$cpu,$match);
		$cpulist[] = trim(str_replace(array('model name',':'),'',$match[0]));
		$ret['message'] = $cpulist[0];
		$dt = round(@disk_total_space(".")/(1024*1024*1024),3); //总
		$df = round(@disk_free_space(".")/(1024*1024*1024),3); //可用
		$du = $dt-$df; //已用
		$hdPercent = (floatval($dt)!=0)?round($du/$dt*100,2):0;
		$ret['diskuse'] = $hdPercent;
	} else {
		$ret['cpuuse'] = 10;
		$ret['ramall'] = '1024';
		$ret['ramuse'] = 50;
		$ret['ramfree'] = '1024';
	}
	echo json_encode($ret);
}