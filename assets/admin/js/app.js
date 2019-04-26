'use strict';

const $ = require('jquery');
require('font-awesome/css/font-awesome.min.css');
require('jquery-datetimepicker');
require('jquery-datetimepicker/build/jquery.datetimepicker.min.css');
require('bootstrap');
require('bootstrap/dist/css/bootstrap.min.css');
require('flag-icon-css/css/flag-icon.min.css');
require('../css/app.css');

$('.delete-button').click(function(){
    return confirm('Confirmez cette suppression ?');
});

$('.btn-search-open').click(function(e){
    e.preventDefault();
    $('.search-zone').fadeToggle();
});

$(document).ready(function() {
    if($('.date').length) {
        $.datetimepicker.setLocale($('html').attr('lang'));
        $('.date').datetimepicker({
            formatDate: 'd-m-Y',
            timepicker: false,
            format: 'd-m-Y'
        });
    }
});
