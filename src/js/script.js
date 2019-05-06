/* <![CDATA[ */

jQuery(document).ready(function ($) {
    if(null !== document.querySelector('.days_to_round_up')) {
        $.ajax({
            url: 'includes/js/ajax/round_up_dates.php', 
            data: {action: 'days_to_round_up'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.days_to_round_up').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }

    if(null !== document.querySelector('.speakers')) {
        $.ajax({
            url: 'includes/js/ajax/speakers.php', 
            data: {action: 'get_speakers'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.speakers').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }

    if(null !== document.querySelector('.annual')) {
        $.ajax({
            url: 'includes/js/ajax/round_up_dates.php', 
            data: {action: 'get_annual'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.annual').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }

    if(null !== document.querySelector('.round_up_dates')) {
        $.ajax({
            url: 'includes/js/ajax/round_up_dates.php', 
            data: {action: 'get_round_up_dates'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.round_up_dates').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }

    if(null !== document.querySelector('.first_year')) {
        $.ajax({
            url: 'includes/js/ajax/round_up_dates.php', 
            data: {action: 'get_first_year'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.first_year').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }

    if(null !== document.querySelector('.current_year')) {
        $.ajax({
            url: 'includes/js/ajax/round_up_dates.php', 
            data: {action: 'get_year'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.current_year').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }

    if(null !== document.querySelector('.meetings')) {
        $.ajax({
            url: 'includes/js/ajax/meetings.php', 
            data: {action: 'get_meetings'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.meetings').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            }
        });
    }
    
});
/* ]]> */