
<html>
<head>
<link href="Carto/OLdefault.css" rel="stylesheet">
<script src="assets/js/OpenLayers.mobile.js" type="text/javascript"></script>
<script src="assets/js/OpenLayers.js" type="text/javascript"></script>
<?php require('php/carteposition.php'); ?>
<style>
body  {
	margin: 0px;
	padding:0px;
}

#mapPosition {
	height: 300px;
	width:100%;
	background-color: #fff;
	margin: 0px;
	padding:0px;
}
</style>
</head>
<body onLoad="init()">
<div id="mapPosition"></div>
</body>
</html>