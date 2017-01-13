function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

var ProjectsCtrl = function($http, $state, $rootScope, $modal, CardCacherService, CardListFiltersService, localStorageService) {
    this.s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
    this.authUser = $rootScope.authUser
    this.team = $rootScope.authUser.team
    this.projects = []
    this.tags = []
    this.currentUser = null
    this.selectedStage = null
    this.selectedCommentBody = ''
    this.selectedTaskBody = ''
    this.searchInput = ''
    this.sortableOptions = {
        placeholder: "sortable-preview",
        connectWith: ".sortable",
        delay: 100,
        stop: (evt, ui) => {
            var stage
            if (ui.item.sortable.droptarget) {
                stage = ui.item.sortable.droptarget.scope().stage
                this.updateStageCards(stage)
            }
        },
        start: function(e, ui){
            ui.placeholder.height(ui.item.height());
        }
    }

    if (isJsonString(localStorageService.get('filters'))) {
        this.filters = JSON.parse(localStorageService.get('filters'))
    } else {
        localStorageService.remove('filters')
    }

    if (! this.filters)
        this.filters = {
            tag: null,
            assignedTo: null,
            quick: null
        }

    $rootScope.$on('projects:reload', () => {
        this.loadProjects()
    })

    $rootScope.$on('team:changed', () => {
        this.loadProjects()
    })

    this.openEditCard = (card) => {
        CardCacherService.set(card)
        $state.go('projects.card', {
            cardId: card.id
        })
    }

    this.loadProjects = () => {
        $http
            .get('/api/projects')
            .success((data) => {
                this.projects = data.projects
            })
    }

    this.updateStageCards = (stage) => {
        var cardIds = _.pluck(stage.cards, 'id')
        $http.put('/api/cards/stageOrder', {
            card_ids: cardIds,
            stage_id: stage.id
        })
    }

    this.updateProjectOrder = (projects) => {
        var projectIds = _.pluck(projects, 'id')
        $http.post('/api/projects/order', {
            project_ids: projectIds
        })
    }

    this.selectAssignedToFilter = (newFilter) => {
        this.filters.assignedTo = newFilter
        $rootScope.$broadcast('filters:update', this.filters)
        localStorageService.set('filters', JSON.stringify(this.filters))
    }

    this.selectTagFilter = (newFilter) => {
        this.filters.tag = newFilter
        $rootScope.$broadcast('filters:update', this.filters)
        localStorageService.set('filters', JSON.stringify(this.filters))
    }

    this.selectQuickFilter = (newFilter) => {
        this.filters.quick = newFilter
        $rootScope.$broadcast('filters:update', this.filters)
        localStorageService.set('filters', JSON.stringify(this.filters))
    }

    this.updateSearchInput = () => {
        $rootScope.$broadcast('search:update', this.searchInput)
    }

    this.clearSearchInput = () => {
        this.searchInput = ''
        this.updateSearchInput()
    }

    this.loadTags = () => {
        $http
            .get('/api/tags')
            .success((data) => {
                this.tags = data.tags
            })
    }

    this.openSortableProjects = () => {
        $modal.open({
            template: require('../layouts/sortableProjects.modal.html'),
            controller: require('./sortableProjects.modal.ctrl.js'),
            controllerAs: 'ctrl',
            size: 'md',
            resolve: {
                projects: () => {
                    return this.projects
                }
            }
        })
        .result
        .then((projects) => {
            this.projects = projects
            this.updateProjectOrder(projects)
        })
    }

    this.loadProjects = () => {
        $http
            .get('/api/projects')
            .success((data) => {
                this.projects = data.projects
            })
    }

    this.openSortStagesModal = () => {
        this.stagesCopy = angular.copy(this.stages)
    }

    this.deleteProject = (project) => {
        if (! confirm("Delete '" + project.name + "' and all it's contents?")) return
        _.remove(this.projects, project)
        $http.delete('/api/projects/' + project.id)
    }

    this.createProject = () => {
        var projectName = prompt("New Project Name")
        if (!projectName) return
        $http
            .post('/api/projects', {
                name: projectName,
                stages: [
                    { name: 'Open' },
                    { name: 'In Progress' },
                    { name: 'Closed' }
                ]
            })
            .success((data) => {
                this.projects = data.projects
            })
    }

    this.deleteStage = (project, stageIndex) => {
        if (! confirm('Delete this stage?')) return
        project.stages.splice(stageIndex, 1)
    }

    this.toggleProjectVisibility = (project) => {
        project.hidden = !project.hidden
    }

    this.openEditCard = (card) => {
        CardCacherService.set(card)
        $state.go('projects.card', {
            cardId: card.id
        })
    }

    this.editStage = (stage) => {
        var newStageName = prompt('Edit Stage Name', stage.name)
        if (! newStageName) return
        stage.name = newStageName
    }

    this.deleteAllCardsInStage = (stage) => {
        if (! confirm('Delete all cards in this stage?')) return
        stage.cards = []
        $http.delete('/api/stages/' + stage.id + '/cards')
    }

    this.createStage = () => {
        var newStage, result, stageName
        stageName = prompt("Stage name")
        if (stageName === null) return
        newStage = {
            id: createId(),
            name: stageName,
            createdAt: (new Date).getTime()
        }
        result = this.stages.$add(newStage)
        _.each(this.projects, (project) => {
            project.stages = project.stages || []
            project.stages.push(newStage)
            this.projects.$save(project)
        })
    }

    this.createCard = (project) => {
        var newCardName = prompt('Task description')
        if (!newCardName) return
        $http
            .post('/api/cards', {
                stage_id: project.stages[0].id,
                name: newCardName
            })
            .success((data) => {
                return project.stages[0].cards.push(data.card)
            })
        this.newCardName = ''
    }

    this.appliedFilters = (card) => {
        return CardListFiltersService.check(this.filters, this.authUser, card)
    }

    this.editProject = (project) => {
        var projectName = prompt("Project name", project.name)
        if (projectName === null) return
        project.name = projectName
        $http
            .put('/api/projects/' + project.id, {
                name: project.name
            })
            .success((data) => {
                var projectIndex = _.indexOf(this.projects, _.find(this.projects, {
                    id: project.id
                }))

                if (cardIndex > -1) {
                    return this.projects.splice(projectIndex, 1, project)
                }
            })
    }

    this.loadProjects()
    this.loadTags()
}

export { ProjectsCtrl }
