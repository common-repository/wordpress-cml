<?php
function ml_newpagecol($defaults) {
    if($_GET['post_status'] == "trash") {
        $langs .= "Language";
    } else {
        $rs = getRs();
        while($rw = mysql_fetch_assoc($rs)) {
            $langs .= "<img src=\"" . ML_IMAGE_FOLDER . "/flags/" . $rw['iso'] . ".png\" title=\"" . $rw['printable_name'] . "\" style=\"width:20px\"/> ";
        }
    }
    $defaults['language'] = $langs;
    $ulvl = getUserLvl();
    if($ulvl > 8) {
        $default_ret = array("cb" => $defaults['cb'], "titley" => $defaults['title'], "language" => $defaults['language'], "comments" => $defaults['comments'], "date" => $defaults['date']);
    } else {
        $default_ret = array("titley" => $defaults['title'], "language" => $defaults['language'], "comments" => $defaults['comments'], "date" => $defaults['date']);
    }
    if(function_exists('tc_newpagecol')) {
        $default_ret['curstate'] = "Current Public State Of International";
    }
    return $default_ret;
}
function ml_newpagecol_data($column_name, $post_id) {
    $ulvl = getUserLvl();
    if( $column_name == 'language' ) {
        if($_GET['post_status'] == "trash") {
            $postlang = getPostLangFromPostId($post_id);
            echo getPrintableName($postlang);
        } else {
            $rs = getRs();
            while($rw = mysql_fetch_assoc($rs)) {
                $lang = $rw['iso'];
                $cname = $rw['printable_name'];
                $icon = "add.png";
                $posttype = "post";
                if($_GET['post_type'] == "page") $posttype = "page";
                $link = "post-new.php?post_type=$posttype&lang=$lang&oldpostid=$post_id";
                
                $rst = mysql_query("select * from wp_postmeta where meta_value='$post_id' and meta_key='page_lang_parent'");
                while($rwt=mysql_fetch_assoc($rst)) {
                    $posbl_post_id = $rwt['post_id'];
                    $xsql = " 1=1 ";
                    if(function_exists('tc_newpagecol')) {
                        $rsth = mysql_query("
                        select p.post_status,p.ID as post_id
                        from wp_postmeta w
                        inner join wp_posts p on w.post_id = p.ID
                        where meta_key='page_lang' and
                        w.post_id='$posbl_post_id' and
                        meta_value='$lang' and
                        p.post_status <> 'trash'
                        ");
                    } else {
                        $rsth = mysql_query("select p.post_status,p.post_id from wp_postmeta w inner join wpcml_post_status p on w.post_id = p.post_parent inner join wp_posts rwp on w.post_id = rwp.ID where $xsql and meta_key='page_lang' and w.post_id='$posbl_post_id' and meta_value='$lang' and rwp.post_status <> 'trash' order by p.post_status_id desc");
                    }
                    if(mysql_num_rows($rsth) > 0) {
                        $rwth = mysql_fetch_assoc($rsth);
                        $showpostid = $rwth['post_id'];
                        $post_status = strtolower($rwth['post_status']);
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
                            case "pending":
                                $icon = "waiting.png";
                                break;
                        }
                        $link = "post.php?post=" . $rwth['post_id'] . "&action=edit&shouldshow=" . $rwth['post_id'];
                    }
                }
                echo "<a href=\"$link\" title=\"$post_status: $cname\"><img src=\"" . CM_IMAGE_FOLDER . "/$icon\" style=\"width:20px\" /></a> ";
            }
        }
    } elseif($column_name == "titley") {
        if($_GET['post_status'] == "trash") {
            $rw = mysql_fetch_assoc(mysql_query("select post_title from wp_posts where ID='$post_id'"));
            $pt = $rw['post_title'];
            $pl = getPostLangFromPostId($post_id);
            echo "<img src=\"" . ML_IMAGE_FOLDER . "/flags/$pl.png\" style=\"width:15px\"> <strong>$pt</strong>";
        } else {
            $rw = mysql_fetch_assoc(mysql_query("select post_title from wp_posts where ID='$post_id'"));
            $pt = $rw['post_title'];
            echo "<img src=\"" . ML_IMAGE_FOLDER . "/flags/zz.png\" style=\"width:15px\"> <strong><a class=\"row-title\" href=\"post.php?post=" . $post_id . "&amp;action=edit\" title=\"Edit &#8220;" . $pt . "&#8221;\">" . $pt . "</a></strong>";
        }
    }
}
function ml_handle_posts_where($where) {
    global $wpdb;
    $purl = curPageURLinWP();
    if(strpos($purl,"upload.php") == 0) {
        if($_GET['post_status'] == "trash") {
            $where .= " ";
        } else {
            $where .= " and ID IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='page_lang' AND meta_value='zz') and ID NOT IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='page_lang_parent') ";
        }
    }
    return $where;
}
?>
