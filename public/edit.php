<?php
require '../vendor/autoload.php';
require "../src/bootstrap.php";
$pdo = getPDO();
$errors = [];
$events = new \Softease\Calendar\Events($pdo);
if(!isset($_GET['id']) || empty($_GET['id'])) {
    error404();
}
try {
    $event = $events->find($_GET['id']);
} catch (Exception $e) {
    error404();
}
$data =[
    'name' => $event->getName(),
    'date' => $event->getStart()->format('Y-m-d'),
    'description' => $event->getDescription(),
    'start' => $event->getStart()->format('H:i'),
    'end' => $event->getEnd()->format('H:i'),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validator = new Softease\Calendar\EventValidator();
    $validator->validates($_POST);
    $errors = $validator->validates($_POST);
    if (empty($errors)) {
        $event->setName($_POST['name']);
        $event->setDescription($_POST['description']);
        $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $_POST['date'].' '.$_POST['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(DateTime::createFromFormat('Y-m-d H:i', $_POST['date'].' '.$_POST['end'])->format('Y-m-d H:i:s'));
        $events->update($event);
        header('Location: /index.php?success=1');
    }
}

render('header', ['title' => $event->getName()]);
?>

<div class="container">
<h1>Editer l'évènement <i>"<?= $event->getName(); ?>"</i></h1>

    <form action="" method="post">
        <?php render('form/form', ['data' => $data, 'errors' => $errors]); ?>
        <button type="submit" class="btn btn-dark">Modifier l'évènement</button>
    </form>
</div>



<?php render('footer'); ?>
