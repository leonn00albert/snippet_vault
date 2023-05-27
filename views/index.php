<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="public/prism.css" rel="stylesheet" />
    <link href="public/styles.css" rel="stylesheet" />
    <title>Document</title>
</head>

<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-2">
                <div id="filetree">

                </div>
            </div>
            <div class="col">
                <div class="card">
                  
                    <div class="card-body">
                        <h5 class="card-title">Add Snippet</h5>
                        <p class="card-text">
                            <textarea id="codeInput" rows="10" cols="50"></textarea>
                        </p>
                        <a href="#" onclick="handle_add()" class="btn btn-primary">Add snippet</a>
                    </div>
                </div>
                <div id="snippets"></div>

            </div>

        </div>
    </div>
    <script src="public/prism.js"></script>

    <script>
        
        window.onload = function() {

            fetch('/api/snippets')
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

        };

        function appendTreeView(url, elementId) {
            const obj = [{
                text: 'Root',
                children: [{
                    text: 'Beverages',
                    children: [
                        'Water',
                        'Coffee',
                        {
                            text: 'Tea',
                            children: [
                                'Black Tea',
                                'White Tea',
                                {
                                    text: 'Green Tea',
                                    children: ['Sencha', 'Gyokuro', 'Matcha', 'Pi Lo Chun']
                                }
                            ]
                        }
                    ]
                }]
            }];
            const treeView = createTreeView(obj);
            const container = document.getElementById("filetree");
            container.appendChild(treeView);

        }

        function createTreeView(data) {
            const ul = document.createElement('ul');
            data.forEach((item, i) => {
                const li = document.createElement('li');
                if (typeof item === 'object') {
                    const span = document.createElement('span');
                    span.className = 'caret';
                    span.textContent = item.text;
                    li.appendChild(span);

                    const nestedUl = createTreeView(item.children);
                    nestedUl.className = "nested";
                    li.appendChild(nestedUl);
                } else {
                    li.textContent = item;
                }

                ul.appendChild(li);
            });

            return ul;
        }


        appendTreeView('https://example.com/api/tree-data', 'treeview-container');


        var toggler = document.getElementsByClassName("caret");
        var i;

        for (i = 0; i < toggler.length; i++) {
            toggler[i].addEventListener("click", function() {
                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });
        }

        function handle_add() {

            const input = {

                code: document.getElementById('codeInput').value,
                langauge: "php",


            }

            fetch('/api/snippets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(input)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                }).then(data => {

                    window.location = '/';

                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        };
    </script>

</body>

</html>