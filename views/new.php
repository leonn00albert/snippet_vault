<?php include "header.php"; ?>

<div class="container text-center">
<?php include "nav.php"; ?>
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

<?php include 'footer.php'; ?>