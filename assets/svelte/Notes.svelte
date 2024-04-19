<script>
  import { onMount } from "svelte";
  import Spinner from "./Spinner.svelte";

  let spinnerVisible = false;

  let notes = [];
  let error = null;

  onMount(async () => {
    try {
      const response = await fetch(
        `${wpApiSettings.siteUrl}/wp-json/custom/v1/my-notes/`,
        {
          method: "GET",
          headers: {
            "X-WP-Nonce": wpApiSettings.nonce,
          },
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      notes = await response.json();
      console.log("🚀 ~ onMount ~ notes:", notes);
    } catch (e) {
      error = e.message;
    } finally {
      spinnerVisible = false; // Hide the spinner whether the fetch was successful or failed
    }
  });

  function deleteNote(noteId) {
    notes = notes.filter((note) => note.id !== noteId);
  }
</script>

{#if spinnerVisible}
  <ul>
    <hr />
    <Spinner />
    <hr />
  </ul>
{:else if error}
  <p style="color: red;">Error: {error}</p>
{:else}
  <ul>
    <hr />
    {#each notes as note (note.id)}
      <button on:click={() => deleteNote(note.id)}>Delete</button>
      <button>Edit</button>
      <h3>{note.title}</h3>
      <p>{note.content}</p>
      <hr />
    {/each}
  </ul>
{/if}
