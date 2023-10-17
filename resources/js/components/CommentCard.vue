<template>
    <div class="card mb-1">
        <div class="card-header">

            <div class="d-grid gap-2 d-md-flex justify-content-between align-items-center">

                <span class="h6 mb-0">{{ comment.name }} {{ comment.created_at }}</span>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <template v-if="attachmentIs()">
                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                v-on:click="showImage()"
                        >
                            <font-awesome-icon icon="fa-solid fa-file-image" class="icon"/>
                        </button>
                    </template>

                    <template v-if="attachmentIs('lines')">
                        <a :href="`/storage/${comment.attachment_storage_url}`"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary"
                        >
                            <font-awesome-icon icon="fa-solid fa-file-lines" class="icon"/>
                        </a>
                    </template>

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
import PhotoSwipe from 'photoswipe';
import 'photoswipe/style.css';

export default {
    name: 'CommentCard',
    data() {
        return {
            page: 1,
            total: 0,
            last: 0,
            comments: [],
            attachmentAllowExtension: {
                image: ['.png', '.gif', '.jpg'],
                lines: ['.txt'],
            }
        }
    },
    props: {
        comment: Array
    },
    methods: {
        onClickButton(event) {
            this.$emit('clicked', this.comment.id)
        },
        attachmentIs(type = 'image') {
            return this.comment.attachment_storage_url ?
                this.attachmentAllowExtension[type]
                    .map(ex => this.comment.attachment_storage_url.includes(ex))
                    .includes(true) : false;
        },
        showImage() {
            const ps = new PhotoSwipe({
                dataSource: [
                    {
                        src: `/storage/${this.comment.attachment_storage_url}`,
                        width: 320,
                        height: 240
                    },
                ],
            });
            ps.init();
        }
    }
}
</script>

<style scoped>

</style>
