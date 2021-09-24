<template>
    <div :class="highlight" :id="`comment-${comment.id}`" class="d-flex">
        <img class="rounded shadow-sm float-left mr-2" width="34px" height="34px"
             :src="comment.user.avatar"
             :alt="comment.user.name">
        <div class="flex-grow-1">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 text-secondary">
                    <a :href="comment.user_link"><b>{{ comment.user.name }}</b></a>
                    {{ comment.body }}
                </div>
            </div>
            <span class="badge badge-pill badge-primary py-1 px-2 mt-1 float-right"
                  dusk="comment-likes-count">
                        <i class="fa fa-thumbs-up"></i>
                        {{ comment.likes_count }}
                    </span>

            <like-btn
                dusk="comment-like-btn"
                :model="comment"
                :url="`/comments/${comment.id}/likes`"
                class="comments-like-btn"
            ></like-btn>
        </div>
    </div>
</template>

<script>
import LikeBtn from './LikeBtn';

export default {
    props: {
        comment: {
            type: Object,
            required: true
        }
    },
    components: {LikeBtn},
    mounted() {
        Echo.channel(`comments.${this.comment.id}.likes`)
        .listen('ModelLiked',comment =>{
            this.comment.likes_count ++;
        })
        Echo.channel(`comments.${this.comment.id}.likes`)
            .listen('ModelUnLiked',comment =>{
                this.comment.likes_count --;
            })
    },
    computed:{
        highlight(){
            if (window.location.hash === `#comment-${this.comment.id}`)
            {
                return 'highlight'
            }
        }
    }
}
</script>

<style scoped>
    .highlight{
        background-color: #ececec;
        padding: 10px;
        border-left: 4px solid #ff8d00;
    }
</style>
