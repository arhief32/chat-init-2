@extends('user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chat-Box</div>
                <div class="card-body chat-box" style="height: 500px; overflow: auto;">
                
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Input chat here..." id="chat-textbox" disabled>
                        <button class="btn btn-primary" id="chat-button" disabled>Kirim</button>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>

<div class="modal modal-start" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){

    Pusher.logToConsole = true;
    var pusher = new Pusher('74f634a0084f2960c016', {
        cluster: 'ap1',
        forceTLS: true
    })

    var channel_approve = pusher.subscribe('channel-approve-{{ session("session.id") }}')
    channel_approve.bind('event-approve-{{ session("session.id") }}', function(data) {
        $('.modal-start').modal()
        $('.modal-body').append('Anda telah terhubung dengan admin '+ data.admin.name +'<br>Silahkan anda memulai chat.')

        $('.card-header').text('#'+ data.id +' - '+ data.admin.name)
        $('#chat-textbox').attr('disabled', false)
        $('#chat-button').attr('disabled', false)
        $('#chat-button').data('info', data.id)

        checkConversation()
    })

    var conversation_id = ''
    function checkConversation(){

        var id = {{ session('session.id') }}
        
        $('.header-message').remove()
        $('.body-message').remove()
                
        $.ajax({
            type: 'GET',
            url: '{{ url("check-conversation") }}?user_id=' + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: false,
            success: function(response){
                
                if(response.conversation){
                    $('.card-header').text('#'+ response.conversation.id +' - '+ response.conversation.admin.name)

                    

                    
                    $('#chat-textbox').attr('disabled', false)
                    $('#chat-button').attr('disabled', false)

                    $('#chat-button').data('info', response.conversation.id)
                }

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

                console.log(response.conversation.user.roles)

                if(response.messages){
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

            }
        })
    }
    checkConversation()

    $('#chat-button').on('click', function() {
        var conversation_id = $(this).data('info')
        var user_id = {{ session('session.id') }}
        var message = $('#chat-textbox').val()

        if($('#chat-textbox').val() != ''){
            $.ajax({
                type: 'POST',
                url: '{{ url("send-message") }}',
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

    var break_conversation = pusher.subscribe('channel-break-conversation-'+conversation_id)
    break_conversation.bind('event-break-conversation-'+conversation_id, function(data) {
        $('.modal-start').modal()
        $('.modal-body').append('Chat anda telah diputus oleh admin<br>Silahkan anda memulai chat lagi.')
    })
})    
</script>
@endsection
