<?php
if (!defined('_GNUBOARD_')) exit;

@include EYOOM_PATH.'/common.php';

if (!$member['mb_id']) alert('회원만 접근하실 수 있습니다.',G5_URL);

// 마이박스
@include_once(EYOOM_CORE_PATH.'/mypage/mybox.php');

// 설정저장 경로
$action_url = G5_URL.'/mypage/config_update.php';

$sql = "select a.bo_table, a.bo_subject, b.gr_subject from {$g5['board_table']} as a left join {$g5['group_table']} as b on a.gr_id = b.gr_id where 1 order by b.gr_subject asc, a.bo_subject asc";
$favorite = (array)unserialize($eyoomer['favorite']);
if(!$favorite) $favorite = array();
$res = sql_query($sql,false);
for($i=0; $row=sql_fetch_array($res);$i++) {
	$list[$i] = $row;
	if(in_array($row['bo_table'],$favorite)) $list[$i]['check'] = true;
}

// 사용자 프로그램
@include_once(EYOOM_USER_PATH.'/mypage/config.skin.php');
$tpl->define(array(
	'tab_category' => 'skin_bs/mypage/'.$eyoom['mypage_skin'].'/tabmenu.skin.html',
));

$tpl->define_template('mypage',$eyoom['mypage_skin'],'config.skin.html');
@include EYOOM_INC_PATH.'/tpl.assign.php';
$tpl->print_($tpl_name);