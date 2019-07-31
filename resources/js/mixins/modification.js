import highlight from './highlight.js';


export default {
    mixins: [highlight],

    data (){
        return {
            editing: false,

        }
    },

    methods: {
        edit(){
            this.setEditCache();
            this.editing = true;
        },
        cancel (){
            this.restoreFromCache();
            this.editing = false;
        },

        setEditCache() {},
        restoreFromCache() {},

        update (){
            axios.put(this.endpoint, this.payLoad())
            .then(({data}) => {
                this.bodyHtml = data.body_html;
                this.$toast.success(data.message, "Success", {timeout: 3000});
                this.editing = false;
            })
            .then(() => this.highlight()) //su dung khi sai highlight
            .catch(({error}) => {
                this.$toast.error(error.data.message, "Error", { timeout: 3000 });
            })
        },

        payLoad() { },

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
                        
                        this.delete();

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