<?php
require_once('Connections/maconnexion.php'); 

$colname_MarkerVilles = "-1";
if (isset($_GET['ch_pay_id'])) {
  $colname_MarkerVilles = $_GET['ch_pay_id'];
}
mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerVilles = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_mis_jour, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_armoiries, ch_vil_mis_jour, ch_vil_capitale, ch_use_lien_imgpersonnage, ch_use_login, ch_use_id, ch_vil_nom, ch_vil_population, ch_vil_specialite, ch_vil_lien_img1, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgdrapeau FROM villes INNER JOIN pays ON ch_pay_id = ch_vil_paysID INNER JOIN users on ch_vil_user=ch_use_id WHERE ch_vil_paysID = %s AND ch_vil_capitale<>3", GetSQLValueString($colname_MarkerVilles, "int"));
$MarkerVilles = mysql_query($query_MarkerVilles, $maconnexion) or die(mysql_error());
$row_MarkerVilles = mysql_fetch_assoc($MarkerVilles);
$totalRows_MarkerVilles = mysql_num_rows($MarkerVilles);

// Connexion BDD Monument pour afficher markers des monuments
mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerMonument = sprintf("SELECT ch_pat_id, ch_pat_paysID, ch_pat_villeID, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_mis_jour, ch_pat_nom, ch_pat_lien_img1, ch_vil_armoiries, ch_vil_ID, ch_vil_nom, ch_vil_capitale, pays.ch_pay_id, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_id = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN villes ON  ch_pat_villeID=villes.ch_vil_ID INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_pat_statut=1 AND ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 AND ch_vil_paysID = %s ORDER BY ch_pat_id ASC", GetSQLValueString($colname_MarkerVilles, "int"));
$MarkerMonument = mysql_query($query_MarkerMonument, $maconnexion) or die(mysql_error());
$row_MarkerMonument = mysql_fetch_assoc($MarkerMonument);
$totalRows_MarkerMonument = mysql_num_rows($MarkerMonument);

$emplacement = $row_MarkerVilles['ch_pay_emplacement'];
coordEmplacement($emplacement, $x, $y);


// Connexion BDD gometries pour afficher zones des pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_ZonesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_type, ch_geo_nom, ch_geo_mesure, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'polygon'";
$ZonesPays = mysql_query($query_ZonesPays, $maconnexion) or die(mysql_error());
$row_ZonesPays = mysql_fetch_assoc($ZonesPays);
$totalRows_ZonesPays = mysql_num_rows($ZonesPays);

// Connexion BDD gometries pour afficher voies des pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_VoiesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_type, ch_geo_nom, ch_geo_mesure, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'line'";
$VoiesPays = mysql_query($query_VoiesPays, $maconnexion) or die(mysql_error());
$row_VoiesPays = mysql_fetch_assoc($VoiesPays);
$totalRows_VoiesPays = mysql_num_rows($VoiesPays);
?>

<script type="text/javascript">
		        var map;
			    var mapBounds = new OpenLayers.Bounds( -180.0, -90.0, 180.0, 90.0);
			    var mapMinZoom = 0;
			    var mapMaxZoom = 7;

		        // avoid pink tiles
		        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
		        OpenLayers.Util.onImageLoadErrorColor = "transparent";

// fonction pr pop-up
		var selectControl, selectedFeature;
        function onPopupClose(evt) {
            selectControl.unselect(selectedFeature);
        }
        function onFeatureSelect(feature, popupContentHTML) {
            selectedFeature = feature;
            popup = new OpenLayers.Popup("popup", 
                                     feature.geometry.getBounds().getCenterLonLat(),
                   					 new OpenLayers.Size(200,390),
                                     popupContentHTML,
                                     null, true, onPopupClose);
            feature.popup = popup;
            map.addPopup(popup);
        }
        function onFeatureUnselect(feature) {
            map.removePopup(feature.popup);
            feature.popup.destroy();
            feature.popup = null;
        }    

// construction de la carte
		        function init(){
  // options
	            var options = {
	                controls: [
					new OpenLayers.Control.TouchNavigation({dragPanOptions: {enableKinetic: true}}),
					new OpenLayers.Control.Zoom()
					],
					numZoomLevels: 8,
		            projection: new OpenLayers.Projection("EPSG:4326"),
		            maxResolution: 0.703125,
		            maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90)
		            };
					
  // creation carte
	            map = new OpenLayers.Map('map', options);
				
  // calque de base geographique
	            var tmsoverlay1 = new OpenLayers.Layer.TMS( " Geographique", "Carto/CarteMondeGC_2013/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Myname"
	                });
	            map.addLayer(tmsoverlay1);

  // calque satellite
	            var tmsoverlay2 = new OpenLayers.Layer.TMS( " Satellite", "Carto/Carte-Monde-GC-sat/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: false,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Clamato & Franco de la Muerte-2012"
	                });
	            map.addLayer(tmsoverlay2);
  
   // calque NEUTRE
	            var tmsoverlay3 = new OpenLayers.Layer.TMS( " Neutre", "Carto/Carte-Monde-GC-neutre/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: false,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Boxxy-2013"
	                });
	            map.addLayer(tmsoverlay3);
  
              // allow testing of specific renderers via "?renderer=Canvas", etc
            var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
            renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;
			
			
			
					// calque vector modifier zones administratives
            var vectorsAdministrations = new OpenLayers.Layer.Vector(" R&eacute;gions", {
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						fillColor: "${couleur}",
						strokeWidth : "${epaisseurTrait}",
                        fillOpacity: "${opaciteCouleur}",
						strokeColor: "${couleurTrait}",
						strokeOpacity: "${opaciteTrait}",
						strokeDashstyle : "${Trait}",
						strokeLinecap : "square",
                        pointRadius: "5",
						label : "${name}",
						fontColor: "black",
                    	fontSize: "1Opx",
						fontOpacity: 0.5,
                    	fontFamily: "Arial",
                    	fontWeight: "200",
                    	labelOutlineWidth: 0,
						cursor: "pointer"
                    }, OpenLayers.Feature.Vector.style["default"])),
                }),
	            maxResolution: map.getResolutionForZoom(4),
                renderers: renderer
            });	
        map.addLayer(vectorsAdministrations);




// calque vector zones
            var vectorsZones = new OpenLayers.Layer.Vector(" Zones", {
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						fillColor: "${couleur}",
						strokeWidth : "${epaisseurTrait}",
                        fillOpacity: "${opaciteCouleur}",
						strokeColor: "${couleurTrait}",
						strokeOpacity: "${opaciteTrait}",
						strokeDashstyle : "${Trait}",
                        pointRadius: "5",
						cursor: "pointer"
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style({
                        strokeColor: "#e2001a",
						strokeWidth : 3,
						strokeOpacity : 1,
						strokeDashstyle : "solid",
						pointRadius: "5"
                    	})
                }),
	            maxResolution: map.getResolutionForZoom(3),
                renderers: renderer,
				eventListeners: {
			"featureselected": function(event) {
			map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());}
            }
            });	
       	map.addLayer(vectorsZones);
			
  			// Ajout geometries zones
			var format = new OpenLayers.Format.WKT({
    		'internalProjection': map.baseLayer.projection,
    		'externalProjection': new OpenLayers.Projection("EPSG:4326")
			});
			<?php do { 
			$Nomzone = str_replace ( '-', ' ', $row_ZonesPays['ch_geo_nom']);
			$typeZone = $row_ZonesPays['ch_geo_type'];
			$surface = $row_ZonesPays['ch_geo_mesure'];
			styleZones($typeZone, $fillcolor, $fillOpacity, $strokeWidth, $strokeColor, $strokeOpacity, $Trait);
			ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population);
			?>
			var polygonFeature= format.read("<?php echo $row_ZonesPays['ch_geo_wkt']; ?>");
			polygonFeature.attributes = {
				couleur : "<?php echo $fillcolor; ?>",
				epaisseurTrait : "<?php echo $strokeWidth; ?>",
                opaciteCouleur : "<?php echo $fillOpacity; ?>",
				couleurTrait : "<?php echo $strokeColor; ?>",
				opaciteTrait : "<?php echo $strokeOpacity; ?>",
				Trait : "<?php echo $Trait; ?>",
				name : "<?php echo $Nomzone; ?>",
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><img src='assets/img/imagesdefaut/zone-carte.jpg'></div><div><h3><?php echo addslashes($Nomzone); ?></h3><p><em>cr&eacute;&eacute; par <?php echo $row_ZonesPays['ch_use_login']; ?> <?php if ($row_ZonesPays['ch_geo_pay_id'] == 1) {?>(avec l'Institut G&eacute;c&eacute;en de G&eacute;ographie)<?php } ?></em></p><p>&nbsp;</p><p><strong>Type&nbsp;:</strong> <?php echo $label; ?></h4><p><strong>Surface&nbsp;:</strong> <?php echo $row_ZonesPays['ch_geo_mesure']; ?>Km<sup>2</sup></p><?php if ($row_ZonesPays['ch_geo_pay_id'] != 1) {?><p><strong>Population&nbsp;:</strong> <?php echo $chiffre_francais = number_format($population, 0, ',', ' ');?></p><ul><div class='row-fluid'><li class='span3'><a title='Budget'><img src='assets/img/ressources/Budget.png' alt='icone Budget'></a><p><?php $chiffre_francais = number_format($budget, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Industrie'><img src='assets/img/ressources/Industrie.png' alt='icone Industrie'></a><p><?php $chiffre_francais = number_format($industrie, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Commerce'><img src='assets/img/ressources/Bureau.png' alt='icone Commerce'></a><p><?php $chiffre_francais = number_format($commerce, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Agriculture'><img src='assets/img/ressources/Agriculture.png' alt='icone Agriculture'></a><p><?php $chiffre_francais = number_format($agriculture, 0, ',', ' '); echo $chiffre_francais; ?></p></li></div><div class='row-fluid'><li class='span3'><a title='Tourisme'><img src='assets/img/ressources/tourisme.png' alt='icone Tourisme'></a><p><?php $chiffre_francais = number_format($tourisme, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Recherche'><img src='assets/img/ressources/Recherche.png' alt='icone Recherche'></a><p><?php $chiffre_francais = number_format($recherche, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Environnement'><img src='assets/img/ressources/Environnement.png' alt=icone Environnement'></a><p><?php $chiffre_francais = number_format($environnement, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Education'><img src='assets/img/ressources/Education.png' alt='icone Education'></a><p><?php $chiffre_francais = number_format($education, 0, ',', ' '); echo $chiffre_francais; ?></p></li></div></ul><div class='clearfix'><?php } ?></div>"
				
            } 
			
			  // Ajout calque administration si zone administrative
		<?php
		if ( $row_ZonesPays['ch_geo_type'] == "region" ){ ?>
		vectorsAdministrations.addFeatures([polygonFeature]);
        <?php } else { ?>
		vectorsZones.addFeatures([polygonFeature]);
		<?php } ?>
		<?php } while ($row_ZonesPays = mysql_fetch_assoc($ZonesPays)); ?>


		// calque vector voies
        var vectorsVoies = new OpenLayers.Layer.Vector(" Routes", {
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						cursor: "pointer",
						fillColor: "#000000",
						strokeLinecap : "square",
						strokeColor : "${couleurTrait}",
						strokeWidth : "${epaisseurTrait}",
						strokeDashstyle : "${Trait}",
                        pointRadius: "5",
						cursor: "pointer"
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style({
                        strokeColor: "#e2001a",
						strokeWidth : 3,
						strokeDashstyle : "solid",
						pointRadius: "5"
                    	})
                }),
	            maxResolution: map.getResolutionForZoom(2),
                renderers: renderer,
				eventListeners: {
			"featureselected": function(event) {
	       	map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());}
            }
            });	
        	map.addLayer(vectorsVoies);
			
  			// Ajout des routes sur calque voies
			var format = new OpenLayers.Format.WKT({
    		'internalProjection': map.baseLayer.projection,
    		'externalProjection': new OpenLayers.Projection("EPSG:4326")
			});
			
			
			<?php do {
			$Nomvoie = str_replace ( '-', ' ', $row_VoiesPays['ch_geo_nom']);
			$typeVoie = $row_VoiesPays['ch_geo_type'];
			$surface = $row_VoiesPays['ch_geo_mesure'];
			styleVoies($typeVoie, $couleurTrait, $epaisseurTrait, $Trait);
			ressourcesGeometrie($surface, $typeVoie, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population);
			?>
			var polygonFeature= format.read("<?php echo $row_VoiesPays['ch_geo_wkt']; ?>");
			polygonFeature.attributes = {
				couleurTrait : "<?php echo $couleurTrait; ?>",
				epaisseurTrait : "<?php echo $epaisseurTrait; ?>",
				Trait : "<?php echo $Trait; ?>",
				name : "<?php echo $Nomvoie; ?>",
				xoffset : "20",
				yoffset : "10",
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><img src='assets/img/imagesdefaut/zone-voie.jpg'></div><div><h3><?php echo addslashes($Nomvoie); ?></h3><p><em>cr&eacute;&eacute; par <?php echo $row_VoiesPays['ch_use_login']; ?> <?php if ($row_VoiesPays['ch_geo_pay_id'] == 1) {?>(avec l'Institut G&eacute;c&eacute;en de G&eacute;ographie)<?php } ?></em></p><p>&nbsp;</p><p><strong>Type&nbsp;:</strong> <?php echo $label; ?></h4><p><strong>Longueur&nbsp;:</strong> <?php echo $row_VoiesPays['ch_geo_mesure']; ?>Km</p><?php if ($row_VoiesPays['ch_geo_pay_id'] != 1) {?><ul><div class='row-fluid'><li class='span3'><a title='Budget'><img src='assets/img/ressources/Budget.png' alt='icone Budget'></a><p><?php $chiffre_francais = number_format($budget, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Industrie'><img src='assets/img/ressources/Industrie.png' alt='icone Industrie'></a><p><?php $chiffre_francais = number_format($industrie, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Commerce'><img src='assets/img/ressources/Bureau.png' alt='icone Commerce'></a><p><?php $chiffre_francais = number_format($commerce, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Agriculture'><img src='assets/img/ressources/Agriculture.png' alt='icone Agriculture'></a><p><?php $chiffre_francais = number_format($agriculture, 0, ',', ' '); echo $chiffre_francais; ?></p></li></div><div class='row-fluid'><li class='span3'><a title='Tourisme'><img src='assets/img/ressources/tourisme.png' alt='icone Tourisme'></a><p><?php $chiffre_francais = number_format($tourisme, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Recherche'><img src='assets/img/ressources/Recherche.png' alt='icone Recherche'></a><p><?php $chiffre_francais = number_format($recherche, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Environnement'><img src='assets/img/ressources/Environnement.png' alt=icone Environnement'></a><p><?php $chiffre_francais = number_format($environnement, 0, ',', ' '); echo $chiffre_francais; ?></p></li><li class='span3'><a title='Education'><img src='assets/img/ressources/Education.png' alt='icone Education'></a><p><?php $chiffre_francais = number_format($education, 0, ',', ' '); echo $chiffre_francais; ?></p></li></div></ul><div class='clearfix'></div><?php } ?></div>"
            } 
			vectorsVoies.addFeatures([polygonFeature]);
		<?php } while ($row_VoiesPays = mysql_fetch_assoc($VoiesPays)); ?>

			
			
			
			
				
  // calque vector pays
var vectors1 = new OpenLayers.Layer.Vector(" Pays", {
	            maxResolution: map.getResolutionForZoom(0),
                renderers: renderer,
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						cursor: "pointer",
						graphicName: "square",
                    	graphicYOffset: -20,
						fillColor: "white",
						strokeColor: "black",
                        externalGraphic: "${flag}",
                        graphicOpacity: 1,
						graphicWidth: 30,
                        pointRadius: 10,
                        label : "${name}",
						labelXOffset: 0,
                    	labelYOffset: -20,
						fontColor: "black",
                    fontSize: "16px",
                    fontFamily: "Arial",
                    fontWeight: "bold",
                    labelOutlineWidth: 2
                    }))
                })
            });
			
  // calque vector villes
            var vectors2 = new OpenLayers.Layer.Vector(" Villes", {
	            maxResolution: map.getResolutionForZoom(2),
                renderers: renderer,
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						cursor: "pointer",
                        fillColor: "${couleur}",
                        strokeColor: "black",
                        graphicName: "circle",
                        fillOpacity: 1,
                        pointRadius: "${size}"
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						label : "${name}",
						labelXOffset: 0,
                    	labelYOffset: -15,
						fontStyle: "italic",
						strokeColor: "white",
                        fillOpacity: 1,
                        pointRadius: "${size}"
                    }, OpenLayers.Feature.Vector.style["select"]))
                })
            });			
			
			
			  // calque vector Monuments
            var vectors3 = new OpenLayers.Layer.Vector(" Monuments", {
	            maxResolution: map.getResolutionForZoom(4),
                renderers: renderer,
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						cursor: "pointer",
                        fillColor: "red",
                        strokeColor: "red",
                        graphicName: "star",
                        fillOpacity: 1,
                        pointRadius: "5"
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						label : "${name}",
						labelOutlineWidth: 2,
						labelXOffset: 0,
                    	labelYOffset: 0,
						fontStyle: "italic",
                        graphicName: "star",
						strokeColor: "white",
                        fillOpacity: 1,
                        pointRadius: "8"
                    }, OpenLayers.Feature.Vector.style["select"]))
                })
            });			
			
			
            map.addLayers([vectors1, vectors2, vectors3]);			
			
	            // ajout switcher calques
	            var switcherControl = new OpenLayers.Control.LayerSwitcher();
	            map.addControl(switcherControl);
			    // boutons zoom
				// affichage copyright
				map.addControl(new OpenLayers.Control.Attribution());
				// affichage coordonnées
	            map.addControl(new OpenLayers.Control.MousePosition());
				// navigation avec le clavier
	            map.addControl(new OpenLayers.Control.KeyboardDefaults());
	       		map.setCenter(new OpenLayers.LonLat(<?php echo $x; ?>, <?php echo $y; ?>), 4);
			vectors1.addFeatures(createFeatures1());
            vectors2.addFeatures(createFeatures2());
            vectors3.addFeatures(createFeatures3());
			

  // Fonction creation drapeaux-pays. 

			function createFeatures1() {
            var extent = map.getExtent();
            var features = [];
			
			<?php
			$Nompays = str_replace ( '-', ' ', $row_MarkerVilles['ch_pay_nom']);

?>
		var x = '<?php echo $x; ?>' ;
		var y = '<?php echo $y; ?>' ;  
		var urlicon ='<?php echo $row_MarkerVilles['ch_pay_lien_imgdrapeau']; ?>'
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "<?php echo $Nompays; ?>",
				flag: "<?php echo $row_MarkerVilles['ch_pay_lien_imgdrapeau']; ?>"
            }));
            return features;
        }
		
		
		 // Fonction creation villes. 

			function createFeatures2() {
            var extent = map.getExtent();
            var features = [];
			
			<?php do { 
			$Nomville = str_replace ( '-', ' ', $row_MarkerVilles['ch_vil_nom']);
$Specialiteville = str_replace ( '-', ' ', $row_MarkerVilles['ch_vil_specialite']);
			?>
		var x = '<?php echo $row_MarkerVilles['ch_vil_coord_X']; ?>' ;
		var y = '<?php echo $row_MarkerVilles['ch_vil_coord_Y']; ?>' ;
		<?php if ($row_MarkerVilles['ch_vil_capitale'] == 1) {?>
		var pointercolor = "black";
        <?php } else { ?>
		var pointercolor = "white";
  		<?php } ?>
  		<?php $population = $row_MarkerVilles['ch_vil_population'];
		tailleVilles($population, $sizeicon); ?>
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "<?php echo $Nomville; ?>",
				size : <?php echo $sizeicon; ?>,
				couleur : pointercolor,
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-ville.php?ch_pay_id=<?php echo $row_MarkerVilles['ch_vil_paysID']; ?>&ch_ville_id=<?php echo $row_MarkerVilles['ch_vil_ID']; ?>'><?php if ($row_MarkerVilles['ch_vil_lien_img1']) {?><img src='<?php echo addslashes($row_MarkerVilles['ch_vil_lien_img1']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/ville.jpg'><?php }?></a></div><div><h3><?php echo addslashes($Nomville); ?></h3><p><em>cr&eacute;&eacute;e par <?php echo addslashes($row_MarkerVilles['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerVilles['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo addslashes($row_MarkerVilles['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerVilles['ch_vil_armoiries']) {?><img class='armoirie' src='<?php echo addslashes($row_MarkerVilles['ch_vil_armoiries']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/blason.jpg'><?php }?></div><p>Mise &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime( $row_MarkerVilles['ch_vil_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerVilles['ch_vil_mis_jour'])); ?></strong></p><p>Population&nbsp;: <strong><?php $population_ville_carte_francais = number_format($row_MarkerVilles['ch_vil_population'], 0, ',', ' '); echo $population_ville_carte_francais; ?> habitants</strong></p><p>Sp&eacute;cialit&eacute;&nbsp;: <strong><?php if ( $row_MarkerVilles['ch_vil_specialite']) { echo addslashes($Specialiteville);} else { echo 'NA'; } ?></strong></p><div class='pull-center'></div></div><div class='pied'><a class='btn btn-primary' href='page-ville.php?ch_pay_id=<?php echo $row_MarkerVilles['ch_vil_paysID']; ?>&ch_ville_id=<?php echo $row_MarkerVilles['ch_vil_ID']; ?>'>Visiter cette ville</a></div>"
            }));
		<?php } while ($row_MarkerVilles = mysql_fetch_assoc($MarkerVilles)); ?>
            return features;
        }
		
		// Fonction creation points Monuments. 

			function createFeatures3() {
            var extent = map.getExtent();
            var features = [];
			
			<?php do { 
			$NomMonument = str_replace ( '-', ' ', $row_MarkerMonument['ch_pat_nom']);
			$Nomville = str_replace ( '-', ' ', $row_MarkerMonument['ch_vil_nom']);
			
$listcategories = $row_MarkerMonument['listcat'];
			if ($row_MarkerMonument['listcat']) {
mysql_select_db($database_maconnexion, $maconnexion);
$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion) or die(mysql_error());
$row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			 }
			?>
		var x = '<?php echo $row_MarkerMonument['ch_pat_coord_X']; ?>' ;
		var y = '<?php echo $row_MarkerMonument['ch_pat_coord_Y']; ?>' ;
       
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "Monument\n\n<?php echo addslashes($NomMonument); ?>",
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-monument.php?ch_pat_id=<?php echo $row_MarkerMonument['ch_pat_id']; ?>'><?php if ($row_MarkerMonument['ch_pat_lien_img1']) {?><img src='<?php echo $row_MarkerMonument['ch_pat_lien_img1']; ?>'><?php } else { ?><img src='assets/img/imagesdefaut/ville.jpg'><?php }?></a></div><div><h3><?php echo addslashes($NomMonument); ?></h3><p><em>cr&eacute;&eacute;e par <?php echo addslashes($row_MarkerMonument['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerMonument['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo addslashes($row_MarkerMonument['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerMonument['ch_vil_armoiries']) {?><img class='armoirie' src='<?php echo addslashes($row_MarkerMonument['ch_vil_armoiries']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/blason.jpg'><?php }?></div><p>Monument appartenant &agrave; la ville <strong><a href='page-ville.php?ch_pay_id=<?php echo $row_MarkerMonument['ch_pay_id']; ?>&ch_ville_id=<?php echo $row_MarkerMonument['ch_vil_ID']; ?>'><?php echo addslashes($Nomville); ?></a></strong></p><p>Mise &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime( $row_MarkerMonument['ch_pat_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerMonument['ch_pat_mis_jour'])); ?></strong></p><div class='pull-center'></div><?php if ($row_MarkerMonument['listcat']) {?><div class='row-fluid icone-categorie'><?php do { ?><div><a title='<?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>'><img src='<?php echo $row_liste_mon_cat3['ch_mon_cat_icon']; ?>' alt='icone <?php echo $row_liste_mon_cat3['ch_mon_cat_nom']; ?>' style='background-color:<?php echo $row_liste_mon_cat3['ch_mon_cat_couleur']; ?>; margin-right:5px;'></a></div><?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); } ?></div><div class='pied'><p>&nbsp;</p><a class='btn btn-primary' href='page-monument.php?ch_pat_id=<?php echo $row_MarkerMonument['ch_pat_id']; ?>'>Visiter ce monument</a></div>"
            }));
		<?php if ($row_MarkerMonument['listcat']) { mysql_free_result($liste_mon_cat3); }?>
		<?php } while ($row_MarkerMonument = mysql_fetch_assoc($MarkerMonument)); ?>
            return features;
        }	
	
		
		  // Evennement a la selection. 
			
            vectors2.events.on({
                "featureselected": function(e) {
					onFeatureSelect(e.feature, e.feature.attributes.popupContentHTML);
	       			map.setCenter(e.feature.geometry.getBounds().getCenterLonLat());},
                "featureunselected": function(e) {
					onFeatureUnselect(e.feature, e.feature.attributes.popupContentHTML);
					}
            });
			
			vectors3.events.on({
                "featureselected": function(e) {
					onFeatureSelect(e.feature, e.feature.attributes.popupContentHTML);
	       			map.setCenter(e.feature.geometry.getBounds().getCenterLonLat());},
                "featureunselected": function(e) {
					onFeatureUnselect(e.feature, e.feature.attributes.popupContentHTML);
					}
            });
			
			vectorsZones.events.on({
                "featureselected": function(e) {
					onFeatureSelect(e.feature, e.feature.attributes.popupContentHTML);
	       			map.setCenter(e.feature.geometry.getBounds().getCenterLonLat());},
                "featureunselected": function(e) {
					onFeatureUnselect(e.feature, e.feature.attributes.popupContentHTML);
					}
            });
			
			vectorsVoies.events.on({
                "featureselected": function(e) {
					onFeatureSelect(e.feature, e.feature.attributes.popupContentHTML);
	       			map.setCenter(e.feature.geometry.getBounds().getCenterLonLat());},
                "featureunselected": function(e) {
					onFeatureUnselect(e.feature, e.feature.attributes.popupContentHTML);
					}
            });
				
			// ajout regles de selection
				selectControl = new OpenLayers.Control.SelectFeature(
                [vectorsZones, vectorsVoies, vectors1, vectors2, vectors3 ]
            );
            map.addControl(selectControl);
            selectControl.activate();
						
  // End Fonction Init. 
	        }
			
  // Fonction map tiler pour construction des dalles. 
	        function overlay_getTileURL(bounds) {
				bounds = this.adjustBounds(bounds);
	            var res = this.map.getResolution();
	            var x = Math.round((bounds.left - this.tileOrigin.lon) / (res * this.tileSize.w));
	            var y = Math.round((bounds.bottom - this.tileOrigin.lat) / (res * this.tileSize.h));
	            var z = this.map.getZoom();
				var path = this.serviceVersion + "/" + this.layername + "/" + z + "/" + x + "/" + y + "." + this.type;
				var url = this.url;
		        if (mapBounds.intersectsBounds( bounds ) && z >= mapMinZoom && z <= mapMaxZoom) {
	               // console.log( this.url + z + "/" + x + "/" + y + "." + this.type);
	               return this.url + z + "/" + x + "/" + y + "." + this.type;
                } else {
                   return "http://www.maptiler.org/img/none.png";
                }
	        }
			
		   function getWindowHeight() {
		        if (self.innerHeight) return self.innerHeight;
		        if (document.documentElement && document.documentElement.clientHeight)
		            return document.documentElement.clientHeight;
		        if (document.body) return document.body.clientHeight;
			        return 0;
		    }

		    function getWindowWidth() {
			    if (self.innerWidth) return self.innerWidth;
			    if (document.documentElement && document.documentElement.clientWidth)
			        return document.documentElement.clientWidth;
			    if (document.body) return document.body.clientWidth;
			        return 0;
		    }
		    </script>
<?php
mysql_free_result($MarkerVilles);
mysql_free_result($MarkerMonument);
?>