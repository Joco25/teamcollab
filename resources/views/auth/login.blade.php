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
                                    <form method="POST" action="/auth/login">
                                        {!! csrf_field() !!}

                                        <h2>Sign In</h2>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Password <a href="/password/email">(forgot password)</a></label>
                                            <input type="password" name="password" id="password" class="form-control">
                                        </div>

                                        <div class="checkbox">
                                            <label>
                                                <input checked type="checkbox" name="remember"> Remember Me
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg">Submit</button>
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
