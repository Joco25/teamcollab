'use strict'

module.exports = function($www, $state) {
    this.newTopic = {}

    this.createTopic = () => {
        if (!this.newTopic.name || !this.newTopic.body) {
            alert('You are missing some info!')
            return
        }

        $www
            .post('/api/topics', this.newTopic)
            .success(function(data) {
                $state.go('conversations.view', {
                    topicId: data.topic.id
                })
            })
    }
}
