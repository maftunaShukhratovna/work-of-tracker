<?php

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

        $query = "INSERT INTO daily (name, arrived_at, leaved_at, required_of) VALUES (:name, :arrived_at, :leaved_at, :required_of)";
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
}
