<script src="../include/jquery.1.10.2.min.js"></script><script>
$(function(){$('#loadanime').click(function(){$('body').html("Loading...");
$.ajax({url:"/develop/AnimeSocket.php?id=<?php echo $_GET['id']; ?>",dataType:"JSON",error:
function(){ console.log("Error"); },success: function(data){$('body').html("");if(data.length>0) {var fansub = 0;for(var a=0;a<data.length;a++) {var embedCode="";
if(data[a]["embedCode"]!="") embedCode=data[a]["embedCode"];else if(data[a]["embedCode2"]!="") embedCode=data[a]["embedCode2"];else if(data[a]["embedCode3"]!="") embedCode=data[a]["embedCode3"];
var n=embedCode.split("src='"); if(n.length>1) { var m=n[1].split("'"); embedCode=m[0]; }
if(embedCode!="") $('body').append("<strong><a target=\"_blank\" href=\""+embedCode+"\">"+data[a]["type"]+"</a></strong> ");
else $('body').append("<strong>"+data[a]["type"]+"</strong> "); $('body').append("<a target=\"_blank\" href=\""+data[a]["link"]+"\">"+data[a]['fname']+"</a><br/>");
/*if(fansub!=data[a]["fansubID"] && fansub!=0) { $('body').append("<br/>"); }*/fansub = data[a]["fansubID"];}}}});});});</script>
<style type="text/css">body {font-family: "Tahoma";font-size:12px;} a { text-decoration:none; color:#06C } a:hover { text-decoration:underline; }</style>
<body><a id="loadanime" style="font-weight:bold; cursor:pointer;">GetAnime ID: <?php echo $_GET['id']; ?></a></body>