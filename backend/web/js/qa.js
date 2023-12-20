$(document).ready(function () {
    $('#qa-run-cron').on('click',function(){
        showLoader();
        $.ajax({
            url : '/qa/run-cron',
            success: function(msg){
                $('.cron-response').html(msg);
                hideLoader();
            }
        });
    });
});