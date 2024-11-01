<?php
function getRs() {
    $ulang = getUserLang();
    $ulvl = getUserLvl();
    if($ulvl > 7) { $rs = mysql_query(QRY_GET_ACTIVE_COUNTRIES);
    } else {
        $rs = mysql_query("select * from wpcml_countries where isActive=1 and iso='$ulang' order by printable_name asc");
    }
    return $rs;
}
function getUserLang() {
    global $current_user;
    $current_user = wp_get_current_user();
    $uid = $current_user->ID;
    $cur_country = get_user_meta($uid,'user_country',true);
    return $cur_country;
}
function getPrintableName($iso) {
    $rs = mysql_query("select printable_name from wpcml_countries where iso='$iso'");
    if(mysql_num_rows($rs) > 0) {
        $rw = mysql_fetch_assoc($rs);
        return $rw['printable_name'];
    }
}
function getPostLang($getlang) {
    if(empty($getlang)) {
        $postid = $_GET['post'];
        $getlang = get_post_meta($postid,"page_lang");
        if(!empty($getlang)) {
            foreach($getlang as $lang) {
                if($lang != "") {
                    $thislang = $lang;
                }
            }
        }
        if($thislang == "") { $thislang = "zz"; }
        return $thislang;
    } else {
        return $getlang;
    }
}
function getPostLangFromPostId($postid) {
    $getlang = get_post_meta($postid,"page_lang",true);
    if($getlang == "") return "zz";
    return $getlang;
}
function wordpresscml_ml_deactivate() {
    $rs = mysql_query("drop table if exists wpcml_countries");
    $rsc = mysql_query("select * from wpcml_master_page_info");
    $rs = mysql_query("drop table if exists wpcml_post_status;");
}
function wordpresscml_ml_activate() {
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
    
    $rs = mysql_query("CREATE TABLE IF NOT EXISTS `wpcml_countries` (
      `iso` char(2) NOT NULL,
      `name` varchar(80) NOT NULL,
      `printable_name` varchar(80) NOT NULL,
      `iso3` char(3) DEFAULT NULL,
      `numcode` smallint(6) DEFAULT NULL,
      `isDefault` int(11) DEFAULT '0',
      `isActive` int(11) DEFAULT '0',
      `isCommon` int(11) DEFAULT '0',
      PRIMARY KEY (`iso`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1;");

    $rs = mysql_query("INSERT INTO `wpcml_countries` (`iso`, `name`, `printable_name`, `iso3`, `numcode`, `isDefault`, `isActive`, `isCommon`) VALUES
('au', 'AUSTRALIA', 'Australia', 'aus', 36, 0, 0,1),
('at', 'AUSTRIA', 'Austria', 'aut', 40, 0, 0,1),
('be', 'BELGIUM', 'Belgium', 'bel', 56, 0, 0,1),
('br', 'BRAZIL', 'Brazil', 'bra', 76, 0, 0,1),
('cz', 'CZECH REPUBLIC', 'Czech Republic', 'cze', 203, 0, 0,1),
('dk', 'DENMARK', 'Denmark', 'dnk', 208, 0, 0,1),
('fi', 'FINLAND', 'Finland', 'fin', 246, 0, 0,1),
('fr', 'FRANCE', 'France', 'fra', 250, 0, 0,1),
('de', 'GERMANY', 'Germany', 'deu', 276, 0, 0,1),
('in', 'INDIA', 'India', 'ind', 356, 0, 0,1),
('it', 'ITALY', 'Italy', 'ita', 380, 0, 0,1),
('nl', 'NETHERLANDS', 'Netherlands', 'nld', 528, 0, 0,1),
('no', 'NORWAY', 'Norway', 'nor', 578, 0, 0,1),
('pt', 'PORTUGAL', 'Portugal', 'prt', 620, 0, 0,1),
('ro', 'ROMANIA', 'Romania', 'rom', 642, 0, 0,1),
('ru', 'RUSSIAN FEDERATION', 'Russian Federation', 'rus', 643, 0, 0,1),
('za', 'SOUTH AFRICA', 'South Africa', 'zaf', 710, 0, 0,1),
('es', 'SPAIN', 'Spain', 'esp', 724, 0, 0,1),
('se', 'SWEDEN', 'Sweden', 'swe', 752, 0, 0,1),
('tr', 'TURKEY', 'Turkey', 'tur', 792, 0, 0,1),
('gb', 'UNITED KINGDOM', 'United Kingdom', 'gbr', 826, 0, 0,1),
('eu', 'EUROPEAN UNION', 'International site', 'eur', NULL, 0, 0, 0);");
}
?>