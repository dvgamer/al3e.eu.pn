<?php
date_default_timezone_set("Asia/Bangkok");
/* Get the port for the WWW service. */
$service_port = getservbyname('www', 'tcp');

/* Get the IP address for the target host. */
$address = gethostbyname('forum.tirkx.com');

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
	$in = "GET /main/tirkx_load_anime_per_topic.php?aid=$_GET[id] HTTP/1.1\r\n";
	$in .= "Host: forum.tirkx.com\r\n";
	$in .= "Connection: keep-alive\r\n";
	$in .= "Accept: application/json, text/javascript, */*; q=0.01\r\n";
	$in .= "X-Requested-With: XMLHttpRequest\r\n";
	$in .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.94 Safari/537.36\r\n";
	$in .= "Referer: http://forum.tirkx.com/main/showthread.php?$_GET[id]-\r\n";
	$in .= "Accept-Encoding: gzip,deflate,sdch\r\n";
	$in .= "Accept-Language: th-TH,th;q=0.8,en-US;q=0.6\r\n";
	$in .= "Cookie: bb_lastvisit=1362199723; bb_lastactivity=0; bb_userid=22200; bb_password=8c6e72b898af1e4152d21345090a3203; HstCfa2334126=1369183629017; HstCmu2334126=1369183629017; vbulletin_multiquote=; c_ref_2334126=https%3A%2F%2Fwww.facebook.com%2F; bb_thread_lastview=129a0f846849b450680c9defc540702816506349a-21-%7Bi-120981_i-1369441115_i-60169_i-1369441288_i-121158_i-1369561989_i-121275_i-1369655536_i-121279_i-1369658974_i-121278_i-1369659656_i-121397_i-1369744118_i-121439_i-1369751764_i-121449_i-1369754994_i-121483_i-1369788530_i-121594_i-1369839624_i-121598_i-1369840116_i-121729_i-1369924197_i-122019_i-1370116317_i-122018_i-1370146448_i-122061_i-1370154898_i-122052_i-1370154719_i-122093_i-1370170989_i-122091_i-1370170431_i-122147_i-1370191644_i-122163_i-1370217036_%7D; bb_sessionhash=2fa3c74aaef4d1ff8ebda995b8e06a14; MLRV_72334126=1370261667458; MLR72334126=1370262133000; HstCla2334126=1370262615157; HstPn2334126=8; HstPt2334126=129; HstCnv2334126=19; HstCns2334126=27\r\n\r\n";
	if ($result = @socket_connect($socket, $address, $service_port) === false) {
		echo "<strong>socket_connect() failed.</strong><br />Reason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
	} else {
		socket_write($socket, $in, strlen($in));
		$html_content = '';
		while ($out = socket_read($socket, 204800)) {
			$html_content .= $out;
		}
		socket_close($socket);
	}
}
@list($header, $content) = split("(\[\{)",$html_content);
@list($content, $other) = split("(\}\])",$content);
echo "[{".$content."}]";
?>