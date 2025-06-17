function deleteToDoItem() {
    const list = document.getElementById('todo-list');
    list.addEventListener('click', async (e) => {
        const li = e.target.closest('li[data-id]');
        if (!li) return;                       // clicked the UL background

        const id = li.dataset.id;
        const label = li.textContent.trim();

        if (!confirm('Delete this todo: ' + label )) return;

        try {
            await axios.delete(`/todos/${id}`);
            li.remove();                       // optimistic UI
        } catch (err) {
            console.error(err);
            alert('Delete failed. Check console.');
        }
    });
}

function addToDoItem() {
    const list = document.getElementById('todo-list');
    const btn = document.getElementById('add-todo');
    btn.addEventListener('click', async () => {
        const title = prompt('New todo text:');
        if (!title) return;

        try {
            // Axios picks up base URL & CSRF token from bootstrap.js
            const { data } = await axios.post('/todos', { title });

            // Dumb DOM update – swap for Alpine/React/Vue as you like
            const li = document.createElement('li');
            // add data-id attribute to the <li> element
            li.setAttribute('data-id', data.id);
            li.innerHTML = "<input type='checkbox' name='" + data.id + "' id='todo-"+ data.id +"' class='todo-item' /> <label for='" + data.id + "'>" + data.title + "</label>";
            list.appendChild(li);
        } catch (err) {
            console.error(err);
            alert('Failed. Check console / network tab.');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    addToDoItem();
    deleteToDoItem();
});
