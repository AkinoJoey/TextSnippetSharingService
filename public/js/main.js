require.config({
    paths: {
        vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs",
    },
});

require(["vs/editor/editor.main"], function () {
    const editor = monaco.editor.create(
        document.getElementById("editor-container"),
        {
            value: 'ここに共有したいテキストを入力してください。' + "\n\n\n",
            language: "plaintext",
            automaticLayout: true,
        }
    );
    
});
