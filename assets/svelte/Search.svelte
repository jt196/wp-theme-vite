<script>
  import Fa from "svelte-fa";
  import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";
  import Spinner from "./Spinner.svelte";
  let isDialogOpen = false;
  let dialog;
  let search = "";
  let spinnerVisible = false;
  let searchTimer;
  let searchResults = resetSearch();
  import { onMount } from "svelte";

  function handleKeydown(event) {
    if (event.key === "Escape" && isDialogOpen) {
      isDialogOpen = false;
      search = "";
      searchResults = resetSearch();
    }
  }

  function resetSearch() {
    return {
      generalInfo: [],
      events: [],
      programs: [],
      professors: [],
    };
  }

  async function handleSearch() {
    spinnerVisible = false;
    searchResults = await getSearchResults(search, postTypes);
    console.log("🚀 ~ handleSearch ~ searchResults:", searchResults);
    console.log("search: ", search);
  }

  function searchTimerSet() {
    console.log("searchTimerSet fired: " + search);
    // Reset the timer
    clearTimeout(searchTimer);
    // Set a new timer if search is not empty
    if (search) {
      spinnerVisible = true;
      searchTimer = setTimeout(handleSearch, 750);
    } else {
      spinnerVisible = false;
    }
  }

  async function getSearchResults(searchString, postTypes) {
    let origin = window.location.origin;
    let results = [];
    results = await fetch(
      `${origin}/wp-json/university/v1/search?term=${encodeURIComponent(searchString)}`
    );
    return results.json();
  }

  let postTypes = ["posts", "pages", "event"];

  function focus(node) {
    node.focus();
  }

  function openSearchDialog() {
    isDialogOpen = true;
    setTimeout(() => {
      const input = dialog.querySelector('input[name="search"]');
      if (input) {
        input.focus();
      }
    }, 0);
  }

  function closeSearchDialog() {
    isDialogOpen = false;
    search = "";
    searchResults = resetSearch();
  }

  onMount(() => {
    dialog.addEventListener("close", () => {
      isDialogOpen = false;
      search = "";
      searchResults = resetSearch();
    });
    document.addEventListener("keydown", handleKeydown);
    return () => {
      // Cleanup when the component is destroyed
      document.removeEventListener("keydown", handleKeydown);
    };
  });
</script>

<button on:click={openSearchDialog}>
  <Fa icon={faMagnifyingGlass} />
</button>

<dialog bind:this={dialog} open={isDialogOpen}>
  <article>
    <div>
      <input
        type="text"
        name="search"
        id="search-input"
        bind:value={search}
        on:input={searchTimerSet}
        placeholder="Search events..."
        aria-label="Search"
        use:focus={isDialogOpen}
      />
      <div>
        <div id="search-results">
          <ul>
            {#if search && spinnerVisible}
              <Spinner />
            {:else if searchResults.generalInfo.length > 0 || searchResults.events.length > 0 || searchResults.programs.length > 0 || searchResults.professors.length > 0}
              <div class="grid">
                {#if searchResults.generalInfo.length > 0}
                  <div>
                    <h1>Pages</h1>
                    {#each searchResults.generalInfo as post}
                      <a href={post.permalink}>{post.title}</a>
                      <span>by {post.authorName}</span>
                      <hr />
                    {/each}
                  </div>
                {/if}
                {#if searchResults.events.length > 0}
                  <div class="event-summary">
                    <h1>Events</h1>
                    {#each searchResults.events as event}
                      <a
                        class="event-summary__date t-center"
                        href={event.permalink}
                      >
                        <span class="event-summary__month">{event.month}</span>
                        <span class="event-summary__day">{event.day}</span>
                      </a>
                      <div class="event-summary__content">
                        <h5
                          class="event-summary__title headline headline--tiny"
                        >
                          <a href={event.permalink}>{event.title}</a>
                        </h5>
                        <p>{event.description}</p>
                      </div>
                      <hr />
                    {/each}
                  </div>
                {/if}
                {#if searchResults.professors.length > 0}
                  <div>
                    <h1>Professors</h1>
                    {#each searchResults.professors as professor}
                      <a href={professor.permalink}>{professor.title}</a>
                      <img
                        src={professor.image}
                        alt="{professor.title} image"
                      />
                      <hr />
                    {/each}
                  </div>
                {/if}
                {#if searchResults.programs.length > 0}
                  <div>
                    <h1>Programs</h1>
                    {#each searchResults.programs as program}
                      <a href={program.permalink}>{program.title}</a>
                      <hr />
                    {/each}
                  </div>
                {/if}
              </div>
            {:else if search && !spinnerVisible}
              <p>No events found for your search.</p>
            {/if}
          </ul>
        </div>
      </div>
      <footer>
        <div>
          <button on:click={closeSearchDialog} class="secondary">Close</button>
        </div>
      </footer>
    </div>
  </article>
</dialog>

<style>
  #search-results ul {
    display: block;
    margin: 0;
  }
  #search-input {
    margin-bottom: 1rem;
  }

  dialog article {
    max-width: 900px;
  }
</style>
