jQuery(document).ready(function ($) {
    if(null !== document.querySelector('.days_to_round_up')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'days_to_round_up'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.days_to_round_up').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.speakers')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_speakers'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.speakers').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.annual')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_annual'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.annual').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.round_up_dates')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_round_up_dates'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.round_up_dates').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.first_year')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_first_year'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.first_year').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.current_year')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_year'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.current_year').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.now_year')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_now_year'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.now_year').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }

    if(null !== document.querySelector('.meetings')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_meetings'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.meetings').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.surcharge')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_surcharge'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    if('' != $result) {
                        $result = '$' + $result;
                    }
                    $('.surcharge').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.paypal')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_paypal'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.paypal').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.how_paypal_works')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'how_paypal_works'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.how_paypal_works').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.price')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_ticket_price'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.price').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.nsru_committee')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_committee'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.nsru_committee').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.nsru_past_chairs')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_past_chairs'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.nsru_past_chairs').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.harbour_room_rate')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_harbour_room_rate'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.harbour_room_rate').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.deluxe_room_rate')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_room_rate'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.deluxe_room_rate').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.hotel_booking_website')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_booking_link'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.hotel_booking_website').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
    if(null !== document.querySelector('.hotel_booking_code')) {
        $.ajax({
            url: '/wp-admin/admin-ajax.php', 
            data: {action: 'get_booking_code'}, 
            type: 'POST', 
            success: function ($result) {
                if ($result) {
                    $('.hotel_booking_code').html($result);
                    $( document.body ).trigger( 'post-load' );
                }
            },
            error: function ($result) {
                console.log($result);
            }
        });
    }
    
});
