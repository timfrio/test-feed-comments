<template>
    <div class="modal" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <form class="needs-validation" novalidate="">
                        <div class="row g-3">

                            <div class="col-6">

                                <label for="name" class="form-label">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{'is-invalid': errors.name}"
                                    id="name"
                                    required
                                    v-bind="name"
                                />
                                <div class="invalid-feedback" v-text="errors.name"/>

                            </div>

                            <div class="col-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" :class="{'is-invalid': errors.email}" id="email" placeholder="you@example.com" v-bind="email">
                                <div class="invalid-feedback" v-text="errors.email"/>
                            </div>

                            <div class="col-12">
                                <label for="homepage" class="form-label">Homepage <span class="text-body-secondary">(Optional)</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{'is-invalid': errors.homepage}"
                                    id="homepage"
                                    placeholder="http://www.where.com/"
                                    v-model="homepage"
                                />
                                <div class="invalid-feedback">
                                    Please enter your homepage.
                                </div>
                            </div>


                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="text" class="form-label">Text</label>
                            <textarea class="form-control" :class="{'is-invalid': errors.text}" id="text" rows="3" v-bind="text"/>
                            <div class="invalid-feedback" v-text="errors.text"/>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="file" class="form-label">File</label>
                            <input class="form-control" type="file" id="file" ref="file" accept="image/png, image/gif, image/jpeg, .txt" v-on:change="handleFileUpload()">
                        </div>

                        <hr class="my-4">

                        <div class="form-group mt-4 mb-4">
                            <div class="captcha">
                                <img :src="captcha.image"/>
                                <button type="button" class="btn btn-danger" v-on:click="reload_captcha">
                                    &#x21bb;
                                </button>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <input id="captcha" type="text" class="form-control" :class="{'is-invalid': errors.captcha}" placeholder="Enter Captcha" name="captcha" v-bind="captcha.text">
                            <div class="invalid-feedback">
                                Not valid.
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" v-on:click="sendComment" :disabled="!isDisabled">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../include/api';
import FormData from 'form-data';
import isUrlHttp from 'is-url-http';
import { useForm } from 'vee-validate';
import * as yup from 'yup';

export default {
    name: 'CommentForm',
    props: {
        parentId: {
            default: 0,
            type: Number
        }
    },
    data() {
        return {
            name: null,
            email: null,
            homepage: null,
            text: null,
            file: null,
            captcha: {
                img: null,
                text: null,
                key: null
            },
            values: {},
            errors: {}
        }
    },
    computed: {
        isDisabled() {
            let {name, email, text, captcha} = this.values;
            return !!name && !!email && !!text && !!captcha;
        }
    },
    mounted() {
        const { values, errors, defineInputBinds } = useForm({
            validationSchema: yup.object({
                name: yup.string().required(),
                email: yup.string().email().required(),
                text: yup.string().required(),
                captcha: yup.string().required()
            }),
        });
        this.name = defineInputBinds('name');
        this.email = defineInputBinds('email');
        this.text = defineInputBinds('text');
        this.captcha.text = defineInputBinds('captcha');
        this.values = values;
        this.errors = errors;
        this.reload_captcha();
    },
    methods: {
        reload_captcha() {
            api.get('/captcha/api').then(({data}) => {
                this.captcha.image = data.img;
                this.captcha.key = data.key;
                this.captcha.text.value = null;
            })
        },
        sendComment: function () {
            let data = new FormData();

            if (this.parentId) {
                data.append('parent', this.parentId);
            }
            data.append('name', this.values.name);
            data.append('email', this.values.email);
            if (this.homepage) {
                if (isUrlHttp(this.homepage)) {
                    data.append('homepage', this.homepage);
                } else {
                    return this.errors.homepage = true;
                }
            }
            data.append('text', this.values.text);
            if (this.file) {
                data.append('file', this.file);
            }
            data.append('captcha', this.values.captcha);
            data.append('key', this.captcha.key);

            api.post('/api/comment/send-comment', data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if (response.status === 200) {
                    document.querySelector('#myModal').style.display = "none";
                    document.querySelector('.modal-backdrop').remove();
                    this.clearData();
                }
            }).catch(({response}) => {
                for (const [errorSection, errorData] of Object.entries(response.data.errors)) {
                    this.errors[errorSection] = errorData[0];
                }
            });
            this.reload_captcha();
        },
        clearData() {
            this.values.name = null;
            this.values.email = null;
            this.homepage = null
            this.values.text = null
            this.file = null
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        }
    }
}
</script>

<style scoped>

</style>
