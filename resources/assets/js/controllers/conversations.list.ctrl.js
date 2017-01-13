'use strict'

module.exports = function($stateParams, $www, $rootScope) {
    this.topics = []
    this.filters = {
        type: $stateParams.type || 'latest',
        busy: false,
        page: 1,
        disableInfiniteScroll: false
    }
    this.authUser = $rootScope.authUser

    this.loadConversations = () => {
        if (this.filters.busy) return
        this.filters.busy = true
        $www
            .get('/api/topics/' + this.filters.type, {
                take: 50,
                page: this.filters.page
            })
            .success((data) => {
                this.topics = this.topics.concat(data.topics)
                this.filters.busy = false
                this.filters.disableInfiniteScroll = data.topics.length === 0 ? true : false
            })
    }

    this.nextPage = () => {
        if (this.filters.busy) return
        this.filters.page += 1
        this.loadConversations()
    }

    this.toggleTopicUserStar = (topic) => {
        if (topic.is_starred) {
            this.unstarTopic(topic.id)
        } else {
            this.starTopic(topic.id)
        }
        topic.is_starred = !topic.is_starred
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

    this.loadConversations()
}
