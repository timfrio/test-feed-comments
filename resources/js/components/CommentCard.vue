<template>
    <div class="card mb-1">
        <div class="card-header">

            <div class="d-grid gap-2 d-md-flex justify-content-between align-items-center">

                <span class="h6 mb-0">{{ comment.name }} {{ comment.created_at }}</span>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <a class="btn btn-sm btn-outline-primary"
                       :href="`mailto:${comment.email}` | lowercase"
                    >
                        <font-awesome-icon icon="fa-solid fa-at" class="icon"/>
                    </a>

                    <template v-if="comment.id">
                        <button type="button" class="btn btn-sm btn-outline-primary" @click="onClickButton">
                            <font-awesome-icon icon="fa-solid fa-comments" class="icon"/>
                            {{ comment.children_count}}
                        </button>
                    </template>

                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="card-text" v-html="comment.text"/>
        </div>

    </div>
</template>

<script>
export default {
    name: 'CommentCard',
    data() {
        return {
            page: 1,
            total: 0,
            last: 0,
            comments: []
        }
    },
    props: {
        comment: Array
    },
    methods: {
        onClickButton(event) {
            this.$emit('clicked', this.comment.id)
        }
    }
}
</script>

<style scoped>

</style>
