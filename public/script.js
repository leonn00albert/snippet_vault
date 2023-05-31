

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
  
    const ul = document.createElement('ul');
    const add = document.createElement('p');
    if (i) {
        add.className = 'add-folder';
        add.innerHTML = "add snippet"
        add.addEventListener("click", function() {
            window.location = '/snippets/new?id=' + item.id;
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
            const i = document.createElement('span');
            i.innerHTML = "<i class='fa fa-folder mr-2'></i>";
            span.className = 'caret';
            span.textContent = item.text;
            span.prepend(i);

            li.appendChild(span);

            span.addEventListener("click", function() {

                this.parentElement.querySelector(".nested").classList.toggle("active");
                this.classList.toggle("caret-down");
            });

            const nestedUl = createTreeView(item.children, true, item);
            nestedUl.className = "nested";
            li.appendChild(nestedUl);
        } else {
            li.innerHTML = `<a href="/snippets/${item.id}"/>${item.text} <a>`
        }

        ul.appendChild(li);
    });



    return ul;
}