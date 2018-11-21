@extends('admin.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Register</div>

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

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary" id="submit-register">
                                Register
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
                <h5 class="modal-title">Registration</h5>
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
    $('#submit-register').click(function(){
        var email = $('#email').val()
        var full_name = $('#full-name').val()
        var password = $('#password').val()
        var password_confirmation = $('#password-confirm').val()

        if(password != password_confirmation)
        {
            $('.alert').attr('hidden', false);
            $('.alert').text('Password dan password konfirmasi anda tidak cocok.')
        }
        else
        {
            $.ajax({
                type: 'POST',
                url: '{{ url("admin/register-validation") }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    email: email,
                    full_name: full_name,
                    password: password,
                },
                success: function(response){
                    console.log(response.status)
                    if(response.status == 'insert'){
                        $('.modal').modal();
                        $('#text').text('Anda telah sukses mendaftar sebagai admin\nSilahkan login')
                        $('.btn-secondary').click(function(){
                            window.location.replace('{{ url("admin/login") }}')
                        })
                    }else{
                        $('.modal').modal();
                        $('#text').text('Anda telah terdaftar di sistem kami\nHalaman ini akan otomatis menuju dashboard chat anda')
                        $('.btn-secondary').click(function(){
                            window.location.replace('{{ url("admin/chat") }}')
                        })
                    }
                }
            })
        }

    })
})
</script>
@endsection
