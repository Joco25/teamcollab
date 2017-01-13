module.exports = class CardListFilters {
    check(filters, authUser, card) {
        if (filters.tag !== null && _.findIndex(card.tags, {id: filters.tag.id}) === -1) {
            return false
        }

        if (filters.assignedTo === 'no one' && card.users.length > 0) {
            return false
        }

        if (_.isObject(filters.assignedTo) && _.findIndex(card.users, {id: filters.assignedTo.id}) === -1) {
            return false
        }

        if (filters.quick === 'Created by me' && card.user_id !== authUser.id) {
            return false
        }

        if (filters.quick === 'With subtasks' && card.subtasks && card.subtasks.length === 0) {
            return false
        }

        if (filters.quick === 'With impact' && !card.impact) {
            return false
        }

        if (filters.quick === 'With comments' && card.comments.length === 0) {
            return false
        }

        if (filters.quick === 'With files attached' && card.attachments && card.attachments.length === 0) {
            return false
        }

        if (filters.quick === 'Tasks blocked' && !card.blocked) {
            return false
        }

        if (filters.quick === 'Tasks unblocked' && card.blocked) {
            return false
        }

        return true
    }
}
