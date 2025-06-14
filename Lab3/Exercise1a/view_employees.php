<?php
session_start(); // For potential messages, though not directly used here for display
require_once 'dbconfig.php'; // Include database configuration

// SQL to fetch employee data with department name using INNER JOIN
$sql = "SELECT e.emp_id, e.emp_name, e.emp_salary, d.dept_name, d.dept_location
        FROM Employee e
        INNER JOIN Department d ON e.emp_dept_id = d.dept_id
        ORDER BY e.emp_name ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="add_employee.php">Add Employee</a>
        <a href="view_employees.php" class="active">View Employees</a>
    </nav>

    <div class="container table-container">
        <h1>Employee List</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . ($_SESSION['message_type'] ?? 'info') . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>Department</th>
                        <th>Dept. Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['emp_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['emp_name']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['emp_salary'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['dept_location']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($result): // Query successful, but no rows ?>
            <p class="message info">No employees found. <a href="add_employee.php">Add one now!</a></p>
        <?php else: // Query failed ?>
            <p class="message error">Error fetching employee data: <?php echo mysqli_error($conn); ?></p>
        <?php endif; ?>
    </div>

    <?php
    // Close connection
    if ($result) {
        mysqli_free_result($result);
    }
    mysqli_close($conn);
    ?>
</body>
</html>