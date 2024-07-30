<template>
  <aside
    class="side-widget -filter"
    :class="isFilterShown && 'active'"
    v-click-outside="hideFilter"
  >
    <div 
      @click="hideFilter" 
      class="close-filter-button"
    >
      <span>{{ labels.filterTitle }}</span>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.5 0 25 25"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/></svg>  
    </div>

    <svg class="animated-overlay" viewBox="0 0 600 1080" preserveAspectRatio="none" version="1.1">
      <path d="M540,1080H0V0h540c0,179.85,0,359.7,0,539.54C540,719.7,540,899.85,540,1080z"></path>
    </svg>
    <div class="filter-inner">
        
      <h2 class="h4">{{ labels.filterTitle }}</h2>
      <div class="filter-group">
        <h3 class="h5">
          {{ labels.dateFilterTitle }}
        </h3>
        
        <div class="filter-option">
          <label>
            <input
              type="radio"
              name="dates"
              :value="`${thisWeek.start} - ${thisWeek.end}`"
              @change="setDates(thisWeek.start, thisWeek.end)"
              :checked="selectedFilters['date'] === thisWeek.start && selectedFilters['dateEnd'] === thisWeek.end"
            />
            {{ labels.thisWeek }}
          </label>
        </div>

        <div class="filter-option">
          <label>
            <input
              type="radio"
              name="dates"
              :value="`${weekend.start} - ${weekend.end}`"
              @change="setDates(weekend.start, weekend.end)"
              :checked="selectedFilters['date'] === weekend.start && selectedFilters['dateEnd'] === weekend.end"
            />
            {{ labels.thisWeekend }}
          </label>
        </div>

      </div>
      <div
        v-for="(group, groupName) in filterOptions"
        :key="groupName"
        class="filter-group"
      >
        <h3 class="h5" v-if="group.hasOwnProperty('options') && group.options.length > 1">
          {{ group.title }}
        </h3>
        <div
          v-for="option in group.options"
          v-if="group.hasOwnProperty('options') && group.options.length > 1"
          :key="option.value"
          class="filter-option"
        >
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
    },
    selectedFilters: {
      type: Object,
      required: true
    },
    filterOptions: {
      type: Object,
      required: true
    },
    labels: {
      type: Object,
      required: true
    },
    isFilterShown: {
      type: Boolean,
      required: true
    }
  },
  data(){
    return {
      today: new Date().toISOString().split('T')[0],
      tomorrow: new Date(Date.now() + 86400000).toISOString().split('T')[0],
      thisWeek: this.getWeekStartAndEnd(),
      weekend: this.getWeekendDates()
    }
  },
  watch: {
    selectedFilters: {
      handler(newFilters) {
        // console.log(newFilters);
        this.$emit('updateFilters', newFilters)
      },
      deep: true,
      // immediate: true
    }
  },
  methods: {
    setDates(start, end) {
      this.selectedFilters['date'] = start
      this.selectedFilters['dateEnd'] = end
    },
    getWeekStartAndEnd() {
      const today = new Date()
      const dayOfWeek = today.getDay()
      const startOfWeek = new Date(today)
      const endOfWeek = new Date(today)

      const mondayOffset = (dayOfWeek === 0 ? 6 : dayOfWeek - 1)
      startOfWeek.setDate(today.getDate() - mondayOffset)
      endOfWeek.setDate(today.getDate() + (6 - mondayOffset))

      const formatDate = date => date.toISOString().split('T')[0]

      return {
        start: formatDate(startOfWeek),
        end: formatDate(endOfWeek)
      }
    },
    getWeekendDates() {
      return {
        start: new Date(Date.now() + (6 - (new Date().getDay() || 7)) * 86400000).toISOString().split('T')[0], 
        end: new Date(Date.now() + (7 - (new Date().getDay() || 7)) * 86400000).toISOString().split('T')[0] 
      }
    },
    hideFilter() {
      this.$emit('hideFilter')
    }
  },
  mounted() {
    // console.log(this.filterOptions)
  }
};
</script>