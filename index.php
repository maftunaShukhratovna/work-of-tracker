<?php
require 'DB.php';
require 'Tracker.php';

$db = new DB();
$pdo = $db->getPDO();

$tracker = new Tracker($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['arrived_at'], $_POST['leaved_at'])) {
    if (!empty($_POST['name']) && !empty($_POST['arrived_at']) && !empty($_POST['leaved_at'])) {
        $name = $_POST['name'];
        $arrived_at = $_POST['arrived_at'];
        $leaved_at = $_POST['leaved_at'];

        $tracker->addRecord($name, $arrived_at, $leaved_at);
        header('Location: index.php');
        exit;
    }
}

$records = $tracker->getAllRecords();
$userRequiredWork = $tracker->calculateRequiredWork();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Of Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-secondary">
<div class="container">
    <h1 class="text-primary text-center">Work Of Tracker</h1>
    <div class="row align-items-end my-3">
        <div class="col">
            <form method="post" class="row g-3 mt-3 align-items-end">
                <div class="col-auto">
                    <label for="name">Ismi</label>
                    <input type="text" name="name" class="form-control" id="name">
                </div>
                <div class="col-auto">
                    <label for="arrived_at">Kelgan vaqti</label>
                    <input type="datetime-local" name="arrived_at" class="form-control" id="arrived_at">
                </div>
                <div class="col-auto">
                    <label for="leaved_at">Ketgan vaqti</label>
                    <input type="datetime-local" name="leaved_at" class="form-control" id="leaved_at">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Yuborish</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-primary table-hover">
        <thead>
        <tr class="table-secondary">
            <th scope="col">#</th>
            <th scope="col">Ism</th>
            <th scope="col">Kelgan vaqti</th>
            <th scope="col">Ketgan vaqti</th>
            <th scope="col">Ishlash kerak</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?= $record['id'] ?></td>
                <td><?= htmlspecialchars($record['name']) ?></td>
                <td><?= $record['arrived_at'] ?></td>
                <td><?= $record['leaved_at'] ?></td>
                <td><?= gmdate('H:i', $record['required_of']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="text-primary text-center">Umumiy Ishlash Kerak</h2>
    <table class="table table-secondary table-hover">
        <thead>
        <tr>
            <th scope="col">Ism</th>
            <th scope="col">Umumiy Ishlash kerak (soat:minut)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($userRequiredWork as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= gmdate('H:i', $user['total_required_of']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
