<?php
$sub_menu = '300600';
if (!defined('_EYOOM_IS_ADMIN_')) exit;
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], "w");

$action_url = EYOOM_ADMIN_URL . '/?dir=board&amp;pid=contentformupdate&amp;smode=1';

// 상단, 하단 파일경로 필드 추가
if(!sql_query(" select co_include_head from {$g5['content_table']} limit 1 ", false)) {
    $sql = " ALTER TABLE `{$g5['content_table']}`  ADD `co_include_head` VARCHAR( 255 ) NOT NULL ,
                                                    ADD `co_include_tail` VARCHAR( 255 ) NOT NULL ";
    sql_query($sql, false);
}

// html purifier 사용여부 필드
if(!sql_query(" select co_tag_filter_use from {$g5['content_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['content_table']}`
                    ADD `co_tag_filter_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `co_content` ", true);
    sql_query(" update {$g5['content_table']} set co_tag_filter_use = '1' ");
}

// 모바일 내용 추가
if(!sql_query(" select co_mobile_content from {$g5['content_table']} limit 1", false)) {
    sql_query(" ALTER TABLE `{$g5['content_table']}`
                    ADD `co_mobile_content` longtext NOT NULL AFTER `co_content` ", true);
}

// 스킨 설정 추가
if(!sql_query(" select co_skin from {$g5['content_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['content_table']}`
                    ADD `co_skin` varchar(255) NOT NULL DEFAULT '' AFTER `co_mobile_content`,
                    ADD `co_mobile_skin` varchar(255) NOT NULL DEFAULT '' AFTER `co_skin` ", true);
    sql_query(" update {$g5['content_table']} set co_skin = 'basic', co_mobile_skin = 'basic' ");
}

$html_title = "내용";
$g5['title'] = $html_title.' 관리';

if ($w == "u")
{
    $html_title = " 수정";
    $readonly = " readonly";

    $sql = " select * from {$g5['content_table']} where co_id = '$co_id' ";
    $co = sql_fetch($sql);
    if (!$co['co_id'])
        alert('등록된 자료가 없습니다.');
}
else
{
    $html_title = ' 입력';
    $co['co_html'] = 2;
    $co['co_skin'] = 'basic';
    $co['co_mobile_skin'] = 'basic';
}

$himg = G5_DATA_PATH.'/content/'.$co['co_id'].'_h';
if (file_exists($himg)) {
    $size = @getimagesize($himg);
    if($size[0] && $size[0] > 750)
        $co['himg_width'] = 750;
    else
        $co['himg_width'] = $size[0];
}

$timg = G5_DATA_PATH.'/content/'.$co['co_id'].'_t';
if (file_exists($timg)) {
    $size = @getimagesize($timg);
    if($size[0] && $size[0] > 750)
        $co['timg_width'] = 750;
    else
        $co['timg_width'] = $size[0];
}

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
$captcha_html = captcha_html();
$captcha_js   = chk_captcha_js();

include EYOOM_ADMIN_INC_PATH . "/atpl.assign.php";

$atpl->assign(array(
	'co' 		=> $co,
));