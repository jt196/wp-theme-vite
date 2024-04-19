<script>
  import { onMount } from "svelte";
  import Spinner from "./Spinner.svelte";
  import Fa from "svelte-fa";
  import {
    faCancel,
    faPlus,
    faFloppyDisk,
    faPen,
    faTrash,
    faXmark,
  } from "@fortawesome/free-solid-svg-icons";

  let spinnerVisible = false;
  let deletingNotes = new Set();
  let editingNotes = new Set();
  let creatingNewNote = false;
  let newNote = { title: "", content: "" };

  let dialogOpen = false;
  let noteToDelete = null;

  let notes = [];
  let error = null;

  onMount(fetchNotes);

  function openDeleteDialog(noteId) {
    noteToDelete = noteId;
    dialogOpen = true;
  }

  function closeDeleteDialog() {
    dialogOpen = false;
    noteToDelete = null;
  }

  async function confirmDelete() {
    if (noteToDelete !== null) {
      deleteNote(noteToDelete);
    }
    closeDeleteDialog();
  }

  async function fetchNotes() {
    spinnerVisible = true;
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
    } catch (e) {
      error = e.message;
    } finally {
      spinnerVisible = false;
    }
  }

  async function deleteNote(noteId) {
    deletingNotes.add(noteId);
    try {
      const response = await fetch(
        `${wpApiSettings.siteUrl}/wp-json/custom/v1/my-notes/${noteId}`,
        {
          method: "DELETE",
          headers: {
            "X-WP-Nonce": wpApiSettings.nonce,
          },
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      notes = notes.filter((note) => note.id !== noteId);
    } catch (e) {
      error = e.message;
    } finally {
      deletingNotes.delete(noteId);
    }
  }

  async function saveNote(noteId, title, content) {
    // Here you would typically make an API call to update the note
    try {
      const response = await fetch(
        `${wpApiSettings.siteUrl}/wp-json/custom/v1/my-notes/${noteId}`,
        {
          method: "POST", // Use POST or PUT as appropriate for your API
          headers: {
            "X-WP-Nonce": wpApiSettings.nonce,
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ title, content }),
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      // Update local note to reflect new title and content
      let updatedNotes = notes.map((note) =>
        note.id === noteId ? { ...note, title, content } : note
      );
      notes = updatedNotes;
    } catch (e) {
      error = e.message;
      console.error("Failed to save note:", e);
    } finally {
      editingNotes.delete(noteId);
    }
  }

  function editNote(noteId) {
    editingNotes.add(noteId);
    editingNotes = new Set(editingNotes); // Reassign to trigger reactivity
  }

  function createNewNote() {
    creatingNewNote = true;
    newNote = { title: "", content: "" };
    editingNotes.add("new"); // Use 'new' to differentiate from existing notes
  }

  async function saveNewNote() {
    spinnerVisible = true;
    try {
      const response = await fetch(
        `${wpApiSettings.siteUrl}/wp-json/custom/v1/my-notes/`,
        {
          method: "POST",
          headers: {
            "X-WP-Nonce": wpApiSettings.nonce,
            "Content-Type": "application/json",
          },
          body: JSON.stringify(newNote),
          credentials: "include",
        }
      );

      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

      const savedNote = await response.json();
      console.log("🚀 ~ saveNewNote ~ savedNote:", savedNote);
      notes = [savedNote, ...notes]; // Add the new note at the top of the list
    } catch (e) {
      error = e.message;
      console.error("Failed to save new note:", e);
    } finally {
      spinnerVisible = false;
      creatingNewNote = false;
      editingNotes.delete("new");
    }
  }

  function cancelNewNote() {
    creatingNewNote = false;
    editingNotes.delete("new");
  }

  function cancelEdit(noteId) {
    editingNotes.delete(noteId);
    editingNotes = new Set(editingNotes); // Reassign to trigger reactivity
  }
</script>

<div class="content">
  {#if spinnerVisible}
    <Spinner />
  {:else if error}
    <p style="color: red;">Error: {error}</p>
  {:else}
    <button on:click={createNewNote}><Fa icon={faPlus} /></button>
    <br />
    {#if creatingNewNote}
      <button on:click={saveNewNote}><Fa icon={faFloppyDisk} /></button>
      <button on:click={cancelNewNote}><Fa icon={faCancel} /></button>
      <input type="text" bind:value={newNote.title} placeholder="Enter title" />
      <textarea bind:value={newNote.content} placeholder="Enter content"
      ></textarea>
    {/if}
    <hr />
    {#each notes as note (note.id)}
      <div class="note">
        {#if deletingNotes.has(note.id)}
          <Spinner />
        {:else if editingNotes.has(note.id)}
          <button on:click={() => saveNote(note.id, note.title, note.content)}
            ><Fa icon={faFloppyDisk} /></button
          >
          <button on:click={() => cancelEdit(note.id)}
            ><Fa icon={faCancel} /></button
          >
          <input type="text" bind:value={note.title} />
          <textarea bind:value={note.content}></textarea>
        {:else}
          <button on:click={() => deleteNote(note.id)}
            ><Fa icon={faTrash} /></button
          >
          <button on:click={() => editNote(note.id)}><Fa icon={faPen} /></button
          >
          <h3>{note.title}</h3>
          <p>{note.content}</p>
        {/if}
        <hr />
      </div>
    {/each}
  {/if}
</div>

{#if dialogOpen}
  <dialog open>
    <article>
      <h2>Confirm Note Deletion</h2>
      <p>
        Are you sure you want to delete this note? This action cannot be undone.
      </p>
      <footer>
        <button class="secondary" on:click={closeDeleteDialog}>Cancel</button>
        <button on:click={confirmDelete}>Confirm</button>
      </footer>
    </article>
  </dialog>
{/if}

<style>
  .content {
    margin-top: 2rem;
    & button {
      padding: 0.2rem;
      margin-bottom: 0.5rem;
    }
  }
  .note {
    & h3 {
      margin-top: 1rem;
    }
  }
</style>
