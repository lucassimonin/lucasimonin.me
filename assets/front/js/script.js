(function($) {
  'use strict';
  $('a.page-scroll').bind('click', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1250, 'easeInOutExpo');
    event.preventDefault();
  });
  $('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
  });
  window.sr = ScrollReveal();
  sr.reveal('.sr-icons', {
    duration: 600,
    scale: 0.3,
    distance: '0px'
  }, 200);
  sr.reveal('.sr-button', {
    duration: 1000,
    delay: 200
  });
  sr.reveal('.sr-contact', {
    duration: 600,
    scale: 0.3,
    distance: '0px'
  }, 300);
})(jQuery); // End of use strict
$( document ).ready(function() {
  var initialPosition = $('#mainNav').offset().top - 60,
    positionTop = false;
  new WOW().init();
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
