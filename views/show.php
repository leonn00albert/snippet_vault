<?php include "header.php"; ?>

<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-4 m-2">
                <div id="filetree" class="m-2">

                </div>
            </div>
            <div class="col m-2">
                <div class="card m-2">
                    <div id="snippets"></div>

                </div>

            </div>

        </div>
    </div>
    <script src="/public/prism.js"></script>

    <script>
        window.onload = function() {
            const path = window.location.pathname;
            const segments = path.split('/');
            const id = segments[segments.length - 1];
            fetch('/api/snippets/' + id)
                .then(response => response.json())
                .then(res => {

                    var snippetsDiv = document.getElementById("snippets");
                    console.log(res);
                    if (Array.isArray(res)) {
                        res.forEach(item => {
                            var snippetElement = document.createElement("div");
                            snippetElement.className = "card";
                            snippetElement.innerHTML = `
                                        <pre>
                                            <code class="language-js}">
                                            ${res.code}
                                            </code>
                                        </pre>

                            `;
                            snippetsDiv.appendChild(snippetElement);
                        });
                    } else {
                        var snippetElement = document.createElement("div");
                        var pre = document.createElement("pre");
                        var code = document.createElement("code");


                        snippetElement.className = "card";
                        pre.className = "language-js";
                        code.className = "language-js";
                        code.innerHTML = Prism.highlight(res.code, Prism.languages.js, 'js');

                        pre.appendChild(code);
                        snippetElement.appendChild(pre);
                        snippetsDiv.appendChild(snippetElement);
                    }

                })
                .catch(error => console.error(error));
            appendTreeView('https://example.com/api/tree-data', 'treeview-container');


        };



    </script>

<?php include 'footer.php'; ?>