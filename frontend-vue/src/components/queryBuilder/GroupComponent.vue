<script lang="ts">
import RuleComponent from './RuleComponent.vue'

import {defineComponent, PropType} from "vue";
import {GroupQuery, QueryBuilderOptions} from "../../types";

export default defineComponent({
  components: {
    RuleComponent
  },
  props: {
    options: {
      type: Object as PropType<QueryBuilderOptions>,
      required: true
    },
    isFirst: {
      type: Boolean,
      default: false
    }
  },

  data() {
    let logicalOperator = this.isFirst ? "" : "AND"

    return {
      logicalOperator,
      operandCount: 0,
      groups: [],
      rules: [],
    }
  },

  computed: {
    hasMaxOperand(): boolean {
      return this.operandCount >= this.getMaxOperandCount();
    },
  },

  watch: {
    logicalOperator(newOperator: string): void {
      if (newOperator === "" && !this.isFirst) {
        this.deleteSelf()
      } else if (this.operandCount > this.getMaxOperandCount()) {
        this.rules.pop()
        this.operandCount--
      }
    }
  },

  methods: {

    addRule(): void {
      this.operandCount++
      let id = this.generateId();
      this.rules.push(id);
    },

    addGroup(): void {
      this.operandCount++
      let id = this.generateId();
      this.groups.push(id);
    },

    deleteSelf(): void {
      this.$emit('delete-group');
    },

    deleteRule(index: string): void {
      this.operandCount--
      this.rules.splice(index, 1);
    },

    deleteGroup(index: string): void {
      this.operandCount--
      this.groups.splice(index, 1);
    },

    queryFormStatus(): GroupQuery {
      let query = {} as GroupQuery;
      let rules = this.$refs.rules || {};
      let groups = this.$refs.groups || {};
      let i, j;

      query.condition = this.logicalOperator;
      query.rules = [];
      for (i = 0; i < rules.length; i++) {
        query.rules.push(rules[i].queryFormStatus());
      }
      for (j = 0; j < groups.length; j++) {
        query.rules[query.rules.length] = groups[j].queryFormStatus();
      }
      return query;
    },

    generateId(): string {
      return 'xxxxxxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    },

    getMaxOperandCount(): number {
      let operator = this.options.logicalOperators.find((o) => o.id === this.logicalOperator)
      if (operator !== undefined) {
        return operator.operandCount
      } else {
        return 1
      }
    }
  }
})
</script>


<template>
  <div class="and-or-template" :class="isFirst ? 'and-or-first' : '' ">
    <div class="and-or-top block h-16">

      <div class="visible-md-inline-block float-left">
        <div class="mb-3 w-48">
          <select v-model="logicalOperator" class="form-select appearance-none block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat
      border border-solid border-gray-300
      rounded
      transition
      ease-in-out
      m-0
      focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
            <option v-for="operator in options.logicalOperators" :key="operator.id" :value="operator.id">
              {{ operator.name }}
            </option>
          </select>
        </div>
      </div>


      <div class="btn-and-or float-right">
        <button v-if="!isFirst" class="float-right mt-2 ml-3" @click.prevent="deleteSelf()">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
               stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </button>
        <button :disabled="hasMaxOperand"
                :class="'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right ml-3' + (hasMaxOperand ? ' cursor-not-allowed' : '')"
                @click.prevent="addGroup"> Add Group
        </button>
        <button :disabled="hasMaxOperand"
                :class="'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-right ml-3' + (hasMaxOperand ? ' cursor-not-allowed' : '')"
                @click.prevent="addRule"> Add Rule
        </button>
      </div>

    </div>

    <rule-component
      v-for="(rule, index) in rules" ref="rules"
      :options="options" :key="rule" @delete-rule="deleteRule(index)">
    </rule-component>

    <group-component
      class="and-or-offset"
      v-for="(group, index) in groups" ref="groups"
      :options="options" :key="group" @delete-group="deleteGroup(index)">
    </group-component>

  </div>
</template>


<style>
.and-or-top {
  padding: 0.75rem 1.25rem;
  margin-bottom: 0;
  background-color: rgba(0, 0, 0, .03);
  border-bottom: 1px solid rgba(0, 0, 0, .125);
}

.and-or-offset {
  margin: 25px;
  border-left: 2px solid #8bc34a;
}
</style>
