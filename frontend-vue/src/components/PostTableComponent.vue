<script lang="ts">

import {defineComponent, PropType} from "vue";
import {PostListInterface} from "../types";

export default defineComponent({
  props: {
    headers: {
      type: Array,
      required: true
    },
    postList: {
      type: Object as PropType<PostListInterface>,
      required: true
    }
  },
  methods:{
    getDateTime(timestamp: string): string {
      return new Date(timestamp).toLocaleDateString("en-US")
    },
  }
})

</script>

<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
      <tr>
        <th v-for="(columnName, index) in headers" :key="index" scope="col" class="px-6 py-3">
          {{ columnName }}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(post,index) in postList.data" :key="post.id"
          :class="index === postList.data.length -1 ? 'odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700' :  'border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700'">

        <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
          {{ post.id }}
        </th>
        <td class="px-6 py-4">
          {{ post.title }}
        </td>
        <td class="px-6 py-4">
          {{ post.views }}
        </td>
        <td class="px-6 py-4">
          {{ getDateTime(post.timestamp) }}
        </td>
        <td class="px-6 py-4 text-right">
          <router-link :to="{name:'savePost',params: {id :post.id}}"
                       class="px-4 py-1 text-sm text-white bg-blue-400 rounded">Edit
          </router-link>
        </td>
        <td class="px-6 py-4 text-right">
          <a href="#" @click="$emit('delete', post)"
             class="px-4 py-1 text-sm text-white bg-red-400 rounded">Delete
          </a>
        </td>

      </tr>

      </tbody>
    </table>
  </div>

</template>
