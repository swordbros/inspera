<template>
  <div class="event-card -hidden">
    <div class="event-card-type">
      {{ category }}
    </div>
    <div class="event-card-date">
      <time :datetime="startDay" class="h4 event-card-day">
        {{ startDay }}
      </time>
      <span class="h4 event-card-day" v-if="null != endDay">  -</span>
      <time :datetime="endDay" class="h4 event-card-day" v-if="endDay">
        {{ endDay }}
      </time>
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
          <img
            class="img-fluid img"
            v-lazy="picture"
            :alt="title"
          >
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
      <small
        v-if="type"
        class="event-card-category mb-4"
        :style="`background-color: `+color"
      >
        {{ type }}
      </small>
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
    dateEnd: {
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
    startDay() {      
      const day = String(this.date.getDate()).padStart(2, '0')
      const month = String(this.date.getMonth() + 1).padStart(2, '0')
      return `${day}.${month}`
    },
    endDay() {      
      if (this.isLastingEvent()) {
        return String(this.dateEnd.getDate()).padStart(2, '0') + '.' + String(this.dateEnd.getMonth() + 1).padStart(2, '0')
      }
      return null;
    }
  },
  methods: {
    isLastingEvent() {
      return this.dateEnd.getDate() !== this.date.getDate()
    }
  },
  mounted() {
    // console.log(this.dateEnd)
  }
};
</script>
