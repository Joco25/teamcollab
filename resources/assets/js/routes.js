import { ProjectsCtrl } from './controllers/projects.ctrl'

angular
    .module('simple.team.routes', [])
    .config(function($stateProvider) {
        $stateProvider
            .state('projects', {
                url: '/projects',
                template: require('./layouts/projects.html'),
                controller: ProjectsCtrl,
                controllerAs: 'ctrl'
            })
            .state('projects.card', {
                url: '/card/?cardId',
                template: require('./layouts/card.html'),
                controller: require('./controllers/card.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('conversations', {
                url: '/conversations',
                template: require('./layouts/conversations.html'),
                controller: require('./controllers/conversations.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('conversations.list', {
                url: '/list?type',
                template: require('./layouts/conversations.list.html'),
                controller: require('./controllers/conversations.list.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('conversations.create', {
                url: '/create',
                template: require('./layouts/conversations.create.html'),
                controller: require('./controllers/conversations.create.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('conversations.view', {
                url: '/view?topicId',
                template: require('./layouts/conversations.view.html'),
                controller: require('./controllers/conversations.view.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('settings', {
                url: '/settings',
                template: require('./layouts/settings.html'),
                controller: require('./controllers/settings.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('settings.account', {
                url: '/account',
                template: require('./layouts/settings.account.html'),
                controller: require('./controllers/settings.account.ctrl.js'),
                controllerAs: 'ctrl'
            })
            .state('settings.teams', {
                url: '/teams',
                template: require('./layouts/settings.teams.html'),
                controller: require('./controllers/settings.teams.ctrl.js'),
                controllerAs: 'ctrl'
            })
    }
)
