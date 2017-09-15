  
   // Lazy Load More Projects
    var ppp = 12; // Post per page
    var pageNumber = 1;
    var type = $('#more_posts_n').data('taxToSend');


    function load_news(){
        pageNumber++;
        var str = '&pageNumber=' + pageNumber + '&type=' + type + '&ppp=' + ppp + '&action=more_news_ajax';
        $.ajax({
            type: "POST",
            dataType: "html",
            url: ajax_posts.ajaxurl,
            data: str,
            success: function(data){
                var $data = $(data);
                if($data.length){
                    var quant = $(data).closest('.archive-item').length;
                    $("#ajax-block").find('.archive-btn').before($data).hide().fadeIn(2000).siblings().slice(-quant).hide().fadeIn(2000);
                    $("#more_posts_n").attr("disabled",false);
                } else{
                    $("#more_posts_n").attr("disabled",true);
                    $("#more_posts_n  h1").html("No More Projects");
                }
                $('.archive-filter li #reset').on('click', function(event) { 
                    location.reload();
                });
            }

        });
        return false;
    }

    $("#more_posts_n").on("click",function(e){ // When btn is pressed.
        $("#more_posts_n").attr("disabled",true); // Disable the button, temp.
        e.preventDefault();
        load_news();
    });


    // Filter Projects
    $('.archive-filter li .cat-btn').on('click', function(event) {
            var $type = $(this).data('tax');
            $.ajax({
                type: "POST",
                url: ajax_posts.ajaxurl,
                data: { 
                    action: 'cs_filter',
                    type: $type,
    
                },
                success: function(data)
                {
                    $('#ajax-block').html(data).children().hide().fadeIn(1000);
                    $(".archive-btn").css('display', 'none');
                    $('.archive-filter li #reset').on('click', function(event) { 
                        location.reload();
                    });
    
                }
                });
        event.preventDefault();     
            
    }); 





