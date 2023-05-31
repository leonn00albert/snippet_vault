<?php include "header.php"; ?>

<div class="container text-center">
    <div class="row">
        <div class="col-4 m-2">
            <div id="filetree" class="m-2">

            </div>
        </div>
        <div class="col m-2">
            <div class="card m-2">

                <div class="card-body">
                    <h5 class="card-title">Add Snippet</h5>
                    <p class="card-text">
                        <label>title</label>
                    <p><input type="text" name="title" id="titleInput" /></p>
                    <textarea id="codeInput" rows="10" cols="50"></textarea>
                    </p>
                    <button onclick="handle_add()" class="btn btn-primary">Add snippet</button>
                </div>
            </div>

        </div>

    </div>
</div>
<script src="public/prism.js"></script>

<script>
    window.onload = function() {

        appendTreeView('https://example.com/api/tree-data', 'treeview-container');


    };

    function appendTreeView(url, elementId) {
        fetch('/api/filetrees')
            .then(response => response.json())
            .then(res => {
                console.log(res)
                const treeView = createTreeView(res);
                const container = document.getElementById("filetree");
                container.appendChild(treeView);
            })
            .catch(error => console.error(error));

    };


    function createTreeView(data, i, item) {
        console.log(data)
        const ul = document.createElement('ul');
        const add = document.createElement('p');
        if (i) {
            add.className = 'add-folder';
            add.textContent = "add snippet"
            add.addEventListener("click", function() {
                window.location = '/?id=' + item.id;
            });
        } else {
            const inputElement = document.createElement('input');
            inputElement.name = "name";
            inputElement.id = "inputElement";
            inputElement.placeholder = "add folder";
            inputElement.className = "add-folder";
            ul.appendChild(inputElement);

            inputElement.addEventListener("keypress", function(event) {
                if (event.keyCode === 13) {
                    let input = {
                        name: document.getElementById("inputElement").value
                    }
                    fetch('/api/filetrees', {
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
                }
            });
        }


        ul.appendChild(add);
        data.forEach((item, i) => {

            const li = document.createElement('li');
            if (typeof item === 'object' && !item.code) {
                const span = document.createElement('span');
                span.className = 'caret';
                span.textContent = item.text;
                li.appendChild(span);

                span.addEventListener("click", function() {

                    this.parentElement.querySelector(".nested").classList.toggle("active");
                    this.classList.toggle("caret-down");
                });

                const nestedUl = createTreeView(item.children, true, item);
                nestedUl.className = "nested";
                li.appendChild(nestedUl);
            } else {
                li.innerHTML = `<a href="snippets/${item.id}"/>${item.text} <a>`
            }

            ul.appendChild(li);
        });



        return ul;
    }

    function handle_add() {
        const queryString = window.location.search;
        const params = new URLSearchParams(queryString);
        const id = params.get('id');

        const input = {
            code: document.getElementById('codeInput').value,
            id: id,
            language: "php",
            title: document.getElementById('titleInput').value,
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