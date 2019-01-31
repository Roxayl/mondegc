<?php

$colname_user = $_SESSION['userObject']->ch_use_id;
$userID = $_SESSION['userObject']->ch_use_id;

if (isset($_POST['maxwidth']) )  {
$maxwidth = $_POST['maxwidth'];
}else{
$maxwidth = 250;
}
if (isset($_POST['ThumbMaxwidth']) )  {
$ThumbMaxwidth = $_POST['ThumbMaxwidth'];
}
if (isset($_POST['SmallThumbMaxwidth']) )  {
$SmallThumbMaxwidth = $_POST['SmallThumbMaxwidth'];
}

$target_dir = "..//userfiles/";
$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType = strtolower($imageFileType);
$nom = md5(uniqid(rand(), true));
$target_file = $target_dir . 'user'.$colname_user.'N'.$nom .'.' . $imageFileType;
$Thumbtarget_dir = $target_dir . 'Thumb/user'.$colname_user.'N'.$nom .'.' . $imageFileType; 
$SmallThumbtarget_dir = $target_dir . 'SmallThumb/user'.$colname_user.'N'.$nom .'.' . $imageFileType;
$link = 'http://www.generation-city.com/monde/userfiles/user'.$colname_user.'N'.$nom .'.' . $imageFileType;


// Check if image file is a actual image or fake image
if(isset($_POST['submit'])) {
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Ce fichier n'est pas une image.</div>";
        $uploadOk = 0;
    }
// Check file size
 	if ($_FILES["fileToUpload"]["size"] > 2000000) {
		echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Votre image est trop volumineuse. Elle ne doit pas d&eacute;passer 2 Mo</div>";
	$uploadOk = 0;
	 }
// Allow certain file formats
	switch ($imageFileType) {
		case "jpg" :
		case "jpeg" :
		$ImgRedim = imagecreatefromjpeg($_FILES['fileToUpload']['tmp_name']); break;
		case "png":
		$ImgRedim = imagecreatefrompng($_FILES['fileToUpload']['tmp_name']); break;
		case "gif":
		$ImgRedim = imagecreatefromgif($_FILES['fileToUpload']['tmp_name']); break;
		default:
		echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Seuls les fichiers jpg, jpeg, png et gif sont autoris&eacute;s.</div>";
		$uploadOk = 0;
		}
		
		
		if ($uploadOk == 0) {
		echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Votre fichier n'a pas &eacute;t&eacute; envoy&eacute;.</div>";
		// Si tout est ok, création vignettes and savegarde fichiers
		} else {
		
		if ($check[0] > $maxwidth) {
			// Calcul pourcentage reduction largeur
			$Reduction = ( ($maxwidth * 100)/$check[0] );
			// Calcul pourcentage reduction hauteur
			$NouvelleHauteur = ( ($check[1] * $Reduction)/100 );
			$NouvelleImage = imagecreatetruecolor($maxwidth , $NouvelleHauteur) or die ("Erreur");
			// Canal alpha pour gif et png
			if(($imageFileType =="png") OR ($imageFileType=="gif")){
  			imagealphablending($NouvelleImage, false);
  			imagesavealpha($NouvelleImage,true);
  			$transparent = imagecolorallocatealpha($NouvelleImage, 255, 255, 255, 127);
  			imagefilledrectangle($NouvelleImage, 0, 0, $maxwidth, $NouvelleHauteur, $transparent);
 			}
 			imagecopyresampled($NouvelleImage , $ImgRedim, 0, 0, 0, 0, $maxwidth, $NouvelleHauteur, $check[0],$check[1]);
			echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Votre image a &eacute;t&eacute; redimension&eacute;e en ".$maxwidth." pixels de large.</div>";
		} else {
			$NouvelleImage = imagecreatetruecolor($check[0] , $check[1]) or die ("Erreur");
			if(($imageFileType =="png") OR ($imageFileType=="gif")){
  			imagealphablending($NouvelleImage, false);
  			imagesavealpha($NouvelleImage,true);
  			$transparent = imagecolorallocatealpha($NouvelleImage, 255, 255, 255, 127);
  			imagefilledrectangle($NouvelleImage, 0, 0, $maxwidth, $NouvelleHauteur, $transparent);
 			}
			imagecopyresampled($NouvelleImage , $ImgRedim, 0, 0, 0, 0, $check[0], $check[1], $check[0],$check[1]);
		}
		
		if (isset ($ThumbMaxwidth)) {
		// Calcul pourcentage reduction largeur
		$ThumbReduction = ( ($ThumbMaxwidth * 100)/$check[0] );
		// Calcul pourcentage reduction hauteur
		$HauteurThumb = ( ($check[1] * $ThumbReduction)/100 );
		$ImageThumb = imagecreatetruecolor($ThumbMaxwidth , $HauteurThumb) or die ("Erreur");
		// Canal alpha pour gif et png
		if(($imageFileType =="png") OR ($imageFileType=="gif")){
  		imagealphablending($ImageThumb, false);
  		imagesavealpha($ImageThumb, true);
  		$Thumbtransparent = imagecolorallocatealpha($ImageThumb, 255, 255, 255, 127);
  		imagefilledrectangle($ImageThumb, 0, 0, $ThumbMaxwidth, $HauteurThumb, $Thumbtransparent);
 		}
 		imagecopyresampled($ImageThumb , $ImgRedim, 0, 0, 0, 0, $ThumbMaxwidth, $HauteurThumb, $check[0],$check[1]);
		
		switch ($imageFileType) {
		case "jpg" :
		case "jpeg" :
		imagejpeg($ImageThumb , $Thumbtarget_dir, 80);
		break;
		case "png":
		imagePng($ImageThumb , $Thumbtarget_dir,8);
		break;
		case "gif":
		imagegif($ImageThumb , $Thumbtarget_dir);
		break;
		default:
		echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Une erreur est survenue lors de l'envoi de votre fichier.</div>";
		break;
		}
		}
		
		if (isset($SmallThumbMaxwidth)) {	
		// Calcul pourcentage reduction largeur
		$SmallThumbReduction = ( ($SmallThumbMaxwidth * 100)/$check[0] );
		// Calcul pourcentage reduction hauteur
		$HauteurSmallThumb = ( ($check[1] * $SmallThumbReduction)/100 );
		$ImageSmallThumb = imagecreatetruecolor($SmallThumbMaxwidth , $HauteurSmallThumb) or die ("Erreur");
		// Canal alpha pour gif et png
		if(($imageFileType =="png") OR ($imageFileType=="gif")){
		imagealphablending($ImageSmallThumb, false);
		imagesavealpha($ImageSmallThumb, true);
		$SmalThumbtransparent = imagecolorallocatealpha($ImageSmallThumb, 255, 255, 255, 127);
		imagefilledrectangle($ImageSmallThumb, 0, 0, $SmallThumbMaxwidth, $HauteurSmallThumb, $SmalThumbtransparent);
 		}
		imagecopyresampled($ImageSmallThumb , $ImgRedim, 0, 0, 0, 0, $SmallThumbMaxwidth, $HauteurSmallThumb, $check[0],$check[1]);
		
		switch ($imageFileType) {
		case "jpg" :
		case "jpeg" :
		imagejpeg($ImageSmallThumb, $SmallThumbtarget_dir, 80);
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Cr&eacute;ation de vignettes.</div>";
		break;
		case "png":
		imagePng($ImageSmallThumb, $SmallThumbtarget_dir,8);
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Cr&eacute;ation de vignettes.</div>";
		break;
		case "gif":
		imagegif($ImageSmallThumb, $SmallThumbtarget_dir);
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Cr&eacute;ation de vignettes.</div>";
		break;
		default:
		echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Une erreur est survenue lors de l'envoi de votre fichier.</div>";
		break;
		}
		}
		switch ($imageFileType) {
		case "jpg" :
		case "jpeg" :
		if (imagejpeg($NouvelleImage , $target_file, 80)){
		$uploadconfirm = 1;
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Votre fichier ".basename($_FILES['fileToUpload']['name'])." a &eacute;t&eacute; envoy&eacute.</div>";}else{
			echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Une erreur est survenue lors de l'envoi de votre fichier.</div>";
		}
				break;
		case "png":
		if (imagePng($NouvelleImage , $target_file,8)){
		$uploadconfirm = 1;
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Votre fichier ".basename($_FILES['fileToUpload']['name'])." a &eacute;t&eacute; envoy&eacute.</div>";}else{
			echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Une erreur est survenue lors de l'envoi de votre fichier.</div>";
		}
				break;
		case "gif":
		if(imagegif($NouvelleImage , $target_file)){
		$uploadconfirm = 1;
		echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Votre fichier ".basename($_FILES['fileToUpload']['name'])." a &eacute;t&eacute; envoy&eacute.</div>";}else{
			echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Une erreur est survenue lors de l'envoi de votre fichier.</div>";
		}
		break;
		default:
		echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button>Une erreur est survenue lors de l'envoi de votre fichier.</div>";
		break;
		}
	}
}
?>

