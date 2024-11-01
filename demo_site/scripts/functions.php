<?php
/*
Wordpress CML Base Functions
Author: Nick Wilkins
Website: http://www.wordpress-cml.com
Version: 1.0

Notes:
The functions listed below must be included in your multi-language site for it to operate properly.
*/

/* Gets the language of the current page. Should be called at the top of all pages */
function getLang($lang=NULL) {
    if($lang) $_SESSION['lang'] = $lang;
    if(!$_SESSION['lang']) $_SESSION['lang'] = "zz";
    return $_SESSION['lang'];
}

/* Translates an ISO into a printable (English) name */
function showLang($iso) {
    $rs = mysql_query("select printable_name from wpcml_countries where iso='$iso'");
    if(!$rs) { echo "No language options. Is WPCML ML turned on?"; } else {
        if(mysql_num_rows($rs) > 0) {
            $rw = mysql_fetch_assoc($rs);
            return $rw['printable_name'];
        }
        return $iso;
    }
}

function isWPCMLTurnedOn() {
    $rs = mysql_query("select printable_name from wpcml_countries");
    if(!$rs) return false;
    return true;
}

function isWPCTCTurnedOn() {
    $rs = mysql_query("select * from wpcml_master_page_info");
    if(!$rs) return false;
    return true;
}

/*
Displays a menu based on Wordpress 3.0 Menu System.
Required: Menu module name
Optional: Language, whether to return the international menu name if a translation cant be found
Returns: Nothing
Notes: Echos an unordered list out to the screen
*/
function show_nav_menu($menu_module,$lang=NULL,$returnInt=true) {
    if(!$lang ) $lang = getLang();
    $rs = mysql_query("select term_taxonomy_id as ttid from wp_terms w inner join wp_term_taxonomy t on w.term_id = t.term_id where slug='$menu_module' and taxonomy='nav_menu'");
    if(mysql_num_rows($rs) > 0) {
        echo "<ul id=\"nav\">";
        $rw = mysql_fetch_assoc($rs);
        $ttid = $rw['ttid'];
        $rstoplevel = mysql_query("
        select m.meta_value, m.post_id, p.post_title, p.guid 
        from wp_postmeta m inner join wp_term_relationships r on r.object_id = m.post_id 
        inner join wp_posts p on p.ID=m.meta_value 
        inner join wp_posts p2 on p2.ID = m.post_id
        where r.term_taxonomy_id='$ttid' and m.meta_key='_menu_item_object_id' and m.post_id in (select post_id from wp_postmeta where meta_value='0' and meta_key='_menu_item_menu_item_parent') order by p2.menu_order");
        while($rwtl = mysql_fetch_assoc($rstoplevel)) {
            $tran_page_name = "";
            $tlpost_id = $rwtl['post_id'];
            $tlpost_mv = $rwtl['meta_value'];
            $post_title = $rwtl['post_title'];
            
            $tran_page_name = is_translated_page($tlpost_mv,$lang);
            if($tran_page_name=="" && $returnInt) { $tran_page_name = $post_title; }
            if($tran_page_name) { echo "<li><a href=\"?pageid=$tlpost_mv\">$tran_page_name</a></li>"; }
        }
        echo "</ul>";
    }
}

/*
Displays a translated page/post content based on a language and a pageid
Required: Page ID
Optional: Language
Returns: Nothing
Notes: Echos page content out to the screen
*/
function show_page_content($pid,$lang=NULL,$returnInt = true) {
    if(!$lang ) $lang = getLang();
    $pub_page_id = get_published_page_id($pid,$lang,true);
    if($pub_page_id) {
        $rs = mysql_query("select * from wp_posts where id='$pub_page_id'");
        if(mysql_num_rows($rs) > 0) {
            $rw = mysql_fetch_assoc($rs);
            echo nl2br($rw['post_content']);
        }
    }
}

/*
Returns a published page/post id for a certain language based on a parent page id
Required: Page ID, language
Optional: Whether to return the parent id if a translation cant be found
Returns: Published page id
*/
function get_published_page_id($parentid,$lang,$returnInt=true) {
    $wpcmlml = isWPCMLTurnedOn();
    $wpcmltc = isWPCTCTurnedOn();
    $rst = mysql_query("select * from wp_postmeta where meta_value='$parentid' and meta_key='page_lang_parent'");
    while($rwt=mysql_fetch_assoc($rst)) {
        $posbl_post_id = $rwt['post_id'];
        if($wpcmlml && !$wpcmltc) { 
            $rsth = mysql_query("select rwp.ID as post_id,rwp.post_title from wp_postmeta w inner  join wp_posts rwp on w.post_id = rwp.ID where rwp.post_status='publish' and meta_key='page_lang' and ID='$posbl_post_id' and meta_value='$lang'");
        }
        if($wpcmltc && $wpcmlml) {
            $rsth = mysql_query("
            select pub_page_id as post_id
            from wp_postmeta w inner join
            wp_posts p on w.post_id = p.ID inner join
            wpcml_master_page_info r on p.ID = r.root_page_id
            where
            meta_key='page_lang' and
            ID='$posbl_post_id' and
            meta_value='$lang'
            ");
        }
        if(mysql_num_rows($rsth) > 0) {
            $rwth = mysql_fetch_assoc($rsth);
            $showpostid = $rwth['post_id'];
            return $showpostid;
        }
    }
    if($returnInt) {
        if($lang == "zz") {
            return $parentid;
        } else {
            return get_published_page_id($parentid,"zz",true);
        }
    }
    return false;
}

/*
Display an unordered list of pages in a particular language
Required: None
Optional: Language, Whether to return the parent post title (ie International) if a translation cant be found
Returns: An unordered list of pages
*/
function show_pages($lang=NULL,$returnInt=NULL) {
    echo "<ul>";
    $wpcmlml = isWPCMLTurnedOn();
    $wpcmltc = isWPCTCTurnedOn();
    if($wpcmlml && !$wpcmltc) { 
        $rs = mysql_query("SELECT distinct p.* FROM wp_posts p inner join wp_postmeta m on p.ID = m.post_id where post_status='publish' and post_type='page' and meta_value='zz'");
    }
    if($wpcmltc && !$wpcmlml) {
        $rs = mysql_query("select m.post_id as ID FROM wp_posts p inner join wpcml_post_status m on p.ID = m.post_parent where m.post_status='published' and post_type='page' order by post_status_id desc limit 0,1");
    }
    if($wpcmlml && $wpcmltc) {
        $rs = mysql_query("SELECT distinct p.* FROM wp_posts p inner join wp_postmeta m on p.ID = m.post_id where post_status='publish' and post_type='page' and meta_value='zz'");
    }
    while($rw = mysql_fetch_assoc($rs)) {
        $pid = $rw['ID'];
        $pub_page_id = get_published_page_id($pid,$lang,true);
        if($pub_page_id) {
            $rsi = mysql_query("select * from wp_posts where id='$pub_page_id'");
            if(mysql_num_rows($rsi) > 0) {
                $rwi = mysql_fetch_assoc($rsi);
                echo "<li><a href=\"?pageid=$pid\">" . $rwi['post_title'] . "</a></li>";
            }
        }
    }
    echo "</ul>";
}

/*
Display an unordered list of posts in a particular language
Required: None
Optional: Language, Whether to return the parent post title (ie International) if a translation cant be found
Returns: An unordered list of pages
*/
function show_posts($lang=NULL,$returnInt=NULL) {
    echo "<ul>";
    $wpcmlml = isWPCMLTurnedOn();
    $wpcmltc = isWPCTCTurnedOn();
    if($wpcmlml && !$wpcmltc) { 
        $rs = mysql_query("SELECT distinct p.* FROM wp_posts p inner join wp_postmeta m on p.ID = m.post_id where post_status='publish' and post_type='post' and meta_value='zz'");
    }
    if($wpcmltc && !$wpcmlml) {
        $rs = mysql_query("select m.post_id as ID FROM wp_posts p inner join wpcml_post_status m on p.ID = m.post_parent where m.post_status='published' and post_type='post' order by post_status_id desc limit 0,1");
    }
    if($wpcmlml && $wpcmltc) {
        $rs = mysql_query("SELECT distinct p.* FROM wp_posts p inner join wp_postmeta m on p.ID = m.post_id where post_status='publish' and post_type='post' and meta_value='zz'");
    }
    while($rw = mysql_fetch_assoc($rs)) {
        $pid = $rw['ID'];
        $pub_page_id = get_published_page_id($pid,$lang,true);
        if($pub_page_id) {
            $rsi = mysql_query("select * from wp_posts where id='$pub_page_id'");
            if(mysql_num_rows($rsi) > 0) {
                $rwi = mysql_fetch_assoc($rsi);
                echo "<li><a href=\"?postid=$pid\">" . $rwi['post_title'] . "</a></li>";
            }
        }
    }
    echo "</ul>";
}

/*
Finds a translation for a post or page. The translation must be published to be found.
Required: Post or page id, Language
Optional: None
Returns: The translated term, or false if one cannot be found
*/
function is_translated_page($pid,$lang) {
    $rst = mysql_query("select * from wp_postmeta where meta_value='$pid' and meta_key='page_lang_parent'");
    while($rwt=mysql_fetch_assoc($rst)) {
        $posbl_post_id = $rwt['post_id'];
        $rsth = mysql_query("select p.post_status,p.post_id,rwp.post_title from wp_postmeta w inner join wpcml_post_status p on w.post_id = p.post_parent inner join wp_posts rwp on w.post_id = rwp.ID where p.post_status='published' and meta_key='page_lang' and w.post_id='$posbl_post_id' and meta_value='$lang' and rwp.post_status <> 'trash' order by p.post_status_id desc");
        if(mysql_num_rows($rsth) > 0) {
            $rwth = mysql_fetch_assoc($rsth);
            $showpostid = $rwth['post_id'];
            $post_status = strtolower($rwth['post_status']);
            switch($post_status) {
                case "published":
                    return $rwth['post_title'];
                    break;
            }
        }
    }
    return $false;
}

/*
Shows a list (in various ways) of the language options for the site
Required: Display type (list,select,flags)
Optional: None
Returns: Echos the options
*/
function showLanguageOptions($dtype) {
    $rs = mysql_query("select distinct * from wpcml_countries where isActive='1'");
    if(!$rs) { echo "No language options. Is WPCML ML turned on?"; } else {
        switch($dtype) {
            case "list":
                $i=0;
                while($rw = mysql_fetch_assoc($rs)) {
                    if($i > 0) echo ", ";
                    echo "<a href='?lang=" . $rw['iso'] . "'>" . $rw['printable_name'] . "</a>";
                    $i++;
                }
                break;
        }
    }
}
?>