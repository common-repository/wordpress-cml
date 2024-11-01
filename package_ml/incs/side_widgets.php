<?php
function ml_restricted_access() {
    echo "<p>This page is for reference only. You cannot save any edits on this page, the content has been set by the admin for reference to translate.";
}

function ml_customposttype_image_box() {
    $ulvl = getUserLvl();
    $ulang = getUserLang();
    $postlang = getPostLang($_GET['lang']);
    if($ulang != $postlang) {
        if($ulvl < 8) {
            $pageurl = curPageURLinWP();
            if(strpos($pageurl,"post-new.php")>0) {
                $posts_priv = get_option('posts_priv');
                if($_GET['post_type'] == "page") { $posts_priv = get_option('pages_priv'); }
                if($posts_priv == "keep") {
                    
                } else {
                    add_meta_box( "restricted_access", "Restricted Access", 'ml_restricted_access', 'page','side');
                    add_meta_box( "restricted_access", "Restricted Access", 'ml_restricted_access', 'post','side');
                    remove_meta_box('submitdiv','page','side');
                    remove_meta_box('submitdiv','post','side');   
                }
            } else {
                add_meta_box( "restricted_access", "Restricted Access", 'ml_restricted_access', 'page','side');
                add_meta_box( "restricted_access", "Restricted Access", 'ml_restricted_access', 'post','side');
                remove_meta_box('submitdiv','page','side');
                remove_meta_box('submitdiv','post','side');  
            }
        }
    }
}

function ml_language_add_custom_box() {
    add_meta_box( "language_choice", "Language Data", 'ml_page_language_choice', 'page','side');
    add_meta_box( "language_choice", "Language Data", 'ml_page_language_choice', 'post','side');
}
function ml_page_language_choice() {
    $ulang = getUserLang();
    $ulvl = getUserLvl();
    $oldpostid = getOldPostId($_GET['oldpostid']);
    $curlang = getPostLang($_GET['lang']);
    echo "<p>Language of this page: " . getPrintableName($curlang) . "</p>";
    echo "<p>Based on page: " . getPageName($oldpostid,true) . "</p>";
    echo "<input type=\"hidden\" id=\"page_lang\" name=\"page_lang\" value=\"$curlang\" />";
    echo "<input type=\"hidden\" id=\"basedonpostid\" name=\"basedonpostid\" value=\"$oldpostid\" />";
}
?>
