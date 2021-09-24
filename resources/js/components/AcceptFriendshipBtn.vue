<template>
    <div class="d-flex justify-content-between bg-light p-3 rounded mb-3 shadow-sm">
        <div>
            <div v-if="localFriendshipStatus === 'pending'">
                <span v-text="sender.name"></span> te ha enviado una solicitud de amistad
            </div>
            <div v-if="localFriendshipStatus === 'accepted'">
                Tú y <span v-text="sender.name"></span> son amigos
            </div>
            <div v-if="localFriendshipStatus === 'denied'">
                Solicitud denegada de <span v-text="sender.name"></span>
            </div>
            <div v-if="localFriendshipStatus === 'deleted'">Solicitud eliminada de <span v-text="sender.name"></span></div>
        </div>

        <div>
            <button class="btn btn-primary btm-sm" v-if="localFriendshipStatus === 'pending'" dusk="accept-friendship" @click="acceptFriendshipRequest">Aceptar solicitud</button>
            <button class="btn btn-warning btm-sm" v-if="localFriendshipStatus === 'pending'" dusk="deny-friendship" @click="denyFriendshipRequest">Aceptar solicitud</button>
            <button class="btn btn-danger btm-sm"  v-if="localFriendshipStatus !== 'deleted'" dusk="delete-friendship" @click="deleteFriendship">Eliminar</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        sender: {
            type: Object,
            required: true
        },
        friendshipStatus: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            localFriendshipStatus: this.friendshipStatus
        }
    },
    methods: {
        async acceptFriendshipRequest() {
            await axios.post(`/accept-friendships/${this.sender.name}`)
                .then(res => {
                    this.localFriendshipStatus = res.data.friendship_status
                })
                .catch(err => {
                    console.log(err.response.data)
                })
        },
        async denyFriendshipRequest() {
            await axios.delete(`/accept-friendships/${this.sender.name}`)
                .then(res => {
                    this.localFriendshipStatus = res.data.friendship_status
                })
                .catch(err => {
                    console.log(err.response.data)
                })
        },
        async deleteFriendship() {
            await axios.delete(`/friendships/${this.sender.name}`)
                .then(res => {
                    this.localFriendshipStatus = res.data.friendship_status
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
