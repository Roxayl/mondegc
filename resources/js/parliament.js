/*****
 * Ce script utilise les données permettant de générer l'hémicycle, les graphiques
 * concernant les votes à l'AG.
 * cf. /back/ocgc-proposal.php
 */

(function(window, document, $, d3, Chart, undefined) {

    /** Throttle **/

    var throttle = function(callback, limit) {
        var wait = false;                  // Initially, we're not waiting
        return function () {               // We return a throttled function
            if (!wait) {                   // If we're not waiting
                callback();                // Execute users function
                wait = true;               // Prevent future invocations
                setTimeout(function () {   // After a period of time
                    wait = false;          // And allow future invocations
                }, limit);
            }
        }
    }


    /** Countdown **/

    try {
        var countdownElementId = 'proposal-countdown';

        var CountDownTimer = function(dt, id) {
            var end = new Date(dt);

            var _second = 1000;
            var _minute = _second * 60;
            var _hour = _minute * 60;
            var _day = _hour * 24;
            var timer;

            function showRemaining() {
                var now = new Date();
                var distance = end - now;
                if (distance < 0) {
                    clearInterval(timer);
                    document.getElementById(id).innerHTML = 'Vote terminé';

                    return;
                }
                var days = Math.floor(distance / _day);
                var hours = Math.floor((distance % _day) / _hour) + (days * 24);
                var minutes = Math.floor((distance % _hour) / _minute);
                var seconds = Math.floor((distance % _minute) / _second);

                var output = '';

                output += ('0' + hours).slice(-2) + ':';
                output += ('0' + minutes).slice(-2) + ':';
                output += ('0' + seconds).slice(-2) + '';

                document.getElementById(id).innerHTML = '<h4 style="margin: 0;">Vote en cours</h4>' + output;
            }

            timer = setInterval(showRemaining, 1000);
        };

        if($('#' + countdownElementId).get(0).hasAttribute('runCountdown')) {
            CountDownTimer(parliamentData.proposal.dateEnd, countdownElementId);
        }
    } catch(err) { }


    /** Modal **/

    $("a[data-toggle=modal]").click(function (e) {
      var lv_target = $(this).attr('data-target');
      var lv_url = $(this).attr('href');
      $(lv_target).load(lv_url)});

    $('#closemodal').click(function() {
        $('#Modal-Monument').modal('hide');
    });


    /** Diagramme semi-circulaire via Chart.js **/

    var ctx = document.getElementById("parliament-chart");
    setTimeout(function() {
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: parliamentData.chart.labels,
                datasets: [{
                    label: '# of Votes',
                    data: parliamentData.chart.dataset,
                    backgroundColor: parliamentData.chart.bgColor,
                    borderWidth: 0
                }]
            },
            options: {
                rotation: 0.955 * Math.PI,
                circumference: 1.09 * Math.PI,
                legend: {
                    display: false
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }, 1000);


    /** Hémicycle **/

    var diagramData = {};

    var generateDiagram = function() {
        var width = Math.min(500, $('#parliament-data-container').width());
        var parliament = d3.parliament().width(width).height(270).innerRadiusCoef(0.39);
        parliament.enter.fromCenter(true).smallToBig(true);
        parliament.exit.toCenter(true).bigToSmall(true);
        parliament.on("click", function(e) { console.log(e); });

        diagramData = parliamentData.diagram.data;

        var setData = function(d) {
            d3.select("#parliament").datum(d).call(parliament);
        };

        setData(diagramData);
    };

    generateDiagram();
    $(window).on('resize', throttle(generateDiagram, 250));


    /** Tooltip **/

    var voteData = parliamentData.tooltip.data;

    var tooltip = d3.selectAll(".tooltip:not(.css)");
    var HTMLmouseTip = d3.select("div.tooltip.mouse");
    /* If this seems like a lot of different variables,
       remember that normally you'd only implement one
       type of tooltip! */

    var generateTooltipHtmlData = function(voteId) {

        var str = '';
        str += '<div class="tooltip-container" style="border-color: ' + voteData[voteId].reponseColor + '">';
        str += '<img src="' + voteData[voteId].paysDrapeau + '" class="img-menu-drapeau" /> '
        str += "<span>"
        str += voteData[voteId].paysNom
        str += "</span>"
        str += "<br>"
        str += '<strong style="color: ' + voteData[voteId].reponseColor + '">'
        str += voteData[voteId].reponseIntitule.toUpperCase()
        str += '</strong>'
        str += '</div>';

        return str;

    };

    /* I'm using d3 to add the event handlers to the circles
       and set positioning attributes on the tooltips, but
       you could use JQuery or plain Javascript. */
    d3.select("svg").select("g")
        .selectAll("circle")

        /***** Easy but ugly tooltip *****/
        .attr("title", "Automatic Title Tooltip")

        .on("mouseover", function () {

            tooltip.style("opacity", "1");

            /* You'd normally set the tooltip text
               here, based on data from the  element
               being moused-over; I'm just setting colour. */
            tooltip.style("color", this.getAttribute("fill") );
          /* Note: SVG text is set in CSS to link fill colour to
             the "color" attribute. */

            var tooltipString = generateTooltipHtmlData(d3.select(this).attr('data-vote-id'));
            tooltip.html(tooltipString);

            /***** Positioning a tooltip precisely
                   over an SVG element *****/

            /***** For an HTML tooltip *****/

            //for the HTML tooltip, we're not interested in a
            //transformation relative to an internal SVG coordinate
            //system, but relative to the page body

            //We can't get that matrix directly,
            //but we can get the conversion to the
            //screen coordinates.

            var matrix = this.getScreenCTM()
                    .translate(+this.getAttribute("cx"),
                             +this.getAttribute("cy"));

        })
        .on("mousemove", function () {

            /***** Positioning a tooltip using mouse coordinates *****/

            /* The code is shorter, but it runs every time
               the mouse moves, so it could slow down other
               processes or animation. */

            /***** For an HTML tooltip *****/

            //mouse coordinates relative to the page as a whole
            //can be accessed directly from the click event object
            //(which d3 stores as d3.event)
            HTMLmouseTip
                .style("left", Math.max(0, d3.event.pageX - 150) + "px")
                .style("top", (d3.event.pageY + 20) + "px");
        })
        .on("mouseout", function () {
            return tooltip.style("opacity", "0");
        });


    /** Editing **/

    var getSpecificSvgId = function(vote_id) {

        for(var i = 0; i < diagramData['d3DataSource'].length; i++) {
            if(diagramData['d3DataSource'][i]['id'] === vote_id) {
                return;
            }
        }

    };

    var manageColors = function($thisInput) {

        var selectedColor = '#83808A';

        $thisInput.closest('ul').find('li').each(function() {

            var el = $(this);

            if(el.find('input[name="voteCast[reponse_choisie]"]').prop('checked')) {
                el.css({
                    "border-color": el.attr('data-default-color'),
                    "background-color": el.attr('data-default-color'),
                    "color": "#ffffff"
                });
                selectedColor = el.attr('data-default-color');
            } else {
                el.css({
                    "border-color": el.attr('data-default-color'),
                    "background-color": "#fafafa",
                    "color": el.attr('data-default-color')
                });
            }

        });

        var row_id = $thisInput.closest('form').find('input[name="voteCast[id]"]').val();
        $('svg .seat.diagram-pays-' + row_id).css({'fill': selectedColor});

    };

    $(document).on('change', 'input[name="voteCast[reponse_choisie]"]', function(ev) {

        $('input[name="voteCast[reponse_choisie]"]').not(this).prop('checked', false);

        var $form = $(this).closest('form');

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize()
        }).success(function(data) {
            // TODO! Ajouter un message dans la bannière.
        });

        manageColors($(ev.target));

    });

    $('input[name="voteCast[reponse_choisie]"]').filter(':checked').each(function() {
        manageColors($(this));
    });

})(window, document, jQuery, d3, Chart);
