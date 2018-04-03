<?php
require '../vendor/autoload.php';
require "../src/bootstrap.php";
$pdo = getPDO();
$events = new \Softease\Calendar\Events($pdo);
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: 404.php');
}
try {
    $event = $events->find($_GET['id']);
} catch (Exception $e) {
    error404();
}

render('header', ['title' => $event->getName()]);
?>

<h1><?= $event->getName(); ?></h1>
<ul>
    <li>
        Date : <?= $event->getStart()->format('d/m/Y'); ?>
    </li>
    <li>
        Heure debut : <?= $event->getStart()->format('H:i'); ?>
    </li>
    <li>
        Heure fin : <?= $event->getEnd()->format('H:i'); ?>
    </li>
    <li>
        Description : <?= $event->getDescription(); ?>
    </li>
</ul>


<?php require '../Views/footer.php'; ?>
