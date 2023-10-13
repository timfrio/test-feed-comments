<template>

    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><button type="button" v-on:click="otherComment(0)" class="nav-link px-2 text-white">Home</button></li>
                </ul>

                <div class="text-end">
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#myModal">New comment</button>
                </div>
            </div>
        </div>
    </header>


    <div class="container">

        <h1>test-feed-comments</h1>

        <template v-if="parent.id">

            <CommentCard
                v-model:comment="parent"
                @clicked="otherComment"
            />

        </template>

        <template v-if="current.id">

            <CommentCard
                class="border-dark"
                :class="{'ms-3': parent.id}"
                v-model:comment="current"
                @clicked="otherComment"
            />

        </template>


        <template v-for="comment in comments">

            <CommentCard
                :class="{
                    'ms-3': current.id && !parent.id,
                    'ms-5': parent.id
                }"
                :comment="comment"
                @clicked="otherComment"
            />

        </template>

        <pagination v-model="page" :records="total" :per-page="25" @paginate="open"/>

        <CommentForm v-model:parent-id="parent.id" />

    </div>

</template>

<script>
import api from '../include/api';
import CommentForm from '../components/CommentForm.vue';
import CommentCard from '../components/CommentCard.vue';

export default {
    name: 'App',
    components: {
        CommentForm,
        CommentCard
    },
    data() {
        return {
            page: 1,
            last: 0,
            total: 0,
            comments: [],
            parent: {
                id: 0,
                name: '',
                text: '',
            },
            current: {
                id: 0,
                name: '',
                text: ''
            }
        }
    },
    created() {

    },
    mounted() {
        this.open();
    },
    methods: {
        /**
         *
         * @param page
         */
        open(page = 1) {
            let inject = `${this.current.id > 0 ? this.current.id : ''}?page=${page}`;
            api.get(`/api/comment/${inject}`).then(response => {
                let {data, meta} = response.data;
                // has parent - only one lvl
                this.setParentData(data.parent);
                // current comment
                this.setCurrentData(data);
                // child comments
                this.setChildData(data.child);

                if (meta) {
                    this.comments = data;
                    this.total = meta.total;
                    this.last = meta.last_page;
                }

                this.page = page;
            });
        },
        otherComment(parent) {
            this.current.id = parent;
            this.open();
        },
        /**
         * @param parent
         */
        setParentData(parent) {
            if (parent) {
                let {id, name, text} = parent;
                this.parent.id = id;
                this.parent.name = name;
                this.parent.text = text;
            } else {
                this.parent.id = 0;
            }
        },
        /**
         *
         * @param current
         */
        setCurrentData(current) {
            let {name, text} = current;
            this.current.name = name;
            this.current.text = text;
        },
        /**
         *
         * @param child
         */
        setChildData(child) {
            if (child) {
                this.comments = child.data;
                this.total = child.total;
                this.last = child.last_child;
            }
        }
    }
}
</script>

<style lang="scss" scoped>
body {
    color: red;
    h1 {
        color: green;
    }
}
</style>
