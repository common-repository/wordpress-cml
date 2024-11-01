<?php
function ml_setup_page() {
    $stage = $_GET['stage'];
    if($stage == "") $stage = "1";
    if($_GET['action'] == "updateActiveCountries") {
        $rs = mysql_query("update wpcml_countries set isActive='0'");
        foreach($_POST['countries'] as $arg => $val) {
            $rsu = mysql_query("update wpcml_countries set isActive='1' where iso='$val'");
        }
    }
    if($_GET['action'] == "updatePriv") {
        update_option('posts_priv',$_POST['posts_priv']);
        update_option('pages_priv',$_POST['pages_priv']);
    }
    ?>
    <link href="<?php echo ML_FILE_FOLDER; ?>/css/screen.css" rel="stylesheet" type="text/css" media="screen" />
    <ol style="margin-top:20px;">
        <li><a href="?page=setup&stage=1">Choose languages</a></li>
        <li><a href="?page=setup&stage=2">Choose preferences</a></li>
        <li>There is no 3, its that easy!</li>
    </ol>
    <?php if($stage == 1) { ?>
        <form method="post" action="?page=ml_setup&action=updateActiveCountries&stage=2" style="margin-top:20px">
        <table class="widefat">
        <thead><tr><th>Stage 1 : Choose Active Languages</th></tr></thead>
        <tbody>
        <tr><td>
        <h2>Currently Active</h2>
        <input type="checkbox" checked disabled> <label style="color: #aaa">International (default, cant be disabled)</label><br />
        <?php
        $i=1;
        $rs = mysql_query("select * from wpcml_countries where iso <> 'zz' and isActive='1' order by isCommon, printable_name");
        while($rw = mysql_fetch_assoc($rs)) {
            echo "<input type='checkbox' name='countries[]' id='countries' value='" . $rw['iso'] . "'";
            if($rw['isActive'] == "1") echo " checked ";
            echo " /> <label for=''>" . $rw['printable_name'] . "</label>";
            if($i%3 == 0) echo "<br />";
            $i++;
        }
        ?>
        <br />&nbsp;
        </td></tr>
        <tr><td><h2>Currently Inactive</h2>
        <?php
        $i=1;
        $oldC = "";
        $rs = mysql_query("select * from wpcml_countries where iso <> 'zz' and isActive='0' order by isCommon desc, printable_name");
        while($rw = mysql_fetch_assoc($rs)) {
            $isC = $rw['isCommon'];
            if($isC != $oldC) {
                if($isC == "1") echo "<b>Common</b><br />";
                if($isC == "0") echo "<br /> <br /><b>Uncommon</b><br />";
            }
            $oldC = $isC;
            echo "<input type='checkbox' name='countries[]' id='countries' value='" . $rw['iso'] . "'";
            if($rw['isActive'] == "1") echo " checked ";
            echo " /> <label for=''>" . $rw['printable_name'] . "</label>";
            if($i%3 == 0) echo "<br />";
            $i++;
        }
        ?>
        <br />&nbsp;
        </td></tr>
        <tr><td style="padding-top:10px;">
        <input type="submit" value="submit" class="button-primary" />
        <br />&nbsp;
        </td></tr>
        </tbody>
        </table>
        </form>
    <?php }
    if($stage == "2") {
        $posts_priv = get_option('posts_priv','alter');
        $pages_priv = get_option('pages_priv','alter');
        ?>
        <form method="post" action="?page=ml_setup&action=updatePriv&stage=3" style="margin-top:20px">
        <table class="widefat">
        <thead><tr><th colspan="2">Stage 2 : Choose Preferences</th></tr></thead>
        <tbody>
        <tr><td colspan="2">
        <p>
        With WPCML (full edition) you can choose to keep the base wordpress privelages for pages / posts to stay the same, or alter it to only allow admins to add new pages / posts. The latter
        will mean that any user beneath admin can only translate pages / posts that have been added by an admin.
        </p>
        <p>
        As this is the free lite version this functionality has been disabled. Check out <a href="http://www.wordpress-cml.com" target="_blank">http://www.wordpress-cml.com</a> to purchase and to see all the features in full version.
        </p>
        </td>
        </tr>
        </tbody>
        </table>
    <?php
    }
    if($stage == "3") {
    ?>
    <p>No really there isn't a stage 3, you're done!</p>
    <?php
    }
}
function ml_about_page() {
    echo "<p>Wordpress CML, the online corporate multi-language plugin</p>";
    echo "<p>Copyright &copy; " . date("Y") . " Wordpress CML.</p>";
}
?>
