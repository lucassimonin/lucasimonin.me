'use strict';

const $ = require('jquery');
require('jquery.easing');
require('bootstrap');
require('bootstrap/js/dist/util');
require('bootstrap/dist/css/bootstrap.min.css');
require('font-awesome/css/font-awesome.min.css');
require('flag-icon-css/css/flag-icon.min.css');
const WOW = require('wowjs');
require('wowjs/css/libs/animate.css');
require('../css/main.css');

$('body').scrollspy({ target: '#mainNav' });

$('a.page-scroll').bind('click', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
        scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1250, 'easeInOutExpo');
    event.preventDefault();
    $('.navbar-toggler').addClass('collapsed');
    $('.navbar-ex1-collapse').removeClass('show')
});
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});

$( document ).ready(function() {
    var initialPosition = $('#mainNav').offset().top - 60,
        positionTop = false;
    new WOW.WOW({
        live: false
    }).init();
    $(window).scroll(function() {
        var height = $(window).scrollTop();
        if(!positionTop) {
            initialPosition = $('#mainNav').offset().top - 60;
        }
        if(height  > $('.scroll-down').offset().top && !positionTop) {
            positionTop = true;
            $('#mainNav').addClass('fixed');
        }
        if(height  <= initialPosition) {
            $('#mainNav').removeClass('fixed');
            positionTop = false;
        }
    });

});
