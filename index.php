<?php if(!isset($_GET['store'])): ?>
<!DOCTYPE HTML>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Al3e-FS :: ANIME STORE</title>
<!--[if IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script language="javascript" src="include/jquery-2.0.3.min.js"></script>
<script language="javascript" src="include/jquery.ui.min.js"></script>
<script language="javascript" src="include/jQuery.jPlayer.2.5.0/jquery.jplayer.min.js"></script>
<link rel="stylesheet" href="include/css/html5reset-1.6.1.css" type="text/css">
<link rel="stylesheet" href="include/css/default_css.css" type="text/css">
<link rel="stylesheet" href="include/css/webfont.css" type="text/css">
<!--[if lt IE 9]><style type="text/css">.store-float { display:none; }</style><![endif]-->

<link rel="icon" href="images/al3e-favorite.ico">
<script>
function CenterSite() {
	$('div#header, div#main').width($('mainstore#panel')[0].scrollWidth);
	$('mainstore#panel').height($(window).height());
	var hWin = ($(document).height()-230);
	var hStore = $('anime-list#store').height();
	if(typeof($('anime-list#store').data('h'))=='undefined') { $('anime-list#store').data('h', $('anime-list#store').height()); }
	if(hWin>$('anime-list#store').data('h')) { $('anime-list#store').height(hWin); } else {$('anime-list#store').height($('anime-list#store').data('h')); }
}
	
$(function(){
	try {
		// Default Site
		$(document).disableSelection().bind("contextmenu",function(e){ return false; });
		$('mainstore#panel').css({'overflow-x': 'hidden','overflow-y': 'scroll'}).height($(window).height());
		$('panel#store-detail').css({'position':'fixed','top':285});
		$('panel#store-filter').css({'position':'fixed','top':228});
		$('table.store-float').fadeOut(0);
		$('div#header, div#main').width($('mainstore#panel')[0].scrollWidth);

		$(window).resize(function(){ CenterSite(); });		
		$('div#header').dblclick(function(){
			open(location, 'mbos');
			open(location, '_self').close();
		});
		// Theme Site
		$("#jquery_jplayer_1").jPlayer({
			ready: function () {
				$(this).jPlayer("setMedia", {
					mp3: "include/IntroTheme.mp3"
				}).jPlayer("stop").jPlayer("option", "volume", v);
			},
			ended: function() {
				$(this).jPlayer("play");
			 },
			swfPath: "/include/jQuery.jPlayer.2.5",
			supplied: "mp3"
		});
		var v = 0.5;
		$(document).bind("mousewheel", function(e){
			if(e.shiftKey) {
				if(e.originalEvent.wheelDelta>0){v+=0.1;if(v>1)v=1;}else{v-=0.1;if(v<0)v=0;}
				$("#jquery_jplayer_1").jPlayer("option", "volume", v)
			}
		});
		
//		$(document).click(function (e) {
//			var tmp = $('#new').clone();
//			tmp.data({
//				r: Math.floor((Math.random() * 205) + 50),
//				g: Math.floor((Math.random() * 205) + 50),
//				b: Math.floor((Math.random() * 205) + 50)
//			});
//			$('html').append(tmp.css({
//				top: (e.clientY - ($('#new').height() / 2)),
//				left: (e.clientX - ($('#new').width() / 2)),
//				border: "rgb(" + tmp.data("r") + ", " + tmp.data("g") + ", " + tmp.data("b") + ") solid 4px"
//			}));                
//			setTimeout(function () { tmp.remove(); }, 1800);
//		});
//		setInterval(function () {
//			var tmp = $('#new').clone();
//			tmp.css('display','block').data({
//				s: Math.floor((Math.random() * 5) + 1),
//				w: Math.floor((Math.random() * 200) + 50),
//				x: Math.floor((Math.random() * $(window).width()-120) -50),
//				y: Math.floor((Math.random() * $(window).height()-120) -50),
//				r: Math.floor((Math.random() * 205) + 50),
//				g: Math.floor((Math.random() * 205) + 50),
//				b: Math.floor((Math.random() * 205) + 50)
//			});
//
//
//			$('mainstore#panel').append(tmp.css({
//				top: tmp.data("y"),
//				left: tmp.data("x"),
//				width: tmp.data("w"),
//				height: tmp.data("w"),
//				"animation-duration": tmp.data("s")+"s",
//				border: "rgb(200,200,200) solid 1px"
//			}));
//			setTimeout(function () { tmp.remove(); }, 5000);
//		}, 50);
//		
		
		
		
		// Event Click List Anime
		var CurrentAnime = null;
		$.fn.GetAnimeDetails = function(){
			var _Icon = $(this).prev();
			var _Episode = $(this).next();
			
			if($(this).attr('class')!=='selected') {
				$('li.selected').each(function(index, element) { $(this).removeClass('selected'); });
				$(CurrentAnime).next().animate({height: 0}, 500, function(){ $(this).fadeOut(0) });
				
				$(this).addClass('selected');
				var eLength = _Episode.find('li').length;
				if(eLength>0) {
					_Episode.fadeIn(0).width(400).height(0).animate({
						height: (eLength*18)
					}, 500, function(){
						
					});
				}
			}
			CurrentAnime = this;
		}
		
		$.fn.GetAnimeDetailsByEpisode = function(){
			$('anime.list li.selected').each(function(index, element) { $(this).removeClass('selected'); });
			if($(this).attr('class')!=='selected') {
				$(this).addClass('selected');
			}
		}
		
		var anime = $($('anime-list#store').html()).clone();
		$('anime-list#store').empty();
		$.ajax({ url: "_sync/ongoing.json",
			type: 'POST',
			xhr: function()
			{
				$('preload#message span').html('PLEASE WAIT...');
				var xhr = new window.XMLHttpRequest();
				//Upload progress
				xhr.upload.addEventListener("progress", function(evt){
					if (evt.lengthComputable) {
						var percentComplete = parseInt((evt.loaded / evt.total) * 100);
						//Do something with upload progress
						$('preload#message span').html('REQUESTING... ('+percentComplete+'%)');
					}
				}, false);
				//Download progress
				xhr.addEventListener("progress", function(evt){
					if (evt.lengthComputable) {
						var percentComplete = parseInt((evt.loaded / evt.total) * 100);
						//Do something with download progress
						$('preload#message span').html('LOADING... ('+percentComplete+'%)');
					}
				}, false);
				
				return xhr;
			},
			success: function(data){
				console.log('Total Anime: ' + data.length);
				for (i=0;i<2;i++) {
					console.log(data);
					var list = anime.clone();
					list.find('.title').html(data[i].Title);
					list.find('.ongoing').html(data[i].Subject);
					$(list[4]).empty();
					$(list[0]).empty();
					$('anime-list#store').append(list);
					list = null;
				} 
				$('table.store-float').fadeIn(500);
				$('preload#message').fadeOut(500);
			}
		});
	} catch(e) {
		$('body').html('<h1>Browser has you not support my Website Al3e-FS</h1>'+ e)
	}
});

</script>
</head>
<body>
<mainstore id="panel">
  <div id="header">
    <div id="jquery_jplayer_1"></div>
    <div id="jp_container_1"> <a href="#" class="jp-play"></a> <a href="#" class="jp-pause"></a> </div>
  </div>
  <div id="main">&nbsp;</div>
  <center>
    <div style="position:fixed;top:48%;left:48%">            
        <preload id="message">
            <preload id="circle1" class="circle"></preload>
            <preload id="circle2" class="circle"></preload>
            <span>PLEASE WAIT...</span>
        </preload>
        <div id="new" style="display:none;"></div>
    </div>
    <table class="store-float" width="960" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" style="height:226px">&nbsp;</td>
      </tr>
      <tr>
        <td align="left"><table id="ContentStore" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><panel id="store-filter">Filter : </panel></td>
              <td width="404" rowspan="2" valign="top"><anime-list id="store">
                  <anime class="icon " id="ico"></anime>
                  <li id="anime" l="" each=""  onClick="$(this).GetAnimeDetails();">
                    <anime class="title"></anime>
                    <anime class="ongoing" id="txt"></anime>
                  </li>
                  <anime class="list" id="" len="a">
                    <li onClick="$(this).GetAnimeDetailsByEpisode();"></li>
                  </anime>
                </anime-list></td>
            </tr>
            <tr>
              <td width="556" valign="top"><panel id="store-detail">
                  <anime class="detail">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><anime class="name">
                            <anime class="season">season</anime>
                            <input class="enname" readonly type="text" value="enname">
                            <anime class="thname">thname</anime>
                          </anime></td>
                      </tr>
                      <tr>
                        <td valign="top"><anime class="description">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td valign="top" width="160" height="100">
                                <anime class="tfansub"><strong>Fansub: </strong></anime>
                                  <anime class="fansub">fansub</anime>
                                </td>
                                <td valign="top" width="150"><anime class="type">type</anime>
                                  <anime class="chapter">chapter</anime>
                                  <anime class="size">size</anime></td>
                                <td valign="top"><anime class="video">video</anime>
                                  <anime class="audio">audio</anime>
                                  <anime class="subtitle">subtitle</anime></td>
                              </tr>
                            </table>
                          </anime></td>
                      </tr>
                    </table>
                  </anime>
                  <anime class="snapshot" style="background-image:url(store/snapshot/!no-image.jpg)"></anime>
                  <footer>Designed by <strong><a href="http://www.facebook.com/dvgamer" target="_blank" style="color:#595959;">โทโนะ คุนาเนะ</a></strong> | Powered by <strong>Al3e-FS &amp; HaKkoMEw-TEAM</strong></footer>
                </panel></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </center>
</mainstore>
</body>
</html>
<?php else: 
error_reporting(0);
$json = array();
foreach (glob("_sync/*.json") as $filename) {
	$json = file_get_contents(realpath($filename));
}
echo json_encode($json); 
endif; 
?>
