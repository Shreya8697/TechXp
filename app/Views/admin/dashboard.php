<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TechXP</title>
    <style>
        * { box-sizing: border-box; }
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }
        header h1 { margin: 0; font-size: 23px; }
        a { color: #2563eb; text-decoration: none; }
        header a { color: white; }
        main { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }
        .stat, .panel {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 15px #1725540d;
        }
        .stat p { color: #64748b; margin: 0 0 10px; }
        .stat h2 { margin: 0; font-size: 30px; }
        .panel h2 { margin-top: 0; font-size: 20px; }
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        input, select, button {
            padding: 11px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 14px;
        }
        input { flex: 1; min-width: 180px; }
        button {
            border: none;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }
        .reset { padding: 11px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            padding: 13px 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            font-size: 14px;
        }
        th { background: #f8fafc; color: #475569; }
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .pages { display: flex; gap: 8px; align-items: center; }
        .pages a, .pages span {
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .pages .current { background: #172554; color: white; }
        .muted { color: #64748b; font-size: 14px; }
        @media (max-width: 600px) {
            header { align-items: flex-start; flex-direction: column; }
            main { padding: 0 12px; }
            .stat, .panel { padding: 16px; }
        }
    </style>
</head>
<body>
<header>
    <h1>TechXP Admin Dashboard</h1>
    <a href="<?= site_url('logout') ?>">Logout</a>
</header>

<main>
    <section class="stats">
        <div class="stat">
            <p>Total Attendees</p>
            <h2><?= esc($totalAttendees) ?></h2>
        </div>
        <div class="stat">
            <p>Total Events</p>
            <h2><?= esc($totalEvents) ?></h2>
        </div>
        <div class="stat">
            <p>Total Registrations</p>
            <h2><?= esc($totalRegistrations) ?></h2>
        </div>
    </section>

    <section class="panel">
        <h2>Attendee Management</h2>
        <p class="muted">Search attendees by name, email, or company. Filter by country.</p>

        <form method="get" action="<?= site_url('admin') ?>" class="filters">
            <input
                type="search"
                name="search"
                placeholder="Search name, email, company..."
                value="<?= esc($search) ?>"
            >

            <select name="country">
                <option value="">All countries</option>
                <?php foreach ($countries as $countryOption): ?>
                    <option
                        value="<?= esc($countryOption) ?>"
                        <?= $country === $countryOption ? 'selected' : '' ?>
                    >
                        <?= esc($countryOption) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Apply Filters</button>
            <a class="reset" href="<?= site_url('admin') ?>">Reset</a>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Country</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (! empty($attendees)): ?>
                    <?php foreach ($attendees as $attendee): ?>
                        <tr>
                            <td><?= esc($attendee['id']) ?></td>
                            <td><?= esc($attendee['name']) ?></td>
                            <td><?= esc($attendee['email']) ?></td>
                            <td><?= esc($attendee['company'] ?? '-') ?></td>
                            <td><?= esc($attendee['country'] ?? '-') ?></td>
                            <td>
                                <a href="<?= site_url('admin/attendees/' . $attendee['id']) ?>">
                                    View details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No attendees found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
            $totalPages = max(1, (int) ceil($total / $perPage));
            $startRecord = $total > 0 ? (($page - 1) * $perPage) + 1 : 0;
            $endRecord = min($page * $perPage, $total);
        ?>

        <div class="pagination">
            <span class="muted">
                Showing <?= $startRecord ?>–<?= $endRecord ?> of <?= esc($total) ?> attendees
            </span>

            <div class="pages">
                <?php if ($page > 1): ?>
                    <a href="<?= site_url('admin') . '?' . http_build_query([
                        'search' => $search,
                        'country' => $country,
                        'page' => $page - 1
                    ]) ?>">Previous</a>
                <?php endif; ?>

                <span class="current"><?= $page ?> / <?= $totalPages ?></span>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= site_url('admin') . '?' . http_build_query([
                        'search' => $search,
                        'country' => $country,
                        'page' => $page + 1
                    ]) ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
</body>
</html>
