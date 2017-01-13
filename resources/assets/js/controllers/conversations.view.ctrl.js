'use strict'

module.exports = function($stateParams, $www, $state, $rootScope) {
    this.authUser = $rootScope.authUser
    this.topicId = $stateParams.topicId
    this.newPost = {}
    this.topic = null
    this.filters = {
        showNewPost: false
    }

    this.selectPost = (post) => {
        this.selectedPost = post
        this.postCopy = angular.copy(post)
    }

    this.resetNewPost = () => {
        if (this.selectedPost) {
            this.selectedPost.showNewPost = false
        }
        this.filters.showNewPost = false
        this.selectedPost = void 0
        this.postCopy = angular.copy(void 0)
        this.newPost = {}
    }

    this.loadTopic = () => {
        $www
            .get('/api/topics/' + this.topicId)
            .success((data) => {
                if (data.error) {
                    $state.go('conversations.list')
                }
                this.topic = data.topic
            })
    }

    this.resetCurrentPost = () => {
        this.selectedPost.editMode = false
    }

    this.updatePost = () => {
        this.selectedPost.body = this.postCopy.body
        this.selectedPost.editMode = false
        $www
            .put('/api/topicPosts/' + this.postCopy.id, {
                body: this.postCopy.body
            })
            .success(() => {
                this.postCopy = null
            })
    }

    this.createPost = () => {
        this.newPost.topic_id = this.topicId
        $www
            .post('/api/topicPosts', this.newPost)
            .success((data) => {
                this.topic.posts.push(data.post)
                if (this.selectedPost) {
                    this.selectedPost.posts = this.selectedPost.posts || []
                    this.selectedPost.posts.push(data.post)
                }
                this.resetNewPost()
            })
    }

    this.deletePost = (postId) => {
        $www
            .delete('/api/topicPosts/' + postId)
            .success(() => {
                this.topic.posts = _.reject(this.topic.posts, {
                    id: +postId
                })
            })
    }

    this.deleteTopic = (topicId) => {
        $www
            .delete('/api/topics/' + topicId)
            .success(() => {
                $state.go('conversations.list')
            })
    }

    this.likePost = (postId) => {
        $www.post('/api/topicPostLikes', {
            topic_post_id: postId
        })
    }

    this.unlikePost = (postId) => {
        $www
            .delete('/api/topicPostLikes', {
                topic_post_id: postId
            })
    }

    this.togglePostUserLike = (post) => {
        if (post.is_liked) {
            this.unlikePost(post.id)
        } else {
            this.likePost(post.id)
        }
        post.is_liked = !post.is_liked
    }

    this.createTopicView = () => {
        $www.post('/api/topicViews', {
            topic_id: this.topicId
        })
    }

    this.loadUserNotification = () => {
        $www
            .get('/api/topicNotifications/' + this.topicId + '/users/' + this.main.authUser.id + '/notification')
            .success(function(data) {
                this.watchNotification = data.notification
            })
    }

    this.createNotification = () => {
        $www
            .post('/api/topicNotifications/' + this.topicId + '/users/' + this.main.authUser.id + '/notification')
            .success((data) => {
                this.watchNotification = data.notification
            })
    }

    this.deleteNotification = () => {
        $www
            .delete('/api/topicNotifications/' + this.topicId + '/users/' + this.main.authUser.id + '/notification')
            .success(() => {
                this.watchNotification = false
            })
    }

    this.toggleNotification = () => {
        if (this.watchNotification) {
            this.deleteNotification()
        } else {
            this.createNotification()
        }
    }

    this.starTopic = (topicId) => {
        $www.post('/api/topicStars', {
            topic_id: topicId
        })
    }

    this.unstarTopic = (topicId) => {
        $www.delete('/api/topicStars', {
            topic_id: topicId
        })
    }

    this.toggleTopicUserStar = (topic) => {
        if (topic.is_starred) {
            this.unstarTopic(topic.id)
        } else {
            this.starTopic(topic.id)
        }
        topic.is_starred = !topic.is_starred
    }

    this.loadTopic()
    this.createTopicView()
}
