var jq = jQuery;
jq(document).ready( function() {

    jq('.input-daterange').datepicker({
        autoclose: true,
        language: "fr",
        todayHighlight: true,
        startDate: "now"
    });

    jq(".myAubaines-form").submit(function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() { 
                form.children(".form-submit-wrapper").children(".my-aubaine-form-submit").button('loading');
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) {

                form.replaceWith(   '<div  class="aubaine-message">'+
                                        data.message+
                                        '<a class="btn btn-xs btn-danger"  href ="'+ data.delete_link+'") }}">'+
                                           '<span class="glyphicon glyphicon-trash"></span>'+
                                        '</a>'+
                                    '</div>' );
            }       
        });
    });

    jq(".myAubaines-form-multiple").submit(function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() { 
                form.children(".my-aubaine-form-submit").button('loading');
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) { 
                window.location.reload();
            }       
            // return false; 
        });
    });
    

});