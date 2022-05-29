<script lang="ts">
import {defineComponent, PropType} from "vue";
import {QueryBuilderOptions, RuleQuery} from "../../types";

export default defineComponent({
  props: {
    options: {
      type: Object as PropType<QueryBuilderOptions>,
      required: true
    }
  },
  watch: {
    'options.keys.options'(): void {
      this.key = '-99';
    },
    'options.conditions.options'(): void {
      this.condition = '-99';
    }
  },
  data() {
    return {
      key: '-99',
      operator: '-99',
      value: ''
    }
  },
  methods: {
    deleteSelf(): void {
      this.$emit('delete-rule');
    },

    queryFormStatus(): RuleQuery {
      return {
        key: this.key,
        operator: this.operator,
        value: this.value
      }
    }
  }
})
</script>


<template>
  <div class="and-or-rule mt-5">


    <div class="flex flex-wrap -mx-3">

      <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
        <select
          class="appearance-none block w-full bg-white-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
          id="grid-first-name" v-model="key">
          <option v-for="option in options.keys" :value="option.id">
            {{ option.name }}
          </option>
        </select>
      </div>

      <div class="w-full md:w-1/4 px-3">
        <select
          class="appearance-none block w-full bg-white-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
          v-model="operator">
          <option v-for="option in options.relationalOperators" :value="option.id">
            {{ option.name }}
          </option>
        </select>
      </div>

      <div class="w-full md:w-1/4 px-3">
        <input type="text"
               class="appearance-none block w-full bg-white-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
               v-model="value">
      </div>

      <button class="btn btn-xs btn-purple-outline btn-radius btn-purple-round" @click.prevent="deleteSelf()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"/>
        </svg>
      </button>

    </div>
  </div>
</template>


<style>

</style>
