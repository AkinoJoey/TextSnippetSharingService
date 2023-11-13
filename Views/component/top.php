<main class="container">
    <h2 class="text-center"><a class="text-decoration-none" href="/">Text Snipetter</a></h2>
    <div>
        <div>
            <div id="editor-container" class="monaco-container"></div>
        </div>
    </div>

    <div class="center-xs mt-2">
        <h3>Option Menu</h3>
        <div>
            <label for="language">Syntax Highlight :</label>
            <select name="language" id="language" class="w-50">
            </select>
        </div>

        <div>
            <label for="expiration">Text Expiration:</label>
            <select name="expiration" id="expiration" class="w-50">
            </select>
        </div>
        <button id="create-btn" role="button" class="w-50 mt-2">Create Snippet</button>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs/loader.min.js"></script>
<script src="../../public/js/main.js"></script>