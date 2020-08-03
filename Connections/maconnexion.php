<?php

/*************************
 *       Paramètres      *
 *************************/
 
// Variables de configuration.
$mondegc_config = array();

// Environnement.
if(\Illuminate\Support\Facades\App::environment() === 'local') {
    if($_SERVER['HTTP_HOST'] === 'localhost') {
        $mondegc_config['env'] = 'localhost';
    } else {
        $mondegc_config['env'] = 'vagrant';
    }
} else {
    $mondegc_config['env'] = 'production';
}

// Définir la configuration depuis Laravel.
$mondegc_config['version'] = config('legacy.version');
$mondegc_config['hide_errors'] = config('legacy.hide_errors');
$mondegc_config['enable_csrf_protection'] = config('legacy.enable_csrf_protection');
$mondegc_config['db'] = array(
    'hostname' => config('database.connections.mysql.host'),
    'username' => config('database.connections.mysql.username'),
    'password' => config('database.connections.mysql.password'),
    'database' => config('database.connections.mysql.database'),
);
$mondegc_config['path'] = !empty(config('app.directory_path'))
                        ? config('app.directory_path') . '/'
                        : '';

// Chemins
defined("DEF_ROOTPATH") or define("DEF_ROOTPATH", str_replace("\\", "/", (substr(__DIR__, -12) == '/Connections' || substr(__DIR__, -12) == '\Connections') ? substr(__DIR__, 0, -12) . '/' : __DIR__ . '/'));
define("DEF_URI_PATH",
        ( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443
            ? 'https' : 'http' )
        . '://' . $_SERVER['HTTP_HOST'] . '/'
        . $mondegc_config['path']
    );

// Masquer les messages d'erreur.
if($mondegc_config['hide_errors'] === true) {
    error_reporting(0);
}


/*************************
 * Librairies (autoload) *
 *************************/

require_once(DEF_ROOTPATH . 'vendor/autoload.php');

// Auto-chargement de classes
spl_autoload_register(function($class) {
    // On vire l'espace de noms "Squirrel\" dans un nom de classe.
    // $class = preg_replace('`Squirrel\\\\`', '', $class, 1);
    // On remplace les anti-slash par des slash
    $class = str_replace('\\', '/', $class);
    include(DEF_ROOTPATH.'php/'. $class . '.php');
});

if($mondegc_config['enable_csrf_protection'] === true) {
    /* CSRF-Magic */
    if(!function_exists('csrf_startup')) {
        function csrf_startup() {
            csrf_conf('rewrite-js', DEF_URI_PATH . 'lib/csrf-magic/csrf-magic.js');
            csrf_conf('rewrite', true);
        }
    }
    require_once DEF_ROOTPATH . 'lib/csrf-magic/csrf-magic.php';
}

/* wrapper mysql_ */
// wrapper pour les fonctions MySQL pour PHP7
if(version_compare(phpversion(), '7.0.0', '>=')) {
    require_once(DEF_ROOTPATH . 'lib/mysql_wrapper/mysql_wrapper.php');
}


/*************************
 *    Base de données    *
 *************************/

$maconnexion = @mysql_pconnect($mondegc_config['db']['hostname'], $mondegc_config['db']['username'], $mondegc_config['db']['password']) or trigger_error(mysql_error(), E_USER_ERROR);

if($mondegc_config['env'] !== 'production') {
    mysql_set_charset('utf8mb4', $maconnexion);
    mysql_query("SET SESSION sql_mode = 'TRADITIONAL'");
}
mysql_select_db($mondegc_config['db']['database']);


/*************************
 *   Fonctions diverses  *
 *************************/

//Protection  données envoyées
function GetSQLValueString($theValue, $theType = "text", $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

// *** Fonction calcul de periodes.
function get_timespan_string($older, $newer) {
  $Y1 = $older->format('Y');
  $Y2 = $newer->format('Y');
  $Y = $Y2 - $Y1;

  $m1 = $older->format('m');
  $m2 = $newer->format('m');
  $m = $m2 - $m1;

  $d1 = $older->format('d');
  $d2 = $newer->format('d');
  $d = $d2 - $d1;

  $H1 = $older->format('H');
  $H2 = $newer->format('H');
  $H = $H2 - $H1;

  $i1 = $older->format('i');
  $i2 = $newer->format('i');
  $i = $i2 - $i1;

  $s1 = $older->format('s');
  $s2 = $newer->format('s');
  $s = $s2 - $s1;

  if($s < 0) {
    $i = $i -1;
    $s = $s + 60;
  }
  if($i < 0) {
    $H = $H - 1;
    $i = $i + 60;
  }
  if($H < 0) {
    $d = $d - 1;
    $H = $H + 24;
  }
  if($d < 0) {
    $m = $m - 1;
    $d = $d + get_days_for_previous_month($m2, $Y2);
  }
  if($m < 0) {
    $Y = $Y - 1;
    $m = $m + 12;
  }
  $timespan_string = create_timespan_string($Y, $m, $d);
  return $timespan_string;
}

function get_days_for_previous_month($current_month, $current_year) {
  $previous_month = $current_month - 1;
  if($current_month == 1) {
    $current_year = $current_year - 1; //going from January to previous December
    $previous_month = 12;
  }
  if($previous_month == 11 || $previous_month == 9 || $previous_month == 6 || $previous_month == 4) {
    return 30;
  }
  else if($previous_month == 2) {
    if(($current_year % 4) == 0) { //remainder 0 for leap years
      return 29;
    }
    else {
      return 28;
    }
  }
  else {
    return 31;
  }
}

function create_timespan_string($Y, $m, $d)
{
  $timespan_string = '';
  $found_first_diff = false;
  if($Y >= 1) {
    $found_first_diff = true;
    $timespan_string .= pluralize($Y, 'an').' ';
  }
  if($m >= 1 || $found_first_diff) {
    $found_first_diff = true;
    $timespan_string .= $m.' mois'.' ';
  }
  if($d >= 1 || $found_first_diff) {
    $found_first_diff = true;
    $timespan_string .= pluralize($d, 'jour').' ';
  }
  return $timespan_string;
}

function pluralize( $count, $text )
{
  return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
}

function get_timespan_string_hour($older, $newer) {
  $Y1 = $older->format('Y');
  $Y2 = $newer->format('Y');
  $Y = $Y2 - $Y1;

  $m1 = $older->format('m');
  $m2 = $newer->format('m');
  $m = $m2 - $m1;

  $d1 = $older->format('d');
  $d2 = $newer->format('d');
  $d = $d2 - $d1;

  $H1 = $older->format('H');
  $H2 = $newer->format('H');
  $H = $H2 - $H1;

  $i1 = $older->format('i');
  $i2 = $newer->format('i');
  $i = $i2 - $i1;

  $s1 = $older->format('s');
  $s2 = $newer->format('s');
  $s = $s2 - $s1;

  if($s < 0) {
    $i = $i -1;
    $s = $s + 60;
  }
  if($i < 0) {
    $H = $H - 1;
    $i = $i + 60;
  }
  if($H < 0) {
    $d = $d - 1;
    $H = $H + 24;
  }
  if($d < 0) {
    $m = $m - 1;
    $d = $d + get_days_for_previous_month_hour($m2, $Y2);
  }
  if($m < 0) {
    $Y = $Y - 1;
    $m = $m + 12;
  }
  $timespan_string = create_timespan_string_hour($Y, $m, $d, $H, $i, $s);
  return rtrim($timespan_string, ', ');
}

function get_days_for_previous_month_hour($current_month, $current_year) {
  $previous_month = $current_month - 1;
  if($current_month == 1) {
    $current_year = $current_year - 1; //going from January to previous December
    $previous_month = 12;
  }
  if($previous_month == 11 || $previous_month == 9 || $previous_month == 6 || $previous_month == 4) {
    return 30;
  }
  else if($previous_month == 2) {
    if(($current_year % 4) == 0) { //remainder 0 for leap years
      return 29;
    }
    else {
      return 28;
    }
  }
  else {
    return 31;
  }
}

function create_timespan_string_hour($Y, $m, $d, $H, $i, $s)
{
  $timespan_string = '';
  $found_first_diff = false;
  if($Y >= 1) {
    $found_first_diff = true;
    $timespan_string .= pluralize_hour($Y, 'an').', ';
  }
  if($m >= 1 || $found_first_diff) {
    $found_first_diff = true;
    $timespan_string .= $m.' mois'.', ';
  }
  if($d >= 1 || $found_first_diff) {
    $found_first_diff = true;
    $timespan_string .= pluralize_hour($d, 'jour').', ';
  }
  if(($H >= 1 || $found_first_diff) && ($d < 1 && $m < 1 && $Y < 1)) {
    $found_first_diff = true;
    $timespan_string .= pluralize_hour($H, 'heure').', ';
  }
  if(($i >= 1 || $found_first_diff) && ($H < 1 && $d < 1 && $m < 1 && $Y < 1)) {
    $found_first_diff = true;
    $timespan_string .= pluralize_hour($i, 'minute').' ';
  }
  return $timespan_string;
}

function pluralize_hour( $count, $text )
{
  return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
}

// *** Fonction calcul de notes.
function get_note_finale($note) {
if ($note >= 56) {
$note_finale = 'A<sup>+</sup>';
} elseif (($note < 56) AND ($note >= 52)) {
$note_finale = 'A';
} elseif (($note < 52) AND ($note >= 48)) {
$note_finale = 'A<sup>-</sup>';
} elseif (($note < 48) AND ($note >= 44)) {
$note_finale = 'B<sup>+</sup>';
} elseif (($note < 44) AND ($note >= 40)) {
$note_finale = 'B';
} elseif (($note < 40) AND ($note >= 36)) {
$note_finale = 'B<sup>-</sup>';
} elseif (($note < 36) AND ($note >= 32)) {
$note_finale = 'C<sup>+</sup>';
} elseif (($note < 32) AND ($note >= 28)) {
$note_finale = 'C';
} elseif (($note < 28) AND ($note >= 24)) {
$note_finale = 'C<sup>-</sup>';
} elseif (($note < 24) AND ($note >= 20)) {
$note_finale = 'D<sup>+</sup>';
} elseif (($note < 20) AND ($note >= 16)) {
$note_finale = 'D';
} elseif (($note < 16) AND ($note >= 12)) {
$note_finale = 'D<sup>-</sup>';
} elseif (($note < 12) AND ($note >= 8)) {
$note_finale = 'E<sup>+</sup>';
} elseif (($note < 8) AND ($note >= 4)) {
$note_finale = 'E';
} elseif (($note < 4)) {
$note_finale = 'F';
} else {
$note_finale = '';
}
  return $note_finale;
}

//Affichage date en français pour fait hist
function affDate($date){
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);
     
    $str = $day." ";
    if($month == 1) $str .= "Janvier";
    if($month == 2) $str .= "F&eacute;vrier";
    if($month == 3) $str .= "Mars";
    if($month == 4) $str .= "Avril";
    if($month == 5) $str .= "Mai";
    if($month == 6) $str .= "Juin";
    if($month == 7) $str .= "Juillet";
    if($month == 8) $str .= "Ao&ucirc;t";
    if($month == 9) $str .= "Septembre";
    if($month == 10) $str .= "Octobre";
    if($month == 11) $str .= "Novembre";
    if($month == 12) $str .= "D&eacute;cembre";
    $str .= " ".$year;
     
    return $str;
}

function coordEmplacement($emplacement, &$x, &$y){
switch ($emplacement) // placement des markers selon la variable d'emplacement
{ 
    case 1: // dans le cas où $emplacement vaut 1
        $x="75.9";
		$y="28.9";
    break;
    
    case 2: 
        $x="107.2";
		$y="28.1";
    break;
    
    case 3: 
        $x="70";
		$y="0";
    break;
    
    case 4: 
        $x="113";
		$y="12";
    break;
    
    case 5: 
        $x="153.5";
		$y="42.1";
    break;
    
    case 6: 
        $x="43.9";
		$y="36.1";
    break;
    
    case 7: 
        $x="86.83";
		$y="48.17";
    break;
	
	case 8: 
        $x="72.77";
		$y="43.95";
    break;
	
	case 9: 
        $x="130.07";
		$y="46.76";
    break;
	
	case 10: 
        $x="99.49";
		$y="61.18";
    break;
	
	case 11: 
        $x="156.44";
		$y="11.25";
    break;
    
    case 12: 
        $x="126.56";
		$y="-6.67";
    break;
    
    case 13: 
        $x="153.98";
		$y="-12";
    break;
    
    case 14: 
        $x="98";
		$y="-30";
    break;
    
    case 15: 
        $x="114";
		$y="-44";
    break;
    
    case 16: 
        $x="93.5";
		$y="-44.5";
    break;
    
    case 17: 
        $x="77.34";
		$y="-40.22";
    break;
	
	case 18: 
        $x="59.06";
		$y="-34";
    break;
	
	case 19: 
        $x="53.433";
		$y="-26";
    break;
	
	case 20: 
        $x="37.26";
		$y="-24";
    break;
	
	case 21: 
        $x="28.82";
		$y="-33";
    break;
    
    case 22: 
        $x="39";
		$y="-43";
    break;
    
    case 23: 
        $x="43.5";
		$y="-58.5";
    break;
    
    case 24: 
        $x="6.5";
		$y="-34.5";
    break;
    
    case 25: 
        $x="-31";
		$y="-46";
    break;
    
    case 26: 
        $x="-13";
		$y="-46";
    break;
    
    case 27: 
        $x="-96";
		$y="-33";
    break;
	
	case 28: 
        $x="-107";
		$y="-16.5";
    break;
	
	case 29: 
        $x="-119";
		$y="-44.5";
    break;
	
	case 30: 
        $x="-139";
		$y="-41.5";
    break;
	
	case 31: 
        $x="-125";
		$y="-32";
    break;
    
    case 32: 
        $x="-116";
		$y="-31";
    break;
    
    case 33: 
        $x="-138.5";
		$y="-4.5";
    break;
    
    case 34: 
        $x="-137";
		$y="20";
    break;
    
    case 35: 
        $x="-121.5";
		$y="15.5";
    break;
    
    case 36: 
        $x="-100";
		$y="28.5";
    break;
    
    case 37: 
        $x="-160";
		$y="27.5";
    break;
	
	case 38: 
        $x="-123.5";
		$y="39";
    break;
	
	case 39: 
        $x="-129";
		$y="52.5";
    break;
	
	case 40: 
        $x="-142.5";
		$y="49.5";
    break;
	
	case 41: 
        $x="-138";
		$y="35";
    break;
	
	case 42: 
        $x="-122";
		$y="-15";
    break;

    case 43:
        $x="-14";
        $y="-48";
    break;

    case 44:
        $x="-21";
        $y="-30";
    break;

    case 45:
        $x="-11";
        $y="-14";
    break;

    case 46:
        $x="-37";
        $y="-36";
    break;

    case 47:
        $x="-41";
        $y="-30";
    break;

    case 48:
        $x="-36";
        $y="-26";
    break;

    case 49:
        $x="-32";
        $y="-19";
    break;

    case 50:
        $x="-46";
        $y="-21";
    break;

    case 51:
        $x="-39";
        $y="-13";
    break;

    case 52:
        $x="-27";
        $y="-12";
    break;

    case 53:
        $x="-30";
        $y="-7";
    break;

    case 54:
        $x="-20";
        $y="-6";
    break;

    case 55:
        $x="-35";
        $y="3";
    break;

    case 56:
        $x="-18";
        $y="-60";
    break;

    case 57:
        $x="54";
        $y="-48";
    break;

    case 58:
        $x="-73";
        $y="-43.5";
    break;

    case 59:
        $x="55";
        $y="-40.5";
    break;
    
    default:
        $x="0";
		$y="0";
}
}

function styleZones($typeZone, &$fillcolor, &$fillOpacity, &$strokeWidth, &$strokeColor, &$strokeOpacity, &$Trait){
			if ( $typeZone == "urbaine" ){
				$fillcolor = "#313131";
				$fillOpacity = "0.8";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "maritime protegee" ){
				$fillcolor = "#006C3A";
				$fillOpacity = "0.1";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "peche traditionnelle" ){
				$fillcolor = "#007F7F";
				$fillOpacity = "0.1";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "peche intensive" ){
				$fillcolor = "#BDCFD6";
				$fillOpacity = "0.3";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "route maritime" ){
				$fillcolor = "#BDCFD6";
				$fillOpacity = "1";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "megapole" ){
				$fillcolor = "#9370DB";
				$fillOpacity = "1";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
            } elseif ( $typeZone == "periurbaine" ){
				$fillcolor = "#a0a0a0";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
            } elseif ( $typeZone == "industrielle" ){
				$fillcolor = "#673b15";
				$fillOpacity = "0.8";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
            } elseif ( $typeZone == "maraichere" ){
				$fillcolor = "#ffeb54";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
            } elseif ( $typeZone == "cerealiere" ){
				$fillcolor = "#eed900";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
            } elseif ( $typeZone == "elevage" ){
				$fillcolor = "#a58752";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "prairies" ){
				$fillcolor = "#97bf0d";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "forestiere" ){
				$fillcolor = "#287621";
				$epaisseurTrait = "0";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "protegee" ){
				$fillcolor = "#01481d";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "marecageuse" ){
				$fillcolor = "#8baed8";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "lagunaire" ){
				$fillcolor = "#ffffff";
				$fillOpacity = "0.8";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			} elseif ( $typeZone == "region" ){
				$fillcolor = "#ffffff";
				$fillOpacity = "0";
				$strokeWidth = "3";
				$strokeColor = "#626262";
				$strokeOpacity = "0.5";
				$Trait = "6 12 6 12";
            } elseif ( $typeZone == "terre" ){
				$fillcolor = "#acd0a5";
				$fillOpacity = "1";
				$strokeWidth = "1";
				$strokeColor = "#1b82ab";
				$strokeOpacity = "0.5";
				$Trait = "solid";
            } else {
				$fillcolor = "#97bf0d";
				$fillOpacity = "0.5";
				$strokeWidth = "0";
				$strokeColor = "#626262";
				$strokeOpacity = "0";
				$Trait = "solid";
			}
}

function styleVoies($typeVoie, &$couleurTrait, &$epaisseurTrait, &$Trait){
			if ( $typeVoie == "lgv" ){
				$couleurTrait = "#8a5b9d";
				$epaisseurTrait = "2";
				$Trait = "solid";
			} elseif ( $typeVoie == "cheminFer" ){
				$couleurTrait = "#E14A51";
				$epaisseurTrait = "2";
				$Trait = "dot";
			} elseif ( $typeVoie == "canal" ){
				$couleurTrait = "#009ee0";
				$epaisseurTrait = "2";
				$Trait = "solid";
            } elseif ( $typeVoie == "maritime" || $typeVoie == 'route maritime' ){
				$couleurTrait = "#009ee0";
				$epaisseurTrait = "2";
				$Trait = "dot";
			} elseif ( $typeVoie == "autoroute" ){
				$couleurTrait = "#9d0d15";
				$epaisseurTrait = "3";
				$Trait = "solid";
			} elseif ( $typeVoie == "voieexpress" ){
				$couleurTrait = "#fc575e";
				$epaisseurTrait = "2";
				$Trait = "solid";
				} elseif ( $typeVoie == "nationale" ){
				$couleurTrait = "#fc575e";
				$epaisseurTrait = "1";
				$Trait = "solid";
			} elseif ( $typeVoie == "ferry" ){
				$couleurTrait = "#5581A0";
				$epaisseurTrait = "2";
				$Trait = "dot";
			} elseif ( $typeVoie == "frontiere" ){
				$couleurTrait = "#000000";
				$epaisseurTrait = "2";
				$Trait = "dashdot";
			} else {
				$couleurTrait = "#af1018";
				$epaisseurTrait = "3";
				$Trait = "solid";
			}
}

function tailleVilles($population, &$sizeicon){
        if ($population <= 100000) {
		$sizeicon  = 7;
		} elseif ($population <= 250000) { 
		$sizeicon = 8;
		} elseif ($population <= 500000) {
		$sizeicon = 9;
		} elseif ($population <= 1000000) {
		$sizeicon = 10;
		} elseif ($population <= 2500000) {
		$sizeicon = 11;
		} elseif ($population <= 5000000) {
		$sizeicon = 12;
		} elseif ($population <= 10000000) {
		$sizeicon = 13;
		} elseif ($population >= 10000000) {
		$sizeicon = 13;
		} else {
		$sizeicon = 6;
		}
		// Romu: on réduit globalement la taille des icônes villes
		$sizeicon -= 3;
		return $sizeicon;
  }

function ressourcesGeometrie($surface, &$typeZone, &$budget, &$industrie, &$commerce, &$agriculture, &$tourisme, &$recherche, &$environnement, &$education, &$label, &$population, &$emploi = 0){
			if ( $typeZone == "urbaine" ){
				$label = "Zone urbaine";
				$budget = $surface*-1;
				$industrie = $surface*0;
				$commerce = $surface*0.04;
				$agriculture = $surface*-0.3;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.08;
				$education = $surface*0.02;
				$population = $surface*75;
			} elseif ( $typeZone == "maritime protegee" ){
				$label = "Zone maritime protegee";
				$budget = $surface*-0.04;
				$industrie = $surface*-0.00025;
				$commerce = $surface*-0.00025;
				$agriculture = $surface*0.001;
				$tourisme = $surface*0.002;
				$recherche = $surface*0.00125;
				$environnement = $surface*0.004;
				$education = $surface*0.0015;
				$population = $surface*0;
				$emploi = $surface*0.001;
			} elseif ( $typeZone == "peche intensive" ){
				$label = "Zone de peche intensive";
				$budget = $surface*0.020;
				$industrie = $surface*0.0004;
				$commerce = $surface*0.0003;
				$agriculture = $surface*0.04;
				$tourisme = $surface*-0.0015;
				$recherche = $surface*0;
				$environnement = $surface*-0.003;
				$education = $surface*0;
				$population = $surface*0;
				$emploi = $surface*0.001;
			} elseif ( $typeZone == "peche traditionnelle" ){
				$label = "Zone de peche traditionnelle";
				$budget = $surface*0.014;
				$industrie = $surface*0.00025;
				$commerce = $surface*0.00025;
				$agriculture = $surface*0.025;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.001;
				$education = $surface*0;
				$population = $surface*0;
				$emploi = $surface*1;
			} elseif ( $typeZone == "megapole" ){
				$label = "Zone megapole";
				$budget = $surface*-2;
				$industrie = $surface*0.04;
				$commerce = $surface*0.10;
				$agriculture = $surface*-2;
				$tourisme = $surface*0.04;
				$recherche = $surface*0.04;
				$environnement = $surface*-0.003;
				$education = $surface*0.04;
				$population = $surface*500;
				$emploi = $surface*1000;
            } elseif ( $typeZone == "periurbaine" ){
				$label = "Zone periurbaine";
				$budget = $surface*-0.25;
				$industrie = $surface*0.005;
				$commerce = $surface*0.005;
				$agriculture = $surface*-0.1;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.0075;
				$education = $surface*0;
				$population = $surface*25;
				$emploi = $surface*30;
            } elseif ( $typeZone == "industrielle" ){
				$label = "Zone industrielle";
				$budget = $surface*-3.5;
				$industrie = $surface*0.16;
				$commerce = $surface*0;
				$agriculture = $surface*-0.6;
				$tourisme = $surface*-0.1;
				$recherche = $surface*0.12;
				$environnement = $surface*-0.4;
				$education = $surface*0;
				$population = $surface*50;
				$emploi = $surface*100;
            } elseif ( $typeZone == "maraichere" ) {
				$label = "Zone mara&icirc;chere";
				$budget = $surface*0.028;
				$industrie = $surface*0.0005;
				$commerce = $surface*0.0005;
				$agriculture = $surface*0.05;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.002;
				$education = $surface*0;
				$population = $surface*2;
				$emploi = $surface*1.5;
			} elseif ( $typeZone == "cerealiere" ) {
				$label = "Zone c&eacute;reali&egrave;re";
				$budget = $surface*0.028;
				$industrie = $surface*0.0005;
				$commerce = $surface*0.0005;
				$agriculture = $surface*0.05;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.002;
				$education = $surface*0;
				$population = $surface*2;
				$emploi = $surface*1.5;
			} elseif ( $typeZone == "elevage" ) {
				$label = "Zone d'&eacute;levage";
				$budget = $surface*0.028;
				$industrie = $surface*0.001;
				$commerce = $surface*0.001;
				$agriculture = $surface*0.05;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.002;
				$education = $surface*0;
				$population = $surface*2;
				$emploi = $surface*1.5;
			} elseif ( $typeZone == "prairies" ){
				$label = "Prairies";
				$budget = $surface*-0.001;
				$industrie = $surface*0;
				$commerce = $surface*0;
				$agriculture = $surface*0.001;
				$tourisme = $surface*0.001;
				$recherche = $surface*0.0005;
				$environnement = $surface*0.00625;
				$education = $surface*0.0005;
				$population = $surface*0.5;
				$emploi = $surface*1.5;
			} elseif ( $typeZone == "forestiere" ){
				$label = "Zone foresti&egrave;re";
				$budget = $surface*0.001;
				$industrie = $surface*-0.00025;
				$commerce = $surface*-0.00025;
				$agriculture = $surface*0.001;
				$tourisme = $surface*0.001;
				$recherche = $surface*0.0005;
				$environnement = $surface*0.002;
				$education = $surface*0.0007;
				$population = $surface*0.1;
				$emploi = $surface*0.5;
			} elseif ( $typeZone == "protegee" ){
				$label = "Zone foresti&egrave;re prot&eacute;g&eacute;e";
				$budget = $surface*-0.04;
				$industrie = $surface*-0.00025;
				$commerce = $surface*-0.00025;
				$agriculture = $surface*0.001;
				$tourisme = $surface*0.002;
				$recherche = $surface*0.00125;
				$environnement = $surface*0.004;
				$education = $surface*0.0015;
				$population = $surface*0.01;
				$emploi = $surface*0.01;
			} elseif ( $typeZone == "marecageuse" ){
				$label = "Zone mar&eacute;cageuse";
				$budget = $surface*0;
				$industrie = $surface*-0.001;
				$commerce = $surface*-0.001;
				$agriculture = $surface*0;
				$tourisme = $surface*-0.001;
				$recherche = $surface*0.001;
				$environnement = $surface*0.002;
				$education = $surface*0.001;
				$population = $surface*0.01;
				$emploi = $surface*0.001;
			} elseif ( $typeZone == "lagunaire" ){
				$label = "Zone lagunaire";
				$budget = $surface*0;
				$industrie = $surface*0.001;
				$commerce = $surface*0.001;
				$agriculture = $surface*-0.001;
				$tourisme = $surface*0.0125;
				$recherche = $surface*0.001;
				$environnement = $surface*0.005;
				$education = $surface*0.001;
				$population = $surface*2;
				$emploi = $surface*3;
			} elseif ( $typeZone == "lgv" ){
				$label = "Lignes &agrave; Grande Vitesse";
				$budget = $surface*-1.5;
				$industrie = $surface*0;
				$commerce = $surface*0;
				$agriculture = $surface*0;
				$tourisme = $surface*0.025;
				$recherche = $surface*0.025;
				$environnement = $surface*-0.02;
				$education = $surface*0.025;
				$population = $surface*0;
			} elseif ( $typeZone == "voieexpress" ){
				$label = "Voie Express";
				$budget = $surface*-0.25;
				$industrie = $surface*0.005;
				$commerce = $surface*0.005;
				$agriculture = $surface*0;
				$tourisme = $surface*0.005;
				$recherche = $surface*0;
				$environnement = $surface*-0.01;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "maritime" || $typeZone == "route maritime" ){
				$label = "Route maritime";
				$budget = $surface*-0.25;
				$industrie = $surface*0.005;
				$commerce = $surface*0.01;
				$agriculture = $surface*0;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.01;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "nationale" ){
				$label = "Route nationales";
				$budget = $surface*-0.01;
				$industrie = $surface*0.002;
				$commerce = $surface*0.002;
				$agriculture = $surface*0;
				$tourisme = $surface*0.002;
				$recherche = $surface*0;
				$environnement = $surface*-0.004;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "autoroute" ){
				$label = "Autoroute";
				$budget = $surface*-0.5;
				$industrie = $surface*0.01;
				$commerce = $surface*0.01;
				$agriculture = $surface*0.01;
				$tourisme = $surface*0.005;
				$recherche = $surface*0;
				$environnement = $surface*-0.04;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "cheminFer" ){
				$label = "Chemin de Fer";
				$budget = $surface*-0.75;
				$industrie = $surface*0.015;
				$commerce = $surface*0.015;
				$agriculture = $surface*0;
				$tourisme = $surface*0.015;
				$recherche = $surface*0;
				$environnement = $surface*-0.01;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "canal" ){
				$label = "Canal";
				$budget = $surface*-1;
				$industrie = $surface*0.03;
				$commerce = $surface*0;
				$agriculture = $surface*0;
				$tourisme = $surface*0.03;
				$recherche = $surface*0;
				$environnement = $surface*0.03;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "ferry" ){
				$label = "Ferry";
				$budget = $surface*-0.75;
				$industrie = $surface*0.015;
				$commerce = $surface*0.015;
				$agriculture = $surface*0;
				$tourisme = $surface*0.015;
				$recherche = $surface*0;
				$environnement = $surface*-0.005;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "route maritime" || $typeZone == "route maritime" ){
				$label = "route maritime";
				$budget = $surface*-0.25;
				$industrie = $surface*0.005;
				$commerce = $surface*0.01;
				$agriculture = $surface*0;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*-0.01;
				$education = $surface*0;
				$population = $surface*0;
			} elseif ( $typeZone == "region" ){
				$label = "R&eacute;gion";
				$budget = 0;
				$industrie = 0;
				$commerce = 0;
				$agriculture = 0;
				$tourisme = 0;
				$recherche = 0;
				$environnement = 0;
				$education = 0;
				$population = 0;
            } else {
				$label = $typeZone;
				$budget = $surface*0;
				$industrie = $surface*0;
				$commerce = $surface*0;
				$agriculture = $surface*0;
				$tourisme = $surface*0;
				$recherche = $surface*0;
				$environnement = $surface*0;
				$education = $surface*0;
				$population = $surface*0;
			}
}

function getResourceColor($resource) {

    $colors = array(
        'agriculture' => '#145d19',
        'budget' => '#ffcd00',
        'commerce' => '#2f659a',
        'education' => '#9a0065',
        'environnement' => '#4ab04e',
        'industrie' => '#652f00',
        'recherche' => '#65659a',
        'tourisme' => '#00cd9a'
    );
    return $colors[$resource];

}

/**
 * Increases or decreases the brightness of a color by a percentage of the current brightness.
 *
 * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
 * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
 *
 * @return  string
 */
function adjustBrightness($hexCode, $adjustPercent) {
    $hexCode = ltrim($hexCode, '#');

    if (strlen($hexCode) == 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    $hexCode = array_map('hexdec', str_split($hexCode, 2));

    foreach ($hexCode as & $color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount = ceil($adjustableLimit * $adjustPercent);

        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    return '#' . implode($hexCode);
}

function renderElement($element, $data = null) {
    if(!is_array($data))
        $data = array($data);
    require(__DIR__ . '/../php/Elements/' . $element . '.php');
}

function formatNum($number, $decimals = 0) {
    return number_format($number, $decimals, '.', '&#8239;');
}

/**
 * @param string $errorType Type d'erreur.
 * @param string|array $errorInfo Corps du message.
 * @param bool $put_on_session Détermine s'il faut stocker le message dans la session. Si ce paramètre est mis à <code>false</code>, le message est directement affiché.
 */
function getErrorMessage($errorType, $errorInfo, $put_on_session = true) {

    if(is_array($errorInfo)) $errorInfo = @implode($errorInfo);

    if($put_on_session) {
        $_SESSION['errmsgs'][] =
            array('msg'      => $errorInfo,
                  'err_type' => $errorType);
    }
    else {
        showErrorMessage($errorType, $errorInfo);
    }

}

function showErrorMessage($errorType, $errorInfo) {

    if($errorType === null) {
        echo $errorInfo;
    }
    else {
        echo '
            <div class="alert alert-block alert-'.$errorType.'">' .
            '<button type="button" class="close" data-dismiss="alert">&#215;</button>' .
            $errorInfo .
            '</div>';
    }

}


function dateFormat($date, $getTime = false) {

    if(!is_numeric($date)) {
        $date = strtotime($date);
    }

    return date('d/m/Y' . ($getTime ? ' à H\hi' : ''), $date);

}


/**
 * Filtre un texte avant affichage.
 * @param string|array $text Texte ou array à traiter.
 * @return mixed|array|string <code>array</code> ou <code>string</code> en cas de succès.
 *  <code>FALSE</code> en cas d'échec.
 */
function __s($text) {

    if(is_array($text)) {
        return filter_var_array($text, FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        return htmlspecialchars($text, ENT_QUOTES);
    }

}


function filter_filename($name) {
    // remove illegal file system characters https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
    $name = str_replace(array_merge(
        array_map('chr', range(0, 31)),
        array('<', '>', ':', '"', '/', '\\', '|', '?', '*')
    ), '', $name);
    // maximise filename length to 255 bytes http://serverfault.com/a/9548/44086
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $name= mb_strcut(pathinfo($name, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($name)) . ($ext ? '.' . $ext : '');
    return $name;
}


function legacyPage($path = '', $params = array()) {

    $url = DEF_URI_PATH;
    $url .= str_replace('.', '/', $path);
    $url .= '.php' . (!empty($params) ? '?' : '') . http_build_query($params);
    return $url;

}


function appendQueryString(&$url) {

    if(isset($_SERVER['QUERY_STRING'])) {
        $url .= (strpos($url, '?')) ? "&" : "?";
        $url .= $_SERVER['QUERY_STRING'];
    }
    $url = preg_replace('/(?:&|(\?))' . 'target' . '=[^&]*(?(1)&|)?/i', "$1", $url);
    $url = rtrim($url, '?');
    $url = rtrim($url, '&');

}


/*************************
 *        Session        *
 *************************/

if(!isset($_SESSION))
    session_start();