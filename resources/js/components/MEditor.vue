<template>
    <div class="card text-center">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#write">Write</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#preview">Preview</a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            <div class="tab-pane active" id="write">
                <slot></slot>
            </div>
            
            <div class="tab-pane" v-html="preview" id="preview"></div>
        </div>
    </div>
</template>

<script>

import MarkdownIt from 'markdown-it';
import autosize from 'autosize';
import prism from 'markdown-it-prism';
// import 'prismjs/themes/prism.css';

const md = new MarkdownIt();
md.use(prism);

export default {
    props: ['body'],

    computed: {
        preview() {
            //MarkdownIt
            return md.render(this.body);
        }
    },

    // watch: {
    //     body: function (){
    //         console.log('watch body');
    //     }
    // },
    
    //Autosize
    // mounted() {
    //     autosize(this.$el.querySelectorAll('textarea'));
    // },
    updated() {
        autosize(this.$el.querySelectorAll('textarea'));
    },


    data() {
        return {

        }
    }
}
</script>
