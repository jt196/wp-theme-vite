<?php get_header() ?>

<div>
    <div>
        <h1>Hello World!</h1>
        <h2>Wordpress Theme rapid development using Vite , Pico and Svelte</h2>
        <div>
            <a href="https://vitejs.dev/" target="_blank">ViteJS</a>
        </div>

        <p>To test browser sync in development mode ensure the following:</p>
        <code>
            <p>
                <span>// define IS_VITE_DEVELOPMENT in functions or wp-config.php</span>
                define('IS_VITE_DEVELOPMENT', TRUE);
            </p>
            <p>
                <span>// run first time in your <strong>theme folder</strong> (node.js required)</span>
                npm install
            </p>
            <p>
                <span>// start development & refresh your browser</span>
                npm run dev
            </p>
        </code>

    </div>
</div>

<?php get_footer() ?>