<?php
require 'DB.php';
$db=new DB();
$pdo= $db->getPDO();

class Tracker {
    private $pdo;

    const REQUIRED_HOUR_DURATION = 8 * 3600;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addRecord($name, $arrived_at, $leaved_at) {
        $arrived_at = new DateTime($arrived_at);
        $leaved_at = new DateTime($leaved_at);

        $worked_seconds = $leaved_at->getTimestamp() - $arrived_at->getTimestamp();
        $required_of = self::REQUIRED_HOUR_DURATION - $worked_seconds;

        $query = "INSERT INTO daily (name, arrived_at, leaved_at, required_of) 
                  VALUES (:name, :arrived_at, :leaved_at, :required_of)";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindValue(':arrived_at', $arrived_at->format('Y-m-d H:i'));
        $stmt->bindValue(':leaved_at', $leaved_at->format('Y-m-d H:i'));
        $stmt->bindParam(':required_of', $required_of);

        $stmt->execute();
    }

    public function getAllRecords() {
        $query = "SELECT * FROM daily";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll();
    }

    public function calculateRequiredWork() {
        $query = "SELECT name, SUM(required_of) AS total_required_of FROM daily GROUP BY name";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsDone($name) {
        $query = "UPDATE daily SET required_of = 0 WHERE name = :name";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
    }

    public function getWorkDayListwithPagination(int $offset){
        $offset=$offset?($offset*10)-10:0;
        $query="SELECT * FROM daily ORDER BY arrived_at DESC 10 OFFSET ".$offset;
        $stmt = $this->pdo->query($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRecords(){
        $query="SELECT COUNT(id) as pageCount FROM daily";
        $stmt= $this->pdo->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function calculatePageCount(){
        $total=$this->getTotalRecords()['pageCount'];
        return ceil($total/10);
    }
}
?>
