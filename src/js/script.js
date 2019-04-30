/* <![CDATA[ */

jQuery(document).ready(function ($) {
    $.ajax({
        url: 'ajax/round_up_dates.php', 
        data: {action: 'days_to_round_up'}, 
        type: 'POST', 
        success: function ($result) {
            if ($result) {
                $('.days_to_round_up').html($result);
                $( document.body ).trigger( 'post-load' );
            }
        }
    });

    $.ajax({
        url: 'ajax/speakers.php', 
        data: {action: 'get_speakers'}, 
        type: 'POST', 
        success: function ($result) {
            if ($result) {
                $('.speakers').html($result);
                $( document.body ).trigger( 'post-load' );
            }
        }
    });

    $.ajax({
        url: 'ajax/round_up_dates.php', 
        data: {action: 'get_annual'}, 
        type: 'POST', 
        success: function ($result) {
            if ($result) {
                $('.annual').html($result);
                $( document.body ).trigger( 'post-load' );
            }
        }
    });

    $.ajax({
        url: 'ajax/round_up_dates.php', 
        data: {action: 'get_round_up_dates'}, 
        type: 'POST', 
        success: function ($result) {
            if ($result) {
                $('.round_up_dates').html($result);
                $( document.body ).trigger( 'post-load' );
            }
        }
    });

    $.ajax({
        url: '/wp-admin/meetings.php', 
        data: {action: 'get_meetings'}, 
        type: 'POST', 
        success: function ($result) {
            if ($result) {
                $('.meetings').html($result);
                $( document.body ).trigger( 'post-load' );
            }
        }
    });

});
/* ]]> */