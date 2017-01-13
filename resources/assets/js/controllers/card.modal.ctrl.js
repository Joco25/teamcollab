'use strict'
module.exports = function($state, $stateParams, $scope, $http, $rootScope, TagDataService, Upload, $modalInstance, cardId, $timeout, CardCacherService) {
  var cardImpactHandle, init, projectId, stageId, updateCardTags, updateCardUsers
  stageId = null
  projectId = null
  this.selectedCard = null
  this.files = null
  this.file = null
  this.tagData = TagDataService
  this.s3BucketAttachmentsUrl = $rootScope.s3BucketAttachmentsUrl
  this.selectedCard = {
    impact: 0
  }
  this.states = {
    uploading: false
  }
  this.tagsConfig = {
    valueField: 'name',
    labelField: 'name',
    delimiter: '|',
    placeholder: 'Add a tag...',
    create: true,
    onChange: function(tags) {
      return updateCardTags(tags.split('|'))
    }
  }
  this.usersConfig = {
    valueField: 'id',
    labelField: 'name',
    delimiter: '|',
    placeholder: 'Assign a user...',
    create: false,
    onChange: function(users) {
      return updateCardUsers(users.split('|'))
    }
  }
  init = (function(_this) {
    return function() {
      _this.selectedCard = CardCacherService.get()
      _this.loadTags()
      _this.loadCard()
      return _this.loadTeamUsers()
    }
  })(this)
  updateCardTags = (function(_this) {
    return function(tags) {
      return $http.post('/api/cards/tags', {
        tags: tags,
        card_id: _this.selectedCard.id
      }).success(function() {
        $rootScope.$emit('projects:reload')
      })
    }
  })(this)
  updateCardUsers = (function(_this) {
    return function(userIds) {
      return $http.post('/api/cards/users', {
        user_ids: userIds,
        card_id: _this.selectedCard.id
      }).success(function() {
        $rootScope.$emit('projects:reload')
      })
    }
  })(this)
  this.updateStage = (function(_this) {
    return function(stage) {
      if (stage.id === _this.selectedCard.stage.id) {
        return
      }
      _this.selectedCard.stage = stage
      return $http.put('/api/cards/' + _this.selectedCard.id + '/updateStage', {
        stage_id: stage.id
      }).success(function(data) {
        _this.selectedCard = data.card
        _this.selectedCard.tagNames = _.pluck(data.card.tags, 'name')
        _this.selectedCard.userIds = _.pluck(data.card.users, 'id')
        _this.selectedCard.impact = _this.selectedCard.impact || 0
        $rootScope.$emit('projects:reload')
      })
    }
  })(this)
  this.loadTags = (function(_this) {
    return function() {
      return TagDataService.loadTags().success(function(data) {
        return _this.tags = data.tags
      })
    }
  })(this)
  this.loadTeamUsers = function() {
    return $http.get('/api/users').success((function(_this) {
      return function(data) {
        return _this.users = data.users
      }
    })(this))
  }
  this.loadCard = (function(_this) {
    return function() {
      return $http.get('/api/cards/' + cardId).success(function(data) {
        _this.selectedCard = data.card
        _this.selectedCard.tagNames = _.pluck(data.card.tags, 'name')
        _this.selectedCard.userIds = _.pluck(data.card.users, 'id')
        return _this.selectedCard.impact = _this.selectedCard.impact || 0
      })
    }
  })(this)
  cardImpactHandle = null
  this.updateCardImpact = (function(_this) {
    return function() {
      $timeout.cancel(cardImpactHandle)
      return cardImpactHandle = $timeout(function() {
        return _this.updateCard()
      }, 500)
    }
  })(this)
  this.updateCard = (function(_this) {
    return function() {
      return $http.put('/api/cards/' + cardId, {
        name: _this.selectedCard.name,
        description: _this.selectedCard.description,
        blocked: _this.selectedCard.blocked,
        impact: _this.selectedCard.impact
      }).success(function() {
        $rootScope.$emit('projects:reload')
      })
    }
  })(this)
  this.deleteCard = (function(_this) {
    return function() {
      if (!confirm('Delete this card?')) {
        return
      }
      return $http["delete"]('/api/cards/' + _this.selectedCard.id).success(function(data) {
        $rootScope.$emit('projects:reload')
        return _this.ok()
      })
    }
  })(this)
  this.updateCardName = (function(_this) {
    return function() {
      _this.selectedCard.name = angular.copy(_this.selectedCardName.replace("\n", ''))
      _this.selectedCard.editName = false
      return _this.updateCard()
    }
  })(this)
  this.selectCardDescription = (function(_this) {
    return function() {
      _this.selectedCard.description = _this.selectedCard.description || ''
      _this.selectedCardDescription = angular.copy(_this.selectedCard.description)
      return _this.showCardDescription = true
    }
  })(this)
  this.updateCardDescription = (function(_this) {
    return function() {
      _this.selectedCard.description = angular.copy(_this.selectedCardDescription)
      _this.selectedCardDescription = null
      _this.showCardDescription = false
      return _this.updateCard()
    }
  })(this)
  this.createSubtask = (function(_this) {
    return function() {
      _this.newSubtaskBody = _this.newSubtaskBody.replace("\n", '')
      $http.post('/api/subtasks', {
        body: _this.newSubtaskBody,
        checked: false,
        card_id: cardId
      }).success(function(data) {
        _this.selectedCard.subtasks.push(data.subtask)
        return $rootScope.$emit('projects:reload')
      })
      return _this.newSubtaskBody = ''
    }
  })(this)
  this.editSubtask = function(task) {
    task.editMode = true
    return task.newBody = angular.copy(task.body)
  }
  this.updateSubtask = function(task) {
    task.editMode = false
    task.body = task.newBody.replace("\n", '')
    task.newBody = null
    return $http.put('/api/subtasks/' + task.id, task).success(function(data) {
      return $rootScope.$emit('projects:reload')
    })
  }
  this.cancelSubtaskEdit = function(task) {
    task.editMode = false
    return task.newBody = null
  }
  this.deleteSubtask = (function(_this) {
    return function(task) {
      if (!confirm('Delete this subtask?')) {
        return
      }
      _.remove(_this.selectedCard.subtasks, task)
      return $http["delete"]('/api/subtasks/' + task.id).success(function() {
        return $rootScope.$emit('projects:reload')
      })
    }
  })(this)
  this.toggleSubtask = (function(_this) {
    return function(task) {
      task.checked = !task.checked
      return _this.updateTask(task)
    }
  })(this)
  this.updateTask = function(task) {
    return $http.put('/api/subtasks/' + task.id, task).success(function() {
      return $rootScope.$emit('projects:reload')
    })
  }
  this.createComment = (function(_this) {
    return function() {
      if (!_this.newCommentBody) {
        return
      }
      $http.post('/api/comments', {
        body: _this.newCommentBody,
        card_id: _this.selectedCard.id
      }).success(function(data) {
        _this.selectedCard.comments.push(data.comment)
        return $rootScope.$emit('projects:reload')
      })
      return _this.newCommentBody = ''
    }
  })(this)
  this.deleteComment = (function(_this) {
    return function(comment) {
      if (!confirm('Delete this comment?')) {
        return
      }
      $http["delete"]('/api/comments/' + comment.id).success(function() {
        $rootScope.$emit('projects:reload')
      })
      return _.remove(_this.selectedCard.comments, comment)
    }
  })(this)
  this.cancelCommentEdit = function(comment) {
    comment.editMode = false
    return comment.newBody = null
  }
  this.updateComment = function(comment) {
    comment.body = angular.copy(comment.newBody)
    comment.editMode = false
    comment.newBody = null
    return $http.put('/api/comments/' + comment.id, comment)
  }
  this.editComment = function(comment) {
    comment.editMode = true
    return comment.newBody = angular.copy(comment.body)
  }
  this.blockToggleSelectedCard = (function(_this) {
    return function() {
      _this.selectedCard.blocked = !_this.selectedCard.blocked
      return _this.updateCard()
    }
  })(this)
  this.submit = (function(_this) {
    return function() {
      if (form.file.$valid && _this.file && !_this.file.$error) {
        return _this.upload(_this.file)
      }
    }
  })(this)
  this.upload = (function(_this) {
    return function(file) {
      return Upload.upload({
        url: '/api/attachments',
        fields: {
          'card_id': cardId
        },
        file: file
      }).progress(function(evt) {
        var progressPercentage
        _this.states.uploading = true
        return progressPercentage = parseInt(100.0 * evt.loaded / evt.total)
      }).success(function(data, status, headers, config) {
        _this.states.uploading = false
        return _this.selectedCard.attachments.push(data.attachment)
      }).error(function(data, status, headers, config) {
        this.states.uploading = false
        return console.log('error status: ' + status)
      })
    }
  })(this)
  this.uploadFiles = (function(_this) {
    return function(files) {
      var i, results
      console.log('files', files)
      if (files && files.length) {
        i = 0
        results = []
        while (i < files.length) {
          _this.upload(files[i])
          results.push(i++)
        }
        return results
      }
    }
  })(this)
  this.deleteAttachment = (function(_this) {
    return function(attachment) {
      if (!confirm('Delete this attachment?')) {
        return
      }
      $http["delete"]('/api/attachments/' + attachment.id)
      return _.remove(_this.selectedCard.attachments, attachment)
    }
  })(this)
  this.downloadAttachment = function(attachment) {
    var a
    a = document.createElement("a")
    a.download = attachment.filename
    a.title = attachment.original_filename
    a.href = attachment.file_url
    a.click()
  }
  this.ok = function() {
    return $modalInstance.close()
  }
  this.cancel = function() {
    return $modalInstance.dismiss('cancel')
  }
  init()
}

// ---
// generated by coffee-script 1.9.2
