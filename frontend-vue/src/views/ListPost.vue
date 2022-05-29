<script lang="ts">
import {defineComponent} from 'vue'
import store from "../store";
import PageComponent from "../components/PageComponent.vue";
import {PostInterface} from "../types";
import {mapState} from "vuex";
import PostTableComponent from "../components/PostTableComponent.vue";
import PostQueryBuilder from "../components/PostQueryBuilder.vue";

export default defineComponent({
  components: {PostQueryBuilder, PostTableComponent, PageComponent},
  data() {
    const headers = [
      "ID", "Title", "Views", "Update time"
    ]
    return {
      headers,
    }
  },

  computed: {
    ...mapState(['postList'])
  },

  methods: {
    deletePost(post: PostInterface): void {
      if (confirm("Are you sure you want to delete this post ?")) {
        store.dispatch('deletePost', post.id).then(() => {
          this.getPosts()
        })
      }
    },
    getPosts(queryParam = ""): void {
      store.dispatch('getPosts', queryParam)
    }
  },
  mounted(): void {
    this.getPosts()
  }
})

</script>

<template>

  <page-component title="Posts">

    <post-query-builder @get-posts="getPosts"></post-query-builder>

    <post-table-component :headers="headers" :post-list="postList" @delete="deletePost"></post-table-component>

  </page-component>

</template>

