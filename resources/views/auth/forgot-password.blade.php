@extends('shared.not-logged-template')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Forgot your password</b></a>
        </div><!-- /.login-logo -->
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Recover your password here</p>

            <form method="POST" action="">
                {!! csrf_field() !!}
                @if($message != null)
                    <p class="error">{{$message}}</p>
                @endif
                <div class="form-group has-feedback">
                    <input type="email" name="email" value="{{ old('email') }}"  class="form-control" placeholder="Email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>

                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                        @if($status == 1)
                            <a href="/" class="btn btn-primary"></i>Back to home</a>
                        @endif
                        @if($status == 0)
                            <button type="submit" class="btn btn-primary">Recover pasword</button>
                        @endif

                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
@endsection
