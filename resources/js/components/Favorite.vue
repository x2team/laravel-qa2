<template>
    <a  title="Click to mark as favorite question (Click again to undo)"
        v-bind:class="classes" @click.prevent="toggle">
        <i class="fas fa-star fa-3x"></i>
        <span class="favorites-count">{{ count }}</span>
    </a>
</template>

<script>
export default {
    props: ['question'],

    data(){
        return {
            isFavorited: this.question.is_favorited,
            count: this.question.favorites_count,
            // signedIn: false,
            id: this.question.id,
        }
    },

    computed: {
        classes() {
            return [
                'favorite', 'mt-2',
                !this.signedIn ? 'off' : (this.isFavorited ? 'favorited' : '')
            ]
        },
        endpoint() {
            return `/questions/${this.id}/favorites`;
        },
        // signedIn() {
        //     return window.Auth.signedIn; // bo dc, vi da su dung global: Vue.prototype.signedIn = window.Auth.signedIn
        // }
    },
    methods: {
        toggle (){
            if(!this.signedIn){
                this.$toast.warning("Please login to favorite this question", "Warning", {
                    timeOut: 3000,
                    position: 'bottomLeft'
                });
                return;
            }
            this.isFavorited ? this.destroy() : this.create()
        },
        destroy() {
            axios.delete(this.endpoint)
            .then(res => {
                this.$toast.warning("Thank you for feedback", "Success", {
                    timeOut: 3000,
                    position: 'bottomLeft'
                });

                this.count--;
                this.isFavorited = false;
            });
        },

        create() {
            axios.post(this.endpoint)
            .then(res => {
                this.$toast.success(res.data.message, "Success", {
                    timeOut: 3000,
                    position: 'bottomLeft'
                });
                this.count++;
                this.isFavorited = true;
            })
            .catch(err => {
                console.log(err.respose);
            });
            
        }
    }
}
</script>
