<?php
include_once('./_common.php');
include_once(EYOOM_PATH.'/common.php');

if ($is_admin != 'super') alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

// 이윰 기본설정 파일에 이윰관리자 사용여부 선택할 수 있도록 변수 추가
$config_basic = G5_DATA_PATH.'/eyoom.config.php';
unset($eyoom, $_eyoom);
include($config_basic);
if(is_array($eyoom)) {
	foreach($eyoom as $key => $val) {
		$_eyoom[$key] = $val;
		if ($key == 'use_eyoom_admin') {
			$_eyoom['use_eyoom_admin'] = 'y';
		}
	}
	$qfile->save_file('eyoom',$config_basic,$_eyoom);
}

goto_url(EYOOM_ADMIN_URL);