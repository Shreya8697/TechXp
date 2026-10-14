<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechXP Event Registration</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #1f2937;
        }
        .container {
            max-width: 680px;
            margin: auto;
            padding: 28px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 8px 30px #00000010;
        }
        h1 { margin-top: 0; color: #312e81; }
        .subtitle { color: #6b7280; margin-bottom: 24px; }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
        }
        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
        }
        .field { margin-bottom: 17px; }
        .consent {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            font-weight: normal;
            line-height: 1.5;
        }
        .consent input { width: auto; margin-top: 4px; }
        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 7px;
            background: #4338ca;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:disabled { opacity: .6; cursor: wait; }
        #message { margin-top: 16px; line-height: 1.5; }
        @media (max-width: 520px) {
            .grid { grid-template-columns: 1fr; gap: 0; }
            .container { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>TechXP Event Registration</h1>
    <p class="subtitle">Register your details to attend our event.</p>

    <form id="registerForm">
        <div class="grid">
            <div class="field">
                <label for="name">Full name</label>
                <input id="name" name="name" required minlength="2" maxlength="100">
            </div>
            <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" required maxlength="255">
            </div>
            <div class="field">
                <label for="password">Password (minimum 8 characters)</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                    required
                >
            </div>
            <div class="field">
                <label for="mobile">Mobile number</label>
                <input id="mobile" name="mobile" type="tel"
                       inputmode="numeric" pattern="[0-9]{10}"
                       minlength="10" maxlength="10" required>
            </div>
            <div class="field">
                <label for="company">Company</label>
                <input id="company" name="company" required maxlength="150">
            </div>
            <div class="field">
                <label for="designation">Designation</label>
                <input id="designation" name="designation" required maxlength="100">
            </div>
            <div class="field">
                <label for="city">City</label>
                <select id="city" name="city" required>
                    <option value="">Select city</option>
                    <option>Mumbai</option>
                    <option>Pune</option>
                    <option>Delhi</option>
                    <option>Bengaluru</option>
                    <option>Hyderabad</option>
                    <option>Chennai</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="field">
                <label for="country">Country</label>
                <select id="country" name="country" required>
                    <option value="">Select country</option>
                    <option>India</option>
                    <option>United States</option>
                    <option>United Kingdom</option>
                    <option>Canada</option>
                    <option>Australia</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="field">
                <label for="session_preference">Session preference</label>
                <select id="session_preference" name="session_preference" required>
                    <option value="">Select session</option>
                    <option>Technical</option>
                    <option>AI and API Integration</option>
                    <option>Web Development</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label class="consent">
                <input type="checkbox" id="consent" name="consent" value="1" required>
                I consent to the collection and use of my registration details.
            </label>
        </div>

        <button id="submitBtn" type="submit">Register Now</button>
        <div id="message" role="status" aria-live="polite"></div>
    </form>
</div>

<script>
const form = document.getElementById('registerForm');
const message = document.getElementById('message');
const button = document.getElementById('submitBtn');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const data = Object.fromEntries(new FormData(form).entries());
    data.consent = document.getElementById('consent').checked ? 1 : 0;

    button.disabled = true;
    message.textContent = 'Submitting registration...';

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) {
            const errors = result.errors
                ? Object.values(result.errors).join(' ')
                : result.message;

            message.textContent = errors || 'Registration failed.';
            return;
        }

        message.textContent = result.message + ' Your attendee ID is ' + result.attendee_id + '.';
        form.reset();
    } catch (error) {
        message.textContent = 'Unable to contact the server. Please try again.';
    } finally {
        button.disabled = false;
    }
});
</script>
</body>
</html>
