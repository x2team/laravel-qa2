<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form class="card-body" v-show="authorize('modify', question) && editing" @submit.prevent="update">
                    <div class="card-title">
                        <input type="text" class="form-control form-control-lg" v-model="title">
                    </div>
                    
                    <hr>
                     
                    <div class="media"> 
                        <div class="media-body">
                            <div class="form-group">
                                <m-editor :body="body">
                                    <textarea v-model="body" rows="10" class="form-control" required></textarea>
                                </m-editor>
                            </div>
                            <button class="btn btn-primary" :disabled="isInvalid">Update</button>
                            <button class="btn btn-secondary" v-on:click.prevent="cancel">Cancel </button>
                        </div>
                    </div> 
                </form>

                <div class="card-body" v-show="!editing">
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{ title }}</h1>
                            <div class="ml-auto">
                                <a href="/questions" class="btn btn-outline-secondary">Back to all questions</a>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                     
                    <div class="media">

                        <vote :model="question" name="question"></vote>

                        <div class="media-body">
                            <div v-html="bodyHtml" ref="bodyHtml"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">
                                         <a v-if="authorize('modify', question)" @click.prevent="edit" class="btn btn-sm btn-outline-info">Edit</a>
                                        <button v-if="authorize('deleteQuestion', question)" @click="destroy" class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <user-info :model="question" label="Asked"></user-info>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { setTimeout } from 'timers';
import Vote from './Vote.vue';
import UserInfo from './UserInfo.vue';
import MEditor from './MEditor';
import modification from '../mixins/modification';


export default {
    props: ['question'],

    components: {
        Vote, UserInfo, MEditor
    },
    data(){
        return {
            title: this.question.title,
            body: this.question.body,
            bodyHtml: this.question.body_html,
            editing: false,
            id: this.question.id,
            beforeEditCache: {},
        }
    },

    computed: {
        isInvalid(){
            return this.body.length < 10 || this.title.length < 10;
        },
        endpoint (){
            return `/questions/${this.id}`;
        }
    },
    methods: {
        edit(){
            this.beforeEditCache = {
                body: this.body,
                title: this.title,
            };
            this.editing = true;
        },
        cancel (){
            this.body = this.beforeEditCache.body;
            this.title = this.beforeEditCache.title;
            this.editing = false;
            

        },
        update (){
            axios.put(this.endpoint, {
                body: this.body,
                title: this.title,
            })
            .then(({data}) => {
                this.bodyHtml = data.body_html;
                this.$toast.success(data.message, "Success", {timeout: 3000});
                this.editing = false;
            })
            .catch(({error}) => {
                this.$toast.error(error.data.message, "Error", { timeout: 3000 });
            })
        },
        destroy() {
            this.$toast.question('Are u sure?', 'Confirm', {
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                title: "Hey",
                
                position: "center",
                buttons: [
                [
                    "<button><b>YES</b></button>",
                    (instance, toast) => {
                        axios.delete(this.endpoint).
                        then(({data}) => {
                            // this.$emit('deleted');
                            this.$toast.success(data.message, "Success");
                        });

                        setTimeout(() => {
                            window.location.href = '/questions';
                        }, 3000);

                        instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                    },
                    true
                ],
                [
                    "<button>NO</button>",
                    function(instance, toast) {
                    instance.hide({ transitionOut: "fadeOut" }, toast, "button");
                    }
                ]
                ],
            });
        }
    }
}
</script>
