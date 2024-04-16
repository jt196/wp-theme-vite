<div class="spinner-loader"></div>

<style>
  @keyframes spin {
    0% {
      -webkit-transform: rotate(0deg);
      transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
      transform: rotate(360deg);
    }
  }
  .spinner-loader {
    margin-top: 45px;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    border: 0.25rem solid rgba(0, 0, 0, 0.2);
    border-top-color: black;
    -webkit-animation: spin 1s infinite linear;
    animation: spin 1s infinite linear;
  }
</style>
