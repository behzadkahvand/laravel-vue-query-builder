<script setup lang="ts">

import PageComponent from "../components/PageComponent.vue";
import {Router, useRoute, useRouter} from "vue-router";
import {useStore} from "vuex";
import {ref} from "vue";
import {PostInterface} from "../types";

const router: Router = useRouter();
const store = useStore();

const route = useRoute()

let post = ref({
  id: "",
  title: "",
  views: 0,
  content: "",
  timestamp: 0
});

let isUpdate = false

if (route.params.id) {
  isUpdate = true
  let temp = store.state.postList?.data?.find(
    (p: PostInterface) => p.id === route.params.id
  )
  if (temp) {
    post.value = temp
  } else {
    getPost(route.params.id)
  }
}

let errorMessages = ref("");

function savePost(): void {
  post.value.timestamp = +new Date();
  store.dispatch('savePost', {post: post.value, isUpdate: isUpdate}).then(() => {
    router.push({
      name: 'listPosts'
    })
  }).catch((err: any) => {
    errorMessages.value = err.response.data.errors
  })
}

function getPost(id: string): void {
  store.dispatch('getPost', id).then((res) => {
    post.value = res.data.results
  }).catch((err: any) => {
    errorMessages.value = err.response.data.errors
  })
}

</script>

<template>

  <page-component :title="isUpdate ? 'Update #'+post.id :'Create a post'">

    <div class="mt-10 sm:mt-0">

      <div v-if="errorMessages" id="alert-2" class="flex p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200" role="alert">

        <div v-for="(message, index) in errorMessages" :key="index"
             class="ml-12 text-sm font-medium text-red-700 dark:text-red-800">

          <svg class="flex-shrink-0 w-5 h-5 text-red-700 dark:text-red-800 float-left gap-1" fill="currentColor"
               viewBox="0 0 20 20"
               xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                  clip-rule="evenodd"></path>
          </svg>

          {{ message[0] }}

        </div>

        <button @click.prevent="errorMessages=''" type="button"
                class="ml-auto -mx-1.5 -my-1.5 bg-red-100 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex h-8 w-8 dark:bg-red-200 dark:text-red-600 dark:hover:bg-red-300"
                data-dismiss-target="#alert-2" aria-label="Close">
          <span class="sr-only">Close</span>
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
          </svg>
        </button>

      </div>


      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="col-span-3">
          <form @submit.prevent="savePost">
            <div class="shadow overflow-hidden sm:rounded-md">
              <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-6 gap-6">

                  <div class="col-span-6 sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Post ID</label>
                    <input type="text" v-model="post.id" :disabled="isUpdate"
                           :class="'mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md' + (isUpdate ? 'opacity-50 cursor-not-allowed' :'') ">
                  </div>

                  <div class="col-span-6 sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" v-model="post.title"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                  </div>

                  <div class="col-span-6 sm:col-span-3">
                    <label for="views" class="block text-sm font-medium text-gray-700">Views count</label>
                    <input type="text" v-model="post.views"
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                  </div>


                  <div class="col-span-6 sm:col-span-3">
                    <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                    <div class="mt-1">
                    <textarea v-model="post.content" rows="3"
                              class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"/>
                    </div>
                  </div>

                </div>
              </div>
              <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  Save
                </button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>

  </page-component>

</template>

