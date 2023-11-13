<main class="container">
    <h2 class="text-center"><a class="text-decoration-none" href="/">Text Snipetter</a></h2>
    <p><kbd><?= $language ?></kbd></p>
    <div>
        <div>
            <div id="preview-container" class="monaco-container"></div>
        </div>
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs/loader.min.js"></script>
<script>
    require.config({
        paths: {
            vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs",
        },
    });

    require(["vs/editor/editor.main"], function() {
        const editor = monaco.editor.create(
            document.getElementById("preview-container"), {
                value: <?= json_encode($snippet) ?>,
                language: <?= json_encode($language) ?>,
                automaticLayout: true,
                readOnly: true
            }
        );

    });
</script>