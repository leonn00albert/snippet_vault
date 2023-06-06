<?php include "header.php"; ?>


<div class="container text-center">
<?php include "nav.php"; ?>
    <div class="row">
        <div class="col m-2">
            <div id="filetree" class="m-2">

            </div>
        </div>
        <div class="col m-2">
            <div class="card m-2">

                <div class="card-body">
                    <h5 class="card-title">Feed</h5>
                </div>
            </div>
            <div id="snippets"></div>
        </div>

    </div>
</div>
<script src="public/prism.js"></script>

<script>
    window.onload = function() {
        const path = window.location.pathname;
        const segments = path.split('/');
        const id = segments[segments.length - 1];
        fetch('/api/snippets')
            .then(response => response.json())
            .then(res => {

                var snippetsDiv = document.getElementById("snippets");
           
                if (Array.isArray(res)) {
                    console.log(res);
                    res.reverse().forEach(child => {
                     
                            var snippetElement = document.createElement("div");
                            var text = document.createElement("p");
                            text.textContent = `${child.user} created a new code snippet -  ${child.date_ago}`
                            var pre = document.createElement("pre");
                            var code = document.createElement("code");
                            snippetElement.className = "card";
                            pre.className = "language-js";
                            code.className = "language-js";
                            code.innerHTML = Prism.highlight(child.code, Prism.languages.js, 'js');

                            pre.appendChild(code);
                            snippetsDiv.appendChild(text);
                            snippetElement.appendChild(pre);
                            snippetsDiv.appendChild(snippetElement);
                      
                    });
                }

            })
            .catch(error => console.error(error));
        appendTreeView('https://example.com/api/tree-data', 'treeview-container');


    };
</script>

<?php include 'footer.php'; ?>