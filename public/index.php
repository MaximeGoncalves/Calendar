<?php

require '../src/bootstrap.php';
$pdo = getPDO();
$month = new \Softease\Calendar\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$events = new \Softease\Calendar\Events($pdo);

$start = $month->getFirstDay();
$start = $start->format('N') === '1' ? $start : $month->getFirstDay()->modify('last monday');
$weeks = $month->getWeeks();
$end = (clone $start)->modify('+' . (6 + 7 * ($weeks - 1)) . ' days');
$events = $events->getEventsByDay($start, $end);
render('header', ['title' => "Mon Calendrier"]);
?>
<div class="calendar">


    <div class="d-flex align-items-center flex-row justify-content-between mx-sm-4">

        <h1> <?= $month->to__String(); ?></h1>
        <div>
            <a href="/?month=<?= $month->previousMonth()->getMonth(); ?>&year=<?= $month->previousMonth()->year; ?>"
               class="btn btn-primary"> &lt </a>
            <a href="/?month=<?= $month->nextMonth()->getMonth(); ?>&year=<?= $month->nextMonth()->year; ?>"
               class="btn btn-primary"> &gt </a>
        </div>
    </div>
    <table class="calendar__table calendar__table-<?= $month->getWeeks(); ?>">
        <?php for ($i = 0; $i < $month->getWeeks(); $i++):
            ?>
            <tr>
                <?php foreach ($month->days as $k => $day):
                    $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");
                    $eventsByDay = $events[$date->format('Y-m-d')] ?? []; ?>
                    <td class="<?= $month->withinMonth($date) ? '' : 'calendar__otherMonth'; ?>">
                        <?php if ($i === 0): ?>
                            <div class="calendar__weekDay"><?= $day; ?></div>
                        <?php endif; ?>
                        <a href="add.php/?date=<?= $date->format('Y-m-d'); ?>" class="calendar__NumberDay"><?= $date->format('d'); ?></a>
                        <?php foreach ($eventsByDay as $event): ?>
                            <div class="calendar__events">
                                <?= (new DateTime($event['start']))->format('H:i'); ?> - <a href="/edit.php/?id=<?= $event['id']; ?>"><?= $event['name']; ?></a>
                            </div>
                        <?php endforeach; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endfor; ?>
    </table>
    <a href="add.php" class="calendar__button">+</a>

</div>

<?php render('footer');?>
