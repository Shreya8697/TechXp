
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
</body>
</html>
