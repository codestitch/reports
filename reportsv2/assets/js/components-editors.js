var ComponentsEditors = function () {
    
   
    var handleWysihtml5 = function () {
        if (!jQuery().wysihtml5) {
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5({
                "stylesheets": ["../../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            });
        }
    }

    var handleSummernote = function () {
        $('#summernote_1').summernote({height: 180});
        $('#termsField').summernote({height: 180});
        $('#descriptionField').summernote({height: 180});
        $('#postDescField').summernote({height: 180});
        $('#serviceDescField').summernote({height: 180});


        $('#aboutField').summernote({height: 180});

        // edit
        //$('#editserviceDescField').summernote({ height: 180, disableDragAndDrop: true });
        //API:
        //var sHTML = $('#summernote_1').code(); // get code
        //$('#summernote_1').destroy(); // destroy
    }

    return {
        //main function to initiate the module
        init: function () {
            handleWysihtml5();
            handleSummernote();
        }
    };

}();