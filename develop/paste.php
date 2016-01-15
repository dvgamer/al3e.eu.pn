<doctype html>
<head>
<style type="text/css">body {font-family: "Tahoma";font-size: 12px;}a {text-decoration: none;color: #06C}a:hover {text-decoration: underline;}</style>
<script src="../include/jquery.min-1.8.2.js"></script><script type="text/javascript">
$(function() {
    $('#textpaste').bind("paste", function(e){
		console.log(e);
	});
});
</script>
</head>
<body>
<textarea id="textpaste" cols="100" rows="10"></textarea>
</body>
</html>