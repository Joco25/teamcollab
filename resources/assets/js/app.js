"use strict"

require('moment')
require('angular-ui-router')
require('angular-ui-sortable')
require('angular-gravatar')
require('angular-elastic')
require('angular-local-storage')
require('angular-moment')
require('ng-showdown')

require('./routes.js')
require('./modules/focusMe')
require('./modules/selectize')
require('./modules/tagData')
require('./modules/userData')
require('./modules/auth')
require('./modules/bytes')
require('./modules/ngBindHtmlUnsafe')
require('./modules/strings')
require('./modules/mediaComment')
require('./modules/redactor')
require('./modules/time')
require('./modules/www')
require('./modules/cardList')
require('./modules/cardCacher')
require('./modules/uiSrefActiveIf')
require('./modules/dateToIso')

angular
    .module('simple.team', [
        'ngFileUpload',
        'ui.router',
        'ui.sortable',
        'ui.gravatar',
        'ui.bootstrap',
        'selectize',
        'angularMoment',
        'angular-loading-bar',
        'ng-showdown',
        'LocalStorageModule',
        'monospaced.elastic',
        'textAngular',

        'simple.dateToIso',
        'simple.team.uiSrefActiveIf',
        'simple.team.routes',
        'simple.team.focusMe',
        'simple.team.ngBindHtmlUnsafe',
        'simple.team.bytes',
        'simple.team.strings',
        'simple.team.auth',
        'simple.team.tagData',
        'simple.team.userData',
        'simple.team.www',
        'simple.team.cardList',
        'simple.team.mediaComment',
        'simple.team.cardCacher'
    ])
    .config(function($urlRouterProvider, cfpLoadingBarProvider) {
        $urlRouterProvider.otherwise('/projects')
        cfpLoadingBarProvider.includeSpinner = false
    })
    .controller('AppCtrl', function($state, $http, $rootScope) {
        var ctrl = this
        $rootScope.teams = angular.copy(ENV.teams)
        $rootScope.authUser = angular.copy(ENV.authUser)
        $rootScope.s3BucketAttachmentsUrl = angular.copy(ENV.s3BucketAttachmentsUrl)

        ctrl.state = $state
        ctrl.s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
        ctrl.teams = $rootScope.teams
        $rootScope.$broadcast('teams:loaded', ctrl.teams)

        ctrl.authUser = $rootScope.authUser
        $rootScope.$broadcast('user:loaded', ctrl.authUser)
        $rootScope.$on('teams:reload', ctrl.loadTeams)

        var init = function() {
            ctrl.loadTeams()
        }

        ctrl.loadTeams = function() {
            $http
                .get('/api/teams')
                .success(function(data) {
                    ctrl.teams = data.teams
                    $rootScope.teams = data.teams
                })
        }

        ctrl.setCurrentTeam = function (team) {
            var previousTeam = angular.copy(ctrl.authUser.team)
            ctrl.authUser.team = team
            $http
                .put('/api/me/team', {
                    team_id: team.id
                })
                .success(function(data) {
                    $state.reload()
                    $rootScope.$broadcast('team:changed')
                })
        }

        init()
    })
