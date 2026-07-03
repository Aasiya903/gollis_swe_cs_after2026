<?php
include '../config/db.php';

$result = mysqli_query($conn,
    "SELECT * FROM students");
?>

<table border="1">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Age</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['student_name']; ?></td>
    <td><?= $row['gender']; ?></td>
    <td><?= $row['age']; ?></td>
</tr>

<?php } ?>

</table>