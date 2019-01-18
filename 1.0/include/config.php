<?php
$host="http://{$_SERVER['HTTP_HOST']}";
$index_page="index.php";
list($path)=explode($index_page,$_SERVER['REQUEST_URI']);
$base_url=$host.$path;
$file="lattice";
///cache start
$cache=false;