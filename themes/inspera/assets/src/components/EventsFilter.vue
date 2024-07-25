<template>
  <div>  
    <ul class="filter-tags" v-if="selectedTags.length">
      <li
        v-for="tag in selectedTags"
        :key="tag.value"
        class="filter-tag"
        @click="removeOption(tag.filter, tag.value)"
      >
        {{ tag.label }}
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.5 0 25 25"><path stroke="currenColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/></svg>
      </li>
    </ul>

    <aside class="side-widget -filter" :class="isFilterShown && 'active'">

      <div 
        @click="$emit('hideFilter')" 
        class="close-filter-button"
      >
        <span>Filter</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="-0.5 0 25 25"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 21.32 18-18M3 3.32l18 18"/></svg>  
      </div>

      <svg class="animated-overlay" viewBox="0 0 600 1080" preserveAspectRatio="none" version="1.1">
        <path d="M540,1080H0V0h540c0,179.85,0,359.7,0,539.54C540,719.7,540,899.85,540,1080z"></path>
      </svg>
          
      <div class="filter-inner">
        <h2 class="h4">Filter</h2>
        <div class="filter-group">
          <h3 class="h5">
            Filter Date
          </h3>
          
          <div class="filter-option">
            <label>
              <input
                type="radio"
                name="dates"
                :value="`${thisWeek.start} - ${thisWeek.end}`"
                @change="setDates(thisWeek.start, thisWeek.end)"
              />
              This week
            </label>
          </div>

          <div class="filter-option">
            <label>
              <input
                type="radio"
                name="dates"
                :value="`${weekend.start} - ${weekend.end}`"
                @change="setDates(weekend.start, weekend.end)"
              />
              This weekend
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
  </div>
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
  computed: {
    selectedTags() {
      const selected = this.selectedFilters
      let dateTags = []

      if (this.params.date) {
        const end = this.params.dateEnd?.length ? this.params.dateEnd : this.params.date
        const dateLabel = this.params.date === end ? this.params.date : `${this.params.date} - ${end}`
        
        dateTags.push({
          filter: 'dates',
          label: dateLabel,
          value: null
        })
      }
      
      return Object.keys(this.filterOptions).reduce((acc, key) => {
        const options = this.filterOptions[key].options

        if (selected.hasOwnProperty(key) && selected[key].length > 0) { // is not empty
          options
            .filter(o => { // likely there will be only arrays of selected
              return selected[key].includes(o.value)
            })
            .forEach(o => {
              acc.push({
                filter: key,
                label: o.label,
                value: o.value
              })
            })
        }
        return acc;
      }, dateTags);
    }
  },
  watch: {
    selectedFilters: {
      handler(newFilters) {
        // console.log(newFilters);
        this.$emit('updateFilters', newFilters)
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    removeOption(filterName, value) {
      if (filterName === 'dates') {
        this.selectedFilters['date'] = null
        this.selectedFilters['dateEnd'] = null
      } else {
        let selected = this.selectedFilters[filterName]
        selected.splice(selected.findIndex(item => item === value), 1)
      }
    },
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
    }
  },
  mounted() {
    // console.log(this.filterOptions)
  }
};
</script>