<?php
if(isset($_GET['id'])):
$html_content = file_get_contents("http://video.mthai.com/player.php?id=$_GET[id]");
@list($header, $content) = split("url: '",$html_content);
@list($content, $other) = split("',",$content);
@list($header, $name) = split("<meta property=\"og:title\" content=\"",$html_content);
@list($name, $other) = split("\" />",$name);
echo json_encode(array('name'=>$name,'link'=>$content));
endif;
?>