<?php
require_once('Connections/maconnexion.php'); 

// Connexion BDD Pays pour afficher markers des pays

mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerPays = "SELECT DISTINCT ch_pay_id, ch_pay_continent, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_pay_header_presentation, ch_pay_mis_jour, ch_pay_population_carte, ch_use_lien_imgpersonnage, ch_use_login, (SELECT SUM(ch_vil_population) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS ch_pay_population, (SELECT COUNT(ch_vil_ID) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS ch_pay_nbvilles FROM pays LEFT JOIN users ON pays.ch_pay_id = users.ch_use_paysID WHERE ch_pay_publication = 1 GROUP BY ch_pay_id ORDER BY ch_pay_nom ASC";
$MarkerPays = mysql_query($query_MarkerPays, $maconnexion) or die(mysql_error());
$row_MarkerPays = mysql_fetch_assoc($MarkerPays);
$totalRows_MarkerPays = mysql_num_rows($MarkerPays);

// Connexion BDD Villes pour afficher markers des villes
mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerVilles = "SELECT ch_vil_ID, ch_vil_paysID, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_mis_jour, ch_vil_armoiries, ch_vil_nom, ch_vil_capitale, ch_vil_specialite, ch_vil_population, ch_vil_lien_img1, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 ORDER BY ch_vil_paysID ASC";
$MarkerVilles = mysql_query($query_MarkerVilles, $maconnexion) or die(mysql_error());
$row_MarkerVilles = mysql_fetch_assoc($MarkerVilles);
$totalRows_MarkerVilles = mysql_num_rows($MarkerVilles);

// Connexion BDD Monument pour afficher markers des monuments
mysql_select_db($database_maconnexion, $maconnexion);
$query_MarkerMonument = "SELECT ch_pat_id, ch_pat_paysID, ch_pat_villeID, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_mis_jour, ch_pat_nom, ch_pat_lien_img1, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_id = ch_disp_mon_id) AS listcat, ch_vil_armoiries, ch_vil_ID, ch_vil_nom, ch_vil_capitale, pays.ch_pay_id, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login FROM patrimoine INNER JOIN villes ON  ch_pat_villeID=villes.ch_vil_ID INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_pat_statut=1 AND ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 ORDER BY ch_pat_id ASC";
$MarkerMonument = mysql_query($query_MarkerMonument, $maconnexion) or die(mysql_error());
$row_MarkerMonument = mysql_fetch_assoc($MarkerMonument);
$totalRows_MarkerMonument = mysql_num_rows($MarkerMonument);

// Connexion BDD gometries pour afficher zones des pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_ZonesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id LEFT JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'polygon'";
$ZonesPays = mysql_query($query_ZonesPays, $maconnexion) or die(mysql_error());
$row_ZonesPays = mysql_fetch_assoc($ZonesPays);
$totalRows_ZonesPays = mysql_num_rows($ZonesPays);

// Connexion BDD gometries pour afficher voies des pays
mysql_select_db($database_maconnexion, $maconnexion);
$query_VoiesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id INNER JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'line'";
$VoiesPays = mysql_query($query_VoiesPays, $maconnexion) or die(mysql_error());
$row_VoiesPays = mysql_fetch_assoc($VoiesPays);
$totalRows_VoiesPays = mysql_num_rows($VoiesPays);
?>
<script type="text/javascript">
// JavaScript Document
 // récuperation des coordonnées au click  
                  // variables de la carte
		        var map;
				var layer, markers;
                var currentPopup;
                var mapBounds = new OpenLayers.Bounds( -180.0, -89.9811063294, 180.0, 90.0);
			    var mapMinZoom = 0;
			    var mapMaxZoom = 7;
				var ll, popupClass, popupContentHTML;
				var x = '<?php echo $x; ?>' ;
				var y = '<?php echo $y; ?>' ;  
				var urlicon ='Carto/images/pin.png';
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
                dragPanOptions: {
                    enableKinetic: true
                }
            }),
			new OpenLayers.Control.Zoom(),
					],
		            projection: new OpenLayers.Projection("EPSG:4326"),
		            maxResolution: 0.703125,
		            maxExtent: new OpenLayers.Bounds(-180, -90, 180, 90),
					numZoomLevels: 8,
					
		            };
					
                
				// construction de la carte

	            map = new OpenLayers.Map('mapPosition', options);
				
 // calque de base geographique
	            var tmsoverlay = new OpenLayers.Layer.TMS( " Geographique", "Carto/CarteGC_2018/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						transitionEffect : "resize",
						attribution:"&copy; Myname"
	                });
	            map.addLayer(tmsoverlay);
				
				// calque de base satellite

				
	            var tmsoverlay = new OpenLayers.Layer.TMS( " Satellite", "Carto/Carte-Monde-GC-sat/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						attribution:"&copy; Clamato & Franco de la Muerte-2012.",
	                });
	            map.addLayer(tmsoverlay);
				if (OpenLayers.Util.alphaHack() == false) { tmsoverlay.setOpacity(1); }
				
				
				// calque de base neutre
				var tmsoverlay = new OpenLayers.Layer.TMS( " Neutre", "Carto/Carte-Monde-GC-neutre/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true,
						attribution:"&copy; Boxxy-2013"
	                });
	            map.addLayer(tmsoverlay);
				if (OpenLayers.Util.alphaHack() == false) { tmsoverlay.setOpacity(1); }
			


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
			var polygonFeature= format.read("<?php echo $row_ZonesPays['ch_geo_wkt']; ?>");
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
			ressourcesGeometrie($surface, $typeVoie, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population, $emploi);
			?>
			
			var polygonFeature= format.read("<?php echo $row_VoiesPays['ch_geo_wkt']; ?>");
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
		
		
					// calque vector pointeur
			var pointeur = new OpenLayers.Layer.Vector(" Pointeur", {
                renderers: renderer,
                styleMap: new OpenLayers.StyleMap({
                    "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
						cursor: "pointer",
						graphicName: "square",
						fillColor: "white",
						strokeColor: "black",
                        externalGraphic: "Carto/images/pin.png",
                        graphicOpacity: 1,
						graphicWidth: 40,
						graphicYOffset : -40,
                        pointRadius: 10
                    }, OpenLayers.Feature.Vector.style["default"]))
                })
            });	
			
            map.addLayers([vectors1, vectors2, vectors3, pointeur]);			

			vectors1.addFeatures(createFeatures1());
            vectors2.addFeatures(createFeatures2());
            vectors3.addFeatures(createFeatures3());
			pointeur.addFeatures(createpointeur());			
			

			
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
				map.setCenter(new OpenLayers.LonLat(x, y), 4);
				// ajout règles de selection
				selectControl = new OpenLayers.Control.SelectFeature(
                [vectors1, vectors2, vectors3, pointeur],
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
		var urlicon ='<?php echo $row_MarkerPays['ch_pay_lien_imgdrapeau']; ?>'
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
                name: "<?php echo $Nompays; ?>",
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
		var x = '<?php echo $row_MarkerMonument['ch_pat_coord_X']; ?>' ;
		var y = '<?php echo $row_MarkerMonument['ch_pat_coord_Y']; ?>' ;
                features.push(new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point(x,y), features.attributes = {
                	name: "Monument\n\n<?php echo addslashes($NomMonument); ?>"
            		}
				));
		<?php } while ($row_MarkerMonument = mysql_fetch_assoc($MarkerMonument)); ?>
            return features;
        }
		
		
		
		// Fonction creation marqueurs. 

			function createpointeur() {
            var extent = map.getExtent();
            var features = [];
			
		var urlicon ='<?php echo $row_MarkerPays['ch_pay_lien_imgdrapeau']; ?>'
                features.push(new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point(x,y), features.attributes = {
				flag: urlicon
            }));
            return features;
        }
		
				
				// end Init
				}
        
        		


        /**
         * Function: addMarker
         * Add a new marker to the markers layer given the following lonlat, 
         *     popupClass, and popup contents HTML. Also allow specifying 
         *     whether or not to give the popup a close box.
         * 
         * Parameters:
         * ll - {<OpenLayers.LonLat>} Where to place the marker
         * popupClass - {<OpenLayers.Class>} Which class of popup to bring up 
         *     when the marker is clicked.
         * popupContentHTML - {String} What to put in the popup
         * closeBox - {Boolean} Should popup have a close box?
         * overflow - {Boolean} Let the popup overflow scrollbars?
         */
		 
			// fonction affichage marker villes

			function addMarker(ll, urlicon) {
            var feature = new OpenLayers.Feature(markers, ll);			
			feature.data.icon = new OpenLayers.Icon(urlicon, new OpenLayers.Size(40,40), new OpenLayers.Pixel(-20,-40));
			        
            var marker = feature.createMarker();
			
			
			
			var mapDiv = document.getElementById("mapPosition");
            markers.addMarker(marker);
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