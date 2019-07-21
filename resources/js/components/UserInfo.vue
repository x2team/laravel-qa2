<template>
  <div class>
    <span class="text-muted">{{ postDate }}</span>
    <div class="media mt-2">
      <a v-bind:href="user.url" class="pr-2">
        <img v-bind:src="user.avatar" alt />
      </a>
      <div class="media-body mt-1">
        <a v-bind:href="user.url">{{ user.name }}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: ['model', 'label'],
    // <user-info model="..." label=""></user-info>
    computed: {
        postDate (){
            return this.label + " " + this.model.created_date;
        }
    },
    data(){
        return {
            user: this.model.user,
        }
    }
}
</script>

