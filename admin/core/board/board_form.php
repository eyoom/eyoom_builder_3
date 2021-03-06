<?php
$sub_menu = "300100";
if (!defined('_EYOOM_IS_ADMIN_')) exit;
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');

$action_url = EYOOM_ADMIN_URL . '/?dir=board&amp;pid=board_form_update&amp;smode=1';

$sql = " select count(*) as cnt from {$g5['group_table']} ";
$row = sql_fetch($sql);
if (!$row['cnt']) alert('게시판그룹이 한개 이상 생성되어야 합니다.', EYOOM_ADMIN_URL . '/?dir=board&amp;pid=boardgroup_form');

if (!isset($board['bo_device'])) {
    // 게시판 사용 필드 추가
    // both : pc, mobile 둘다 사용
    // pc : pc 전용 사용
    // mobile : mobile 전용 사용
    // none : 사용 안함
    sql_query(" ALTER TABLE  `{$g5['board_table']}` ADD  `bo_device` ENUM(  'both',  'pc',  'mobile' ) NOT NULL DEFAULT  'both' AFTER  `bo_subject` ", false);
}

if (!isset($board['bo_mobile_skin'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_skin` VARCHAR(255) NOT NULL DEFAULT '' AFTER `bo_skin` ", false);
}

if (!isset($board['bo_gallery_width'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_gallery_width` INT NOT NULL AFTER `bo_gallery_cols`,  ADD `bo_gallery_height` INT NOT NULL DEFAULT '0' AFTER `bo_gallery_width`,  ADD `bo_mobile_gallery_width` INT NOT NULL DEFAULT '0' AFTER `bo_gallery_height`,  ADD `bo_mobile_gallery_height` INT NOT NULL DEFAULT '0' AFTER `bo_mobile_gallery_width` ", false);
}

if (!isset($board['bo_mobile_subject_len'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_subject_len` INT(11) NOT NULL DEFAULT '0' AFTER `bo_subject_len` ", false);
}

if (!isset($board['bo_mobile_page_rows'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_page_rows` INT(11) NOT NULL DEFAULT '0' AFTER `bo_page_rows` ", false);
}

if (!isset($board['bo_mobile_content_head'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_content_head` TEXT NOT NULL AFTER `bo_content_head`, ADD `bo_mobile_content_tail` TEXT NOT NULL AFTER `bo_content_tail`", false);
}

if (!isset($board['bo_use_cert'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_cert` ENUM('','cert','adult') NOT NULL DEFAULT '' AFTER `bo_use_email` ", false);
}

if (!isset($board['bo_use_sns'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_sns` TINYINT NOT NULL DEFAULT '0' AFTER `bo_use_cert` ", false);

    $result = sql_query(" select bo_table from `{$g5['board_table']}` ");
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        sql_query(" ALTER TABLE `{$g5['write_prefix']}{$row['bo_table']}`
                    ADD `wr_facebook_user` VARCHAR(255) NOT NULL DEFAULT '' AFTER `wr_ip`,
                    ADD `wr_twitter_user` VARCHAR(255) NOT NULL DEFAULT '' AFTER `wr_facebook_user` ", false);
    }
}

$sql = " SHOW COLUMNS FROM `{$g5['board_table']}` LIKE 'bo_use_cert' ";
$row = sql_fetch($sql);
if(strpos($row['Type'], 'hp-') === false) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` CHANGE `bo_use_cert` `bo_use_cert` ENUM('','cert','adult','hp-cert','hp-adult') NOT NULL DEFAULT '' ", false);
}

if (!isset($board['bo_use_list_file'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_list_file` TINYINT NOT NULL DEFAULT '0' AFTER `bo_use_list_view` ", false);

    $result = sql_query(" select bo_table from `{$g5['board_table']}` ");
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        sql_query(" ALTER TABLE `{$g5['write_prefix']}{$row['bo_table']}`
                    ADD `wr_file` TINYINT NOT NULL DEFAULT '0' AFTER `wr_datetime` ", false);
    }
}

if (!isset($board['bo_mobile_subject'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_mobile_subject` VARCHAR(255) NOT NULL DEFAULT '' AFTER `bo_subject` ", false);
}

if (!isset($board['bo_use_captcha'])) {
    sql_query(" ALTER TABLE `{$g5['board_table']}` ADD `bo_use_captcha` TINYINT NOT NULL DEFAULT '0' AFTER `bo_use_sns` ");
}

$required = "";
$readonly = "";
if ($w == '') {

    $html_title .= ' 생성';

    $required = 'required';
    $required_valid = 'alnum_';
    $sound_only = '<strong class="sound_only">필수</strong>';

    $board['bo_count_delete'] = 1;
    $board['bo_count_modify'] = 1;
    $board['bo_read_point'] = $config['cf_read_point'];
    $board['bo_write_point'] = $config['cf_write_point'];
    $board['bo_comment_point'] = $config['cf_comment_point'];
    $board['bo_download_point'] = $config['cf_download_point'];

    $board['bo_gallery_cols'] = 4;
    $board['bo_gallery_width'] = 600;
    $board['bo_gallery_height'] = 0;
    $board['bo_mobile_gallery_width'] = 600;
    $board['bo_mobile_gallery_height'] = 0;
    $board['bo_table_width'] = 100;
    $board['bo_page_rows'] = $config['cf_page_rows'];
    $board['bo_mobile_page_rows'] = $config['cf_page_rows'];
    $board['bo_subject_len'] = 60;
    $board['bo_mobile_subject_len'] = 30;
    $board['bo_new'] = 24;
    $board['bo_hot'] = 100;
    $board['bo_image_width'] = 800;
    $board['bo_upload_count'] = 2;
    $board['bo_upload_size'] = 1048576;
    $board['bo_reply_order'] = 1;
    $board['bo_use_search'] = 1;
    $board['bo_skin'] = 'basic';
    $board['bo_mobile_skin'] = 'basic';
    $board['gr_id'] = $gr_id;
    $board['bo_use_secret'] = 0;
    $board['bo_include_head'] = '_head.php';
    $board['bo_include_tail'] = '_tail.php';

} else if ($w == 'u') {

    $html_title .= ' 수정';

    if (!$board['bo_table'])
        alert('존재하지 않은 게시판 입니다.');

    if ($is_admin == 'group') {
        if ($member['mb_id'] != $group['gr_admin'])
            alert('그룹이 틀립니다.');
    }

    $readonly = 'readonly';

}

if ($is_admin != 'super') {
    $group = get_group($board['gr_id']);
    $is_admin = is_admin($member['mb_id']);
}
$anchor_skin = "skin_bs/board/basic/board_form_anchor.skin.html";
adm_pg_anchor('anc_bo_basic', 		$anchor_skin);
adm_pg_anchor('anc_bo_auth', 		$anchor_skin);
adm_pg_anchor('anc_bo_function', 	$anchor_skin);
adm_pg_anchor('anc_bo_design', 		$anchor_skin);
adm_pg_anchor('anc_bo_point', 		$anchor_skin);
adm_pg_anchor('anc_bo_extra', 		$anchor_skin);

$board_auth = array(
	'list'		=> '목록보기',
	'read'		=> '글읽기',
	'write'		=> '글쓰기',
	'reply'		=> '글답변',
	'comment'	=> '댓글쓰기',
	'link'		=> '링크',
	'upload'	=> '업로드',
	'download'	=> '다운로드',
	'html'		=> 'HTML 쓰기'
);
$i = 0;
foreach($board_auth as $key => $val) {
	$bo_auth[$i]['item'] = $key;
	$bo_auth[$i]['text'] = $val;
	$bo_auth[$i]['field'] = "bo_{$key}_level";
	$bo_auth[$i]['level'] = $board["bo_{$key}_level"];
	$i++;
}

for ($i=1; $i<=10; $i++) {
	$bo_extra[$i]['bo_subject']	= $board['bo_' . $i . '_subj'];
	$bo_extra[$i]['bo_value'] 	= $board['bo_' . $i];
}

include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
$captcha_html = captcha_html();
$captcha_js   = chk_captcha_js();

$frm_submit  = ' <div class="text-center margin-top-30 margin-bottom-30"> ';
$frm_submit .= ' <input type="submit" value="확인" id="btn_submit" class="btn-e btn-e-lg btn-e-red" accesskey="s">' ;
$frm_submit .= ' <a href="' . EYOOM_ADMIN_URL . '/?dir=board&amp;pid=board_list&amp;'.$qstr.'" class="btn-e btn-e-lg btn-e-dark">목록</a> ';
if ($w == 'u') {
	$frm_submit .= ' <a href="' . EYOOM_ADMIN_URL . '/?dir=board&amp;pid=board_copy&amp;bo_table='.$bo_table.'&amp;wmode=1" onclick="eb_modal(this.href); return false;" class="btn-e btn-e-lg btn-e-dark">게시판복사</a> 
    <a href="'.G5_BBS_URL.'/board.php?bo_table='.$board['bo_table'].'" class="btn-e btn-e-lg btn-e-dark">게시판 바로가기</a> ';
}
$frm_submit .= '</div>';


include EYOOM_ADMIN_INC_PATH . "/atpl.assign.php";

$atpl->assign(array(
	'board' 		=> $board,
	'bo_auth' 		=> $bo_auth,
	'bo_extra' 	=> $bo_extra,
	'frm_submit' 	=> $frm_submit,
));