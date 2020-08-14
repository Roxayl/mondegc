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
					numZoomLevels: 3,
					
		            };
					
                
				// construction de la carte

	            map = new OpenLayers.Map('map', options);
				
				// calque de base neutre
				var tmsoverlay = new OpenLayers.Layer.TMS( " Emplacements", "../Carto/Carte-Monde-GC-emplacements/",
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
        $x="54.5";
		$y="-45.5";
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
        $x="-57";
        $y="-44";
    break;

    case 58:
        $x="-73";
        $y="-43.5";
    break;
    
    default:
        echo "";
}
?>
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