<script src="../include/jquery.1.10.2.min.js"></script><script>
$(function(){
	$('#mthai_get').click(function(){
		// http://video.mthai.com/player.php?id=18M1200585533M0
		var id = $.trim($('#mthai_id').val());
		id = id.split('player.php?id=');
		if(id.length>1) {
			$('#list').html("Loading...");
			$.ajax({
				url:"/develop/GetMthai.php?id="+id[1],
				data: { clip_password: 'INDEX' },
				dataType:"JSON",
				type:"POST",
				error:function(){console.log("Error"); },
				success: function(data){
					if(data.link!="") $('#list').html('<a href="'+data.link+'">'+data.name+'.flv</a>');
					else if(data.name=="") $('#list').html('File not Found.');   
					else $('#list').html('Want Password.'); 
				}
			});
		} else {
			$('#list').html("URL No Mthai.com");
		}
	});
});</script>
<style type="text/css">body {font-family: "Tahoma";font-size: 12px;}a {text-decoration: none;color: #06C}a:hover {text-decoration: underline;}</style>
<body>
<input type="text" id="mthai_id"  value="" style="font-weight:bold;width:400px;" />
<button id="mthai_get" style="font-weight:bold; cursor:pointer;">DOWNLOAD</button>
<anime id="list" style="font-weight:bold;margin:20px;"></anime>
</body>
