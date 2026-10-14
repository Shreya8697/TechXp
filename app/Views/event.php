
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your TechXP Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 28px 16px;
            background: #f3f4f6;
            color: #1f2937;
        }
        main {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 24px;
        }
        .top {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }
        a {
            color: #4338ca;
        }
        iframe {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>

<body>
<main>
    <div class="top">
        <div>
            <h1>TechXP Event</h1>
            <p>Welcome, <?= esc($name) ?>!</p>
            <p><?= esc($email) ?></p>
        </div>
        <a href="<?= site_url('logout') ?>">Log out</a>
    </div>

    <hr>

    <h2>Event information</h2>
    <p>You are logged in and can access this protected event page.</p>

    <h2>Event content</h2>
    <iframe
        title="Event content"
        src="https://www.youtube.com/embed/ysz5S6PUM-U"
        loading="lazy"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe>
</main>

<section id="activity-section" style="max-width:700px;margin:30px auto;padding:20px;font-family:Arial,sans-serif;border:1px solid #ddd;border-radius:12px;">
    <h2>Discover an Activity</h2>
    <p>Get an activity suggestion from our external API.</p>

    <button id="activity-btn" type="button">
        Get Activity
    </button>

    <p id="activity-status" role="status" aria-live="polite"></p>

    <div id="activity-result" hidden>
        <h3 id="activity-name"></h3>
        <p>Type: <span id="activity-type"></span></p>
        <p>Participants: <span id="activity-participants"></span></p>
    </div>
</section>

<script>
(() => {
    const button = document.getElementById('activity-btn');
    const status = document.getElementById('activity-status');
    const result = document.getElementById('activity-result');

    button.addEventListener('click', async () => {
        button.disabled = true;
        status.textContent = 'Loading activity...';
        result.hidden = true;

        try {
            const response = await fetch(
                '<?= site_url('api/external-activity') ?>',
                { headers: { Accept: 'application/json' } }
            );

            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Unable to load activity.');
            }

            document.getElementById('activity-name').textContent =
                payload.data.activity;

            document.getElementById('activity-type').textContent =
                payload.data.type;

            document.getElementById('activity-participants').textContent =
                payload.data.participants ?? 'Not specified';

            result.hidden = false;
            status.textContent = 'Activity loaded successfully!';
        } catch (error) {
            status.textContent =
                error.message || 'Something went wrong. Please try again.';
        } finally {
            button.disabled = false;
        }
    });
})();
</script>

<section id="chat-section" style="max-width:700px;margin:30px auto;padding:24px;font-family:Arial,sans-serif;border:1px solid #ddd;border-radius:12px;background:#fff;">
    <h2>Event Chat</h2>
    <p>Chat with the event assistant (demo).</p>

    <div
        id="chat-messages"
        role="log"
        aria-live="polite"
        style="height:260px;overflow-y:auto;padding:14px;background:#f5f7fb;border-radius:8px;margin-bottom:14px;"
    >
        <p><strong>Assistant:</strong> Hi! Welcome to the event. How can I help?</p>
    </div>

    <form id="chat-form" style="display:flex;gap:10px;">
        <input
            id="chat-input"
            type="text"
            maxlength="500"
            placeholder="Type your message..."
            aria-label="Your message"
            required
            style="flex:1;min-width:0;padding:12px;border:1px solid #ccc;border-radius:7px;"
        >
        <button
            type="submit"
            style="padding:12px 18px;background:#2563eb;color:white;border:0;border-radius:7px;cursor:pointer;"
        >
            Send
        </button>
    </form>

    <p style="font-size:12px;color:#64748b;">
        Demo only: messages are not saved and replies are simulated.
    </p>
</section>

<script>
(() => {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');

    function addMessage(sender, text) {
        const paragraph = document.createElement('p');
        const label = document.createElement('strong');

        label.textContent = sender + ': ';
        paragraph.appendChild(label);
        paragraph.appendChild(document.createTextNode(text));

        messages.appendChild(paragraph);
        messages.scrollTop = messages.scrollHeight;
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const message = input.value.trim();

        if (!message) return;

        addMessage('You', message);
        input.value = '';

        const normalized = message.toLowerCase();
        let reply = 'Thanks for your message! This is a demo assistant.';

        if (normalized.includes('event')) {
            reply = 'You can check the event page for event information.';
        } else if (normalized.includes('hello') || normalized.includes('hi')) {
            reply = 'Hello! Welcome to the event. How can I help you?';
        } else if (normalized.includes('register')) {
            reply = 'Please use the registration page to register for the event.';
        } else if (normalized.includes('thank')) {
            reply = 'You are welcome!';
        }

        window.setTimeout(() => {
            addMessage('Assistant', reply);
        }, 400);
    });
})();
</script>


</body>
</html>
