$('.btn-search-open').click(function(e){
    e.preventDefault();
    $('.search-zone').fadeToggle();
});

$('.delete-button').click(function(){
    return confirm('Confirmez cette suppression ?');
});

function loadWysiwyg(element, onChange)
{
    element.each(function () {
        if (CKEDITOR.instances[this.id]) {
            CKEDITOR.instances[this.id].destroy();
        }
    });
    element.each(function (e) {
        var config = "/build/admin/ckeditor/ckeditor_config.js";
        var instance = CKEDITOR.replace(this.id, {
            customConfig: config
        });
        if (onChange) {
            instance.on('change', function() { instance.updateElement() });
        }
    });

    return true;
}


$(document).ready(function() {
    if($('.editor-wysiwyg').length) {
        loadWysiwyg($('.editor-wysiwyg'), true);
    }

    if($('.date').length) {
        jQuery.datetimepicker.setLocale($('html').attr('lang'));
        $('.date').datetimepicker({
            formatDate: 'd-m-Y',
            timepicker: false,
            format: 'd-m-Y'
        });
    }

});
