<?php
require_once '../config/db.php';
require_once '../includes/auth.php';
include '../includes/header.php';
?>
<h2>Reports & Insights</h2>
<ul class="list-group">
  <li class="list-group-item">
    <a href="?report=occupancy">Occupancy by Hostel (View)</a>
  </li>
  <li class="list-group-item">
    <a href="export_bookings.php">Export Bookings CSV (if exists)</a>
  </li>
</ul>
<?php
if (isset($_GET['report']) && $_GET['report'] == 'occupancy') {
    $res = mysqli_query($conn, "SELECT h.name, SUM(r.current_occupancy) as occupied, SUM(r.capacity) as total_cap FROM hostels h JOIN rooms r ON h.id=r.hostel_id GROUP BY h.id");
    echo "<table class='table mt-3'><thead><tr><th>Hostel</th><th>Occupied</th><th>Capacity</th><th>Rate</th></tr></thead><tbody>";
    while($row = mysqli_fetch_assoc($res)) {
        $rate = $row['total_cap']>0 ? round(($row['occupied']/$row['total_cap'])*100,1).'%' : 'N/A';
        echo "<tr><td>{$row['name']}</td><td>{$row['occupied']}</td><td>{$row['total_cap']}</td><td>$rate</td></tr>";
    }
    echo "</tbody></table>";
}
include '../includes/footer.php';
?>