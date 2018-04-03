<?php

    namespace Softease\Calendar;


/**
 * Permet de gérer les mois dans l'application.
 * Class Month
 * @package Softease
 */
class Month
{
    /**
     * @var array
     */
    private $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    /**
     * @var int
     */
    public $month;

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @var int
     */
    public $year;

    /**
     * @var array
     */
    public $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

    /**
     * Month constructor.
     * @param int $month Le mois compris entre 1 et 12
     * @param int $year l'année au format "YYYY"
     * @throws \Exception
     */
    public function __construct($month = null, $year = null)
    {
        if($month === null OR $month < 1 OR $month > 12){
            $month = intval(date('m'));
        }
        if($year === null){
            $year = intval(date('Y'));
        }
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Retourne le mois en toute lettre (ex: Mars 2018)
     * @return string
     */
    public function to__String(): string {
        return $this->months[$this->month - 1]." ".$this->year;
    }

    /**
     * @return int Retour le nombre de semaine dans le mois
     */
    public function getWeeks(): int{
        $start = $this->getFirstDay();
        $end = (clone $start)->modify('+1 month -1 day');
        $weeks = intval($end->format('W')) - intval($start->format('W')) + 1;
        if($weeks < 0){
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }

    /**
     * Renvoi le premier jour du mois.
     */
    public function getFirstDay(){
        return $start = new \DateTime("{$this->year}-{$this->month}-01");
    }

    /**
     * Retourne true si le jour est dans le mois et false si non.
     * @param \DateTime $date
     * @return bool
     */
    public function withinMonth(\DateTime $date): bool {
        return $this->getFirstDay()->format('Y-m') == $date->format('Y-m');
    }

    /**
     * @return Month
     * @throws \Exception
     * Renvoi le mois suivant
     */
    public function nextMonth(): Month{
        $month = $this->month + 1;
        $year = $this->year;
        if($month > 12){
            $month = 1;
            $year += 1;
        }
        return new Month($month, $year);
    }

    /**
     * Renvoi le mois précédent
     * @return Month
     * @throws \Exception
     */
    public function previousMonth(): Month
    {
        $month = $this->month - 1;
        $year = $this->year;
        if($month < 1){
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }
}