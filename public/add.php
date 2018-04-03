<?php
require '../src/bootstrap.php';

render('header', ['title' => 'Ajouter un évènement']);
$errors = [];
$data = [];
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if(!empty($_GET['date'])){
        $data = $_GET;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $validator = new Softease\Calendar\EventValidator();
    $validator->validates($data);
    $errors = $validator->validates($data);
    $pdo = getPDO();
    if (empty($errors)) {
        $event = new \Softease\Calendar\Event();
        $event->setName($_POST['name']);
        $event->setDescription($_POST['description']);
        $event->setStart(DateTime::createFromFormat('Y-m-d H:i', $_POST['date'].' '.$_POST['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(DateTime::createFromFormat('Y-m-d H:i', $_POST['date'].' '.$_POST['end'])->format('Y-m-d H:i:s'));
        $evenement = new \Softease\Calendar\Events($pdo);
        $evenement->create($event);
        header('Location: /index.php?success=1');
    }
}
    ?>

    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger"> Merci de corriger les points ci dessous.</div>
        <?php endif; ?>
        <h1>Ajouter un Evenement</h1>
        <form action="" method="post" class="form">
            <?php render('form/form', ['data' => $data, 'errors' => $errors])?>
            <div class="form-goup">
                <button class="btn btn-primary">Ajouter l'évenement</button>
            </div>
        </form>
    </div>


    <?php
    render('footer'); ?>