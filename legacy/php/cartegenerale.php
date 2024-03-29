<?php

// Connexion BDD Pays pour afficher markers des pays

$query_MarkerPays = "SELECT DISTINCT ch_pay_id, ch_pay_continent, ch_pay_emplacement, ch_pay_nom, ch_pay_lien_imgheader, ch_pay_lien_imgdrapeau, ch_pay_mis_jour, ch_pay_population_carte, ch_use_lien_imgpersonnage, ch_use_login, (SELECT SUM(ch_vil_population) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS ch_pay_population, (SELECT COUNT(ch_vil_ID) FROM villes WHERE ch_vil_paysID = ch_pay_id AND ch_vil_capitale != 3) AS ch_pay_nbvilles FROM pays LEFT JOIN users ON pays.ch_pay_id = users.ch_use_paysID WHERE ch_pay_publication = 1 GROUP BY ch_pay_id ORDER BY ch_pay_id ASC";
$MarkerPays = mysql_query($query_MarkerPays, $maconnexion);
$row_MarkerPays = mysql_fetch_assoc($MarkerPays);
$totalRows_MarkerPays = mysql_num_rows($MarkerPays);

// Connexion BDD Villes pour afficher markers des villes

$query_MarkerVilles = "SELECT ch_vil_ID, ch_vil_paysID, ch_vil_coord_X, ch_vil_coord_Y, ch_vil_mis_jour, ch_vil_armoiries, ch_vil_nom, ch_vil_capitale, ch_vil_specialite, ch_vil_population, ch_vil_lien_img1, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login FROM villes INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 ORDER BY ch_vil_paysID ASC";
$MarkerVilles = mysql_query($query_MarkerVilles, $maconnexion);
$row_MarkerVilles = mysql_fetch_assoc($MarkerVilles);
$totalRows_MarkerVilles = mysql_num_rows($MarkerVilles);

// Connexion BDD Monument pour afficher markers des monuments

$query_MarkerMonument = "SELECT ch_pat_id, ch_pat_paysID, ch_pat_villeID, ch_pat_coord_X, ch_pat_coord_Y, ch_pat_mis_jour, ch_pat_nom, ch_pat_lien_img1, (SELECT GROUP_CONCAT(ch_disp_cat_id) FROM dispatch_mon_cat WHERE ch_pat_id = ch_disp_mon_id) AS listcat, ch_vil_armoiries, ch_vil_ID, ch_vil_nom, ch_vil_capitale, pays.ch_pay_id, pays.ch_pay_publication, pays.ch_pay_nom, ch_use_lien_imgpersonnage, ch_use_login FROM patrimoine INNER JOIN villes ON  ch_pat_villeID=villes.ch_vil_ID INNER JOIN pays ON villes.ch_vil_paysID = pays.ch_pay_id LEFT JOIN users ON villes.ch_vil_user = users.ch_use_id WHERE ch_pat_statut=1 AND ch_vil_capitale <> 3 AND pays.ch_pay_publication = 1 ORDER BY ch_pat_id ASC";
$MarkerMonument = mysql_query($query_MarkerMonument, $maconnexion);
$row_MarkerMonument = mysql_fetch_assoc($MarkerMonument);
$totalRows_MarkerMonument = mysql_num_rows($MarkerMonument);

// Connexion BDD gometries pour afficher terres

$query_ZonesTerres = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_type, ch_geo_nom FROM geometries WHERE ch_geo_geometries = 'polygon' AND ch_geo_type= 'terre'";
$ZonesTerres = mysql_query($query_ZonesTerres, $maconnexion);
$totalRows_ZonesTerres = mysql_num_rows($ZonesTerres);

// Connexion BDD gometries pour afficher zones des pays

$query_ZonesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_type, ch_geo_nom FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'polygon' AND ch_geo_type= 'region' AND ch_geo_type != 'terre'";
$ZonesPays = mysql_query($query_ZonesPays, $maconnexion);
$row_ZonesPays = mysql_fetch_assoc($ZonesPays);
$totalRows_ZonesPays = mysql_num_rows($ZonesPays);

// Connexion BDD gometries pour afficher voies des pays

$query_VoiesPays = "SELECT ch_geo_id, ch_geo_wkt, ch_geo_pay_id, ch_geo_user, ch_geo_maj_user, ch_geo_date, ch_geo_mis_jour, ch_geo_geometries, ch_geo_mesure, ch_geo_type, ch_geo_nom, ch_use_login FROM geometries LEFT JOIN pays ON ch_geo_pay_id = ch_pay_id INNER JOIN users ON ch_geo_user = ch_use_id WHERE (ch_pay_publication = 1 OR ch_geo_pay_id = 1) AND ch_geo_geometries = 'line' AND ch_geo_type='frontiere' ";
$VoiesPays = mysql_query($query_VoiesPays, $maconnexion);
$row_VoiesPays = mysql_fetch_assoc($VoiesPays);
$totalRows_VoiesPays = mysql_num_rows($VoiesPays);
?>
<script type="text/javascript">
    var map;
    var mapBounds = new OpenLayers.Bounds(-180.0, -89.9811063294, 180.0, 90.0);
    var mapMinZoom = 0;
    var mapMaxZoom = 7;

    // avoid pink tiles
    OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
    OpenLayers.Util.onImageLoadErrorColor = "transparent";

    function init() {
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
            numZoomLevels: mapMaxZoom,
            projection: new OpenLayers.Projection("EPSG:4326"),
            maxResolution: 0.703125,
            maxExtent: new OpenLayers.Bounds(-180.0, -90.0, 180.0, 90.0)
        };

        // creation carte
        map = new OpenLayers.Map('map', options);

        // calque Climat
        var tmsoverlay4 = new OpenLayers.Layer.TMS(" Climats", "carto/Carte-Monde-GC-Climat/",
            {
                serviceVersion: '.', layername: '.', alpha: true,
                type: 'png', getURL: overlay_getTileURL,
                isBaseLayer: false,
                visibility: false,
                transitionEffect: "resize",
                attribution: "&copy; Flo49-2013"
            });
        map.addLayer(tmsoverlay4);
        if (OpenLayers.Util.alphaHack() == false) {
            tmsoverlay4.setOpacity(0.5);
        }

        // calque de base geographique
        var tmsoverlay1 = new OpenLayers.Layer.TMS(" Geographique", "carto/CarteMondeGC_2013/",
            {
                serviceVersion: '.', layername: '.', alpha: true,
                type: 'png', getURL: overlay_getTileURL,
                isBaseLayer: true,
                transitionEffect: "resize",
                attribution: "&copy; Boxxy-2014"
            });
        map.addLayer(tmsoverlay1);

        // calque satellite
        var tmsoverlay2 = new OpenLayers.Layer.TMS(" Satellite", "carto/Carte-Monde-GC-sat/",
            {
                serviceVersion: '.', layername: '.', alpha: false,
                type: 'png', getURL: overlay_getTileURL,
                isBaseLayer: true,
                transitionEffect: "resize",
                attribution: "&copy; Clamato & Franco de la Muerte-2012"
            });
        map.addLayer(tmsoverlay2);

        // calque NEUTRE
        var tmsoverlay3 = new OpenLayers.Layer.TMS(" Neutre", "carto/Carte-Monde-GC-neutre/",
            {
                serviceVersion: '.', layername: '.', alpha: false,
                type: 'png', getURL: overlay_getTileURL,
                isBaseLayer: true,
                transitionEffect: "resize",
                attribution: "&copy; Boxxy-2013"
            });
        map.addLayer(tmsoverlay3);

        // calque GC 2018 (non fonctionnel)
        var tmsoverlay4 = new OpenLayers.Layer.TMS(" Geographique (2018 - beta)", "carto/CarteGC_2018/",
            {
                serviceVersion: '.', layername: '.', alpha: false,
                type: 'png', getURL: overlay_getTileURL,
                isBaseLayer: true,
                transitionEffect: "resize",
                attribution: "&copy; Boxxy-2013, Sakuro-2018"
            });
        map.addLayer(tmsoverlay4);

        // allow testing of specific renderers via "?renderer=Canvas", etc
        var renderer = OpenLayers.Util.getParameters(window.location.href).renderer;
        renderer = (renderer) ? [renderer] : OpenLayers.Layer.Vector.prototype.renderers;


        // calque vector TERRES
        var vectorsTerres = new OpenLayers.Layer.Vector(" Terres", {
            styleMap: new OpenLayers.StyleMap({
                "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                    fillColor: "${couleur}",
                    strokeWidth: "${epaisseurTrait}",
                    fillOpacity: "${opaciteCouleur}",
                    strokeColor: "${couleurTrait}",
                    strokeOpacity: "${opaciteTrait}",
                    strokeDashstyle: "${Trait}",
                    strokeLinecap: "square",
                    pointRadius: "5",
                    label: "",
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


        // Ajout geometries zones TERRES
        var format = new OpenLayers.Format.WKT({
            'internalProjection': map.baseLayer.projection,
            'externalProjection': new OpenLayers.Projection("EPSG:4326")
        });
        <?php while ($row_ZonesTerres = mysql_fetch_assoc($ZonesTerres)) {
        $Nomzone = $row_ZonesTerres['ch_geo_nom'];
        $typeZone = $row_ZonesTerres['ch_geo_type'];
        styleZones($typeZone, $fillcolor, $fillOpacity, $strokeWidth, $strokeColor, $strokeOpacity, $Trait);
        ?>
        var polygonFeature = format.read("<?= e($row_ZonesTerres['ch_geo_wkt']) ?>");
        polygonFeature.attributes = {
            couleur: "<?php echo $fillcolor; ?>",
            epaisseurTrait: "<?php echo $strokeWidth; ?>",
            opaciteCouleur: "<?php echo $fillOpacity; ?>",
            couleurTrait: "<?php echo $strokeColor; ?>",
            opaciteTrait: "<?php echo $strokeOpacity; ?>",
            Trait: "<?php echo $Trait; ?>",
            name: "<?php echo e($Nomzone); ?>"
        }
        vectorsTerres.addFeatures([polygonFeature]);
        <?php }; ?>


        // calque vector modifier zones administratives
        var vectorsAdministrations = new OpenLayers.Layer.Vector(" R&eacute;gions", {
            styleMap: new OpenLayers.StyleMap({
                "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                    fillColor: "${couleur}",
                    strokeWidth: "${epaisseurTrait}",
                    fillOpacity: "${opaciteCouleur}",
                    strokeColor: "${couleurTrait}",
                    strokeOpacity: "${opaciteTrait}",
                    strokeDashstyle: "${Trait}",
                    strokeLinecap: "square",
                    pointRadius: "5",
                    label: "${name}",
                    fontColor: "black",
                    fontSize: "11px",
                    fontOpacity: 0.5,
                    fontFamily: "Roboto",
                    fontWeight: "200",
                    labelOutlineWidth: 0,
                    cursor: "pointer"
                }, OpenLayers.Feature.Vector.style["default"])),
            }),
            maxResolution: map.getResolutionForZoom(3),
            renderers: renderer
        });
        map.addLayer(vectorsAdministrations);


        // Ajout geometries zones administratives
        var format = new OpenLayers.Format.WKT({
            'internalProjection': map.baseLayer.projection,
            'externalProjection': new OpenLayers.Projection("EPSG:4326")
        });
        <?php do {
        $Nomzone = $row_ZonesPays['ch_geo_nom'];
        $typeZone = $row_ZonesPays['ch_geo_type'];
        styleZones($typeZone, $fillcolor, $fillOpacity, $strokeWidth, $strokeColor, $strokeOpacity, $Trait);
        ?>
        var polygonFeature = format.read("<?= e($row_ZonesPays['ch_geo_wkt']) ?>");
        polygonFeature.attributes = {
            couleur: "<?php echo $fillcolor; ?>",
            epaisseurTrait: "<?php echo $strokeWidth; ?>",
            opaciteCouleur: "<?php echo $fillOpacity; ?>",
            couleurTrait: "<?php echo $strokeColor; ?>",
            opaciteTrait: "<?php echo $strokeOpacity; ?>",
            Trait: "<?php echo $Trait; ?>",
            name: "<?php echo e($Nomzone); ?>"
        }
        vectorsAdministrations.addFeatures([polygonFeature]);
        <?php } while ($row_ZonesPays = mysql_fetch_assoc($ZonesPays)); ?>

        // calque vector voies
        var vectorsVoies = new OpenLayers.Layer.Vector(" Routes", {
            styleMap: new OpenLayers.StyleMap({
                "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                    cursor: "pointer",
                    fillColor: "#000000",
                    strokeLinecap: "square",
                    strokeColor: "${couleurTrait}",
                    strokeWidth: "${epaisseurTrait}",
                    strokeDashstyle: "${Trait}",
                    pointRadius: "5"
                }, OpenLayers.Feature.Vector.style["default"])),
                "select": new OpenLayers.Style({
                    strokeColor: "#e2001a",
                    strokeWidth: 3,
                    strokeDashstyle: "solid",
                    pointRadius: "5"
                })
            }),
            maxResolution: map.getResolutionForZoom(2),
            renderers: renderer,
            eventListeners: {
                "featureselected": function (event) {
                    map.setCenter(event.feature.geometry.getBounds().getCenterLonLat());
                }
            }
        });
        map.addLayer(vectorsVoies);

        // Ajout des routes sur calque voies
        var format = new OpenLayers.Format.WKT({
            'internalProjection': map.baseLayer.projection,
            'externalProjection': new OpenLayers.Projection("EPSG:4326")
        });


        <?php do {
        $Nomvoie = $row_VoiesPays['ch_geo_nom'];
        $typeVoie = $row_VoiesPays['ch_geo_type'];
        $surface = $row_VoiesPays['ch_geo_mesure'];
        styleVoies($typeVoie, $couleurTrait, $epaisseurTrait, $Trait);
        ressourcesGeometrie($surface, $typeVoie, $budget, $industrie, $commerce, $agriculture, $tourisme, $recherche, $environnement, $education, $label, $population);
        ?>

        var polygonFeature = format.read("<?= e($row_VoiesPays['ch_geo_wkt']) ?>");
        polygonFeature.attributes = {
            couleurTrait: "<?php echo $couleurTrait; ?>",
            epaisseurTrait: "<?php echo $epaisseurTrait; ?>",
            Trait: "<?php echo $Trait; ?>",
            popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><img src='assets/img/imagesdefaut/zone-voie.jpg'></div><div><h3><?php echo e($Nomvoie); ?></h3><p><em>cr&eacute;&eacute; par <?= e($row_VoiesPays['ch_use_login']) ?> <?php if ($row_VoiesPays['ch_geo_pay_id'] == 1) {?>(avec l'Institut G&eacute;c&eacute;en de G&eacute;ographie)<?php } ?></em></p><p>&nbsp;</p><p><strong>Type&nbsp;:</strong> <?php echo e($label); ?></h4><p><strong>Longueur&nbsp;:</strong> <?= e($row_VoiesPays['ch_geo_mesure']) ?>Km</p><?php if ($row_VoiesPays['ch_geo_pay_id'] != 1) {?><ul><div class='row-fluid'><li class='span3'><a title='Budget'><img src='assets/img/ressources/budget.png' alt='icone Budget'></a><p><?= formatNum($budget) ?></p></li><li class='span3'><a title='Industrie'><img src='assets/img/ressources/industrie.png' alt='icone Industrie'></a><p><?= formatNum($industrie) ?></p></li><li class='span3'><a title='Commerce'><img src='assets/img/ressources/bureau.png' alt='icone Commerce'></a><p><?= formatNum($commerce) ?></p></li><li class='span3'><a title='Agriculture'><img src='assets/img/ressources/agriculture.png' alt='icone Agriculture'></a><p><?= formatNum($agriculture) ?></p></li></div><div class='row-fluid'><li class='span3'><a title='Tourisme'><img src='assets/img/ressources/tourisme.png' alt='icone Tourisme'></a><p><?= formatNum($tourisme) ?></p></li><li class='span3'><a title='Recherche'><img src='assets/img/ressources/recherche.png' alt='icone Recherche'></a><p><?= formatNum($recherche) ?></p></li><li class='span3'><a title='Environnement'><img src='assets/img/ressources/environnement.png' alt=icone Environnement'></a><p><?= formatNum($environnement) ?></p></li><li class='span3'><a title='Education'><img src='assets/img/ressources/education.png' alt='icone Education'></a><p><?= formatNum($education) ?></p></li></div></ul><div class='clearfix'></div><?php } ?></div>"
        }
        vectorsVoies.addFeatures([polygonFeature]);
        <?php } while ($row_VoiesPays = mysql_fetch_assoc($VoiesPays)); ?>

        // calque vector continents
        var vectorLayer = new OpenLayers.Layer.Vector(" Continents", {
            styleMap: new OpenLayers.StyleMap({
                'default': {
                    strokeOpacity: 0,
                    pointerEvents: "visiblePainted",
                    label: "${name}",
                    fontColor: "black",
                    fontSize: "18px",
                    fontOpacity: 0.5,
                    fontFamily: "Arial",
                    fontWeight: "bold",
                    labelOutlineWidth: 0
                }
            }),
            minResolution: map.getResolutionForZoom(4),
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
            maxResolution: map.getResolutionForZoom(2),
            renderers: renderer,
            styleMap: new OpenLayers.StyleMap({
                "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                    cursor: "pointer",
                    graphicName: "square",
                    fillColor: "white",
                    strokeColor: "black",
                    externalGraphic: "${flag}",
                    label: "${name}",
                    fontColor: "black",
                    fontFamily: "Roboto",
                    fontWeight: "bold",
                    fontSize: "11px",
                    labelAlign: "cm",
                    labelXOffset: 30,
                    labelYOffset: -15,
                    graphicOpacity: 0.6,
                    graphicWidth: 30,
                    pointRadius: 10
                }, OpenLayers.Feature.Vector.style["default"])),
                "select": new OpenLayers.Style({
                    graphicOpacity: 1,
                    labelOutlineWidth: 2
                })
            })
        });

        // calque vector villes
        var vectors2 = new OpenLayers.Layer.Vector(" Villes", {
            maxResolution: map.getResolutionForZoom(3),
            renderers: renderer,
            styleMap: new OpenLayers.StyleMap({
                "default": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                    cursor: "pointer",
                    fillColor: "${couleur}",
                    strokeColor: "${couleurTrait}",
                    label: "${label}",
                    graphicName: "circle",
                    labelAlign: "cm",
                    labelXOffset: 30,
                    labelYOffset: -15,
                    fillOpacity: 1,
                    pointRadius: "${size}",
                    fontSize: "${fontSize}",
                    fontFamily: "Roboto"
                }, OpenLayers.Feature.Vector.style["default"])),
                "select": new OpenLayers.Style(OpenLayers.Util.applyDefaults({
                    label: "${name}",
                    fontStyle: "italic",
                    strokeColor: "white",
                    fillOpacity: 1,
                    fontSize: "11px",
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
        var selectControl = new OpenLayers.Control.SelectFeature(
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

        // Fonction afficher dans div info.
        function showStatus(text) {
            document.getElementById("info").innerHTML = text;
        }

        // Fonction creation drapeaux-pays.

        function createFeatures1() {
            var extent = map.getExtent();
            var features = [];

            <?php do {
            $Nompays = $row_MarkerPays['ch_pay_nom'];
            $emplacement = $row_MarkerPays['ch_pay_emplacement'];
            coordEmplacement($emplacement, $x, $y);
            if(preg_match("#^http://www.generation-city.com/monde/userfiles/#", $row_MarkerPays['ch_pay_lien_imgdrapeau'])) {
                $row_MarkerPays['ch_pay_lien_imgdrapeau'] = preg_replace('#^http://www.generation-city\.com/monde/userfiles/(.+)#', 'http://www.generation-city.com/monde/userfiles/Thumb/$1', $row_MarkerPays['ch_pay_lien_imgdrapeau']);
            }
            ?>
            var x = '<?php echo e($x); ?>';
            var y = '<?php echo e($y); ?>';
            var urlicon = '<?= e($row_MarkerPays['ch_pay_lien_imgdrapeau']) ?>'
            features.push(new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point(x, y), features.attributes = {
                    name: "<?php echo e($Nompays); ?>",
                    flag: "<?= e($row_MarkerPays['ch_pay_lien_imgdrapeau']) ?>",
                    popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-pays.php?ch_pay_id=<?= e($row_MarkerPays['ch_pay_id']) ?>'><?php if ($row_MarkerPays['ch_pay_lien_imgheader']) {?><img src='<?php echo e($row_MarkerPays['ch_pay_lien_imgheader']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/drapeau.jpg'><?php }?></a></div><div><h3><?php echo e($Nompays); ?></h3><p><em>cr&eacute;&eacute; par <?php echo e($row_MarkerPays['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerPays['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo e($row_MarkerPays['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerPays['ch_pay_lien_imgdrapeau']) {?><img class='drapeau' src='<?php echo e($row_MarkerPays['ch_pay_lien_imgdrapeau']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/drapeau.jpg'><?php }?></div><p>Mis &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime($row_MarkerPays['ch_pay_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerPays['ch_pay_mis_jour'])); ?></strong></p><p>Nombre de villes&nbsp;: <strong><?= e($row_MarkerPays['ch_pay_nbvilles']) ?> villes</strong></p><p>Population&nbsp;: <strong><?= formatNum($row_MarkerPays['ch_pay_population'] + $row_MarkerPays['ch_pay_population_carte']) ?> habitants</strong></p><div class='pull-center'></div></div><div class='pied'><a class='btn btn-primary' href='page-pays.php?ch_pay_id=<?= e($row_MarkerPays['ch_pay_id']) ?>'>Visiter ce pays</a></div>"
                }));
            <?php } while ($row_MarkerPays = mysql_fetch_assoc($MarkerPays)); ?>
            return features;
        }


        // Fonction creation villes.

        function createFeatures2() {
            var extent = map.getExtent();
            var features = [];

            <?php do {
            $Nomville = $row_MarkerVilles['ch_vil_nom'];
            $Specialiteville = $row_MarkerVilles['ch_vil_specialite'];
            $Paysville = $row_MarkerVilles['ch_pay_nom'];
            ?>
            var x = '<?= e($row_MarkerVilles['ch_vil_coord_X']) ?>';
            var y = '<?= e($row_MarkerVilles['ch_vil_coord_Y']) ?>';
            <?php if ($row_MarkerVilles['ch_vil_capitale'] == 1) {?>
            var pointercolor = "red";
            <?php } else { ?>
            var pointercolor = "black";
            <?php } ?>
            <?php $population = $row_MarkerVilles['ch_vil_population'];
            tailleVilles($population, $sizeicon); ?>
            features.push(new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point(x, y), features.attributes = {
                    name: "<?php echo e($Nomville); ?>",
                    size: <?php echo $sizeicon; ?>,
                    couleur: "white",
                    label: "<?php echo($population > 1000000 || $row_MarkerVilles['ch_vil_capitale'] == 1 ? $Nomville : ""); ?>",
                    fontSize: "<?php echo($population > 3000000 || $row_MarkerVilles['ch_vil_capitale'] == 1 ? "11px" : "10px"); ?>",
                    couleurTrait: pointercolor,
                    popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-ville.php?ch_pay_id=<?= e($row_MarkerVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_MarkerVilles['ch_vil_ID']) ?>'><?php if ($row_MarkerVilles['ch_vil_lien_img1']) {?><img src='<?php echo e($row_MarkerVilles['ch_vil_lien_img1']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/ville.jpg'><?php }?></a></div><div><h3><?php echo e($Nomville); ?></h3><p><em>cr&eacute;&eacute;e par <?php echo e($row_MarkerVilles['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerVilles['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo e($row_MarkerVilles['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerVilles['ch_vil_armoiries']) {?><img class='armoirie' src='<?php echo e($row_MarkerVilles['ch_vil_armoiries']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/blason.jpg'><?php }?></div><p><?php if($row_MarkerVilles['ch_vil_capitale'] == 1) {
                        echo 'Capitale';
                    } else {
                        echo 'Ville';
                    } ?> du pays <strong><a href='page-pays.php?ch_pay_id=<?= e($row_MarkerVilles['ch_vil_paysID']) ?>'><?php echo e($Paysville); ?></a></strong></p><p>Mise &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime($row_MarkerVilles['ch_vil_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerVilles['ch_vil_mis_jour'])); ?></strong></p><p>Population&nbsp;: <strong><?= formatNum($row_MarkerVilles['ch_vil_population']) ?> habitants</strong></p><p>Sp&eacute;cialit&eacute;&nbsp;: <strong><?php if($row_MarkerVilles['ch_vil_specialite']) {
                        echo e($Specialiteville);
                    } else {
                        echo 'NA';
                    } ?></strong></p><div class='pull-center'></div></div><div class='pied'><a class='btn btn-primary' href='page-ville.php?ch_pay_id=<?= e($row_MarkerVilles['ch_vil_paysID']) ?>&ch_ville_id=<?= e($row_MarkerVilles['ch_vil_ID']) ?>'>Visiter cette ville</a></div>"
                }));
            <?php } while ($row_MarkerVilles = mysql_fetch_assoc($MarkerVilles)); ?>
            return features;
        }

        // Fonction creation points Monuments.

        function createFeatures3() {
            var extent = map.getExtent();
            var features = [];

            <?php do {
            $NomMonument = $row_MarkerMonument['ch_pat_nom'];
            $Nomville = $row_MarkerMonument['ch_vil_nom'];
            $listcategories = $row_MarkerMonument['listcat'];
            if($row_MarkerMonument['listcat']) {

                $query_liste_mon_cat3 = "SELECT * FROM monument_categories WHERE ch_mon_cat_ID In ($listcategories) AND ch_mon_cat_statut =1";
                $liste_mon_cat3 = mysql_query($query_liste_mon_cat3, $maconnexion);
                $row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3);
                $totalRows_liste_mon_cat3 = mysql_num_rows($liste_mon_cat3);
            }
            ?>
            var x = '<?= e($row_MarkerMonument['ch_pat_coord_X']) ?>';
            var y = '<?= e($row_MarkerMonument['ch_pat_coord_Y']) ?>';

            features.push(new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point(x, y), features.attributes = {
                    name: "Monument\n\n<?php echo e($NomMonument); ?>",
                    popupContentHTML: "<div class='fiche'><div class='pull-center illustration'><a href='page-monument.php?ch_pat_id=<?= e($row_MarkerMonument['ch_pat_id']) ?>'><img src='assets/img/imagesdefaut/ville.jpg'></a></div><div><h3><?php echo e($NomMonument); ?></h3><p><em>cr&eacute;&eacute;e par <?php echo e($row_MarkerMonument['ch_use_login']); ?></em></p></div><div class='infocarte-icon'><?php if ($row_MarkerMonument['ch_use_lien_imgpersonnage']) {?><img class='avatar' src='<?php echo e($row_MarkerMonument['ch_use_lien_imgpersonnage']); ?>'></img><?php } else { ?><img src='assets/img/imagesdefaut/personnage.jpg'><?php }?><?php if ($row_MarkerMonument['ch_vil_armoiries']) {?><img class='armoirie' src='<?php echo e($row_MarkerMonument['ch_vil_armoiries']); ?>'><?php } else { ?><img src='assets/img/imagesdefaut/blason.jpg'><?php }?></div><p>Monument appartenant &agrave; la ville <strong><a href='page-ville.php?ch_pay_id=<?= e($row_MarkerMonument['ch_pay_id']) ?>&ch_ville_id=<?= e($row_MarkerMonument['ch_vil_ID']) ?>'><?php echo e($Nomville); ?></a></strong></p><p>Mise &agrave; jour le&nbsp;: <strong><?php  echo date('d/m/Y', strtotime($row_MarkerMonument['ch_pat_mis_jour'])); ?> &agrave; <?php  echo date('G:i', strtotime($row_MarkerMonument['ch_pat_mis_jour'])); ?></strong></p><div class='pull-center'></div><?php if ($row_MarkerMonument['listcat']) {?><div class='row-fluid icone-categorie'><?php do { ?><div><a title='<?= e($row_liste_mon_cat3['ch_mon_cat_nom']) ?>'><img src='<?= e($row_liste_mon_cat3['ch_mon_cat_icon']) ?>' alt='icone <?= e($row_liste_mon_cat3['ch_mon_cat_nom']) ?>' style='background-color:<?= e($row_liste_mon_cat3['ch_mon_cat_couleur']) ?>; margin-left:10px;'></a></div><?php } while ($row_liste_mon_cat3 = mysql_fetch_assoc($liste_mon_cat3)); } ?></div><div class='pied'><a class='btn btn-primary' href='page-monument.php?ch_pat_id=<?= e($row_MarkerMonument['ch_pat_id']) ?>'>Visiter ce monument</a></div>"
                }));
            <?php if($row_MarkerMonument['listcat']) {
            mysql_free_result($liste_mon_cat3);
        }?>
            <?php } while ($row_MarkerMonument = mysql_fetch_assoc($MarkerMonument)); ?>
            return features;
        }

        // Evennement a la selection.

        vectors1.events.on({
            "featureselected": function (e) {
                showStatus(e.feature.attributes.popupContentHTML);
                map.setCenter(e.feature.geometry.getBounds().getCenterLonLat(), 3);
            },
            "featureunselected": function (e) {
            }
        });

        vectors2.events.on({
            "featureselected": function (e) {
                showStatus(e.feature.attributes.popupContentHTML);
                map.setCenter(e.feature.geometry.getBounds().getCenterLonLat());
            },
            "featureunselected": function (e) {
            }
        });

        vectors3.events.on({
            "featureselected": function (e) {
                showStatus(e.feature.attributes.popupContentHTML);
                map.setCenter(e.feature.geometry.getBounds().getCenterLonLat());
            },
            "featureunselected": function (e) {
            }
        });

        // ajout regles de selection
        selectControl = new OpenLayers.Control.SelectFeature(
            [vectors1, vectors2, vectors3]
        );
        selectControl.handlers.feature.stopDown = false;
        map.addControl(selectControl);
        selectControl.activate();


        // Affichage legende au changement de calque.

        var legende = "<div class='fiche'><div class='pull-center' style='padding-top:10px;'><h3>L&eacute;gende</h3></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'>&nbsp;</div><img src='carto/images/fontiere.png'>Fronti&egrave;res</p></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'>&nbsp;</div><img src='carto/images/capitale.png'>Capitale</p></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'>&nbsp;</div><img src='carto/images/ville.png'>Ville</p></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'>&nbsp;</div><img src='carto/images/monument.png'>Monument</p></div><div><h4 style='padding-bottom:10px;'>Carte des climats</h4><div style='margin-top:5px;'><div class='pull-left' style='background-color:#808080; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p>Subtropical</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#ff0000; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>&Eacute;quatorial &agrave; humidit&eacute; constante</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#ff6a00; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>Tropical &agrave; saison pluviom&eacute;trique altern&eacute;e</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#ffd800; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>M&eacute;diterran&eacute;en</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#4cff00; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>Steppes et d&eacute;serts &agrave; latitude moyenne</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#267f00; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>Temp&eacute;r&eacute;</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#00ffff; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>Continental &agrave; hiver froid</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#0094ff; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>Montagnard</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#0000ff; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>Froid sans &eacute;t&eacute;</p></div><div style='margin-top:10px;'><div class='pull-left' style='background-color:#ff00dc; width:50px; height:20px; margin:5px; margin-top:-5px;'>&nbsp;</div><p style='margin-top:10px;'>D&eacute;serts et semi-d&eacute;serts de la zone chaude</p></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'><img src='carto/images/courant-froid.png' width='50px' style='margin-left:5px; margin-right:5px;'></div><p style='margin-top:10px;'>Courant froid</p></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'><img src='carto/images/courant-chaud.png' width='50px' style='margin-left:5px; margin-right:5px;'></div><p style='margin-top:10px;'>Courant chaud</p></div><div style='margin-top:10px;'><div class='pull-left' margin:5px; margin-top:-5px;'><img src='carto/images/courant-neutre.png' width='50px' style='margin-left:5px; margin-right:5px;'></div><p style='margin-top:10px;'>Contre courant &eacute;quatorial</p></div></div>";

        var panel = new OpenLayers.Control.Panel();
        panel.addControls([
            new OpenLayers.Control.Button({
                displayClass: "helpButton", trigger: function () {
                    document.getElementById("info").innerHTML = legende;
                }
            })
        ]);
        map.addControl(panel);

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
        if (mapBounds.intersectsBounds(bounds) && z >= mapMinZoom && z <= mapMaxZoom) {
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
