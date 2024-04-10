import "@picocss/pico";
import HelloWorld from "../svelte/HelloWorld.svelte";

document.addEventListener("DOMContentLoaded", function () {
  // Handler when the DOM is fully loaded
  console.log("js executed...");
});

const app = new HelloWorld({
  target: document.getElementById("svelte-root"),
});
