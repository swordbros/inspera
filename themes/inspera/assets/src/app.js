import { createApp } from 'vue';
import EventsList from './components/EventsList.vue';

createApp({
  template: '<EventsList :noEventsText="noEventsText"/>',
  components: {
    EventsList
  },
  data() {
    return {
      noEventsText: 'No events available'
    }
  }
}).mount('#events-list');