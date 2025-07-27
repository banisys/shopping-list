const loginForm = document.getElementById('login-form');
const mobileInput = document.getElementById('mobile');
const passwordInput = document.getElementById('password');

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const mobile = mobileInput.value.trim();
    const password = passwordInput.value.trim();
    if (!mobile) return;

    console.log(password)

    const res = await fetch('/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
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
