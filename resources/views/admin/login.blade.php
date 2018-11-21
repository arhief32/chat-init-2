@extends('admin.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Login</div>

                <div class="card-body">
                    
                    <div class="alert alert-dismissible alert-primary" hidden>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Email dan password tidak cocok.
                    </div>
                
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 col-form-label text-md-right">Email</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" id="submit-login">
                                Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#submit-login').click(function(){
        var email = $('#email').val()
        var password = $('#password').val()
        
        $.ajax({
            type: 'POST',
            url: '{{ url("admin/login-validation") }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                email: email,
                password: password,
            },
            success: function(response){
                console.log(response)
                if(response.status == 'failed'){
                    $('.alert').attr('hidden', false);
                    $('.alert').text('Email dan password tidak cocok.')
                }else{
                    window.location.replace('{{ url("admin/chat") }}')
                }
            }
        })
    })
})
</script>
@endsection
