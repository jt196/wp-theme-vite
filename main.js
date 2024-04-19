import Slider from "/assets/svelte/Slider.svelte";
import Search from "/assets/svelte/Search.svelte";
import Notes from "/assets/svelte/Notes.svelte";

// main entry point
// include your assets here

// get styles
import "./assets/css/styles.css";
import "@picocss/pico";

// get scripts
import "./assets/js/scripts.js";

if (document.getElementById("svelte-slider")) {
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
}

const search = new Search({
  target: document.getElementById("svelte-search"),
});

if (document.getElementById("svelte-notes")) {
  const notes = new Notes({
    target: document.getElementById("svelte-notes"),
  });
}
