<?php
$var_postype = "";
function tc_newpagecol($defaults) {
    if(!function_exists('ml_newpagecol')) {
        $default_ret = array("titley" => $defaults['title'], "author" => $defaults['author'], "categories" => $defaults['categories'], "tags" => $defaults['tags'], "comments" => $defaults['comments'], "date" => $defaults['date']);
        $default_ret['curstate'] = "Current Public State";
        return $default_ret;
    }
    return $defaults;
}
function tc_newpagecol_data($column_name, $post_id) {
    $ulvl = getUserLvl();
    if( $column_name == 'curstate' ) {
        if($_GET['post_status'] == "trash") {   
        } else {
            $postidts = $post_id;
            
            $rsc = mysql_query("select pub_page_id from wpcml_master_page_info where root_page_id='$post_id' and pub_page_id <> '0'");
            if(mysql_num_rows($rsc) > 0) {
                $rwc = mysql_fetch_assoc($rsc);
                $postidts = $rwc['pub_page_id'];
                $icon = "published.png";
                $post_status = "Published";
                echo "<a href=\"post.php?post=$postidts&action=edit\"><img src=\"" . CM_IMAGE_FOLDER . "/$icon\" style=\"width:20px;vertical-align:middle\" /> $post_status</a> " . $post_extra;
            } else {
                echo "No public version published";
            }
        }
    }
    if($column_name == "titley") {
        if(!function_exists('ml_newpagecol')) {
            $title = tc_post_title($post_id);
            echo $title;
        }
    }
}
function tc_handle_posts_where($where) {
    global $wpdb, $current_user;
    $purl = curPageURLinWP();
    if(strpos($purl,"upload.php") == 0) {
        $uid = $current_user->ID;
        $where .= " and ID IN (select post_parent from wpcml_post_status where ID=post_parent ) ";
    }
    return $where;
}
function tc_post_title($id) {
    $url = curPageURLinWP();
    if(strpos($url,"wp-admin") > 0) {
        global $current_user;
        $uid = $current_user->ID;
        
       
        $rw = mysql_fetch_assoc(mysql_query("select post_id as n from wpcml_post_status where post_parent='$id' order by post_status_id desc limit 0,1"));
        $useid = $rw['n'];
        $rw = mysql_fetch_assoc(mysql_query("select post_title from wp_posts where ID='$useid'"));
        $titletr = $rw['post_title'];
        
        $rw = mysql_fetch_assoc(mysql_query("select post_status from wpcml_post_status where post_id='$useid'"));
        $post_status = $rw['post_status'];
        
        switch($post_status) {
            case "published":
                $icon = "published.png";
                break;
            case "draft":
                $icon = "draft.png";
                break;
            case "review":
                $icon = "waiting.png";
                break;
        }
        return "<a href=\"post.php?post=$useid&action=edit\"><img src=\"" . CM_IMAGE_FOLDER . "/$icon\" style=\"width:20px;vertical-align:middle\" /> $titletr </a>";
    } else {
        echo $id;
    }
}
function tc_admin_head() {
    global $var_postype;
    if($_GET['post'] != "") {
        $rw = mysql_fetch_assoc(mysql_query("select post_type from wp_posts where ID='" . $_GET['post'] . "'"));
        $var_postype = $rw['post_type'];
        $changeposttype = getParentPostType($_GET['post']);
        $rs = mysql_query("update wp_posts set post_type='$changeposttype' where ID='" . $_GET['post'] . "'");
    }
}
function tc_admin_footer() {
    global $var_postype;
    if($_GET['post'] != "") {
        $rs = mysql_query("update wp_posts set post_type='" . $var_postype . "' where ID='" . $_GET['post'] . "'");
    }
}
function getParentPostType($pid) {
    $rw = mysql_fetch_assoc(mysql_query("select post_parent from wpcml_post_status where post_id='$pid'"));
    $rwc = mysql_fetch_assoc(mysql_query("select post_type from wp_posts where ID='" . $rw['post_parent'] . "'"));
    return $rwc['post_type'];
}
?>
