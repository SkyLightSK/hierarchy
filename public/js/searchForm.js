$(document).ready(function () {

    $('.dataTables_length').addClass('bs-select');

    $('[contenteditable]')
        .focus(function () {
            $(this).attr('data-initial-text', $(this).html() );
        })
        .blur(function () {

            var el = $(this);
            var user_id = el.parent().parent().attr('data-user-id');
            var new_data = el.html();

            if( el.attr('data-initial-text') !== new_data  ){

                el.attr("contenteditable", false).fadeTo( 'slow', 0.5 );

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type:'POST',
                    url:'/update',
                    data: {
                        user_id : user_id,
                        user_type : el.attr('data-field-type'),
                        user_post : el.parent().parent().attr('data-user-position'),
                        position_id : el.parent().parent().attr('data-position-id'),
                        field : el.attr('data-field'),
                        new_data : new_data

                    },

                    success:function(data){
                        // console.log(data.msg);
                        el.attr("contenteditable", true).fadeTo( 'slow', 1 );

                        if(data.msg == false){
                            el.attr("contenteditable", true).parent().css('border-color','red');
                        }
                        else if(data.msg == true){
                            el.attr("contenteditable", true).parent().css('border-color','#dee2e6');

                        }
                    }
                });
            }

        });

    $('#db_filter').submit(function (e) {
        e.preventDefault();

        var keyword = $('#db_filter input[type="search"]').val();

        if (!keyword){
            return false;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url:'/search',
            data: {
                keyword : keyword
            },

            success:function(data){

                $('.table-content').html(data.msg);

            }
        });

    });

    $('.pagination .page-item .page-link').click(function (e) {
        e.preventDefault();

        var keyword = $('#db_filter input[type="search"]').val();
        var page = parseInt($(this).text());

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            url:'/searchPagination',
            data: {
                keyword : keyword,
                page : page
            },

            success:function(data){

                $('.table-content').html(data.msg);

            }
        });
    });


});