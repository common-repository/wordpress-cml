<?php
function ml_saving_post_page($post_id) {
    $lang = $_POST['page_lang'];
    $basedon = $_POST['basedonpostid'];
    global $current_user;
    $uid = $current_user->ID;
    
    ml_update_custom_meta($post_id, 'page_lang', $lang);
    ml_update_custom_meta($post_id, 'page_lang_parent', $basedon);
    
    $rs = mysql_query("select ID from wp_posts where ID in (select post_parent from wp_posts where ID='$post_id')");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $parent_id = $rw['ID'];
    } else {
        $parent_id = $post_id;
    }
    
    
    $action = $_POST['publish'];
    if($action == "") { $action = $_POST['post_status']; }
    switch(strtolower($action)) {
        case "publish":
            $newstatus = "published";
            break;
        case "submit for review":
            $newstatus = "review";
            break;
        case "pending":
            $newstatus = "review";
            break;
        default:
            $newstatus = "draft";
            break;
    }
    
    $rs = mysql_query("select post_status_id from wpcml_post_status where post_parent='$parent_id' order by post_status_id desc limit 0,1");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $rsu = mysql_query("update wpcml_post_status set post_id='$post_id' where post_status_id='" . $rw['post_status_id'] . "'");
    }
    if($post_id != $parent_id) $rs = mysql_query("insert into wpcml_post_status (post_id,post_status,post_parent,post_author) values ('$parent_id','$newstatus','$parent_id','$post_author')");
}
function ml_update_custom_meta($postID, $field_name, $newvalue) {
    if($newvalue != "") {
        $root_page_arr = get_post_ancestors($postID);
        $root_page_id = $root_page_arr[0];
        if($root_page_id != "") {
            $postID = $root_page_id;
        }
        if(!get_post_meta($postID, $field_name)) {
            add_post_meta($postID, $field_name, $newvalue);
        } else {
            update_post_meta($postID, $field_name, $newvalue);
        }
    }
}
?>
