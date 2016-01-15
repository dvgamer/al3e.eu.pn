<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
date_default_timezone_set("Asia/Bangkok");
if ($handle = opendir('F://')) {
    echo "Directory handle: $handle<br>";
    echo "Entries:<br>";
    while (false !== ($entry = readdir($handle))) {
        echo substr($entry,4,1)."<br>";//iconv("tis-620","utf-8",$entry)."<br>";
    }
    closedir($handle);
}



$content1 = "Ah! My Goddess ~ Tatakau Tsubasa (2nd) (OAD)";
$content2 = "5 Centimeters Per Second (ระยะห่าง เวลา และพันธนาการของหัวใจ) (Movie)";
$content3 = "Azumanga Daioh {โรงเรียนป่วน นักเรียนเป๋อ} [TH]";
$content4 = "Cardcaptor Sakura {ซากุระ มือปราบไพ่ทาโรต์} [Blu-ray 1080p.JPN-THA!THA]{1980}";
$content5 = "%Kyoukai Senjou no Horizon • II [03!12] {2012-2}";
$content6  = 'Eureka Seven ~ Good Night, Sleep Tight, Young Lovers (Movie) [Blu-ray 1080p.THA-JPN]';
preg_match_all('/[^({[•~]+/', $content5, $match); 
//echo "<strong>Name: </strong>".$match['namejp']."<br>"; [A-Za-z0-9ก-๙เ!\',. -@#$]+
//echo "<strong>Thai: </strong>".$match['nameth']."<br>";
?><pre><?php echo print_r($match); ?></pre>
<?php
echo $match[0][0];

?>