<template>
  <div>
    <div class="container-fluid">
      <div class="date-navigator">
        <span class="month-nav -left" @click="getPrevMonth"></span>
        <div class="today h2">
          {{ thisMonth }}
        </div>
        <span class="month-nav -right" @click="getNextMonth"></span>
      </div>

      <div class="date-days" v-if="monthDays.length">
        <ul class="list-unstyled">
          <li
            v-for="(day, index) in monthDays"
            :key="index"
            :class="getDayClasses(day)"
          >
            <span v-if="!day.hasEvent" class="day-disabled">{{ day.number }}</span>
            <a 
              v-else
              class="day-button"
              @click="filterDate(day.number)"
            >
              {{ day.number }}
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="container">
      <ul class="breadcrumbs">
        <li v-for="page in breadcrumbs" :key="page.title">
          <a v-if="page.url" :href="page.url">{{ page.title }}</a>
          <span class="breadcrumb-delimiter" v-if="page.url">/</span>
          <span v-else>{{ page.title }}</span>
        </li>
      </ul>
      
      <div class="filter-button" type="button" @click="showFilter">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 64 64"><path d="M50.69 32h5.63M7.68 32h31.01M26.54 15.97h29.78M7.68 15.97h6.88M35 48.03h21.32M7.68 48.03H23"/><circle cx="20.55" cy="15.66" r="6"/><circle cx="44.69" cy="32" r="6"/><circle cx="29" cy="48.03" r="6"/></svg>
      </div>

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

      <Teleport to="body">
        <events-filter 
        :filterOptions="filterOptions"
        :selectedFilters="filters"
        :isFilterShown="isFilterShown"
        :params="params"
        :labels="labels"
        @hideFilter="isFilterShown = false"
        @updateFilters="handleFiltersUpdate"
        />
      </Teleport>
    </div>

    <div class="container">
      <div v-if="isLoading">Loading...</div>
      <div v-else-if="events.length === 0">{{ noEventsText }}.</div>
      <div v-else>
        <div class="row">
          <div class="col-lg-6 mb-4" v-for="event in events" :key="event.id">
            <event-card 
              :title="event.title"
              :url="event.url"
              :picture="event.thumb"
              :date="new Date(event.start)"
              :dateEnd="new Date(event.end)"
              :color="event.color"
              :venue="event.venue"
              :type="event.type"
              :category="event.category"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EventCard from './EventCard.vue';
import EventsFilter from './EventsFilter.vue';

export default {
  components: {
    EventCard,
    EventsFilter
  },
  data() {
    return {
      params: {
        month: null,
        date: null,
        dateEnd: null,
        types: [],
        categories: [],
        venues: [],
        audiences: [],
        // searchQuery: null
      },
      events: [],
      isLoading: false,
      error: null,
      monthIndex: null,
      year: null,
      monthDays: [],
      isFilterShown: false,
      shouldChangeMonth: true,
      filters: {
        'categories': [],
        'venues': [],
        'types': [],
        'date': null,
        'dateEnd': null
      },
      filterOptions: {},
      // selectedTags: {},
      labels: {},
      breadcrumbs: [],
      noEventsText: 'No events available'
    };
  },
  methods: {
    getComponentSettings() {
      return new Promise(() => {
       
        const self = this
        $.request('onGetVars', {          
          success: function(data) {
            self.breadcrumbs = data.breadcrumbs
            self.noEventsText = data.noEventsText
            self.labels = {
              'filterTitle': data.filterTitle,
              'dateFilterTitle': data.dateFilterTitle,
              'thisWeek': data.thisWeek,
              'thisWeekend': data.thisWeekend
            }
          },
          error: function(err) {
          }
        })
      })
    },
    getEvents() {
      return new Promise(() => {
        this.getFeedStart()
        const self = this
        $.request('onGetEvents', {
          data: {
            params: self.params, // test push
            shouldChangeMonth: this.shouldChangeMonth ? true : false
          },
          success: function(data) {
            self.getFeedSuccess(data.events)
  
            if (self.shouldChangeMonth) {
              self.monthDays = data.daysData
              self.filterOptions = data.filters
              // console.log(self.monthDays)
            }
            self.shouldChangeMonth = false
          },
          error: function(err) {
            self.getFeedFailure(err)
          }
        })
      })
    },    
    getFeedStart() {
      this.isLoading = true
      this.events = []
    },
    getFeedSuccess(payload) {
      this.isLoading = false
      this.events = payload
    },
    getFeedFailure(err) {
      this.isLoading = false
      this.error = err
    },
    setParamsFromUrl() {
      const urlParams = new URLSearchParams(window.location.search);
      const params = this.params
      urlParams.forEach((value, key) => {
        if (params.hasOwnProperty(key)) {
          if (Array.isArray(params[key])) {
            params[key] = value.split(',')
          } else {
            params[key] = value
          }
        }
      })
      this.params = params
    },
    updateUrlSearchParams() {
      const url = new URL(window.location);
      const params = this.params;
      const searchParams = new URLSearchParams();

      for (const [key, value] of Object.entries(params)) {
        if (value === null || value === undefined || value === '') {
          searchParams.delete(key);
          continue;
        }

        if (key === 'date') {
          searchParams.delete('month');
        }

        if (Array.isArray(value) && value.length === 0) {
          searchParams.delete(key);
          continue;
        }

        if (Array.isArray(value)) {
          searchParams.set(key, value.join(','));
        } else {
          searchParams.set(key, value);
        }
      }

      const searchString = Array.from(searchParams.entries())
        .map(([key, val]) => `${key}=${val}`)
        .join('&');

      url.search = searchString;
      window.history.replaceState({}, '', url.toString());
    },
    setMonthYear() {
      // console.log('params month ' + this.params.month)
      if (this.params.month && this.isValidMonth(this.params.month)) {
        const [year, month] = this.params.month.split('-').map(Number)
        this.year = year
        this.monthIndex = month - 1 // month index start is 0
      } else {
        const today = new Date()
        this.year = today.getFullYear()
        this.monthIndex = today.getMonth()
        this.params.month = this.formatMonth(this.year, this.monthIndex)
      }
    },
    isValidMonth(dateString) {
      // Regular expression to match YYYY-MM format
      const regex = /^\d{4}-(0[1-9]|1[0-2])$/;

      if (!regex.test(dateString)) {
        return false;
      }

      const [year, month] = dateString.split('-').map(Number);
      if (year < 2023 || year > 3000) {
        return false;
      }

      return true;
    },
    formatMonth(year, monthIndex) {
      return `${year}-${String(monthIndex + 1).padStart(2, '0')}`
    },
    getPrevMonth() {
      this.monthIndex--
      this.params.date = null
      this.params.month = this.formatMonth(this.year, this.monthIndex)
      this.shouldChangeMonth = true
    },
    getNextMonth() {
      this.monthIndex++
      this.params.date = null
      this.params.month = this.formatMonth(this.year, this.monthIndex)
      this.shouldChangeMonth = true
    },
    filterDate(day) {
      this.params.date = this.formatMonth(this.year, this.monthIndex) + '-' + String(day).padStart(2, '0')
      this.params.dateEnd = this.formatMonth(this.year, this.monthIndex) + '-' + String(day).padStart(2, '0')
      this.filters.date = this.params.date
      this.filters.dateEnd = this.params.dateEnd
    },
    getDayClasses(day) {
      return {
        'is-weekend': day.isWeekend,
        'is-today': day.isToday,
        'is-tomorrow': day.isTomorrow
      }
    },
    removeOption(filterName, value) {
      if (filterName === 'dates') {
        // nullify all params
        Object.keys(this.filters).forEach(key => this.filters[key] = Array.isArray(this.filters[key]) ? [] : null)
        this.shouldChangeMonth = true
      } else {
        let selected = this.filters[filterName]
        selected.splice(selected.findIndex(item => item === value), 1)
      }
    },
    showFilter(e) {
      e.stopPropagation()
      this.isFilterShown = true
    },
    
    handleFiltersUpdate(newParams) {
      this.params = Object.keys(this.params).reduce((acc, key) => {
        if (newParams.hasOwnProperty(key)) {
          acc[key] = newParams[key];
        } else {
          acc[key] = this.params[key];
        }
        return acc;
      }, {});
    }
  },
  created() {
    this.getComponentSettings()
    this.setParamsFromUrl()
    this.setMonthYear()

    this.filters = Object.keys(this.filters).reduce((acc, key) => {
      if (this.params.hasOwnProperty(key)) {
        if (Array.isArray(this.params[key])) {
          acc[key] = this.params[key].map(v => parseInt(v, 10))
        } else {
          acc[key] = this.params[key]
        }
        
      } else {
        acc[key] = this.filters[key];
      }
      return acc;
    }, {});
  },
  computed: {
    thisMonth() {
      const month = String(this.monthIndex + 1).padStart(2, '0')
      return `${month}.${this.year}`
    },
    selectedTags() {
      const selected = this.filters
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
    params: {
      handler() {
        this.updateUrlSearchParams()
        this.getEvents()        
      },
      deep: true
    }
  },
};
</script>