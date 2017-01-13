module.exports = {
    data: function () {
        return {
            newSecureNote: '',
            secureNoteLink: '',
            secureNotes: []
        }
    },

    ready: function() {
        this.loadSecureNotes()
    },

    methods: {
        clearSecureNoteLink: function() {
            this.secureNoteLink = ''
        },

        createSecureNote: function() {
            _this = this
            this.$http.post('/api/secureNotes', {
                body: this.newSecureNote
            }, function(data) {
                _this.secureNoteLink = data.link
                _this.loadSecureNotes()
            })
            this.newSecureNote = ''
        },

        deleteSecureNote: function(secureNote) {
            if ( ! confirm('Delete this secure note?')) return

            this.$http.delete('/api/secureNotes/' + secureNote.id, function(data) {
                if (data.success !== true) {
                    alert('Secure note was not deleted.')
                }
            })

            this.secureNotes.$remove(secureNote)
        },

        loadSecureNotes: function() {
            _this = this
            this.$http.get('/api/secureNotes', function(data) {
                _this.secureNotes = data.secureNotes
            })
        }
    }
}
