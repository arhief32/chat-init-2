@extends('user')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Start Chat</div>

                <div class="card-body">
                    <div class="alert alert-dismissible alert-primary" hidden>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label text-md-right">Email</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="full-name" class="col-md-4 col-form-label text-md-right">Full Name</label>
                        <div class="col-md-6">
                            <input id="full-name" type="text" class="form-control" name="full-name" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" id="submit-start">
                                Start
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="text"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#submit-start').click(function(){
        var email = $('#email').val()
        var full_name = $('#full-name').val()
        
        $.ajax({
            type: 'POST',
            url: '{{ url("start-validation") }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                email: email,
                full_name: full_name,
            },
            success: function(response){
                console.log(response.status)
                if(response.status == 'insert'){
                    $('.modal').modal();
                    $('#text').text('Anda telah sukses mendaftar sebagai user')
                    $('.btn-secondary').click(function(){
                        window.location.replace('{{ url("chat") }}')
                    })
                }
                if(response.status == 'exist'){
                    $('.modal').modal();
                    $('#text').text('Anda telah terdaftar di sistem kami\nHalaman ini akan otomatis menuju dashboard chat anda')
                    $('.btn-secondary').click(function(){
                        window.location.replace('{{ url("chat") }}')
                    })
                }
            }
        })
    })
})
</script>
@endsection
