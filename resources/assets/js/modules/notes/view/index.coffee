module.exports = ['$firebaseArray', 'config', '$state', '$stateParams', ($firebaseArray, config, $state, $stateParams) ->
    init = =>
        noteId = $stateParams.noteId
        notesRef = new Firebase(config.FIREBASE_URL + 'notes')
        @notes = $firebaseArray(notesRef)
        @notes.$loaded (ref) =>
            @note = ref.$getRecord noteId

    @updateNote = =>
        @notes.$save(@note)

    @deleteNote = =>
        if ! confirm 'Delete this note?' then return
        @notes.$remove(@note)
        $state.go 'notes.list'

    init()

    return

]
