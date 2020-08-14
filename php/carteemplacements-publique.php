<?php

// Connexion BDD Pays pour afficher markers des pays


$query_MarkerPays = "SELECT ch_pay_id, ch_pay_continent, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_pay_header_presentation, ch_pay_mis_jour, ch_use_lien_imgpersonnage, Sum(villes.ch_vil_population) AS ch_pay_population, Count(villes.ch_vil_ID) AS ch_pay_nbvilles FROM pays LEFT JOIN villes ON ch_pay_id = ch_vil_paysID AND ch_vil_capitale != 3 LEFT JOIN users ON pays.ch_pay_id = users.ch_use_paysID WHERE ch_pay_publication = 1 GROUP BY ch_pay_id ORDER BY ch_pay_nom ASC";
$MarkerPays = mysql_query($query_MarkerPays, $maconnexion) or die(mysql_error());
$row_MarkerPays = mysql_fetch_assoc($MarkerPays);
$totalRows_MarkerPays = mysql_num_rows($MarkerPays);
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
			    var mapMaxZoom = 3;
				
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
					numZoomLevels: 3
                };
					
                
				// construction de la carte

	            map = new OpenLayers.Map('map', options);
				
				// calque de base neutre
				var tmsoverlay = new OpenLayers.Layer.TMS( " Emplacements", "Carto/Carte-Monde-GC-emplacements/",
	                {
	                    serviceVersion: '.', layername: '.', alpha: true,
						type: 'png', getURL: overlay_getTileURL,
						isBaseLayer: true
	                });
	            map.addLayer(tmsoverlay);
				if (OpenLayers.Util.alphaHack() == false) { tmsoverlay.setOpacity(1); }

                 // calque pays
				markers = new OpenLayers.Layer.Markers(" Drapeaux",
				{
				maxResolution: map.getResolutionForZoom(1),
                minResolution: map.getResolutionForZoom(3),
				}
				);
            map.addLayer(markers);
			
				 // ajout switcher calques
	            var switcherControl = new OpenLayers.Control.LayerSwitcher();
	            map.addControl(switcherControl);
				switcherControl.maximizeControl();
				// affichage copyright
				map.addControl(new OpenLayers.Control.Attribution());
				// affichage coordonnées
	            map.addControl(new OpenLayers.Control.MousePosition());
				// navigation avec le clavier
	            map.addControl(new OpenLayers.Control.KeyboardDefaults());
				
				
	       map.setCenter(new OpenLayers.LonLat(0, 0), 1);

            


            addMarkers();
			// end Init
        }
        
        function addMarkers() {

            var ll, popupClass, popupContentHTML;
            <?php do { ?>
			<?php 
$emplacement = $row_MarkerPays['ch_pay_emplacement'];
coordEmplacement($emplacement, $x, $y);
if (preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_MarkerPays['ch_pay_lien_imgdrapeau']))
					{
					$row_MarkerPays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 				'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_MarkerPays['ch_pay_lien_imgdrapeau']);
					} ?>
		var x = '<?php echo $x; ?>' ;
		var y = '<?php echo $y; ?>' ;  
		var urlicon ='<?= e($row_MarkerPays['ch_pay_lien_imgdrapeau']) ?>'
            //anchored popup thin long fixed contents autosize closebox overflow
            ll = new OpenLayers.LonLat(x,y);
            addMarker(ll, urlicon, popupClass);
		<?php } while ($row_MarkerPays = mysql_fetch_assoc($MarkerPays)); ?>

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
		 
		 // fonction affichage markers pays

        function addMarker(ll, urlicon) {

            var feature = new OpenLayers.Feature(markers, ll);			
			feature.data.icon = new OpenLayers.Icon(urlicon, new OpenLayers.Size(40,26), new OpenLayers.Pixel(-20,-13));				        
            var marker = feature.createMarker();
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
<?php
mysql_free_result($MarkerPays);
?>