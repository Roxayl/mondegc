<?php

// Connexion BDD Pays pour afficher markers des pays


$query_MarkerPays = sprintf ("SELECT DISTINCT ch_pay_id, ch_pay_continent, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_pay_header_presentation, ch_pay_mis_jour, ch_pay_population_carte, ch_use_lien_imgpersonnage, ch_use_login, (SELECT SUM(ch_vil_population) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS ch_pay_population, (SELECT COUNT(ch_vil_ID) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS ch_pay_nbvilles FROM pays LEFT JOIN users ON pays.ch_pay_id = users.ch_use_paysID WHERE ch_pay_publication = 1 AND ch_pay_id=%s GROUP BY ch_pay_id ORDER BY ch_pay_nom ASC", GetSQLValueString($paysID, "int"));
$MarkerPays = mysql_query($query_MarkerPays, $maconnexion) or die(mysql_error());
$row_MarkerPays = mysql_fetch_assoc($MarkerPays);
$totalRows_MarkerPays = mysql_num_rows($MarkerPays);

// Connexion BDD Villes pour afficher markers des villes

$query_MarkerVilles = sprintf ("SELECT ch_vil_ID, ch_vil_paysID, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_mis_jour, ch_vil_armoiries, ch_vil_nom, ch_vil_capitale, ch_vil_specialite, ch_vil_population, ch_vil_lien_img1, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 AND ch_vil_paysID=%s ORDER BY ch_vil_paysID ASC", GetSQLValueString($paysID, "int"));
$MarkerVilles = mysql_query($query_MarkerVilles, $maconnexion) or die(mysql_error());
$row_MarkerVilles = mysql_fetch_assoc($MarkerVilles);
$totalRows_MarkerVilles = mysql_num_rows($MarkerVilles);

// Connexion BDD Monument pour afficher markers des monuments

$query_MarkerMonument = sprintf ("SELECT ch_pat_id, ch_pat_paysID, ch_pat_villeID, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_mis_jour, ch_pat_nom, ch_pat_lien_img1, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_id = ch_disp_mon_id) AS listcat, ch_vil_armoiries, ch_vil_ID, ch_vil_nom, ch_vil_capitale, pays.ch_pay_id, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login FROM patrimoine INNER JOIN villes ON  ch_pat_villeID=villes.ch_vil_ID INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_pat_statut=1 AND ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 AND ch_pat_paysID=%s ORDER BY ch_pat_id ASC", GetSQLValueString($paysID, "int"));
$MarkerMonument = mysql_query($query_MarkerMonument, $maconnexion) or die(mysql_error());
$row_MarkerMonument = mysql_fetch_assoc($MarkerMonument);
$totalRows_MarkerMonument = mysql_num_rows($MarkerMonument);

// Connexion BDD gometries pour afficher zones des pays

$query_ZonesPays = sprintf ("SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE ((ch_pay_publication = 1 AND ch_geo_pay_id=%s)  OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'polygon'", GetSQLValueString($paysID, "int"));
$ZonesPays = mysql_query($query_ZonesPays, $maconnexion) or die(mysql_error());
$row_ZonesPays = mysql_fetch_assoc($ZonesPays);
$totalRows_ZonesPays = mysql_num_rows($ZonesPays);

// Connexion BDD gometries pour afficher voies des pays

$query_VoiesPays = sprintf ("SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id INNER JOIN users ON ch_geo_user = ch_use_id WHERE ((ch_pay_publication = 1 AND ch_geo_pay_id=%s) OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'line'", GetSQLValueString($paysID, "int"));
$VoiesPays = mysql_query($query_VoiesPays, $maconnexion) or die(mysql_error());
$row_VoiesPays = mysql_fetch_assoc($VoiesPays);
$totalRows_VoiesPays = mysql_num_rows($VoiesPays);
?>
<script type="text/javascript">

// JavaScript Document
 // récuperation des coordonnées au click  
			OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
                defaultHandlerOptions: {
                    'single': true,
                    'double': false,
                    'pixelTolerance': 0,
                    'stopSingle': false,
                    'stopDouble': false
                },

                initialize: function(options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                        {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                        this, arguments
                    ); 
                    this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'click': this.trigger
                        }, this.handlerOptions
                    );
                }, 

                trigger: function(e) {
                    var lonlat = map.getLonLatFromPixel(e.xy);
					 // Ajout d'un marker
					var size = new OpenLayers.Size(50,50);
                    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
                    var icon = new OpenLayers.Icon('../Carto/images/pin.png', size, offset);   
                    var markerslayer = map.getLayer('Markers');
					markerslayer.clearMarkers();
                    markerslayer.addMarker(new OpenLayers.Marker(lonlat,icon));
					document.getElementById('form_coord_X').value = lonlat.lon;
					document.getElementById('form_coord_Y').value = lonlat.lat;
					document.getElementById('coord_X').innerHTML = lonlat.lon;
					document.getElementById('coord_Y').innerHTML = lonlat.lat;
                }
				
				
            });
			
			
			                
                  // variables de la carte
		        var map;
                var mapBounds = new OpenLayers.Bounds( -180.0, -89.9811063294, 180.0, 90.0);
			    var mapMinZoom = 0;
			    var mapMaxZoom = 7;
				
		        // avoid pink tiles
		        OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
		        OpenLayers.Util.onImageLoadErrorColor = "transparent";
				
				// Init

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
		            projection: new OpenLayers.Projection("EPSG:4326"),
		            maxResolution: 0.703125,
		            maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90),
					numZoomLevels: mapMaxZoom,
                };
					
                
				// construction de la carte
				map = new OpenLayers.Map('map', options);

				var tmsoverlay;
				
 // calque de base geographique
	            tmsoverlay = new OpenLayers.Layer.TMS( " Geographique", "../Carto/CarteMondeGC_2013/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Myname"
	                });
	            map.addLayer(tmsoverlay);
								
				// calque de base satellite
               tmsoverlay = new OpenLayers.Layer.TMS( " Satellite", "../Carto/Carte-Monde-GC-sat/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						attribution:"&copy; Clamato & Franco de la Muerte-2012",
	                });
	            map.addLayer(tmsoverlay);
				if (! OpenLayers.Util.alphaHack()) { tmsoverlay.setOpacity(1); }
				
				
				// calque de base neutre
				tmsoverlay = new OpenLayers.Layer.TMS( " Neutre", "../Carto/Carte-Monde-GC-neutre/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
                        attribution: "&copy; Boxxy-2013"
	                });
	            map.addLayer(tmsoverlay);
				if (! OpenLayers.Util.alphaHack()) { tmsoverlay.setOpacity(1); }

                // calque GC 2018 (non fonctionnel)
                tmsoverlay = new OpenLayers.Layer.TMS(" Geographique (2018 - beta)", "https://www.generation-city.com/monde/Carto/CarteGC_2018/",
                    {
                        serviceVersion: '.', layername: '.', alpha: false,
                        type: 'png', getURL: overlay_getTileURL,
                        isBaseLayer: true,
                        transitionEffect: "resize",
                        attribution: "&copy; Boxxy-2013, Sakuro-2018"
                    });
                map.addLayer(tmsoverlay);
				if (! OpenLayers.Util.alphaHack()) { tmsoverlay.setOpacity(1); }

	
	
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
                    }, OpenLayers.Feature.Vector.style["default"]))
                }),
	            maxResolution: map.getResolutionForZoom(2),
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
				name : "<?php echo $Nomzone; ?>"
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
                        pointRadius: "5"
                    }, OpenLayers.Feature.Vector.style["default"]))
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
			ressourcesGeometrie($surface, $typeVoie, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $emploi);
			?>
			
			var polygonFeature= format.read("<?= e($row_VoiesPays['ch_geo_wkt']) ?>");
			polygonFeature.attributes = {
				couleurTrait : "<?php echo $couleurTrait; ?>",
				epaisseurTrait : "<?php echo $epaisseurTrait; ?>",
				Trait : "<?php echo $Trait; ?>"
            } 
			vectorsVoies.addFeatures([polygonFeature]);
		<?php } while ($row_VoiesPays = mysql_fetch_assoc($VoiesPays)); ?>


  		// calque vector continents
           var vectorLayer = new OpenLayers.Layer.Vector(" Continents", {
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
			
			
  			// calque vector pays
			var vectors1 = new OpenLayers.Layer.Vector(" Pays", {
	            maxResolution: map.getResolutionForZoom(1),
                renderers: renderer,
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						cursor: "pointer",
						graphicName: "square",
						fillColor: "white",
						strokeColor: "black",
                        externalGraphic: "${flag}",
                        graphicOpacity: 1,
						graphicWidth: 30,
                        pointRadius: 10
                    }, OpenLayers.Feature.Vector.style["default"])),
                    "select": new OpenLayers.Style({
                        externalGraphic: "${flag}",
                        graphicOpacity: 0.5,
                        label : "${name}",
						fontColor: "black",
                    	fontSize: "16px",
                    	fontFamily: "Arial",
                    	fontWeight: "bold",
                    	labelOutlineWidth: 2
                    	})
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
						label: "${name}",
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
				map.setCenter(new OpenLayers.LonLat(0, 0), 1);
				// ajout règles de selection
				selectControl = new OpenLayers.Control.SelectFeature(
                [vectors1, vectors2, vectors3],
                {
                    clickout: true, toggle: false,
                    multiple: false, hover: false,
                    toggleKey: "ctrlKey", // ctrl key removes from selection
                    multipleKey: "shiftKey" // shift key adds to selection
                }
            );
            
            selectControl.handlers.feature.stopDown = false;
            map.addControl(selectControl);
            selectControl.activate();
			vectors1.addFeatures(createFeatures1());
            vectors2.addFeatures(createFeatures2());
            vectors3.addFeatures(createFeatures3());
			


  // Fonction creation drapeaux-pays. 

			function createFeatures1() {
            var extent = map.getExtent();
            var features = [];
			
			<?php do { 
			$Nompays = str_replace ( '-', ' ', $row_MarkerPays['ch_pay_nom']);
$emplacement = $row_MarkerPays['ch_pay_emplacement'];
coordEmplacement($emplacement, $x, $y);
?>
		var x = '<?php echo $x; ?>' ;
		var y = '<?php echo $y; ?>' ;  
		var urlicon ='<?= e($row_MarkerPays['ch_pay_lien_imgdrapeau']) ?>'
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "<?php echo $Nompays; ?>",
				flag: "<?= e($row_MarkerPays['ch_pay_lien_imgdrapeau']) ?>"
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
				couleur : pointercolor
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
			?>
		var x = '<?= e($row_MarkerMonument['ch_pat_coord_X']) ?>' ;
		var y = '<?= e($row_MarkerMonument['ch_pat_coord_Y']) ?>' ;
                features.push(new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point(x,y), features.attributes = {
                	name: "Monument\n\n<?php echo addslashes($NomMonument); ?>"
            		}
				));
		<?php } while ($row_MarkerMonument = mysql_fetch_assoc($MarkerMonument)); ?>
            return features;
        }
		
				
				// calque pointeur
				markers = new OpenLayers.Layer.Markers( " Pointeur" );
                markers.id = "Markers";
                map.addLayer(markers);
				
				
				
				// ajout location ville
				var x = '<?php echo $coord_X; ?>';
		        var y = '<?php echo $coord_Y; ?>'; 
				var size = new OpenLayers.Size(50,50);
                var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
                var icon = new OpenLayers.Icon('../Carto/images/pin.png', size, offset); 
                var marker = new OpenLayers.Marker(new OpenLayers.LonLat(x,y), icon);
				markers.addMarker(marker);
				
				// création de l'interaction pour ajouter un marqueur
				var click = new OpenLayers.Control.Click();
                map.addControl(click);
                click.activate();			
				map.events.register("click", map, function(e) {});
				
				// end Init
	        }
			
			
			 // fonction affichage des tuiles --- Map Tiler -----
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
</script>