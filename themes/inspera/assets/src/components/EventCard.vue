<template>
  <div class="event-card">
    <div class="event-card-type">
      {{ type }}
    </div>
    <div class="event-card-date">
      <time :datetime="date" class="h4">
        {{ dayOfMonth }}
      </time><br>
      <span class="event-card-year">
        <i class="fa fa-calendar-alt"></i>
        <span>{{ year }}</span>
      </span><br>
      <span class="event-card-time">
        <i class="fa fa-clock"></i>
        <span>{{ time }}</span>
      </span>
    </div>

    <div class="event-card-info">
      <div class="event-card-thumb">
        <a :href="url">
          <img :src="picture" class="img-fluid">
        </a>
      </div>
      <h3 class="h5">
        <a :href="url">
          {{ title }}
        </a>
      </h3>
      <p class="event-card-venue">
        {{ venue }}
      </p>
      <small v-if="category" class="event-card-category mb-4">{{ category }}</small>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    },
    url: {
      type: String,
      required: true
    },
    picture: {
      type: String,
      required: true
    },
    date: {
      type: Date,
      required: true
    },
    color: {
      type: String,
      required: false
    },
    type: {
      type: String,
      required: true
    },
    category: {
      type: String,
      required: true
    },
    venue: {
      type: String,
      required: false
    },
  },
  computed: {
    year() {
      return this.date.getFullYear()
    },
    time() {
      const hours = String(this.date.getHours()).padStart(2, '0')
      const minutes = String(this.date.getMinutes()).padStart(2, '0')
      return `${hours}:${minutes}`;
    },
    dayOfMonth() {      
      const day = String(this.date.getDate()).padStart(2, '0');
      const month = String(this.date.getMonth() + 1).padStart(2, '0');
      return `${day}.${month}`;
    }
  },
  methods: {
    isLastingEvent(event) {
      // compare days of start and end
    },
  }
};
</script>
