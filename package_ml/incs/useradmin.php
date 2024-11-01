<?php
function ml_newFields () {
    global $current_user;
    $current_user = wp_get_current_user();
    $uid = $_GET['user_id'];
    if($uid == "") $uid = $current_user->ID;
    
    $cur_country = get_user_meta($uid,'user_country',true);
        
    $ulvl = getUserLvl();
    if($ulvl > 7) {
        echo "<h3>User Country</h3>
        <table class=\"form-table\">
        <tr>
        <th>Country</th>
        <td>
        <select name='user_country' id='user_country'>
        <option value='zz'>International</option>
        ";
        $rs = mysql_query("select * from wpcml_countries where isActive=1 order by printable_name asc");
        while($rw = mysql_fetch_assoc($rs)) {
            echo "<option value='" . $rw['iso'] . "'";
            if($cur_country == $rw['iso']) echo " selected ";
            echo ">" . $rw['printable_name'] . "</option>";
        }
        echo "</select></td></tr></table>";
    } else {
        $rs = mysql_query("select printable_name from wpcml_countries c where c.iso = '$cur_country'");
        if(mysql_num_rows($rs) > 0) {
            $rw = mysql_fetch_assoc($rs);
            $cur_country_name = $rw['printable_name'];
        }
        echo "<h3>User Country</h3>$cur_country_name";
    }
}
function ml_updateUserCountry($uid) {
    $ulvl = getUserLvl();
    if($ulvl > 7) {
        $country = $_POST['user_country'];
        update_usermeta($uid,'user_country',$country);
    }
}
?>
