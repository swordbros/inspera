import { createApp } from 'vue';
import EventsList from './components/EventsList.vue';
import ClickOutside from './helpers/ClickOutsideDirective.js';
import VueLazyload from 'vue-lazyload';

const app = createApp({
  template: '<EventsList/>',
  components: {
    EventsList
  }
});

app.directive('click-outside', ClickOutside);
app.mount('#events-list');
app.use(VueLazyload, {
  preLoad: 1.3,
  // error: 'path/to/error-image.png',
  // loading: 'path/to/loading-image.gif',
  attempt: 1,
  observer: true,
  observerOptions: {
    rootMargin: '0px',
    threshold: 0.1
  }
});