<?php
require 'Tracker.php';

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


if (isset($_GET['done'])) {
    $tracker->markAsDone($_GET['done']);
    header('Location: index.php');
    exit;
}

$records = $tracker->getAllRecords();
$userRequiredWork = $tracker->calculateRequiredWork();
?>




<!DOCTYPE html>
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
                <form method="post" id="main" class="row g-3 mt-3 align-items-end">
                    <div class="col-auto">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                    <div class="col-auto">
                        <label for="arrived_at">Arrived At</label>
                        <input type="datetime-local" name="arrived_at" class="form-control" id="arrived_at">
                    </div>
                    <div class="col-auto">
                        <label for="leaved_at">Leaved At</label>
                        <input type="datetime-local" name="leaved_at" class="form-control" id="leaved_at">
                    </div>
                    <div class="col-auto">
                        <button form="main" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div class="col-auto">
                        <button form="export" type="submit" class="btn btn-success">Export</button>
                    </div>
                </form>

                <form action="download.php" id="export" method="post">
                    <input type="text" name="export" value="true" hidden="">
                </form>
            </div>
        </div>

        <table class="table table-primary table-hover">
            <thead>
                <tr class="table-secondary">
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Arrived At</th>
                    <th scope="col">Leaved At</th>
                    <th scope="col">Required Work</th>
                    <th scope="col">Work status</th>
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
                    <td><a href="index.php?done=<?= urlencode($record['name']) ?>" class="btn btn-success">Done</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="...">
            <ul class="pagination">
                <?php
                global $workday, $currentPage;
                $disabled=$currentPage==1?"disabled":"";
                ?>
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <span class="page-link">2</span>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>


    </div>
</body>

</html>