<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css" />
      <link rel="shortcut icon" type="image/x-icon" href="http://www.generation-city.com/givlimar/images/gcforum.ico">
<title>Convertisseur de devises</title>
</head>
<body>


<a id="0"></a>
<h1 id="nom">Convertisseur des devises du monde GC </h1>

<?php

// o = valeur originale in = inverse  1€ = o
$viose = array(
'o' => 1145.0,
'in' => 1/1145);
$gs = array(
'o' =>3348.0,
'in' => 1/3348);
$uyo = array(
'o' => 0.73,
'in' => 1/0.73);
$simflouz = array(
'o' => 7.5170,
'in' => 1/7.5170);
?>

<div id="monnaie">

<form method="post" action="index.php">
<fieldset class="choix">
<legend class="legende">Monnaies à Convertir</legend>
<div><select name="monnaie1">

<optgroup label="Monnaie à convertir">
<option value="viose">Viose</option>
<option value="gs">Goldsilver</option>
<option value="uyo">µyo</option>
<option value="simflouz">Simflouz</option>
</optgroup>
</select>

==>

<select name="monnaie2">
<optgroup label="En quelle monnaie la convertir ?">
<option value="viose">Viose</option>
<option value="gs">Goldsilver</option>
<option value="uyo">µyo</option>
<option value="simflouz">Simflouz</option>
</optgroup>
</select>
</div>
<div id="sommec"><label>Quelle somme convertir ?</label> <input type="text" placeholder="Ex: 1256" id="somme" name="somme"></input></div>


<br />
<button type="submit" id="submit" >Convertir</button>



<?php


$monnaie1 = $_POST['monnaie1'];

$somme = $_POST['somme'];

$monnaie2 = $_POST['monnaie2'];
if(isset($monnaie1)AND isset($monnaie1) AND isset($somme) AND $somme >= 1)
{
$r = $somme / ${$monnaie1}['o'];

$rm = $r / ${$monnaie2}['in'];

$somme = (float) $somme;


?><div id="resultat"><?php
echo $somme, ' ',$monnaie1, ' = ',$rm, ' ',$monnaie2;
}

if(isset($somme) AND $somme <=-1)
{
echo 'Une valeur au-dessus de zéro et en CHIFFRE est requise.';
}

else
{
   echo'';
}
?></div>




</fieldset>
</form>

</div>

   </body>

</html>