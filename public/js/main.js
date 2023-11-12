const createBtn = document.getElementById('create-btn');

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

    createBtn.addEventListener('click', function () {
        let formData = new FormData();
        formData.append("language", document.getElementById("language").value);
        formData.append("expiration", document.getElementById("expiration").value);
        formData.append("snippet",  editor.getValue());

        fetch("../../createSnippet.php", {
            method: "POST",
            body:formData,
        })
            .then((response) => response.text())
            .then((data) => {
                console.log(data);
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    })
});
