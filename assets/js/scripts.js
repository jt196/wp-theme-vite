import "@picocss/pico";
import Slider from "../svelte/Slider.svelte";

document.addEventListener("DOMContentLoaded", function () {
  // Handler when the DOM is fully loaded
  console.log("js executed...");
});

const slider = new Slider({
  target: document.getElementById("svelte-slider"),
  props: {
    duration: 3000,
    slides: [
      "wp-content/themes/wp-theme-vite/assets/img/apples.jpg",
      "wp-content/themes/wp-theme-vite/assets/img/bread.jpg",
      "wp-content/themes/wp-theme-vite/assets/img/bus.jpg",
    ],
  },
});
