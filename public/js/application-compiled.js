// NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
// IT'S ALL JUST JUNK FOR OUR DOCS!
// ++++++++++++++++++++++++++++++++++++++++++

!function ($) {

  $(function(){

    var $window = $(window)

    // Disable certain links in docs
    $('section [href^=#]').click(function (e) {
      e.preventDefault()
    })

    // side bar
    $('.bs-docs-sidenav').affix({
      offset: {
        top: function () { return $window.width() <= 980 ? 290 : 210 }
      , bottom: 270
      }
    })

    // make code pretty
    window.prettyPrint && prettyPrint()

    // add-ons
    $('.add-on :checkbox').on('click', function () {
      var $this = $(this)
        , method = $this.attr('checked') ? 'addClass' : 'removeClass'
      $(this).parents('.add-on')[method]('active')
    })

    // add tipsies to grid for scaffolding
    if ($('#gridSystem').length) {
      $('#gridSystem').tooltip({
          selector: '.show-grid > div'
        , title: function () { return $(this).width() + 'px' }
      })
    }

    // tooltip demo
    $('.tooltip-demo').tooltip({
      selector: "a[rel=tooltip]"
    })

    $('.tooltip-test').tooltip()
    $('.popover-test').popover()

    // popover demo
    $("a[rel=popover]")
      .popover()
      .click(function(e) {
        e.preventDefault()
      })

    // button state demo
    $('#fat-btn')
      .click(function () {
        var btn = $(this)
        btn.button('loading')
        setTimeout(function () {
          btn.button('reset')
        }, 3000)
      })

    // carousel demo
    $('#myCarousel').carousel()

    // javascript build logic
    var inputsComponent = $("#components.download input")
      , inputsPlugin = $("#plugins.download input")
      , inputsVariables = $("#variables.download input")

    // toggle all plugin checkboxes
    $('#components.download .toggle-all').on('click', function (e) {
      e.preventDefault()
      inputsComponent.attr('checked', !inputsComponent.is(':checked'))
    })

    $('#plugins.download .toggle-all').on('click', function (e) {
      e.preventDefault()
      inputsPlugin.attr('checked', !inputsPlugin.is(':checked'))
    })

    $('#variables.download .toggle-all').on('click', function (e) {
      e.preventDefault()
      inputsVariables.val('')
    })

    // request built javascript
    $('.download-btn').on('click', function () {

      var css = $("#components.download input:checked")
            .map(function () { return this.value })
            .toArray()
        , js = $("#plugins.download input:checked")
            .map(function () { return this.value })
            .toArray()
        , vars = {}
        , img = ['glyphicons-halflings.png', 'glyphicons-halflings-white.png']

    $("#variables.download input")
      .each(function () {
        $(this).val() && (vars[ $(this).prev().text() ] = $(this).val())
      })

      $.ajax({
        type: 'POST'
      , url: /\?dev/.test(window.location) ? 'http://localhost:3000' : 'http://bootstrap.herokuapp.com'
      , dataType: 'jsonpi'
      , params: {
          js: js
        , css: css
        , vars: vars
        , img: img
      }
      })
    })
  })

// Modified from the original jsonpi https://github.com/benvinegar/jquery-jsonpi
$.ajaxTransport('jsonpi', function(opts, originalOptions, jqXHR) {
  var url = opts.url;

  return {
    send: function(_, completeCallback) {
      var name = 'jQuery_iframe_' + jQuery.now()
        , iframe, form

      iframe = $('<iframe>')
        .attr('name', name)
        .appendTo('head')

      form = $('<form>')
        .attr('method', opts.type) // GET or POST
        .attr('action', url)
        .attr('target', name)

      $.each(opts.params, function(k, v) {

        $('<input>')
          .attr('type', 'hidden')
          .attr('name', k)
          .attr('value', typeof v == 'string' ? v : JSON.stringify(v))
          .appendTo(form)
      })

      form.appendTo('body').submit()
    }
  }
});

$(window).scroll(function() {
  if ($(document).scrollTop() > 50) {
    $('div.navbar').addClass('shrink');
  } else {
    $('div.navbar').removeClass('shrink');
  }
});

}(window.jQuery);


$(document).ready(function() {

    var environ = window.location.host;
    var baseurl;
    if (environ === "localhost") { // localhost
        baseurl = window.location.protocol + "//" + window.location.host + "/" + "mondegc/";
    } else if(environ === "mondegc.test") { // vagrant
        baseurl = window.location.protocol + "//" + window.location.host + "/";
    } else if(environ === "generation-city.test") { // vagrant
        baseurl = window.location.protocol + "//" + window.location.host + "/mondegc/";
    } else { // production
        baseurl = window.location.protocol + "//" + window.location.host + "/monde/";
    }

    var $notification_container = $('.dropdown-notification');
    var notification_url_request = baseurl + 'user/notifications';

    $notification_container.find('ul.dropdown-menu').html(
        "<div class='well'><p><img src='https://squirrel.romukulot.fr/media/icons/ajax-loader2.gif'> Chargement...</p></div>");
    $notification_container.find('a[data-toggle="dropdown"]').on('click', function(ev) {
        $.get(notification_url_request, function(returnedData) {
            $notification_container.find('ul.dropdown-menu').html(returnedData);
        }).fail(function() {
            $notification_container.find('ul.dropdown-menu').html("Une erreur s'est produite.");
        });
    });

    $notification_container.on('submit', 'form.notification-markasread', function(ev) {
        ev.preventDefault();
        $(ev.target).find('button').attr('value', 'Chargement...');
        $.post($(this).attr('action'), $(this).serialize(), function(returnedData) {
            $notification_container.find('li').removeClass('notification-unread');
            $notification_container.find('.notification-count').remove();
            $notification_container.find('.notification-toggle-btn').addClass('btn-transparent').removeClass('btn-primary');
            $notification_container.find('form.notification-markasread').remove();
        }).fail(function() {
            $notification_container.find('ul.dropdown-menu').html("Une erreur s'est produite.");
        });
        return false;
    });

});