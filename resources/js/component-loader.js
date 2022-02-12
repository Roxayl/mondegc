
/**** component loader ****/

(function($, document, window) {
    $(document).on('click', '.component-trigger', function(ev) {
        ev.preventDefault();

        let $elem = $(ev.currentTarget);
        let $targetElem = $('#' + $elem.attr('data-component-target'));

        $.ajax({
            url: $elem.attr('data-component-url'),
            method: 'GET',
            beforeSend: function() {
                $targetElem.css({'opacity': '50%'});
            },
        }).success(function(response) {
            $targetElem.html(response);
        }).complete(function() {
            $targetElem.css({'opacity': '100%'});
        });
    });
})(jQuery, document, window);
