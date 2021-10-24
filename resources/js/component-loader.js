
/**** component loader ****/

(function($, document, window) {
    $(document).on('click', '.component-trigger', function(ev) {
        ev.preventDefault();

        let $elem = $(ev.target);

        $.ajax({
            url: $elem.attr('data-component-url'),
            method: 'GET',
        }).success(function(response) {
            $('#' + $elem.attr('data-component-target')).html(response);
        });
    });
})(jQuery, document, window);
