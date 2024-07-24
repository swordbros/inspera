<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="events.length === 0">No events available.</div>
    <div v-else>
      <div class="date-navigator">
        <span class="month-nav -left" @click="getPrevMonth"></span>
        <div class="today h2">
          {{ thisMonth }}
        </div>
        <span class="month-nav -right" @click="getNextMonth"></span>
      </div>
      <div class="date-days">
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
        // searchQuery: null
      },
      events: [],
      isLoading: false,
      error: null,
      monthIndex: null,
      year: null,
      monthDays: [],
      isFilterShown: false
    };
  },
  methods: {
    getEvents() {
      return new Promise(() => {
        this.getFeedStart()
        const self = this
        $.request('onGetEvents', {
          data: {
            params: self.params
          },
          success: function(data) {
            self.getFeedSuccess(data.events);
            self.monthDays = data.daysData.days
            // console.log(self.monthDays)
          },
          error: function(err) {
            self.getFeedFailure(err)
          }
        });
      });
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
    setMonthYear() {
      // console.log('params month ' + this.params.month)
      if (this.params.month && this.isValidMonth(this.params.month)) {
        const [year, month] = dateString.split('-').map(Number)
        this.year = year
        this.monthIndex = month - 1 // month index start is 0
      } else {
        var today = new Date();
        this.year = today.getFullYear()
        this.monthIndex = today.getMonth()
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
    formatMonth(year, month) {
      return '${year}-${month}'
    },
    getPrevMonth() {
      this.params.month = this.formatMonth(this.year, this.monthIndex - 1)
      this.updateUrlSearchParams()
      this.getEvents()
    },
    getNextMonth() {
      this.params.month = this.formatMonth(this.year, this.monthIndex + 1)
      this.updateUrlSearchParams()
      this.getEvents()
    },
    filterDate(day) {
      this.params.date = this.formatMonth(this.year, this.monthIndex - 1) + '-' + String(day).padStart(2, '0')
      this.updateUrlSearchParams()
      this.getEvents()
    },
    getDayClasses(day) {
      return {
        'is-weekend': day.isWeekend
      }
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
      const url = new URL(window.location)
      const searchParams = new URLSearchParams(url.search)
      const params = this.params

      for (const [key, value] of Object.entries(params)) {
        // if (value === null) {
        //   searchParams.delete(key)
        //   continue;
        // }

        if (Array.isArray(value)) {
          let currentValues = searchParams.get(key)
          if (currentValues) {
            currentValues = currentValues.split(',')

            // Toggle values: Add if not present, remove if present
            value.forEach(val => {
              const index = currentValues.indexOf(val)
              if (index === -1) {
                currentValues.push(val)
              } else {
                currentValues.splice(index, 1)
              }
            });

            searchParams.set(key, currentValues.join(','))
          } else {
            searchParams.set(key, value.join(','))
          }
        } else {
          searchParams.set(key, value)
        }
      }
      url.search = searchParams.toString()
      window.history.replaceState({}, '', url.toString())
    }
  },
  created() {
    this.setParamsFromUrl()
    this.setMonthYear()
    this.getEvents()
  },
  computed: {
    thisMonth() {
      const month = String(this.monthIndex + 1).padStart(2, '0')
      return `${month}.${this.year}`
    }
  }
};
</script>