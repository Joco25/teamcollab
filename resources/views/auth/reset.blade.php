@extends('layouts.public')

@section('content')
<div class="home-page">
    @include('partials.publicHeader')

    <section class="section-auth">
        <div class="container-fluid">
            <div class="header-content">
                <div class="header-content-inner">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 text-left">
                            <div class="panel">
                                <div class="panel-body">
                                    <form method="POST" action="/password/reset">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <h2>Set your new password</h2>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="form-control" type="password" name="password">
                                        </div>

                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input class="form-control" type="password" name="password_confirmation">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-primary btn-lg">Reset Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@stop
