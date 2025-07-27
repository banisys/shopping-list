const form = document.getElementById('add-form');
const input = document.getElementById('item-name');
const list = document.getElementById('items-list');


async function fetchItems() {
    const res = await fetch('/api/items');
    const items = await res.json();
    list.innerHTML = '';
    items.forEach(addItemToDOM);
}

function addItemToDOM(item) {
    const li = document.createElement('li');
    li.innerHTML = `
    <input type="checkbox" ${item.is_checked ? 'checked' : ''} onclick="toggleCheck(${item.id})">
    <span contenteditable="true" onblur="editItem(${item.id}, this.innerText)">${item.name}</span>
    <button onclick="deleteItem(${item.id})">❌</button>
  `;
    list.appendChild(li);
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = input.value.trim();
    if (!name) return;
    await fetch('/api/items', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
    });
    input.value = '';
    fetchItems();
});

async function toggleCheck(id) {
    await fetch(`/api/items/${id}/check`, { method: 'PATCH' });
    fetchItems();
}

async function deleteItem(id) {
    await fetch(`/api/items/${id}`, { method: 'DELETE' });
    fetchItems();
}

async function editItem(id, name) {
    await fetch(`/api/items/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
    });
}

document.getElementById('logout-btn').addEventListener('click', function () {
    document.cookie = 'token=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    window.location.reload();
});


fetchItems();
