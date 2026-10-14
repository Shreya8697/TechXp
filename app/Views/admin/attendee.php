
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendee Details | TechXP</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            color: #202938;
        }
        header {
            background: #172554;
            color: white;
            padding: 22px 5%;
        }
        main {
            max-width: 800px;
            margin: 35px auto;
            padding: 0 18px;
        }
        .card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 15px #1725540d;
        }
        h1 { margin-top: 0; font-size: 25px; }
        .field {
            padding: 14px 0;
            border-bottom: 1px solid #e2e8f0;
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 12px;
        }
        .label { color: #64748b; }
        .actions { margin-top: 25px; display: flex; gap: 18px; }
        a { color: #2563eb; text-decoration: none; }
        @media (max-width: 500px) {
            .field { grid-template-columns: 1fr; gap: 5px; }
            .card { padding: 18px; }
        }
    </style>
</head>
<body>
<header>
    <strong>TechXP Admin Dashboard</strong>
</header>

<main>
    <section class="card">
        <h1>Attendee Details</h1>
        <p>Attendee ID: <?= esc($attendee['id']) ?></p>

        <?php
        $fields = [
            'name' => 'Full Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'company' => 'Company',
            'designation' => 'Designation',
            'city' => 'City',
            'country' => 'Country',
            'session_preference' => 'Session Preference',
            'consent' => 'Consent',
            'created_at' => 'Registered At',
        ];
        ?>

        <?php foreach ($fields as $key => $label): ?>
            <div class="field">
                <span class="label"><?= esc($label) ?></span>
                <strong>
                    <?php
                    $value = $attendee[$key] ?? null;

                    if ($key === 'consent') {
                        echo esc((string) $value === '1' ? 'Yes' : 'No');
                    } else {
                        echo esc(($value !== null && $value !== '') ? (string) $value : 'Not provided');
                    }
                    ?>
                </strong>
            </div>
        <?php endforeach; ?>

        <div class="actions">
            <a href="<?= site_url('admin') ?>">← Back to dashboard</a>
            <a href="<?= site_url('logout') ?>">Logout</a>
        </div>
    </section>
</main>
</body>
</html>
