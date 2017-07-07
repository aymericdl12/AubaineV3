var jq = jQuery;
jq(document).ready( function() {

    jq('.input-daterange').datepicker({
        autoclose: true,
        language: "fr",
        todayHighlight: true,
        startDate: "now"
    });

});