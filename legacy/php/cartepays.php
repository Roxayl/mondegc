<?php

$colname_MarkerVilles = "-1";
if (isset($_GET['ch_pay_id'])) {
  $colname_MarkerVilles = $_GET['ch_pay_id'];
}
// Connexion BDD Pays

$query_drapeauPays = sprintf("SELECT ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgdrapeau FROM pays WHERE ch_pay_id = %s AND ch_pay_publication=1", escape_sql($colname_MarkerVilles, "int"));
$drapeauPays = mysql_query($query_drapeauPays, $maconnexion);
$row_drapeauPays = mysql_fetch_assoc($drapeauPays);
$totalRows_drapeauPayss = mysql_num_rows($drapeauPays);

// Connexion BDD Villes pour afficher markers des villes

$query_MarkerVilles = sprintf("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_mis_jour, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_armoiries, ch_vil_mis_jour, ch_vil_capitale, ch_use_lien_imgpersonnage, ch_use_login, ch_use_id, ch_vil_nom, ch_vil_population, ch_vil_specialite, ch_vil_lien_img1, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgdrapeau FROM villes INNER JOIN pays ON ch_pay_id = ch_vil_paysID INNER JOIN users on ch_vil_user=ch_use_id WHERE ch_vil_paysID = %s AND ch_vil_capitale<>3", escape_sql($colname_MarkerVilles, "int"));
$MarkerVilles = mysql_query($query_MarkerVilles, $maconnexion);
$row_MarkerVilles = mysql_fetch_assoc($MarkerVilles);
$totalRows_MarkerVilles = mysql_num_rows($MarkerVilles);

// Connexion BDD Monument pour afficher markers des monuments

$query_MarkerMonument = sprintf("SELECT ch_pat_id, ch_pat_paysID, ch_pat_villeID, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_mis_jour, ch_pat_nom, ch_pat_lien_img1, ch_vil_armoiries, ch_vil_ID, ch_vil_nom, ch_vil_capitale, pays.ch_pay_id, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_id = ch_disp_mon_id) AS listcat FROM patrimoine INNER JOIN villes ON  ch_pat_villeID=villes.ch_vil_ID INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_pat_statut=1 AND ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 AND ch_vil_paysID = %s ORDER BY ch_pat_id ASC", escape_sql($colname_MarkerVilles, "int"));
$MarkerMonument = mysql_query($query_MarkerMonument, $maconnexion);
$row_MarkerMonument = mysql_fetch_assoc($MarkerMonument);
$totalRows_MarkerMonument = mysql_num_rows($MarkerMonument);

$emplacement = $row_drapeauPays['ch_pay_emplacement'];
coordEmplacement($emplacement, $x, $y);

// Connexion BDD gometries pour afficher terres

$query_ZonesTerres = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_type, ch_geo_nom FROM geometries WHERE ch_geo_geometries = 'polygon' AND ch_geo_type= 'terre'";
$ZonesTerres = mysql_query($query_ZonesTerres, $maconnexion);
$totalRows_ZonesTerres = mysql_num_rows($ZonesTerres);

// Connexion BDD gometries pour afficher zones des pays

$query_ZonesPays = sprintf("SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_type, ch_geo_nom, ch_geo_mesure, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'polygon' AND ch_geo_type != 'terre' AND (ch_geo_pay_id = %s OR ch_geo_pay_id = 1)", escape_sql($colname_MarkerVilles, "int"));
$ZonesPays = mysql_query($query_ZonesPays, $maconnexion);
$totalRows_ZonesPays = mysql_num_rows($ZonesPays);

// Connexion BDD gometries pour afficher voies des pays

$query_VoiesPays = sprintf("SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_type, ch_geo_nom, ch_geo_mesure, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'line' AND (ch_geo_pay_id = %s OR ch_geo_pay_id = 1)", escape_sql($colname_MarkerVilles, "int"));
$VoiesPays = mysql_query($query_VoiesPays, $maconnexion);
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
                        new OpenLayers.Control.TouchNavigation({
                            dragPanOptions: {enableKinetic: true}
                        }),
                        new OpenLayers.Control.Zoom(),
                        new OpenLayers.Control.Navigation({
                            mouseWheelOptions: {interval: 100}
                        })
                    ],
					numZoomLevels: 6,
		            projection: new OpenLayers.Projection("EPSG:4326"),
		            maxResolution: 0.703125,
		            maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90)
		            };
					
  // creation carte
	            map = new OpenLayers.Map('map', options);
				
  // calque de base geographique
	            var tmsoverlay1 = new OpenLayers.Layer.TMS( " Geographique", "carto/CarteMondeGC_2013/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Myname"
	                });
	            map.addLayer(tmsoverlay1);

  // calque satellite
	            var tmsoverlay2 = new OpenLayers.Layer.TMS( " Satellite", "carto/Carte-Monde-GC-sat/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: false,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Clamato & Franco de la Muerte-2012"
	                });
	            map.addLayer(tmsoverlay2);
  
   // calque NEUTRE
	            var tmsoverlay3 = new OpenLayers.Layer.TMS( " Neutre", "carto/Carte-Monde-GC-neutre/",
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
			
			// calque vector TERRES
            var vectorsTerres = new OpenLayers.Layer.Vector(" Terres", {
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
						label : "",
						fontColor: "black",
                        fontSize: "11px",
                        fontOpacity: 0.5,
                        fontFamily: "Roboto",
                        fontWeight: "200",
                        labelOutlineWidth: 0,
						cursor: "pointer"
                    }, OpenLayers.Feature.Vector.style["default"])),
                }),
	            maxResolution: map.getResolutionForZoom(0),
                renderers: renderer
            });
        map.addLayer(vectorsTerres);


  			// Ajout geometries zones administratives
			var format = new OpenLayers.Format.WKT({
    		'internalProjection': map.baseLayer.projection,
    		'externalProjection': new OpenLayers.Projection("EPSG:4326")
			});
			<?php while ($row_ZonesTerres = mysql_fetch_assoc($ZonesTerres)) {
			$Nomzone = $row_ZonesTerres['ch_geo_nom'];
			$typeZone = $row_ZonesTerres['ch_geo_type'];
			styleZones($typeZone, $fillcolor, $fillOpacity, $strokeWidth, $strokeColor, $strokeOpacity, $Trait);
			?>
			var polygonFeature= format.read("<?= e($row_ZonesTerres['ch_geo_wkt']) ?>");
			polygonFeature.attributes = {
				couleur : "<?php echo $fillcolor; ?>",
				epaisseurTrait : "<?php echo $strokeWidth; ?>",
                opaciteCouleur : "<?php echo $fillOpacity; ?>",
				couleurTrait : "<?php echo $strokeColor; ?>",
				opaciteTrait : "<?php echo $strokeOpacity; ?>",
				Trait : "<?php echo $Trait; ?>",
				name : "<?php echo e($Nomzone); ?>"
            }
		vectorsTerres.addFeatures([polygonFeature]);
		<?php } ?>
		
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
			<?php while ($row_ZonesPays = mysql_fetch_assoc($ZonesPays)) {
			$Nomzone = $row_ZonesPays['ch_geo_nom'];
			$typeZone = $row_ZonesPays['ch_geo_type'];
			$surface = $row_ZonesPays['ch_geo_mesure'];
			styleZones($typeZone, $fillcolor, $fillOpacity, $strokeWidth, $strokeColor, $strokeOpacity, $Trait);
			ressourcesGeometrie($surface, $typeZone, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
			?>
			var polygonFeature= format.read("<?= e($row_ZonesPays['ch_geo_wkt']) ?>");
			polygonFeature.attributes = {
				couleur : "<?php echo $fillcolor; ?>",
				epaisseurTrait : "<?php echo $strokeWidth; ?>",
                opaciteCouleur : "<?php echo $fillOpacity; ?>",
				couleurTrait : "<?php echo $strokeColor; ?>",
				opaciteTrait : "<?php echo $strokeOpacity; ?>",
				Trait : "<?php echo $Trait; ?>",
				name : "<?php echo e($Nomzone); ?>",
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><img src='assets/img/imagesdefaut/zone-carte.jpg'></div><div><h3><?php echo e($Nomzone); ?></h3><p><em>cr&eacute;&eacute; par <?= e($row_ZonesPays['ch_use_login']) ?> <?php if ($row_ZonesPays['ch_geo_pay_id'] == 1) {?>(avec l'Institut G&eacute;c&eacute;en de G&eacute;ographie)<?php } ?></em></p><p>&nbsp;</p><p><strong>Type&nbsp;:</strong> <?php echo e($label); ?></h4><p><strong>Surface&nbsp;:</strong> <?= e($row_ZonesPays['ch_geo_mesure']) ?>Km<sup>2</sup></p><?php if ($row_ZonesPays['ch_geo_pay_id'] != 1) {?><p><strong>Population&nbsp;:</strong> <?= formatNum($population);?></p><ul><div class='row-fluid'><li class='span3'><a title='Budget'><img src='assets/img/ressources/budget.png' alt='icone Budget'></a><p><?= formatNum($budget); ?></p></li><li class='span3'><a title='Industrie'><img src='assets/img/ressources/industrie.png' alt='icone Industrie'></a><p><?= formatNum($industrie); ?></p></li><li class='span3'><a title='Commerce'><img src='assets/img/ressources/bureau.png' alt='icone Commerce'></a><p><?= formatNum($commerce); ?></p></li><li class='span3'><a title='Agriculture'><img src='assets/img/ressources/agriculture.png' alt='icone Agriculture'></a><p><?= formatNum($agriculture); ?></p></li></div><div class='row-fluid'><li class='span3'><a title='Tourisme'><img src='assets/img/ressources/tourisme.png' alt='icone Tourisme'></a><p><?= formatNum($tourisme); ?></p></li><li class='span3'><a title='Recherche'><img src='assets/img/ressources/recherche.png' alt='icone Recherche'></a><p><?= formatNum($recherche); ?></p></li><li class='span3'><a title='Environnement'><img src='assets/img/ressources/environnement.png' alt=icone Environnement'></a><p><?= formatNum($environnement); ?></p></li><li class='span3'><a title='Education'><img src='assets/img/ressources/education.png' alt='icone Education'></a><p><?= formatNum($education); ?></p></li></div></ul><div class='clearfix'><?php } ?></div>"
            } 
			
			  // Ajout calque administration si zone administrative
		<?php
		if ( $row_ZonesPays['ch_geo_type'] == "region" ) { ?>
		vectorsAdministrations.addFeatures([polygonFeature]);
        <?php } else { ?>
		vectorsZones.addFeatures([polygonFeature]);
		<?php } ?>
		<?php } ?>

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
                        pointRadius: "5"
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
			
			
			<?php while ($row_VoiesPays = mysql_fetch_assoc($VoiesPays)) {
			$Nomvoie = $row_VoiesPays['ch_geo_nom'];
			$typeVoie = $row_VoiesPays['ch_geo_type'];
			$surface = $row_VoiesPays['ch_geo_mesure'];
			styleVoies($typeVoie, $couleurTrait, $epaisseurTrait, $Trait);
			ressourcesGeometrie($surface, $typeVoie, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
			?>
			var polygonFeature = format.read("<?= e($row_VoiesPays['ch_geo_wkt']) ?>");
			polygonFeature.attributes = {
				couleurTrait : "<?php echo $couleurTrait; ?>",
				epaisseurTrait : "<?php echo $epaisseurTrait; ?>",
				Trait : "<?php echo $Trait; ?>",
				name : "<?php echo e($Nomvoie); ?>",
				xoffset : "20",
				yoffset : "10",
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><img src='assets/img/imagesdefaut/zone-voie.jpg'></div><div><h3><?php echo e($Nomvoie); ?></h3><p><em>cr&eacute;&eacute; par <?= e($row_VoiesPays['ch_use_login']) ?> <?php if ($row_VoiesPays['ch_geo_pay_id'] == 1) {?>(avec l'Institut G&eacute;c&eacute;en de G&eacute;ographie)<?php } ?></em></p><p>&nbsp;</p><p><strong>Type&nbsp;:</strong> <?php echo e($label); ?></h4><p><strong>Longueur&nbsp;:</strong> <?= e($row_VoiesPays['ch_geo_mesure']) ?>Km</p><?php if ($row_VoiesPays['ch_geo_pay_id'] != 1) {?><ul><div class='row-fluid'><li class='span3'><a title='Budget'><img src='assets/img/ressources/budget.png' alt='icone Budget'></a><p><?= formatNum($budget); ?></p></li><li class='span3'><a title='Industrie'><img src='assets/img/ressources/industrie.png' alt='icone Industrie'></a><p><?= formatNum($industrie); ?></p></li><li class='span3'><a title='Commerce'><img src='assets/img/ressources/bureau.png' alt='icone Commerce'></a><p><?= formatNum($commerce); ?></p></li><li class='span3'><a title='Agriculture'><img src='assets/img/ressources/agriculture.png' alt='icone Agriculture'></a><p><?= formatNum($agriculture); ?></p></li></div><div class='row-fluid'><li class='span3'><a title='Tourisme'><img src='assets/img/ressources/tourisme.png' alt='icone Tourisme'></a><p><?= formatNum($tourisme); ?></p></li><li class='span3'><a title='Recherche'><img src='assets/img/ressources/recherche.png' alt='icone Recherche'></a><p><?= formatNum($recherche); ?></p></li><li class='span3'><a title='Environnement'><img src='assets/img/ressources/environnement.png' alt=icone Environnement'></a><p><?= formatNum($environnement); ?></p></li><li class='span3'><a title='Education'><img src='assets/img/ressources/education.png' alt='icone Education'></a><p><?= formatNum($education); ?></p></li></div></ul><div class='clearfix'></div><?php } ?></div>"
            } 
			vectorsVoies.addFeatures([polygonFeature]);
		<?php } ?>
			
			
			
			
				
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
	       		map.setCenter(new OpenLayers.LonLat(<?php echo e($x); ?>, <?php echo e($y); ?>), 4);
			vectors1.addFeatures(createFeatures1());
			

  // Fonction creation drapeaux-pays. 

			function createFeatures1() {
            var extent = map.getExtent();
            var features = [];
			
			<?php
			$Nompays = $row_drapeauPays['ch_pay_nom'];

?>
		var x = '<?php echo $x; ?>' ;
		var y = '<?php echo $y; ?>' ;  
		var urlicon ='<?= e($row_drapeauPays['ch_pay_lien_imgdrapeau']) ?>'
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "<?= e($Nompays); ?>",
				flag: "<?= e($row_drapeauPays['ch_pay_lien_imgdrapeau']) ?>"
            }));
            return features;
        }
		
		
		 // Fonction creation villes.
            vectors2.addFeatures(createFeatures2());
			function createFeatures2() {
            var extent = map.getExtent();
            var features = [];
			
			<?php while($row_MarkerVilles = mysql_fetch_assoc($MarkerVilles)) {
			$Nomville = $row_MarkerVilles['ch_vil_nom'];
            $Specialiteville = $row_MarkerVilles['ch_vil_specialite'];
			?>
		var x = '<?= e($row_MarkerVilles['ch_vil_coord_X']) ?>' ;
		var y = '<?= e($row_MarkerVilles['ch_vil_coord_Y']) ?>' ;
		<?php if ($row_MarkerVilles['ch_vil_capitale'] == 1) {?>
		var pointercolor = "red";
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
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-ville.php?ch_pay_id=<?= e($row_MarkerVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_MarkerVilles['ch_vil_ID']) ?>'><?php if ($row_MarkerVilles['ch_vil_lien_img1']) {?><img src='<?php echo e($row_MarkerVilles['ch_vil_lien_img1']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/ville.jpg'><?php }?></a></div><div><h3><?php echo e($Nomville); ?></h3><p><em>cr&eacute;&eacute;e par <?php echo e($row_MarkerVilles['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerVilles['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo e($row_MarkerVilles['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerVilles['ch_vil_armoiries']) {?><img class='armoirie' src='<?php echo e($row_MarkerVilles['ch_vil_armoiries']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/blason.jpg'><?php }?></div><p>Mise &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime( $row_MarkerVilles['ch_vil_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerVilles['ch_vil_mis_jour'])); ?></strong></p><p>Population&nbsp;: <strong><?= formatNum($row_MarkerVilles['ch_vil_population']); ?> habitants</strong></p><p>Sp&eacute;cialit&eacute;&nbsp;: <strong><?php if ( $row_MarkerVilles['ch_vil_specialite']) { echo e($Specialiteville);} else { echo 'NA'; } ?></strong></p><div class='pull-center'></div></div><div class='pied'><a class='btn btn-primary' href='page-ville.php?ch_pay_id=<?= e($row_MarkerVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_MarkerVilles['ch_vil_ID']) ?>'>Visiter cette ville</a></div>"
            }));
		<?php } ?>
            return features;
        }

		// Fonction creation points Monuments.
            vectors3.addFeatures(createFeatures3());
			function createFeatures3() {
            var extent = map.getExtent();
            var features = [];
			
			<?php while ($row_MarkerMonument = mysql_fetch_assoc($MarkerMonument)) {
			$NomMonument = $row_MarkerMonument['ch_pat_nom'];
			$Nomville = $row_MarkerMonument['ch_vil_nom'];
			
$listcategories = $row_MarkerMonument['listcat'];
			if ($row_MarkerMonument['listcat']) {

$query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
$liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion);
$totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
			 }
			?>
		var x = '<?= e($row_MarkerMonument['ch_pat_coord_X']) ?>' ;
		var y = '<?= e($row_MarkerMonument['ch_pat_coord_Y']) ?>' ;
       
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "Monument\n\n<?php echo e($NomMonument); ?>",
				popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-monument.php?ch_pat_id=<?= e($row_MarkerMonument['ch_pat_id']) ?>'><?php if ($row_MarkerMonument['ch_pat_lien_img1']) {?><img src='<?php echo $row_MarkerMonument['ch_pat_lien_img1']; ?>'><?php } else { ?><img src='assets/img/imagesdefaut/ville.jpg'><?php }?></a></div><div><h3><?php echo e($NomMonument); ?></h3><p><em>cr&eacute;&eacute;e par <?php echo e($row_MarkerMonument['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerMonument['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo e($row_MarkerMonument['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerMonument['ch_vil_armoiries']) {?><img class='armoirie' src='<?php echo e($row_MarkerMonument['ch_vil_armoiries']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/blason.jpg'><?php }?></div><p>Monument appartenant &agrave; la ville <strong><a href='page-ville.php?ch_pay_id=<?= e($row_MarkerMonument['ch_pay_id']) ?>&ch_ville_id=<?= e($row_MarkerMonument['ch_vil_ID']) ?>'><?php echo e($Nomville); ?></a></strong></p><p>Mise &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime( $row_MarkerMonument['ch_pat_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerMonument['ch_pat_mis_jour'])); ?></strong></p><div class='pull-center'></div><?php if ($row_MarkerMonument['listcat']) {?><div class='row-fluid icone-categorie'><?php while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)) { ?><div><a title='<?php echo e($row_liste_mon_cat3['ch_mon_cat_nom']); ?>'><img src='<?php echo e($row_liste_mon_cat3['ch_mon_cat_icon']); ?>' alt='icone <?php echo e($row_liste_mon_cat3['ch_mon_cat_nom']); ?>' style='background-color:<?php echo e($row_liste_mon_cat3['ch_mon_cat_couleur']); ?>; margin-right:5px;'></a></div><?php } } ?></div><div class='pied'><p>&nbsp;</p><a class='btn btn-primary' href='page-monument.php?ch_pat_id=<?= e($row_MarkerMonument['ch_pat_id']) ?>'>Visiter ce monument</a></div>"
            }));
		<?php if ($row_MarkerMonument['listcat']) { mysql_free_result($liste_mon_cat3); }?>
		<?php } ?>
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
            selectControl.handlers.feature.stopDown = false;
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
