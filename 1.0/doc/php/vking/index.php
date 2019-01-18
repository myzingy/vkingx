<pre>
<?php 
include "dosql.class.php";
$db=new dbsql("127.0.0.1","root","","gallery");
//$db->set_result_type("object");
print_r($db->dosql(" id from gallery_photofiles","img_id"));
?>