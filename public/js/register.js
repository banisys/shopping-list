const registerForm = document.getElementById('register-form');
const nameInput = document.getElementById('name');
const mobileInput = document.getElementById('mobile');
const passwordInput = document.getElementById('password');

registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const name = nameInput.value.trim();
    const mobile = mobileInput.value.trim();
    const password = passwordInput.value.trim();

    if (!mobile) return;

    const res = await fetch('/api/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            name,
            mobile,
            password,
        })
    });

    const data = await res.json();

    if (res.ok && data.token) {
        window.location.replace("/items");

    } else {
        alert(data.error || 'Login failed');
    }
});
