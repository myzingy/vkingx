<?php
header("charset:utf-8");
error_reporting(E_ALL & ~E_NOTICE);
define("FF",str_replace("\\", "/",dirname(__FILE__))."/");
define("TIME",time());
require_once(FF."include/common.php");
