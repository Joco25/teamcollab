module.exports = ['$firebaseArray', 'config', '$state', ($firebaseArray, config, $state) ->
    init = =>
        notesRef = new Firebase(config.FIREBASE_URL + 'notes')
        @notes = $firebaseArray(notesRef)

    @createNote = ->
        @notes.$add({
            name: ''
            body: ''
        }).then (ref) ->
            id = ref.key()
            $state.go 'notes.view', { noteId: id }

    init()

    return

]
