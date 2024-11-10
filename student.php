<?php
class Student
{
    private $conn;
    private $id;

    public function __construct($dbConnection, $userId)
    {
        $this->conn = $dbConnection;
        $this->id = $userId;
    }

    public function getRequests()
    {
        $stmt = $this->conn->prepare("SELECT g.id, l.name, g.date, g.time_period, l.image FROM guidances g JOIN lecturers l ON l.id = g.lecturer_id WHERE g.student_id = ? AND g.status = 'Pending'");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getSchedule()
    {
        $stmt = $this->conn->prepare("SELECT g.id, l.name, g.date, g.time_period, l.image, g.media, g.status, g.reason FROM guidances g JOIN lecturers l ON l.id = g.lecturer_id WHERE g.student_id = ? AND (g.status = 'Diterima' OR g.status = 'Dibatalkan')");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function cancelRequest($requestId)
    {
        $stmt = $this->conn->prepare("DELETE FROM guidances WHERE id = ?");
        $stmt->bind_param("i", $requestId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function getLecturers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM lecturers l JOIN supervisors s ON l.id = s.lecturer_id WHERE s.student_id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }
}
?>
