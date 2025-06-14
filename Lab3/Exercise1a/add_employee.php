<?php
session_start(); // Start session to store messages
require_once 'dbconfig.php'; // Include database configuration

// Fetch departments for the dropdown
$departments_sql = "SELECT dept_id, dept_name FROM Department ORDER BY dept_name";
$departments_result = mysqli_query($conn, $departments_sql);
$departments = [];
if ($departments_result && mysqli_num_rows($departments_result) > 0) {
    while ($row = mysqli_fetch_assoc($departments_result)) {
        $departments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="add_employee.php" class="active">Add Employee</a>
        <a href="view_employees.php">View Employees</a>
    </nav>

    <div class="container">
        <h1>Add New Employee</h1>

        <?php
        // Display messages if any
        if (isset($_SESSION['message'])) {
            echo '<div class="message ' . ($_SESSION['message_type'] ?? 'info') . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']); // Clear message after displaying
            unset($_SESSION['message_type']);
        }
        ?>

        <form action="process_employee.php" method="POST" id="addEmployeeForm">
            <div class="form-group">
                <label for="emp_name">Employee Name:</label>
                <input type="text" id="emp_name" name="emp_name" required>
                <span class="error-message" id="nameError"></span>
            </div>

            <div class="form-group">
                <label for="emp_salary">Salary:</label>
                <input type="number" id="emp_salary" name="emp_salary" step="0.01" min="0" required>
                 <span class="error-message" id="salaryError"></span>
            </div>

            <div class="form-group">
                <label for="emp_dept_id">Department:</label>
                <select id="emp_dept_id" name="emp_dept_id" required>
                    <option value="">-- Select Department --</option>
                    <?php if (!empty($departments)): ?>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo htmlspecialchars($dept['dept_id']); ?>">
                                <?php echo htmlspecialchars($dept['dept_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No departments found. Please add departments first.</option>
                    <?php endif; ?>
                </select>
                 <span class="error-message" id="deptError"></span>
            </div>

            <button type="submit">Add Employee</button>
        </form>
    </div>

    <script>
        // Basic client-side validation for interactivity
        const form = document.getElementById('addEmployeeForm');
        const nameInput = document.getElementById('emp_name');
        const salaryInput = document.getElementById('emp_salary');
        const deptSelect = document.getElementById('emp_dept_id');

        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Clear previous errors
            document.getElementById('nameError').textContent = '';
            document.getElementById('salaryError').textContent = '';
            document.getElementById('deptError').textContent = '';
            nameInput.style.borderColor = '#ccc';
            salaryInput.style.borderColor = '#ccc';
            deptSelect.style.borderColor = '#ccc';


            if (nameInput.value.trim() === '') {
                document.getElementById('nameError').textContent = 'Employee name is required.';
                nameInput.style.borderColor = 'red';
                isValid = false;
            }

            if (salaryInput.value.trim() === '' || parseFloat(salaryInput.value) <= 0) {
                document.getElementById('salaryError').textContent = 'Please enter a valid positive salary.';
                salaryInput.style.borderColor = 'red';
                isValid = false;
            }

            if (deptSelect.value === '') {
                document.getElementById('deptError').textContent = 'Please select a department.';
                deptSelect.style.borderColor = 'red';
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
            }
        });

        // Style inputs on focus/blur for interactivity
        [nameInput, salaryInput, deptSelect].forEach(input => {
            input.addEventListener('focus', () => input.style.borderColor = '#007bff');
            input.addEventListener('blur', () => {
                if (!input.value && input.hasAttribute('required')) { // Check if still invalid on blur
                    // Keep red border if invalid, otherwise revert
                    if ( (input === nameInput && nameInput.value.trim() === '') ||
                         (input === salaryInput && (salaryInput.value.trim() === '' || parseFloat(salaryInput.value) <= 0)) ||
                         (input === deptSelect && deptSelect.value === '') ) {
                        // do nothing, error message will still be there or re-validate on submit
                    } else {
                         input.style.borderColor = '#ccc';
                    }
                } else if (input.value) {
                    input.style.borderColor = '#ccc'; // Revert if valid
                }
            });
        });
    </script>
</body>
</html>