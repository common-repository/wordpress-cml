<?php
function tc_saving_post_page($post_id) {
    global $current_user;
    $uid = $current_user->ID;
    
    
    $parent_id = $_POST['basedon'];
    if($parent_id == "" || $parent_id == "0") {
        $rs = mysql_query("select post_parent from wp_posts where ID='$post_id'");
        if(mysql_num_rows($rs) > 0) {
            $rw = mysql_fetch_assoc($rs);
            $parent_id = $rw['ID'];
        }
    }
    if($parent_id == "" || $parent_id == "0") {
        $parent_id = $_POST['ID'];
    }
    if($parent_id == "" || $parent_id == "0") {
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
    $post_author = $uid;
    
    
    $rs = mysql_query("select * from wpcml_post_status where post_id='$parent_id' order by post_status_id desc limit 0,1");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $rsu = mysql_query("update wpcml_post_status set post_id='$post_id' where post_status_id='" . $rw['post_status_id'] . "'");
    }
    
    $rs = mysql_query("select * from wpcml_post_status where post_id='$parent_id' and post_parent='$parent_id'");
    if(mysql_num_rows($rs) == 0) {
        $rs = mysql_query("insert into wpcml_post_status (post_id,post_status,post_parent,post_author) values ('$parent_id','$newstatus','$parent_id','$post_author')");
    }
    
    
    
    $rs = mysql_query("select * from wpcml_master_page_info where root_page_id='$parent_id'");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $mpid = $rw['mpid'];
    } else {
        $rsi = mysql_query("insert into wpcml_master_page_info (root_page_id) values ('$parent_id')");
        $mpid = mysql_insert_id();
    }
    
    $rsu = mysql_query("update wpcml_master_page_info set recent_status='$newstatus', recent_id='$parent_id' where mpid='$mpid'");
    if($newstatus == "published") {
        $rsu = mysql_query("update wpcml_master_page_info set pub_page_id='$parent_id' where mpid='$mpid'");
    } else {
        $rsc = mysql_query("select max(post_id) as n from wpcml_post_status where post_parent='$parent_id' and post_status='published'");
        if(mysql_num_rows($rsc) > 0) {
            $rwc = mysql_fetch_assoc($rsc);
            $rsu = mysql_query("update wpcml_master_page_info set pub_page_id='" . $rwc['n'] . "' where mpid='$mpid'");
        }
    }
    
}
?>
