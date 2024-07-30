import { createApp } from 'vue';
import EventsList from './components/EventsList.vue';
import ClickOutside from './helpers/ClickOutsideDirective.js';

const app = createApp({
  template: '<EventsList/>',
  components: {
    EventsList
  }
});

app.directive('click-outside', ClickOutside);
app.mount('#events-list');