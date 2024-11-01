=== Wordpress CML Lite ===
Contributors: dodg
Donate link: http://www.wordpress-cml.com
Tags: multilanguage, multi language, post revision, published, draft, multilingual, multi lingual, multi-language, post access
Requires at least: 3.0
Tested up to: 3.0.5
Stable tag: trunk

A plugin that caters for corporate multi-lingual websites. It allows specific country level user control and a detailed post revision mechanism.

== Description ==

This individual lite plugin is a subversion of the suite of plugins that cater for corporate multi-lingual websites. It allows specific country level user control and a detailed post revision mechanism.

You can:
<ul>
<li>Set up the plugin with all the countries you want posts / pages translated into.</li>
<li>Designate users as specific country level users, giving them access to only translate their own language.</li>
<li>Allow a page / post to be published, while a newer version of the page is worked on in the background.</li>
<li>Admin's can see all states of the translated pages or posts over all the languages.</li>
<li>Give the admin control over the current public published version of a page while still seeing the progress of a newer version.</li>
</ul>

== Installation ==

1. Upload the whole wordress_cml_lite to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Follow more detailed instructions from http://www.wordpress-cml.com/?module=doc_gettingstarted

== Frequently Asked Questions ==

= I've just activated the plugins and my pages post screen is not what I was expecting. =

It is safer to activate the plugin without any current pages or posts already in the system. The plugin adds in extra meta information into the database about a page when it is saved, and all previous pages or posts wont have this information in there. We recommend activating these plugins from a fresh install of wordpress.

= How do I get my front ended theme to show the correct language choice? =

In the zip file of the download we provide an example site (in the 'demo_site' folder), that provides all the functions with examples on how to use the plugins in your theme. For multi-language options you will need to provide a language switcher of some sort that passes to the page the language choice, as well as use the functions we provide in your theme's 'functions.php' file (or create one if one doesn't exist). Some level of PHP knowledge is required here.

= What about The Corporate, it doesn't show the most recent published page? =

In the zip file of the download we provide an example site (in the 'demo_site' folder), that provides all the functions with examples on how to use the plugins in your theme. For The Corporate this will involve copying out the functions that retrieve the most recent published page / post, and placing that function into your themes 'functions.php' file. Some level of PHP knowledge is required here.

= A little more help .... =

Sure .... specifically however you probably want to use one of these two functions:
function show_page_content($pid,$lang=NULL,$returnInt = true) {
Which will take in a page / post id, and echo the "content" of the page / post in the new language.
Or maybe:
function get_published_page_id($parentid,$lang,$returnInt=true) {
Which you can pass a current page / post id and it will retrieve the translated page / post id (and published version if you are using The Corporate).
Alternatively you can use these higher level function calls:
function show_pages($lang=NULL,$returnInt=NULL) {
function show_posts($lang=NULL,$returnInt=NULL) {
Which does most of the above for you.
Just remember, all function calls and examples are fully documented in the download.

= I'm a little confused, I have my Wordpress loop and want to use the functions within there? =

Sure no problem. The best way to explain this is with another code example:
<?php while ( have_posts() ) : the_post(); ?>
// the loop display code
<?php endwhile; ?>
Is how it normally looks. We just need to include one of our functions in that loop:
<?php
while ( have_posts() ) : the_post()
$this_post_id = get_the_ID();
$translated_post_id = get_published_page_id($this_post_id,$mylang,true);
$mynewpost_object = get_post($translated_post_id);
// do what I want with the post object
// ie $title = $mynewpost_object->post_title
endwhile;
?>

= I don't use a theme, but just use wordpress for the backend, can I still use this? =

A big fat Yes on this one. In the zip file the folder 'demo_site' shows a basic html webpage with all the required functions. If you are only using wordpress for the backend then you should have some knowledge of PHP, and the structure should be easy to understand. All functions have in depth comments so you can quickly get up and running.

= Whats in the full paid for version? =

A full list of possible countries, rather than a selected list, option to turn on/off the multi-language and corporate sections individually, higher level of post / page revision control and lots more. Full features are available from <a href="http://www.wordpress-cml.com">wordpress-cml.com</a>.

== Screenshots ==

1. An example of the posts screen with a host of languages and a published public state
2. The UK users same screen - note how he/she only sees his/her own language choices
3. Choice of languages during setup

== Changelog ==

= 1.0 =
* First public release

== Upgrade Notice ==

= 1.0 =
No upgrade current present