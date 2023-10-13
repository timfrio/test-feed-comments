<template>
    <div>

        <template v-for="comment in comments">

            <CommentCard :comment="comment" @clicked="otherComment"/>

        </template>

        <pagination v-model="page" :records="total" :per-page="25" @paginate="open"/>

    </div>
</template>

<script>
import CommentCard from './CommentCard.vue';
import api from "../include/api";

export default {
    name: 'CommentsList',
    data() {
        return {
            page: 1,
            total: 0,
            last: 0,
            comments: [],
            parent: 0
        }
    },
    components: {
        CommentCard
    },
    mounted() {
        this.open(this.page);
    },
    methods: {
        open(page = 1) {
            this.page = page;
            api.get(`/api/comment/${
                this.parent > 0 ? this.parent : ''
            }?page=${this.page}`).then(({data}) => {
                this.comments = data.data;
                this.total = data.meta.total;
                this.last = data.meta.last_page;
            })
        },
        otherComment(parent) {
            this.parent = parent;
            this.open();
        }
    }
}
</script>

<style scoped>

</style>
