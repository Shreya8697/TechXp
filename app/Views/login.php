
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechXP Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 40px 16px;
        }
        .card {
            max-width: 420px;
            margin: auto;
            padding: 28px;
            background: white;
            border-radius: 12px;
        }
        input, button {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin: 8px 0 16px;
            border-radius: 7px;
        }
        input { border: 1px solid #d1d5db; }
        button {
            border: 0;
            background: #4338ca;
            color: white;
            cursor: pointer;
        }
        #message { line-height: 1.5; }
    </style>
</head>
<body>
<div class="card">
    <h1>Welcome back</h1>
    <p>Log in to access your event.</p>

    <form id="loginForm">
        <label for="identifier">Email or mobile number</label>
        <input id="identifier" name="identifier"
               autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password"
               autocomplete="current-password" required>

        <button type="submit" id="submitBtn">Log in</button>
        <p id="message" role="status" aria-live="polite"></p>
    </form>
</div>

<script>
const form = document.getElementById('loginForm');
const message = document.getElementById('message');
const button = document.getElementById('submitBtn');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    button.disabled = true;
    message.textContent = 'Logging in...';

    try {
        const data = Object.fromEntries(new FormData(form).entries());

        const response = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) {
            message.textContent = result.message
                || Object.values(result.errors || {}).join(' ')
                || 'Login failed.';
            return;
        }

        window.location.href = result.redirect;
    } catch {
        message.textContent = 'Could not contact the server. Try again.';
    } finally {
        button.disabled = false;
    }
});
</script>
</body>
</html>
