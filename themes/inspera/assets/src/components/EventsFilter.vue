<template>
  <aside class="side-widget -right active">
    <h4>Filter</h4>
    <div v-for="(group, groupName) in filters" :key="groupName" class="filter-group">
      <h3>{{ group.title }}</h3>
      <div v-for="option in group.options" :key="option.value" class="filter-option">
        <label>
          <input
            type="checkbox"
            :value="option.value"
            v-model="selectedFilters[groupName]"
          />
          {{ option.label }}
        </label>
      </div>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'FilterComponent',
  props: {
    params: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      selectedFilters: {
        venue: [],
        category: []
      },
      filters: {
        venue: {
          title: 'Filter Venue',
          options: [
            { value: 1, label: 'Area 1' },
            { value: 2, label: 'Area 2' },
            { value: 3, label: 'Area 3' },
          ]
        },
        category: {
          options: [
            { value: 1, label: 'Category 1' },
            { value: 2, label: 'Category 2' },
            { value: 3, label: 'Category 3' },
          ]
        }
      }
    };
  },
  watch: {
    selectedFilters: {
      handler(newFilters) {
        // This function is called every time selectedFilters change
        // You can handle your filtering logic here
        console.log(newFilters);
      },
      deep: true
    }
  }
};
</script>