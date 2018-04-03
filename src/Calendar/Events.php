<?php
/**
 * Created by PhpStorm.
 * User: Maxime
 * Date: 01/04/2018
 * Time: 21:37
 */

namespace Softease\Calendar;


class Events
{
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {

        $this->pdo = $pdo;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     *
     * Retourne les événements commencant entre deux dates
     */
    public function getEventsBetween(\DateTime $start, \DateTime $end): array
    {
        $sql = "SELECT * FROM Events WHERE start BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59' )}' ORDER BY start ASC";
        $statement = $this->pdo->query($sql);
        $resultat = $statement->fetchAll();

        return $resultat;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     * Retourne les évenements entre deux dates indexé par jours.
     */
    public function getEventsByDay(\DateTime $start, \DateTime $end): ?array
    {
        $events = $this->getEventsBetween($start, $end);
        $days = [];
        foreach ($events as $event) {
            $date = explode(' ', $event['start'])[0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            } else {
                $days[$date][] = $event;
            }
        }
        return $days;
    }

    /**
     * @param int $id
     * Récupère un évènement
     * @return mixed
     * @throws \Exception
     */
    public function find(int $id): Event
    {
        require 'Event.php';
        $statement =  $this->pdo->query("SELECT * FROM Events WHERE id = $id");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Softease\Calendar\Event::class);
        $result = $statement->fetch();
        if($result == null){
            throw new \Exception('Aucun resultat n\'a été trouvé.');
        }
        return $result;
    }

    /**
     * @param Event $event  Crée un évènement dans la base de données
     */
    public function create(Event $event){

        $statement = $this->pdo->prepare('INSERT INTO Events (name, description, start, end) VALUES (?,?,?,?)');
        $statement->execute([
            $event->getName(),
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i'),
            $event->getEnd()->format('Y-m-d H:i'),
        ]);
    }

    /**
     * @param Event $event Met à jour un évènement dans la base de données
     */
    public function update(Event $event){

        $statement = $this->pdo->prepare('UPDATE Events SET name = ?, description = ?, start = ?, end = ? WHERE id = ?');
        $statement->execute([
            $event->getName(),
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i'),
            $event->getEnd()->format('Y-m-d H:i'),
            $event->getId(),
        ]);
    }
}