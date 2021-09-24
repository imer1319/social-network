<template>
    <form @submit.prevent="addComment" v-if="isAuthenticated" class="mb-3">
        <div class="d-flex align-items-center">
            <img class="rounded shadow-sm mr-2" width="34px"
                 :src="currentUser.avatar"
                 :alt="currentUser.name">
            <div class="input-group">
                        <textarea
                            class="form-control"
                            name="comment"
                            v-model="newComment"
                            rows="1"
                            placeholder="Escribe un comentario..."
                            required
                        ></textarea>
                <div class="input-group-append">
                    <button dusk="comment-btn" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </form>
    <div v-else class="pb-3 text-center">
        Debes <a href="/login">autenticarte</a> para poder comentar
    </div>
</template>

<script>
export default {
    props: {
        statusId:{
            type: Number,
            required:true
        }
    },
    data() {
        return {
            newComment: '',
        }
    },
    methods: {
        addComment() {
            axios.post(`/statuses/${this.statusId}/comments`, {body: this.newComment})
                .then(res => {
                    EventBus.$emit(`statuses.${this.statusId}.comments`, res.data.data)
                    this.newComment = ''
                })
                .catch(err => {
                    console.log(err.response.data)
                })
        }
    }
}
</script>

<style scoped>

</style>
