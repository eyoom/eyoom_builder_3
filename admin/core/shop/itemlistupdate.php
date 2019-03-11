<?php
$sub_menu = '400300';
if (!defined('_EYOOM_IS_ADMIN_')) exit;

check_demo();

check_admin_token();

if (!count($_POST['chk'])) {
    alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if ($_POST['act_button'] == "선택수정") {

    auth_check($auth[$sub_menu], 'w');

    for ($i=0; $i<count($_POST['chk']); $i++) {

        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        $sql = "update {$g5['g5_shop_item_table']}
                    set it_name        = '".sql_real_escape_string($_POST['it_name'][$k])."',
                        it_cust_price  = '".sql_real_escape_string($_POST['it_cust_price'][$k])."',
                        it_price       = '".sql_real_escape_string($_POST['it_price'][$k])."',
                        it_stock_qty   = '".sql_real_escape_string($_POST['it_stock_qty'][$k])."',
                        it_use         = '".sql_real_escape_string($_POST['it_use'][$k])."',
                        it_soldout     = '".sql_real_escape_string($_POST['it_soldout'][$k])."',
                        it_order       = '".sql_real_escape_string($_POST['it_order'][$k])."',
                        it_type1       = '".sql_real_escape_string($_POST['it_type1'][$k])."',
                        it_type2       = '".sql_real_escape_string($_POST['it_type2'][$k])."',
                        it_type3       = '".sql_real_escape_string($_POST['it_type3'][$k])."',
                        it_type4       = '".sql_real_escape_string($_POST['it_type4'][$k])."',
                        it_type5       = '".sql_real_escape_string($_POST['it_type5'][$k])."',
                       it_update_time = '".G5_TIME_YMDHIS."'
                 where it_id   = '{$_POST['it_id'][$k]}' ";
        sql_query($sql);
    }
} else if ($_POST['act_button'] == "선택삭제") {

    if ($is_admin != 'super')
        alert('상품 삭제는 최고관리자만 가능합니다.');

    auth_check($auth[$sub_menu], 'd');

    // _ITEM_DELETE_ 상수를 선언해야 itemdelete.inc.php 가 정상 작동함
    define('_ITEM_DELETE_', true);

    for ($i=0; $i<count($_POST['chk']); $i++) {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];

        // include 전에 $it_id 값을 반드시 넘겨야 함
        $it_id = $_POST['it_id'][$k];
        include (G5_ADMIN_PATH . '/shop_admin/itemdelete.inc.php');
    }
}

goto_url(EYOOM_ADMIN_URL . "/?dir=shop&amp;pid=itemlist&amp;sca=$sca&amp;sst=$sst&amp;sod=$sod&amp;sfl=$sfl&amp;stx=$stx&amp;page=$page");
?>
