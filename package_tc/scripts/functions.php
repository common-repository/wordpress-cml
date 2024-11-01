<?php
function wordpresscml_tc_deactivate() {
    $rs = mysql_query("drop table if exists wpcml_master_page_info;");
    $rs = mysql_query("drop table if exists wpcml_post_status;");
}
function wordpresscml_tc_activate() {
    $rs = mysql_query("
    CREATE TABLE IF NOT EXISTS `wpcml_post_status` (
      `post_status_id` int(11) NOT NULL AUTO_INCREMENT,
      `post_id` int(11) NOT NULL,
      `post_status` varchar(20) DEFAULT NULL,
      `post_parent` int(11) DEFAULT NULL,
      `post_author` int(11) DEFAULT NULL,
      PRIMARY KEY (`post_status_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
    ");
    $rs = mysql_query("
    CREATE TABLE IF NOT EXISTS `wpcml_master_page_info` (
      `mpid` int(11) NOT NULL AUTO_INCREMENT,
      `root_page_id` int(11) NOT NULL,
      `pub_page_id` int(11) NOT NULL,
      `draft_review_status` varchar(20) DEFAULT NULL,
      `draft_review_id` int(11) DEFAULT NULL,
      PRIMARY KEY (`mpid`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
    ");
}
?>