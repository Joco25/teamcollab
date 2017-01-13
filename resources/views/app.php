<!DOCTYPE html>
<html lang="en" ng-app="simple.team" ng-controller="AppCtrl as appCtrl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Simple tools for design, development, and communication.">
    <meta name="author" content="">

    <title>simple.team - A Web Developer's Best Friend</title>

    <link rel="shortcut icon" type="image/png" href="/img/SimpleTeam01-favicon.png"/>

    <!-- Bootstrap Core CSS -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
	<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <!-- Angular CSS -->
    <link href='//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.7.1/loading-bar.min.css' rel='stylesheet' type='text/css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.default.min.css" rel="stylesheet" type='text/css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/textAngular/1.4.6/textAngular.css" rel="stylesheet" type='text/css'>

    <!-- Custom CSS -->
	<link href="/css/app.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="simple-page">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <a ui-sref="projects" class="navbar-brand">
                <img src="/img/SimpleTeamBlack.png" height="20">
            </a>
            <ul class="nav navbar-nav navbar-right">
                <li ui-sref-active-if="projects">
                    <a ui-sref="projects">Projects</a>
                </li>
                <li ui-sref-active-if="conversations">
                    <a ui-sref="conversations.list">Conversations</a>
                </li>
                <li class="dropdown">
                    <a class="pointer dropdown-toggle" data-toggle="dropdown">{{ appCtrl.authUser.team.name || 'Select a team...' }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li ng-repeat="team in appCtrl.teams" ng-click="appCtrl.setCurrentTeam(team)">
                            <a href="#">{{ team.name }}</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bars"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a ui-sref="settings.account">Account</a></li>
                        <li><a ui-sref="settings.teams">Teams</a></li>
                        <li class="divider"></li>
                        <li><a href="/auth/logout">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div ui-view></div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/showdown/1.2.3/showdown.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!--
     * Angular Scripts
    -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.5/angular.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.4/ui-bootstrap-tpls.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/danialfarid-angular-file-upload/9.0.7/ng-file-upload-all.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-loading-bar/0.8.0/loading-bar.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/textAngular/1.4.6/textAngular-rangy.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/textAngular/1.4.6/textAngular-sanitize.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/textAngular/1.4.6/textAngular.min.js"></script>

    <script>
        var ENV = {
            authUser: {
                id: <?php echo Auth::user()->id ?>,
                team: <?php echo json_encode(Auth::user()->team) ?>,
                name: <?php echo json_encode(Auth::user()->name) ?>,
                email: <?php echo json_encode(Auth::user()->email) ?>,
                timezone: <?php echo json_encode(Auth::user()->timezone) ?>
            },
            s3BucketAttachmentsUrl: <?php echo json_encode(env('S3_BUCKET_ATTACHMENTS_URL')) ?>,
            teams: <?php echo json_encode(Auth::user()->teams) ?>
        }
    </script>
    <script src="/js/register.js"></script>
    <script src="/js/app.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.min.js"></script>
    <?php if ($app->environment('production')): ?>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-53112500-6', 'auto');
          ga('send', 'pageview');
        </script>

        <!-- begin olark code -->
        <script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
        f[z]=function(){
        (a.s=a.s||[]).push(arguments)};var a=f[z]._={
        },q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
        f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
        0:+new Date};a.P=function(u){
        a.p[u]=new Date-a.p[0]};function s(){
        a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
        hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
        return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
        b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
        b.contentWindow[g].open()}catch(w){
        c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
        var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
        b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
        loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
        /* custom configuration goes here (www.olark.com/documentation) */
        olark.identify('6959-142-10-9785');/*]]>*/</script><noscript><a href="https://www.olark.com/site/6959-142-10-9785/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
        <!-- end olark code -->
    <?php endif; ?>
</body>
</html>
