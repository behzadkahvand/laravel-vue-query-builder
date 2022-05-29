<script lang="ts">

import {defineComponent} from "vue";
import GroupComponent from './queryBuilder/GroupComponent.vue'
import {GroupQuery, QueryBuilderOptions, RuleQuery} from "../types";

const options = {
  keys: [{
    name: 'ID',
    id: 'id'
  }, {
    name: 'Title',
    id: 'title'
  }, {
    name: 'Views',
    id: 'views'
  }, {
    name: 'Timestamp',
    id: 'timestamp'
  }],

  relationalOperators: [{
    name: 'Greater than',
    id: 'GREATER_THAN'
  }, {
    name: 'Equal',
    id: 'EQUAL'
  }, {
    name: 'Less than',
    id: 'LESS_THAN'
  }],

  logicalOperators: [
    {id: "", name: "", operandCount: 1},
    {id: "AND", name: "AND", operandCount: 2},
    {id: "OR", name: "OR", operandCount: 2},
    {id: "NOT", name: "NOT", operandCount: 1},
  ]
}

export default defineComponent({
  components: {
    GroupComponent
  },
  data() {
    return {
      options: options as QueryBuilderOptions,
      isFirst: true,
      fetchedQuery: {},
    }
  },

  methods: {

    fetchQuery(): void {
      this.fetchedQuery = this.$refs.GroupComponent.queryFormStatus();
    },

    getFinalQuery(): string {
      this.fetchQuery()
      return this.compileQuery();
    },

    getSubGroupQuery: function (node: GroupQuery): string {
      switch (node.condition) {
        case "":
          return stack.pop()
        case "NOT":
          return `${node.condition}(${stack.pop()})`
        default:
          let fo = stack.pop()
          let lo = stack.pop()
          return node.condition + "(" + lo + "," + fo + ")"
      }
    },

    compileQuery(): string {
      let node = {}
      let query = ""

      for (node of postOrderTraverse(this.fetchedQuery)) {
        if (node.condition !== undefined) {
          query = this.getSubGroupQuery(node);
        } else {
          query = node.operator + "(" + node.key + "," + node.value + ")"
        }

        if (query.includes("undefined") || query.includes("-99")) {
          alert("The query filter is not valid!")
          return "";
        }

        stack.push(query)
      }

      return stack.pop()
    },

    resetFilter(): void {
      this.$refs.GroupComponent.groups = [];
      this.$refs.GroupComponent.rules = [];
    }

  }

})

let stack = [] as string[]

function* postOrderTraverse(node: (RuleQuery | GroupQuery)): Generator {
  let rules = node.rules || []

  for (let child of rules) {
    yield* postOrderTraverse(child)
  }

  yield node
}

</script>


<template>
  <div class="mb-12">
    <div class="block p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
      <group-component :options="options" :isFirst="isFirst" ref="GroupComponent"></group-component>

      <div class="mt-6">
        <button @click="$emit('getPosts', this.getFinalQuery())"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">Search
        </button>
      </div>
    </div>
  </div>
</template>
