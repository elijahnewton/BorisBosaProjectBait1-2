<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) { exit; }
$result = mysqli_query($conn, "SELECT h.name, COUNT(b.id) as total FROM hostels h LEFT JOIN rooms r ON h.id=r.hostel_id LEFT JOIN bookings b ON r.id=b.room_id GROUP BY h.id");
$labels = []; $values = [];
while($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['name'];
    $values[] = (int)$row['total'];
}
echo json_encode(['labels'=>$labels, 'values'=>$values]);
?>