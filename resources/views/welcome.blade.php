@extends('layouts.public')

@section('content')
<div class="home-page">
    @include('partials.publicHeader')

    <section class="section-home">
        <h1>A Web Developer's Best Friend.</h1>
        <p>Simple tools for design, development, and communication.</p>
        <a href="/auth/register" class="btn btn-primary btn-lg">Create My Free Account</a>
        <div class="site-preview"></div>
    </section>

    <section class="section-features">
        <div class="container">
            <div class="row text-center">
                <div class="col-sm-12">
                    <h2>Bring your team to one platform.</h2>
                    <h3>Simple tools optimized for making progress in one place.</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="preview-descriptions">
                                        <h4>Projects, expressive progress.</h4>
                                        <p>
                                            Clearly communicate project progress by quickly glancing over your projects. Prioritize what is most important to your team and give them the info to get it done.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-center">
                                    <div class="image-preview" style="background-image: url(/img/feature-projects.png)"></div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6 text-center">
                                    <div class="image-preview image-preview-left" style="background-image: url(/img/feature-tasklist.png)"></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="preview-descriptions">
                                        <h4>Tasklist, tailored goals.</h4>
                                        <p>
                                            Turns your cards into a list that allows you to sort and filter by impact or stage.
                                            You can scan them quickly to see what issues are being issues are being worked on and what might have been missed.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-highlight text-center">
        <h2>Easy to learn, and easy to teach.</h2>
        <h3>Focused on clear communication and empowerment.</h3>
        <a href="/auth/register" class="btn btn-primary btn-lg">Create My Free Account</a>
    </section>
</div>
@stop
