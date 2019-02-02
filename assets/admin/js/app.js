'use strict';

const $ = require('jquery');
require('font-awesome/css/font-awesome.min.css');
require('jquery-datetimepicker');
require('jquery-datetimepicker/build/jquery.datetimepicker.min.css');
require('bootstrap');
require('bootstrap/dist/css/bootstrap.min.css');
require('../css/app.css');


function _loadWysiwyg(element, onChange) {
    element.each(function () {
        if (CKEDITOR.instances[this.id]) {
            CKEDITOR.instances[this.id].destroy();
        }
    });
    element.each(function () {
        var config = "/libs/ckeditor/ckeditor_config.js";
        var instance = CKEDITOR.replace(this.id, {
            customConfig: config
        });
        if (onChange) {
            instance.on('change', function() { instance.updateElement() });
        }
    });

    return true;
}

$('.delete-button').click(function(){
    return confirm('Confirmez cette suppression ?');
});

$('.btn-search-open').click(function(e){
    e.preventDefault();
    $('.search-zone').fadeToggle();
});

$(document).ready(function() {
    if($('.editor-wysiwyg').length) {
        _loadWysiwyg($('.editor-wysiwyg'), true);
    }
    if($('.date').length) {
        $.datetimepicker.setLocale($('html').attr('lang'));
        $('.date').datetimepicker({
            formatDate: 'd-m-Y',
            timepicker: false,
            format: 'd-m-Y'
        });
    }

});
