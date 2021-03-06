<?php
if (!defined('_EYOOM_IS_ADMIN_')) exit;

/**
 * 테마 설치폼 action 
 */
$theme_action_url = EYOOM_ADMIN_URL . "/?dir=theme&amp;pid=theme_form_update&amp;smode=1";

/**
 * 설치하고자 하는 테마명
 */
$theme_name = clean_xss_tags(trim($_GET['thema']));

/**
 * 홈페이지 주소
 */
$hostname = $eb->eyoom_host();

/**
 * 무료 테마인지 체크
 */
$is_free_theme = $eb->check_free_theme($theme_name);

/**
 * 테마 설정 폴더 업로드 체크
 */
$tmp_dir = G5_PATH.'/tmp';

/**
 * 유료테마 출처
 */
$is_cmall = false; // sir 콘텐츠몰
$cmall_check_file = $tmp_dir.'/g5_cmall.txt';
if (file_exists($cmall_check_file) && !is_dir($cmall_check_file)) {
    $g5_cmall = file($cmall_check_file);
    $it_id = $g5_cmall[0];
    $is_cmall = $it_id ? true: false;
}
unset($cmall_check_file);

/**
 * submit 버튼
 */
$frm_submit  = ' <div class="text-center margin-top-30 margin-bottom-30"> ';
$frm_submit .= ' <input type="submit" value="설치하기" id="btn_submit" class="btn-e btn-e-lg btn-e-red" accesskey="s">' ;
$frm_submit .= '</div>';

$atpl->assign(array(
	'frm_submit' 	=> $frm_submit,
	'hostname' 		=> $hostname,
	'theme_name' 	=> $theme_name,
	'is_free_theme' => $is_free_theme,
));