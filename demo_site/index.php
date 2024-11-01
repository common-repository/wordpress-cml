<?php
include("scripts/dbconnex.php");
include("scripts/functions.php");
$lang = getLang($_GET['lang']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="eu" lang="eu"> 
    <head> 
        <meta http-equiv="content-language" content="<?php echo $lang; ?>" /> 
        <meta name="language" content="<?php echo $lang; ?>" /> 
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /> 
        <meta http-equiv="X-UA-Compatible" content="chrome=1" /> 
        <meta name="description" content="Wordpress CML Demo Site" /> 
        <meta name="keywords" content="wordpress,corporate,multilanguage,multi lingual,multi language,multilingual,bilingual,post revision control,page control" /> 
        <meta name="robots" content="index,follow" /> 
        <meta name="revisit-after" content="28 days" /> 
        <title>Wordpress CML Demo Site</title> 
        <link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    </head>
<body class="wrap"> 
<h1>Current Language: <?php echo showLang($lang); ?></h1>
<br />
Change languge:
<?php showLanguageOptions('list');?>

<h1>Navigation Menu / Pages</h1>
<?php show_nav_menu("test_menu",$lang,true); ?>

<h1>Published Posts</h1>
<?php show_posts($lang,true); ?>

<h1>Published Pages</h1>
<?php show_pages($lang,true); ?>

<?php if($_GET['pageid'] != "") { ?>
<h1>Page View</h1>
<?php show_page_content($_GET['pageid'],$lang,true); ?>
<?php } ?>

<?php if($_GET['postid'] != "") { ?>
<h1>Post View</h1>
<?php show_page_content($_GET['postid'],$lang,true); ?>
<?php } ?>
</body>
</html>