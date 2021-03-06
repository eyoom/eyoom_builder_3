<?php
if (!defined('_GNUBOARD_')) exit;

@include EYOOM_PATH.'/common.php';

if (!$member['mb_id']) alert('회원만 접근하실 수 있습니다.',G5_URL);

// 마이박스
@include_once(EYOOM_CORE_PATH.'/mypage/mybox.php');

$page = (int)$_GET['page'];
if(!$page) $page = 1;
if(!$page_rows) $page_rows = 50;
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

// 팔로워
if($eyoomer['cnt_follower'] > 0) {
	$follower = array_slice($eb->get_user_info($eyoomer['follower']),$from_record,$page_rows);
}

// 사용자 프로그램
@include_once(EYOOM_USER_PATH.'/mypage/myfollower.skin.php');

$tpl->assign(array(
	'follower' => $follower,
));
$tpl->define_template('mypage',$eyoom['mypage_skin'],'follower.skin.html');
@include EYOOM_INC_PATH.'/tpl.assign.php';
$tpl->print_($tpl_name);