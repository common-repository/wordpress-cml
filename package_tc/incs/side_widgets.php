<?php
function tc_page_info_box() {
    add_meta_box( "page_state_box", "Page Info", 'page_state_box', 'page','side');
    add_meta_box( "page_state_box", "Page Info", 'page_state_box', 'post','side');
}
function page_state_box() {
    $id = $_GET['post'];
    $rs = mysql_query("select post_status, post_parent from wpcml_post_status where post_id='$id' order by post_status_id asc");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $thisversion = $rw['post_status'];
        $basedon = $rw['post_parent'];
    } else { $thisversion = "(new)"; }
    
    $rs = mysql_query("select post_parent from wpcml_post_status where post_id='$id'");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $parentid = $rw['post_parent'];
        $rwi = mysql_fetch_assoc(mysql_query("select pub_page_id from wpcml_master_page_info where root_page_id='$parentid'"));
        $pubid = $rwi['pub_page_id'];
        
        if($pubid == $id) { $pubversion = " This one! "; } else {
            $title = getPageName($pubid,false);
            $pubversion = $title;
        }
    } else { $pubversion = "(new)"; }
    
    
    echo "<p>This version: " . $thisversion . "</p>";
    echo "<p>Public version: " . $pubversion . "</p>";
    echo "<input type='hidden' name='basedon' value='$basedon' />";
}

function tc_custom_submit_div() {
    $postid = $_GET['post'];
    $rs = mysql_query("select post_status, post_parent from wpcml_post_status where post_id='$postid' order by post_status_id asc");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        $thisversion = $rw['post_status'];
        $basedon = $rw['post_parent'];
    }
    
    if($thisversion == "published") {
        if($postid != $basedon) {
            add_meta_box( "restricted_access", "Restricted Access", 'tc_restricted_access', 'page','side');
            add_meta_box( "restricted_access", "Restricted Access", 'tc_restricted_access', 'post','side');
            remove_meta_box('submitdiv','page','side');
            remove_meta_box('submitdiv','post','side');    
        }
    }
}

function tc_restricted_access() {
    ?>
    <p>This is the public version of the page / post, and a more recent draft or pending review state has been created to work from.</p>
    <p>Please use that state to make any changes, and if necessary update the public version.</p>
    <?php
}
?>
