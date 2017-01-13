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
                                    <form method="POST" action="/password/email">
                                        {!! csrf_field() !!}

                                        <h2>Forgot Password</h2>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-primary btn-lg">Submit</button>
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
