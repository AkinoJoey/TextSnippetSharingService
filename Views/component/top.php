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
                <?php
                foreach ($expirations as $expiration) {
                    echo "<option value=\"{$expiration['value']}\">{$expiration['text']}</option>";
                }
                ?>
            </select>
        </div>
        <button id="create-btn" role="button" class="w-50 mt-2">Create Snippet</button>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs/loader.min.js"></script>
<script>
    const createBtn = document.getElementById("create-btn");

    require.config({
        paths: {
            vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.41.0/min/vs",
        },
    });

    require(["vs/editor/editor.main"], function() {
        const editor = monaco.editor.create(
            document.getElementById("editor-container"), {
                value: "ここに共有したいテキストを入力してください。\n\n",
                language: "plaintext",
                automaticLayout: true,
            }
        );

        const languages = monaco.languages.getLanguages();
        createLanguageOption(languages);

        createBtn.addEventListener("click", async function(event) {
            event.preventDefault();

            const snippet = editor.getValue();
            const language = document.getElementById("language").value;
            const expiration = document.getElementById("expiration").value;

            await fetch('../../submitForm.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        'snippet': snippet,
                        'language': language,
                        'expiration': expiration
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.url;
                    }else{
                        alert(data.message);
                    }
                })

        });
    });

    function createLanguageOption(languages) {
        const languageSelect = document.getElementById("language");

        for (const languageId in languages) {
            const languageInfo = languages[languageId];
            const language = languageInfo["id"];

            let newOption = document.createElement("option");
            let optionText = document.createTextNode(language);
            newOption.appendChild(optionText);
            newOption.value = language;

            languageSelect.appendChild(newOption);
        }
    }
</script>