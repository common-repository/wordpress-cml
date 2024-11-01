<?php
/*
Wordpress CML Databse Connection
Author: Nick Wilkins
Website: http://www.wordpress-cml.com
Version: 1.0

Notes:
An example database connection script. Alter the values as necessary
*/
if(! @mysql_connect("localhost", "wordpresscml", "g0g0daddy")) { echo("Error connecting to MYSQL"); die; }
if(! @mysql_select_db("wordpress_cml")) { echo("Error connecting to the database"); die; }
if(!session_id()) session_start();
?>