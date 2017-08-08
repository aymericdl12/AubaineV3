var jq = jQuery;
jq(document).ready( function() {

	jq(".email-form").submit(function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() { 
                jq(".emailInfos").hide();
                form.children(".submit").button('loading');
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) {
                jq(".emailInfos").show();
                form.children(".submit").button('reset');
                form.children(".emailInput").val('');
            }       
        });
    })

    // search form
    jq('.dropdown-el').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        jq(this).toggleClass('expanded');
        jq('#'+jq(e.target).attr('for')).prop('checked',true);
    });
    jq(document).click(function() {
        jq('.dropdown-el').removeClass('expanded');
    });

}); //document ready