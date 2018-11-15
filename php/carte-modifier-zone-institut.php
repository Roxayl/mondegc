<?php
include('../Connections/maconnexion.php'); 

// Connexion BDD Pays pour afficher markers des pays

mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerPays = "SELECT ch_pay_id, ch_pay_continent, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_pay_header_presentation, ch_pay_mis_jour, ch_use_lien_imgpersonnage, ch_use_login, Sum(villes.ch_vil_population) AS ch_pay_population, Count(villes.ch_vil_ID) AS ch_pay_nbvilles FROM pays LEFT JOIN villes ON ch_pay_id = ch_vil_paysID AND ch_vil_capitale != 3 LEFT JOIN users ON pays.ch_pay_id = users.ch_use_paysID WHERE ch_pay_publication = 1 GROUP BY ch_pay_id ORDER BY ch_pay_nom ASC";
$MarkerPays = mysql_query($query_MarkerPays, $maconnexion) or die(mysql_error());
$row_MarkerPays = mysql_fetch_assoc($MarkerPays);
$totalRows_MarkerPays = mysql_num_rows($MarkerPays);

// Connexion BDD Villes pour afficher markers des villes
mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerVilles = "SELECT ch_vil_nom, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_capitale, ch_vil_population FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 ORDER BY ch_vil_paysID ASC";
$MarkerVilles = mysql_query($query_MarkerVilles, $maconnexion) or die(mysql_error());
$row_MarkerVilles = mysql_fetch_assoc($MarkerVilles);
$totalRows_MarkerVilles = mysql_num_rows($MarkerVilles);

// Connexion BDD Monument pour afficher markers des monuments
mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerMonument = "SELECT ch_pat_nom, ch_pat_coord_X, ch_pat_coord_Y FROM patrimoine INNER JOIN villes ON  ch_pat_villeID=villes.ch_vil_ID INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id WHERE ch_pat_statut=1 AND ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 ORDER BY ch_pat_id ASC";
$MarkerMonument = mysql_query($query_MarkerMonument, $maconnexion) or die(mysql_error());
$row_MarkerMonument = mysql_fetch_assoc($MarkerMonument);
$totalRows_MarkerMonument = mysql_num_rows($MarkerMonument);


// Connexion BDD gometries pour afficher zones des pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_ZonesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'polygon'";
$ZonesPays = mysql_query($query_ZonesPays, $maconnexion) or die(mysql_error());
$row_ZonesPays = mysql_fetch_assoc($ZonesPays);
$totalRows_ZonesPays = mysql_num_rows($ZonesPays);


// Connexion BDD gometries pour afficher voies des pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_VoiesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'line'";
$VoiesPays = mysql_query($query_VoiesPays, $maconnexion) or die(mysql_error());
$row_VoiesPays = mysql_fetch_assoc($VoiesPays);
$totalRows_VoiesPays = mysql_num_rows($VoiesPays);

$now= date("Y-m-d G:i:s");
?>
<script type="text/javascript">
		        var map;
			    var mapBounds = new OpenLayers.Bounds( -180.0, -90.0, 180.0, 90.0);
			    var mapMinZoom = 0;
			    var mapMaxZoom = 7;


		     // avoid pink tiles
		        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
		        OpenLayers.Util.onImageLoadErrorColor = "transparent";

		        function init(){
  			// options
	            var options = {
	                controls: [
                        new OpenLayers.Control.ScaleLine(),
                        new OpenLayers.Control.TouchNavigation({
                            dragPanOptions: {enableKinetic: true}
                        }),
                        new OpenLayers.Control.Zoom(),
                        new OpenLayers.Control.Navigation({
                            mouseWheelOptions: {interval: 100}
                        })
                    ],
					numZoomLevels: 8,
		            projection: new OpenLayers.Projection("EPSG:4326"),
		            maxResolution: 0.703125,
		            maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90)
		            };
					
  			// creation carte
	            map = new OpenLayers.Map('map', options);
				
  			// calque de base geographique
	            var tmsoverlay1 = new OpenLayers.Layer.TMS( " Geographique", "http://www.generation-city.com/monde/Carto/CarteGC_2018/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Myname"
	                });
				tmsoverlay1.setVisibility(false);	
	            map.addLayer(tmsoverlay1);

  			// calque satellite
	            var tmsoverlay2 = new OpenLayers.Layer.TMS( " Satellite", "http://www.generation-city.com/monde/Carto/Carte-Monde-GC-sat/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: false,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Clamato & Franco de la Muerte-2012"
	                });
	            map.addLayer(tmsoverlay2);
  
   			// calque NEUTRE
	            var tmsoverlay3 = new OpenLayers.Layer.TMS( " Neutre", "http://www.generation-city.com/monde/Carto/Carte-Monde-GC-neutre/",
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


  			// calque vector continents
            var vectorLayer = new OpenLayers.Layer.Vector("Continents", {
                styleMap: new OpenLayers.StyleMap({'default':{
                    strokeOpacity: 0,
                    pointerEvents: "visiblePainted",
                    label : "${name}",
                    fontColor: "black",
                    fontSize: "20px",
					fontOpacity: 0.5,
                    fontFamily: "Arial",
                    fontWeight: "bold",
                    labelOutlineWidth: 0
                }}),
                minResolution: map.getResolutionForZoom(3),
                renderers: renderer
            });
            
            // create point feature Philicie
            var point = new OpenLayers.Geometry.Point(-130, 0);
            var pointFeature = new OpenLayers.Feature.Vector(point);
            pointFeature.attributes = {
                name: "Philicie"
            };
			
			// create point feature Aldesyl
            var point2 = new OpenLayers.Geometry.Point(-30, -19);
            var pointFeature2 = new OpenLayers.Feature.Vector(point2);
            pointFeature2.attributes = {
                name: "Aldesyl"
            };
			
			// create point feature Aurinea
            var point3 = new OpenLayers.Geometry.Point(113, 36);
            var pointFeature3 = new OpenLayers.Feature.Vector(point3);
            pointFeature3.attributes = {
                name: "Aurinea"
            };
			
			// create point feature Volcania
            var point4 = new OpenLayers.Geometry.Point(45, -38);
            var pointFeature4 = new OpenLayers.Feature.Vector(point4);
            pointFeature4.attributes = {
                name: "Volcania"
            };
			
			// create point feature Volcania
            var point5 = new OpenLayers.Geometry.Point(104, -40);
            var pointFeature5 = new OpenLayers.Feature.Vector(point5);
            pointFeature5.attributes = {
                name: "Oceania"
            };
			
map.addLayer(vectorLayer);
            vectorLayer.addFeatures([pointFeature, pointFeature2, pointFeature3, pointFeature4, pointFeature5]);
		
  // Ajout control Info
		    var wgs84 = new OpenLayers.Projection("EPSG:4326");
        var defStyle = {strokeColor: "blue", strokeOpacity: "0.7", strokeWidth: 2, fillColor: "blue", pointRadius: 3, cursor: "pointer"};
        var sty = OpenLayers.Util.applyDefaults(defStyle, OpenLayers.Feature.Vector.style["default"]);
        var sm = new OpenLayers.StyleMap({
            'default': sty,
            'select': {strokeColor: "red", fillColor: "red"}
        });
		
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
            "featuremodified": function(event) {
			modifierZone(event.feature, event.feature.attributes.formulaire);},
			"beforefeaturemodified": function(event) {
			modifierZone(event.feature, event.feature.attributes.formulaire);
	       	map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());},
			"featureselected": function(event) {
			modifierZone(event.feature, event.feature.attributes.formulaire);
			map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());}
            }
            });	
        map.addLayer(vectorsAdministrations);

// calque vector modifier zone
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
	            maxResolution: map.getResolutionForZoom(2),
                renderers: renderer,
				eventListeners: {
            "featuremodified": function(event) {
			modifierZone(event.feature, event.feature.attributes.formulaire);},
			"beforefeaturemodified": function(event) {
			modifierZone(event.feature, event.feature.attributes.formulaire);
	       	map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());},
			"featureselected": function(event) {
			modifierZone(event.feature, event.feature.attributes.formulaire);
			map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());}
            }
            });	
        map.addLayer(vectorsZones);
  // Ajout des zones sur calque modification
var format = new OpenLayers.Format.WKT({
    'internalProjection': map.baseLayer.projection,
    'externalProjection': new OpenLayers.Projection("EPSG:4326")
});
			<?php do {
			$typeZone = $row_ZonesPays['ch_geo_type'];
			styleZones($typeZone, $fillcolor, $fillOpacity, $strokeWidth, $strokeColor, $strokeOpacity, $Trait);
			?>
var polygonFeature= format.read("<?php echo $row_ZonesPays['ch_geo_wkt']; ?>");
polygonFeature.attributes = {
				couleur : "<?php echo $fillcolor; ?>",
				epaisseurTrait : "<?php echo $strokeWidth; ?>",
                opaciteCouleur : "<?php echo $fillOpacity; ?>",
				couleurTrait : "<?php echo $strokeColor; ?>",
				opaciteTrait : "<?php echo $strokeOpacity; ?>",
				Trait : "<?php echo $Trait; ?>",
				formulaire : "<h2>Modifier cette zone</h2><p>Cr&eacute;&eacute; par&nbsp;:&nbsp;<?php echo $row_ZonesPays['ch_use_login']; ?></p><form action='<?php echo $editFormAction; ?>' method='POST' class='' name='modifier_feature' Id='ajout_feature' onsubmit=''><input name='ch_geo_id' type='hidden' value='<?php echo $row_ZonesPays['ch_geo_id']; ?>'><input name='ch_geo_bounds' id='ch_geo_bounds' type='hidden' value=''><input name='ch_geo_pay_id' type='hidden' value='<?php echo $row_ZonesPays['ch_geo_pay_id']; ?>'><input name='ch_geo_user' type='hidden' value='<?php echo $row_ZonesPays['ch_geo_user']; ?>'><input name='ch_geo_maj_user' type='hidden' value='<?php echo $_SESSION['user_ID']; ?>'><input name='ch_geo_date' type='hidden' value='<?php echo $row_ZonesPays['ch_geo_date']; ?>'><input name='ch_geo_mis_jour' type='hidden' value='<?php echo $now; ?>'><input name='ch_geo_geometries' type='hidden' value='polygon'><div><h4>Type de zone</h4><select id='ch_geo_type' name='ch_geo_type' class='span12'><optgroup label='Zones am&eacute;nag&eacute;es'><option value='urbaine' <?php if (!(strcmp("urbaine", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone urbaine</option><option value='periurbaine' <?php if (!(strcmp("periurbaine", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone p&eacute;riurbaine</option><option value='industrielle' <?php if (!(strcmp("industrielle", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone industrielle</option><option value='maraichere' <?php if (!(strcmp("maraichere", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Culture mara&icirc;ch&egrave;re</option><option value='cerealiere' <?php if (!(strcmp("cerealiere", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Culture c&eacute;r&eacute;ali&egravere</option><option value='elevage' <?php if (!(strcmp("elevage", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Elevage</option></optiongroup><optgroup label='Zones naturelles'><option value='prairies' <?php if (!(strcmp("prairies", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Prairies</option><option value='forestiere' <?php if (!(strcmp("forestiere", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone foresti&egrave;re</option><option value='protegee' <?php if (!(strcmp("protegee", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone foresti&egrave;re prot&eacute;g&eacute;e</option><option value='marecageuse' <?php if (!(strcmp("marecageuse", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone mar&eacute;cageuse</option><option value='lagunaire' <?php if (!(strcmp("lagunaire", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Zone lagunaire</option></optiongroup><optgroup label='Limites administratives'><option value='region' <?php if (!(strcmp("region", $row_ZonesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>R&eacute;gion</option></optiongroup></select></div><div><h4>Nom de votre zone</h4><input class='span12' type='text' id='ch_geo_nom' name='ch_geo_nom' value='<?php echo $row_ZonesPays['ch_geo_nom']; ?>'></div><div><h4>Surface (km<sup>2</sup>)</h4><input class='span12' type='text' id='ch_geo_mesure' name='ch_geo_mesure' value='<?php echo $row_ZonesPays['ch_geo_mesure']; ?>' readonly = 'readonly'></div><div><h4>Geometrie</h4><textarea class='span12' id='ch_geo_wkt' name='ch_geo_wkt' rows='6' readonly = 'readonly'></textarea></div><a class='btn btn-danger pull-right' href='back/carte_geometrie_supprimer.php?ch_geo_id=<?php echo $row_ZonesPays['ch_geo_id']; ?>' title='supprimer cette zone' style='text-decoration:none;'>supprimer</a><button type='submit' class='btn btn-primary'>Enregistrer</button><input type='hidden' name='MM_update' value='modifier_feature'></form>"
            } 
			
			  // Ajout calque administration si zone administrative
			<?php
			if ( $row_ZonesPays['ch_geo_type'] == "region" ){ ?>
			vectorsAdministrations.addFeatures([polygonFeature]);
            <?php } else { ?>
			vectorsZones.addFeatures([polygonFeature]);
			<?php } ?>
		<?php } while ($row_ZonesPays = mysql_fetch_assoc($ZonesPays)); ?>

  // Calque pour dessiner nouvelle zone
        var vectorsAjoutZone = new OpenLayers.Layer.Vector("", {
            styleMap: sm,
			displayInLayerSwitcher : false,
            eventListeners: {
			"beforefeatureadded": function(event) {
			vectorsAjoutZone.removeAllFeatures()},
            "featureadded": function(event) {
			ajouterZone(event.feature);},
			"featureselected": function(event) {
            ajouterZone(event.feature);}
            },
        });
        map.addLayer(vectorsAjoutZone);

// calque vector modifier voies
            var vectorsVoies = new OpenLayers.Layer.Vector(" Routes", {
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
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
            "featuremodified": function(event) {
			modifierVoie(event.feature, event.feature.attributes.formulaire);},
			"beforefeaturemodified": function(event) {
			modifierVoie(event.feature, event.feature.attributes.formulaire);},
			"featureselected": function(event) {
			modifierVoie(event.feature, event.feature.attributes.formulaire);}
            }
            });	
        map.addLayer(vectorsVoies);
  // Ajout des voies sur calque modifier
var format = new OpenLayers.Format.WKT({
    'internalProjection': map.baseLayer.projection,
    'externalProjection': new OpenLayers.Projection("EPSG:4326")
});
			<?php do { 
			$typeVoie = $row_VoiesPays['ch_geo_type'];
			styleVoies($typeVoie, $couleurTrait, $epaisseurTrait, $Trait);
			?>
var polygonFeature= format.read("<?php echo $row_VoiesPays['ch_geo_wkt']; ?>");
polygonFeature.attributes = {
				couleurTrait : "<?php echo $couleurTrait; ?>",
				epaisseurTrait : "<?php echo $epaisseurTrait; ?>",
				Trait : "<?php echo $Trait; ?>",
				formulaire : "<h2>Modifier une voie</h2><p>Cr&eacute;&eacute; par&nbsp;:&nbsp;<?php echo $row_VoiesPays['ch_use_login']; ?></p><form action='<?php echo $editFormAction; ?>' method='POST' class='' name='modifier_feature' Id='ajout_feature' onsubmit=''><input name='ch_geo_id' type='hidden' value='<?php echo $row_VoiesPays['ch_geo_id']; ?>'><input name='ch_geo_bounds' id='ch_geo_bounds' type='hidden' value=''><input name='ch_geo_pay_id' type='hidden' value='<?php echo $row_VoiesPays['ch_geo_pay_id']; ?>'><input name='ch_geo_user' type='hidden' value='<?php echo $row_VoiesPays['ch_geo_user']; ?>'><input name='ch_geo_maj_user' type='hidden' value='<?php echo $_SESSION['user_ID']; ?>'><input name='ch_geo_date' type='hidden' value='<?php echo $row_VoiesPays['ch_geo_date']; ?>'><input name='ch_geo_mis_jour' type='hidden' value='<?php echo $now; ?>'><input name='ch_geo_geometries' type='hidden' value='line'><div><h4>Type de voie</h4><select id='ch_geo_type' name='ch_geo_type' class='span12'><option value='lgv' <?php if (!(strcmp("lgv", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Ligne &agrave; Grande Vitesse</option><option value='cheminFer' <?php if (!(strcmp("cheminFer", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Chemin de fer</option><option value='canal' <?php if (!(strcmp("canal", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Canal</option><option value='maritime' <?php if (!(strcmp("maritime", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Route maritime</option><option value='autoroute' <?php if (!(strcmp("autoroute", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Autoroutes</option><option value='voieexpress' <?php if (!(strcmp("voieexpress", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Voie express</option><option value='nationale' <?php if (!(strcmp("nationale", $row_VoiesPays['ch_geo_type']))) {echo "selected=\'selected\'";} ?>>Route nationale</option></select></div><div><h4>Nom de votre voie</h4><input class='span12' type='text' id='ch_geo_nom' name='ch_geo_nom' value='<?php echo $row_VoiesPays['ch_geo_nom']; ?>'></div><div><h4>Longueur (km)</h4><input class='span12' type='text' id='ch_geo_mesure' name='ch_geo_mesure' value='<?php echo $row_VoiesPays['ch_geo_mesure']; ?>' readonly = 'readonly'></div><div><h4>Geometrie</h4><textarea class='span12' id='ch_geo_wkt' name='ch_geo_wkt' rows='6' readonly = 'readonly'></textarea></div><a class='btn btn-danger pull-right' href='back/carte_geometrie_supprimer.php?ch_geo_id=<?php echo $row_VoiesPays['ch_geo_id']; ?>' title='supprimer cette zone' style='text-decoration:none;'>supprimer</a><button type='submit' class='btn btn-primary'>Enregistrer</button><input type='hidden' name='MM_update' value='modifier_feature'></form>"
            } 
vectorsVoies.addFeatures([polygonFeature]);
		<?php } while ($row_VoiesPays = mysql_fetch_assoc($VoiesPays)); ?>


  // Calque pour dessiner nouvelles voies
        var vectorsAjoutVoie = new OpenLayers.Layer.Vector("", {
            styleMap: sm,
			displayInLayerSwitcher : false,
            eventListeners: {
			"beforefeatureadded": function(event) {
			vectorsAjoutVoie.removeAllFeatures()},
            "featureadded": function(event) {
			ajouterVoie(event.feature);},
			"featureselected": function(event) {
            ajouterVoie(event.feature);}
            },
        });
        map.addLayer(vectorsAjoutVoie);

  // calque vector pays
var vectors1 = new OpenLayers.Layer.Vector(" Pays", {
	styleMap: new OpenLayers.StyleMap({'default':{
						graphicName: "square",
						fillColor: "white",
						strokeColor: "black",
                        externalGraphic: "${flag}",
                        graphicOpacity: 1,
						graphicWidth: 30,
                        pointRadius: 10
                }}),
	            maxResolution: map.getResolutionForZoom(1),
                renderers: renderer
            });
			
  // calque vector villes
            var vectors2 = new OpenLayers.Layer.Vector(" Villes", {
			styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                        fillColor: "${couleur}",
                        strokeColor: "black",
                        graphicName: "circle",
                        fillOpacity: 1,
                        pointRadius: "${size}"
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						label : "${name}",
						labelOutlineWidth: 2,
						labelXOffset: 0,
                    	labelYOffset: 0,
						fontStyle: "italic",
                        fillColor: "${couleur}",
                        strokeColor: "black",
                        graphicName: "circle",
                        fillOpacity: 1,
                        pointRadius: "${size}"
                    }, OpenLayers.Feature.Vector.style["select"]))
                }),
				maxResolution: map.getResolutionForZoom(2),
                renderers: renderer
            });
					
// calque vector Monuments
            var vectors3 = new OpenLayers.Layer.Vector(" Monuments", {
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                        fillColor: "red",
                        strokeColor: "red",
                        graphicName: "star",
                        fillOpacity: 1,
                        pointRadius: "5"
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						label: "${name}",
						labelOutlineWidth: 2,
						labelXOffset: 0,
                    	labelYOffset: 0,
						fontStyle: "italic",
                        fillColor: "red",
                        strokeColor: "red",
                        graphicName: "star",
                        fillOpacity: 1,
                        pointRadius: "5"
                    }, OpenLayers.Feature.Vector.style["select"]))
                }),
	            maxResolution: map.getResolutionForZoom(4),
                renderers: renderer
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
	       		map.setCenter(new OpenLayers.LonLat(<?php echo $bounds; ?>), 2);
			vectors1.addFeatures(createFeatures1());
            vectors2.addFeatures(createFeatures2());
            vectors3.addFeatures(createFeatures3());


  // Fonction creation drapeaux-pays. 

			function createFeatures1() {
            var extent = map.getExtent();
            var features = [];
			<?php do { 
$emplacement = $row_MarkerPays['ch_pay_emplacement'];
coordEmplacement($emplacement, $x, $y);
?>
		var x = '<?php echo $x; ?>' ;
		var y = '<?php echo $y; ?>' ;  
		var urlicon ='<?php echo $row_MarkerPays['ch_pay_lien_imgdrapeau']; ?>'
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
				flag: "<?php echo $row_MarkerPays['ch_pay_lien_imgdrapeau']; ?>"
            }));
		<?php } while ($row_MarkerPays = mysql_fetch_assoc($MarkerPays)); ?>
            return features;
        }
		
		
		 // Fonction creation villes. 

			function createFeatures2() {
            var extent = map.getExtent();
            var features = [];
			<?php do { 
			$Nomville = str_replace ( '-', ' ', $row_MarkerVilles['ch_vil_nom']);
			?>
		var x = '<?php echo $row_MarkerVilles['ch_vil_coord_X']; ?>' ;
		var y = '<?php echo $row_MarkerVilles['ch_vil_coord_Y']; ?>' ;
		<?php if ($row_MarkerVilles['ch_vil_capitale'] == 1) {?>
		var pointercolor = "red";
        <?php } else { ?>
		var pointercolor = "white";
  		<?php } 
		$population = $row_MarkerVilles['ch_vil_population'];
		tailleVilles($population, $sizeicon);
		?>
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
				size : <?php echo $sizeicon; ?>,
				couleur : pointercolor,
				name : "<?php echo $Nomville; ?>"
            }));
		<?php } while ($row_MarkerVilles = mysql_fetch_assoc($MarkerVilles)); ?>
            return features;
        }		
		
		// Fonction creation points Monuments. 

			function createFeatures3() {
            var extent = map.getExtent();
            var features = [];
			<?php do { 
			$NomMonument = str_replace ( '-', ' ', $row_MarkerMonument['ch_pat_nom']);?>
		var x = '<?php echo $row_MarkerMonument['ch_pat_coord_X']; ?>' ;
		var y = '<?php echo $row_MarkerMonument['ch_pat_coord_Y']; ?>' ;
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y),  features.attributes = {
                name: "Monument\n\n<?php echo addslashes($NomMonument); ?>"
				}));
		<?php } while ($row_MarkerMonument = mysql_fetch_assoc($MarkerMonument)); ?>
            return features;
        }

  // Ajout control Info
        var navControl = new OpenLayers.Control.Navigation({title: 'Pan/Zoom'});
        var editPanel = new OpenLayers.Control.Panel({displayClass: 'editPanel'});
        editPanel.addControls([
            new OpenLayers.Control.DrawFeature(vectorsAjoutVoie, OpenLayers.Handler.Path, {displayClass: 'lineButton', title: 'Tracer une nouvelle route', handlerOptions: {style: sty}}),
			new OpenLayers.Control.ModifyFeature(vectorsVoies, {displayClass: 'ModifyLineButton', title: 'Modifier une route'}),
			new OpenLayers.Control.DrawFeature(vectorsAjoutZone, OpenLayers.Handler.Polygon, {displayClass: 'polygonButton', title: 'Tracer une nouvelle zone', handlerOptions: {style: sty}}),
			new OpenLayers.Control.ModifyFeature(vectorsZones, {displayClass: 'ModifyPolygonButton', title: 'Modifier une zone'}),
            new OpenLayers.Control.ModifyFeature(vectorsAdministrations, {displayClass: 'ModifyAdministrativeButton', title: 'Modifier une zone administrative'}),
            navControl
        ]);
        editPanel.defaultControl = navControl;
        map.addControl(editPanel);

// ajout regles de selection
				selectControl = new OpenLayers.Control.SelectFeature(
                [vectorsAjoutZone, vectorsAdministrations, vectorsZones, vectorsVoies, vectorsAjoutVoie, vectors1, vectors2, vectors3 ]
            );
            selectControl.handlers.feature.stopDown = false;
            map.addControl(selectControl);
            selectControl.activate();

// configure the snapping agent
            snap = new OpenLayers.Control.Snapping({
                layer: vectorsAjoutVoie,
                targets: [vectorsVoies, vectorsZones, vectors2, vectors3, vectorsAdministrations],
                greedy: false
            });
            snap.activate(); 
			
			snap2 = new OpenLayers.Control.Snapping({
                layer: vectorsAjoutZone,
                targets: [vectorsVoies, vectorsZones, vectors2, vectors3, vectorsAdministrations],
                greedy: false
            });
            snap2.activate();   
   
   			snap3 = new OpenLayers.Control.Snapping({
                layer: vectorsVoies,
                targets: [vectorsVoies, vectorsZones, vectors2, vectors3, vectorsAdministrations],
                greedy: false
            });
            snap3.activate();   
   
   			snap4 = new OpenLayers.Control.Snapping({
                layer: vectorsZones,
                targets: [vectorsVoies, vectorsZones, vectors2, vectors3, vectorsAdministrations],
                greedy: false
            });
            snap4.activate();  
			
			snap5 = new OpenLayers.Control.Snapping({
                layer: vectorsAdministrations,
                targets: [vectorsVoies, vectorsZones, vectors2, vectors3, vectorsAdministrations],
                greedy: false
            });
            snap5.activate();   
   
    // End init
    }

function ajouterZone(feature) {
            var wkt = new OpenLayers.Format.WKT();
			var out = wkt.write(feature);
			var poly = new OpenLayers.Geometry.Polygon(feature);
			var area = feature.geometry.getGeodesicArea();
            area = area / 1000000;
            var bounds = feature.geometry.getBounds().getCenterLonLat();
			bounds= bounds.toShortString();
			var formulaire = "<h2>Ajouter une zone</h2><form action='<?php echo $editFormAction; ?>' method='POST' class='' name='ajout_feature' Id='ajout_feature' onsubmit=''><input name='ch_geo_bounds' id='ch_geo_bounds' type='hidden' value=''><input name='ch_geo_pay_id' type='hidden' value='1'><input name='ch_geo_user' type='hidden' value='<?php echo $_SESSION['user_ID']; ?>'><input name='ch_geo_maj_user' type='hidden' value='<?php echo $_SESSION['user_ID']; ?>'><input name='ch_geo_date' type='hidden' value='<?php echo $now; ?>'><input name='ch_geo_mis_jour' type='hidden' value='<?php echo $now; ?>'><input name='ch_geo_geometries' type='hidden' value='polygon'><div><h4>Type de zone</h4><select id='ch_geo_type' name='ch_geo_type' class='span12'><optgroup label='Zones am&eacute;nag&eacute;es'><option value='urbaine'>Zone urbaine</option><option value='periurbaine'>Zone p&eacute;riurbaine</option><option value='industrielle'>Zone industrielle</option><option value='maraichere'>Culture mara&icirc;ch&egrave;re</option><option value='cerealiere'>Culture c&eacute;r&eacute;ali&egravere</option><option value='elevage'>Elevage</option></optiongroup><optgroup label='Zones naturelles'><option value='prairies'>Prairies</option><option value='forestiere'>Zone foresti&egrave;re</option><option value='protegee'>Zone foresti&egrave;re prot&eacute;g&eacute;e</option><option value='marecageuse'>Zone mar&eacute;cageuse</option><option value='lagunaire'>Zone lagunaire</option></optiongroup><optgroup label='Limites administratives'><option value='region' label='limite administrative'>R&eacute;gion</option></optiongroup></select></div><div><h4>Nom de votre zone</h4><input class='span12' type='text' id='ch_geo_nom' name='ch_geo_nom' value=''></div><div><h4>Surface (km<sup>2</sup>)</h4><input class='span12' type='text' id='ch_geo_mesure' name='ch_geo_mesure' value='' readonly = 'readonly'></div><div><h4>Geometrie</h4><textarea class='span12' id='ch_geo_wkt' name='ch_geo_wkt' rows='6' readonly = 'readonly'></textarea></div><center><button type='submit' class='btn btn-primary'>Enregistrer</button></center><input type='hidden' name='MM_insert' value='ajout_feature'></form>";
			document.getElementById("info").innerHTML = formulaire;
			document.getElementById("ch_geo_wkt").innerHTML = out;
			document.getElementById("ch_geo_mesure").value = area;
			document.getElementById("ch_geo_bounds").value = bounds;
        }


function ajouterVoie(feature) {
            var wkt = new OpenLayers.Format.WKT();
			var out = wkt.write(feature);
			var poly = new OpenLayers.Geometry.Polygon(feature);
			var longueur = feature.geometry.getGeodesicLength();
            longueur = longueur / 1000;
            var bounds = feature.geometry.getBounds().getCenterLonLat();
			bounds= bounds.toShortString();
			var formulaire = "<h2>Ajouter une voie</h2><form action='<?php echo $editFormAction; ?>' method='POST' class='' name='ajout_feature' Id='ajout_feature' onsubmit=''><input name='ch_geo_bounds' id='ch_geo_bounds' type='hidden' value=''><input name='ch_geo_pay_id' type='hidden' value='1'><input name='ch_geo_user' type='hidden' value='<?php echo $_SESSION['user_ID']; ?>'><input name='ch_geo_maj_user' type='hidden' value='<?php echo $_SESSION['user_ID']; ?>'><input name='ch_geo_date' type='hidden' value='<?php echo $now; ?>'><input name='ch_geo_mis_jour' type='hidden' value='<?php echo $now; ?>'><input name='ch_geo_geometries' type='hidden' value='line'><div><h4>Type de voie</h4><select id='ch_geo_type' name='ch_geo_type' class='span12'><option value='lgv'>Ligne &agrave; grande vitesse</option><option value='cheminFer'>Chemin de fer</option><option value='canal'>Canal</option><option value='maritime'>Route maritime</option><option value='autoroute'>Autoroute</option><option value='voieexpress'>Voie express</option><option value='nationale'>Route nationale</option></select></div><div><h4>Nom de votre voie</h4><input class='span12' type='text' id='ch_geo_nom' name='ch_geo_nom' value=''></div><div><h4>Longueur (km)</h4><input class='span12' type='text' id='ch_geo_mesure' name='ch_geo_mesure' value='' readonly = 'readonly'></div><div><h4>Geometrie</h4><textarea class='span12' id='ch_geo_wkt' name='ch_geo_wkt' rows='6' readonly = 'readonly'></textarea></div><center><button type='submit' class='btn btn-primary'>Enregistrer</button></center><input type='hidden' name='MM_insert' value='ajout_feature'></form>";
			document.getElementById("info").innerHTML = formulaire;
			document.getElementById("ch_geo_wkt").innerHTML = out;
			document.getElementById("ch_geo_mesure").value = longueur;
			document.getElementById("ch_geo_bounds").value = bounds;
        }



function modifierVoie(feature, formulaire) {
            var wkt = new OpenLayers.Format.WKT();
			var out = wkt.write(feature);
			var poly = new OpenLayers.Geometry.Polygon(feature);
			var longueur = feature.geometry.getGeodesicLength();
            longueur = longueur / 1000;
            var bounds = feature.geometry.getBounds().getCenterLonLat();
			bounds= bounds.toShortString();
			document.getElementById("info").innerHTML = formulaire;
			document.getElementById("ch_geo_wkt").value = out;
			document.getElementById("ch_geo_mesure").value = longueur;
			document.getElementById("ch_geo_bounds").value = bounds;
        }
		
function modifierZone(feature, formulaire) {
            var wkt = new OpenLayers.Format.WKT();
			var out = wkt.write(feature);
			var poly = new OpenLayers.Geometry.Polygon(feature);
			var area = feature.geometry.getGeodesicArea();
            area = area / 1000000;
            var bounds = feature.geometry.getBounds().getCenterLonLat();
			bounds= bounds.toShortString();
			document.getElementById("info").innerHTML = formulaire;
			document.getElementById("ch_geo_wkt").value = out;
			document.getElementById("ch_geo_mesure").value = area;
			document.getElementById("ch_geo_bounds").value = bounds;
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
mysql_free_result($MarkerPays);
mysql_free_result($MarkerVilles);
mysql_free_result($MarkerMonument);
?>