@extends('admin.main')

@section('content')

<div class="row">
<!-- team member area start -->
<div class="col-lg-4 mt-5" style="height: 650px;">
    <div class="card">
        <div class="card-body">
            <div class="d-sm-flex justify-content-between align-items-center">
                <h4 class="header-title"></h4>
                <div class="trd-history-tabs">
                    <ul class="nav" role="tablist">
                        <li>
                            <a class="active" data-toggle="tab" href="#conversation"  id="conversation-tab" role="tab">Conversation</a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#unapproved" id="unapproved-tab" role="tab">Unapproved</a>
                        </li>
                    </ul>
                </div>
                <div class="custome-select border-0 pr-3"></div>
            </div>

            <div class="trad-history mt-4">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="conversation" role="tabpanel" style="height: 500px; overflow: auto;">
                        
                    </div>
                    <div class="tab-pane fade" id="unapproved" role="tabpanel" style="height: 500px; overflow: auto;">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- team member area end -->

<!-- trading history area start -->
<div class="col-lg-8 mt-5" style="height: 650px;">
    <div class="card" style="height: 600px; overflow: auto;">
        <div class="card-body">
            <div class="d-sm-flex justify-content-between align-items-center">
                <h4 class="header-title">Chat Box</h4>
                
            </div>
        </div>
        <div class="card-footer">
            <div class="input-group">
                <input class="form-control mb-4" type="text" placeholder="Input message here">
                <button type="button" class="btn btn-flat btn-primary mb-4">Kirim</button>
            </div>
        </div>
    </div>
</div>
<!-- trading history area end -->
</div>




<!-- <div class="container" id="application">
    <div class="row justify-content-center">
    <div class="col-md-4">
            <div class="card">
                <div class="card-header">Conversation</div>
                <div class="card-body" style="height: 500px; overflow: auto;">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active show" data-toggle="tab" href="#conversation" id="conversation-tab">Conversation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#unapproved" id="unapproved-tab">Unapproved</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active show" id="conversation">
                        
                        </div>
                        <div class="tab-pane fade" id="unapproved">
                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" id="card-header">Chat-Box</div>
                <div class="card-body chat-box" style="height: 500px; overflow: auto;">
                
                </div>
            </div>
            <div class="card-footer">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Input chat here..." id="chat-textbox" disabled>
                    <button class="btn btn-primary" id="chat-button" data-info="" disabled>Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div> -->



<script>
$(document).ready(function(){
    Pusher.logToConsole = true;
    var pusher = new Pusher('74f634a0084f2960c016', {
        cluster: 'ap1',
        forceTLS: true
    })

    function conversationTab(){
        var admin_id = '{{ session("session.id") }}'
        
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/conversation-list") }}?admin_id='+ admin_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('#conversation').empty()
                
                $.each(response, function(){
                    $('#conversation').append('<div class="s-member conversation conversation-'+ this.id +'">'+
                            '<div class="media align-items-center">'+
                                '<img src="{{ asset("public/assets/images/author/avatar.png") }}" class="d-block ui-w-30 rounded-circle" alt="">'+
                                '<div class="media-body ml-5">'+
                                    '<p>'+ this.user.name +'</p><span>'+ this.created_at +'</span>'+
                                '</div>'+
                                '<div class="tm-social">'+
                                    '<a href="#"><i class="fa fa-wechat open-chat" data-info="'+ this.id +','+ this.user.name +'"></i></a>'+
                                    '<a href="#"><i class="fa fa-unlink break-chat" data-info="'+ this.id +'"></i></a>'+
                                '</div>'+
                            '</div>'+
                        '</div>')
                })
            }
        })
    }
    conversationTab()

    $('#conversation-tab').on('click', function(){
        conversationTab()
    })

    $('#unapproved-tab').on('click', function(){
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/unapproved-list") }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('#unapproved').empty()
                $.each(response, function(){
                    $('#unapproved').append('<div class="s-member unapproved unapproved-'+ this.id +'">'+
                        '<div class="media align-items-center">'+
                            '<img src="{{ asset("public/assets/images/author/avatar.png") }}" class="d-block ui-w-30 rounded-circle" alt="">'+
                            '<div class="media-body ml-5">'+
                                '<p>'+ this.name +'</p><span>Manager</span>'+
                            '</div>'+
                            '<div class="tm-social">'+
                                '<button class="btn btn-flat btn-primary btn-xs button-approved" data-info="'+ this.id +'">Approve</button>'+
                            '</div>'+
                        '</div>'+
                    '</div>')
                })
            }
        })
    })
    
    $('#unapproved').on('click', '.button-approved', function() {
        var admin_id = {{ session('session.id') }}
        var user_id = $(this).attr('data-info')
        
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/approved") }}?admin_id='+ admin_id +'&&user_id='+user_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('.unapproved-'+user_id).attr('hidden', true)
            }
        })
    })

    var conversation_id = ''
    $('#conversation').on('click', '.open-chat', function() {
        var data_info = $(this).attr('data-info').split(',')
        var conversation_id = data_info[0]
        var user_name = data_info[1]

        $('.header-message').remove()
        $('.body-message').remove()
        
        $('#chat-textbox').attr('disabled', false)
        $('#chat-button').attr('disabled', false)
        $('#chat-button').data('info', conversation_id)

        $.ajax({
            type: 'GET',
            url: '{{ url("admin/open-conversation") }}?conversation_id=' + conversation_id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response){
                $('.card-header').text('#'+response.conversation.id+' - '+ response.conversation.user.name)
                
                conversation_id = response.conversation.id
                var channel_message = pusher.subscribe('channel-message-'+ conversation_id)
                channel_message.bind('event-message-'+ conversation_id, function(data) {
                    if(data.user.roles == 'admin'){
                        $('.chat-box').append('<div class="header header-message" style="margin-top: 20px;">'+
                            '<strong>'+ data.user.name +'</strong>'+
                            '<small class="float-right">'+ data.created_at +'</small>'+
                            '</div>'+
                            '<div class="message-one body-message">'+ data.message +'</div>')
                        $('.chat-box').animate({
                            scrollTop: $('.chat-box').get(0).scrollHeight
                        }, 1)
                    }
                    else {
                        $('.chat-box').append('<div class="header header-message" style="margin-top: 20px;">'+
                            '<strong>'+ data.user.name +'</strong>'+
                            '<small class="float-right">'+ data.created_at +'</small>'+
                            '</div>'+
                            '<div class="message-two body-message">'+ data.message +'</div>')
                        $('.chat-box').animate({
                            scrollTop: $('.chat-box').get(0).scrollHeight
                        }, 1)
                    }
                })
                
                $.each(response.messages, function(){
                    if(this.user.roles == 'admin'){
                        $('.chat-box').append('<div class="header header-message" style="margin-top: 20px;">'+
                            '<strong>'+ this.user.name +'</strong>'+
                            '<small class="float-right">'+ this.created_at +'</small>'+
                            '</div>'+
                            '<div class="message-one body-message">'+ this.message +'</div>')
                    }else{
                        $('.chat-box').append('<div class="header header-message" style="margin-top: 20px;">'+
                            '<strong>'+ this.user.name +'</strong>'+
                            '<small class="float-right">'+ this.created_at +'</small>'+
                            '</div>'+
                            '<div class="message-two body-message">'+ this.message +'</div>')
                    }
                    $('.chat-box').animate({
                        scrollTop: $('.chat-box').get(0).scrollHeight
                    }, 1)
                })
            }
        })
    })

    $('#conversation').on('click', '.break-chat', function() {
        var id = $(this).attr('data-info')
        
        $.ajax({
            type: 'GET',
            url: '{{ url("admin/break-conversation") }}?id='+ id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                $('.conversation-'+ id).attr('hidden', true)
            }
        })
    })

    $('#chat-button').on('click', function() {
        var conversation_id = $(this).data('info')
        var user_id = {{ session('session.id') }}
        var message = $('#chat-textbox').val()

        if($('#chat-textbox').val() != ''){
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/send-message") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    conversation_id: conversation_id,
                    message: message,
                    user_id: user_id
                },
                success: function(response){
                    
                }
            })
        }
        
        $('#chat-textbox').val('')
    })

    $('#chat-textbox').keypress(function(e) {
        if(e.which == 13) {
            $(this).blur()
            $('#chat-button').focus().click()
            $('#chat-textbox').val('')
            $('#chat-textbox').focus()
        }
    })

})
</script>
@endsection
