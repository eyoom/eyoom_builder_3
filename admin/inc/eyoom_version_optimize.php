<?php
if (!defined('_GNUBOARD_')) exit;
if ($is_admin != 'super') alert('최고관리자로 로그인 후 실행해 주십시오.', G5_URL);

if (defined('_EYOOM_VESION_')) {
	$eb_version = $eb->version_score(str_replace("EyoomBuilder_", "", _EYOOM_VESION_));
} else {
	$eb_version = $eb->version_score('1.0.6');
}

/**
 * EyoomBuilder_1.3.0
 */
if ($eb_version >= $eb->version_score('1.3.0')) {

	// 이윰 기본설정 파일에 이윰관리자 사용여부 선택할 수 있도록 변수 추가
	$config_basic = G5_DATA_PATH.'/eyoom.config.php';
	unset($eyoom, $_eyoom);
	include($config_basic);
	if(is_array($eyoom)) {
		foreach($eyoom as $key => $val) {
			$_eyoom[$key] = $val;
			if ($key == 'use_gnu_newwin' && !isset($eyoom['use_eyoom_admin'])) {
				$_eyoom['use_eyoom_admin'] = 'y';
			}
		}
		$qfile->save_file('eyoom',$config_basic,$_eyoom);
	}
}