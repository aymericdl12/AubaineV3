var jq = jQuery;
jq(document).ready( function() {

	// preference on place
    jq(".preference-form").submit(function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() {
                form.children(".submit").css({"opacity":"0.7"}).prop('disabled',true);
                addorremove = form.children(".addorremove").val();
                if(addorremove=="add"){
                    form.children(".addorremove").val("remove");
                }
                else{
                    form.children(".addorremove").val("add");
                }
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) {
                form.children(".submit").css({"opacity":"1"}).prop('disabled',false);
                form.children(".submit").toggleClass('addPreference-button').toggleClass('removePreference-button');
                form.children(".score").text(data.score);
            }       
        });
    })
   

}); //document ready