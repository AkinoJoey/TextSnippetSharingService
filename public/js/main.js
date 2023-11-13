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

    const languages = monaco.languages.getLanguages();
    createLanguageOption(languages);

    // expiration optionの作成
    fetch('../../expirations.php')
        .then((res) => {
            return res.json()
        })
        .then((data) => {
            createExpirationOption(data);
        })

    createBtn.addEventListener('click', async function (event) {
        event.preventDefault();

        let formData = new FormData();
        formData.append("language", document.getElementById("language").value);
        formData.append("expiration", document.getElementById("expiration").value);
        formData.append("snippet", editor.getValue());

        let hash = await fetch("../../insertData.php", {
            method: "POST",
            body: formData,
        }).then(res => res.text());

        window.location.href = `snippet/${hash}`;
    })
});


function createLanguageOption(languages) {
    const languageSelect = document.getElementById('language');

    for (const languageId in languages) {
        const languageInfo = languages[languageId];
        const language = languageInfo['id'];

        let newOption = document.createElement('option');
        let optionText = document.createTextNode(language);
        newOption.appendChild(optionText);
        newOption.value = language;

        languageSelect.appendChild(newOption);
    }
}

function createExpirationOption(expirations) {
    const expirationSelect = document.getElementById("expiration");

    expirations.forEach(expiration => {
        let newOption = document.createElement("option");
        let optionText = document.createTextNode(expiration);
        newOption.appendChild(optionText);
        newOption.value = expiration;

        expirationSelect.appendChild(newOption);
    });{
        
    }
}