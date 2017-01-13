@extends('layouts.public')

@section('content')
<div class="features-page">
        @include('partials.publicHeader')
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    @include('features.partials.menu')
                </div>
                <div class="col-sm-8">

                </div>
            </div>
        </div>
    </div>
</div>
@stop
