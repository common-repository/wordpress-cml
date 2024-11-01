<?php
function getUserLvl() {
    global $current_user;
    $current_user = wp_get_current_user();
    return $current_user->user_level;
}
function getPageName($oldpostid,$showlink) {
    $rs = mysql_query("select post_title from wp_posts where ID='$oldpostid'");
    if(mysql_num_rows($rs) > 0) {
        if($showlink) $ret .= "<a href=\"post.php?post=$oldpostid&action=edit\">";
        $rw = mysql_fetch_assoc($rs);
        $ret .= stripslashes($rw['post_title']);
        if($showlink) $ret .= "</a>";
    } else {
        $ret = "<i>Base page</i>";
    }
    return $ret;
}

function getOldPostId($opid) {
    if(empty($opid)) {
        $postid = $_GET['post'];
        $opid = get_post_meta($postid,"page_lang_parent",true);
        return $opid;
    } else {
        return $opid;
    }
}
function curPageURLinWP() {
     $pageURL = 'http';
     if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
     $pageURL .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") { $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     } else { $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; }
     return $pageURL;
}
function get_post_by_title($page_title, $output = OBJECT) {
    global $wpdb;
        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='post'", $page_title ));
        if ( $post )
            return get_post($post, $output);

    return null;
}
function array_rpush(&$arr, $item) {
    $arr = array_pad($arr, -(count($arr) + 1), $item);
}
?>
