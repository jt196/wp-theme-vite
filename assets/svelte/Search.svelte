<script>
  import Fa from "svelte-fa";
  import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";
  import Spinner from "./Spinner.svelte";
  let isDialogOpen = false;
  let dialog;
  let search = "";
  let spinnerVisible = false;
  let searchTimer;
  let searchResults = [];
  import { onMount } from "svelte";

  function handleKeydown(event) {
    if (event.key === "Escape" && isDialogOpen) {
      isDialogOpen = false;
      search = "";
      searchResults = "";
    }
  }

  async function handleSearch() {
    spinnerVisible = false;
    searchResults = await getSearchResults(search, postTypes);
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
    let promises = [];

    for (let type of postTypes) {
      // Create a fetch promise for each post type
      let promise = fetch(
        `${origin}/wp-json/wp/v2/${type}?search=${encodeURIComponent(searchString)}`
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          if (data.length === 0) {
            console.log(`No results found for type ${type}.`);
          }
          return data;
        })
        .catch((error) => {
          console.error(`Failed to fetch ${type}: `, error);
          return []; // Return an empty array in case of error to keep consistency in results structure
        });

      promises.push(promise);
    }

    // Wait for all promises to resolve
    const results = await Promise.all(promises);

    // Flatten the array of results (since each fetch returns an array)
    return results.flat();
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
    searchResults = "";
  }

  onMount(() => {
    dialog.addEventListener("close", () => {
      isDialogOpen = false;
      search = "";
      searchResults = "";
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
            {:else if searchResults.length > 0}
              {#each searchResults as event}
                <a href={event.link}>{event.title.rendered}</a>
                <hr />
              {/each}
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
  #search-results ul,
  #search-results li {
    display: block;
  }
</style>
