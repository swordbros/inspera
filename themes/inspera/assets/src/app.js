import { createApp } from 'vue';
import EventsList from './components/EventsList.vue';

createApp({
  template: '<EventsList/>',
  components: {
    EventsList
  }
}).mount('#events-list');