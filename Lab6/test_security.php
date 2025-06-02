<?php
require_once 'csrf_token.php';
require_once '../Lab5/Exercise1,2,3,4(Final)/includes/db_connect.php';

// Process form submissions
$sql_result = $xss_output = $csrf_status = '';

// SQL Injection Test
if (isset($_POST['sql_test'])) {
    $user_input = $_POST['sql_input'];
    
    // Vulnerable query (DO NOT USE IN PRODUCTION)
    $unsafe_query = "SELECT * FROM Books WHERE title LIKE '%$user_input%'";
    
    // Safe query using prepared statement
    $stmt = $db->prepare("SELECT * FROM Books WHERE title LIKE ?");
    $search_term = "%" . $_POST['sql_input'] . "%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sql_result = "<div class='result-box'>";
    $sql_result .= "<h4>Unsafe Query (Vulnerable):</h4>";
    $sql_result .= "<pre>" . htmlspecialchars($unsafe_query) . "</pre>";
    $sql_result .= "<h4>Safe Query (Using Prepared Statement):</h4>";
    $sql_result .= "<pre>SELECT * FROM Books WHERE title LIKE ?</pre>";
    $sql_result .= "<p>Parameter: " . htmlspecialchars($search_term) . "</p>";
    
    if ($result->num_rows > 0) {
        $sql_result .= "<h4>Results:</h4><ul>";
        while ($row = $result->fetch_assoc()) {
            $sql_result .= "<li>" . htmlspecialchars($row['title']) . "</li>";
        }
        $sql_result .= "</ul>";
    } else {
        $sql_result .= "<p>No results found.</p>";
    }
    $sql_result .= "</div>";
}

// XSS Test
if (isset($_POST['xss_test'])) {
    $user_input = $_POST['xss_input'];
    
    // Unsafe output (vulnerable to XSS)
    $unsafe_output = "Unsafe output: " . $user_input;
    
    // Safe output (protected against XSS)
    $safe_output = "Safe output: " . htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
    
    $xss_output = "<div class='result-box'>";
    $xss_output .= "<h4>Unsafe Output (Vulnerable):</h4>";
    $xss_output .= $unsafe_output;
    $xss_output .= "<h4>Safe Output (Using htmlspecialchars):</h4>";
    $xss_output .= $safe_output;
    $xss_output .= "</div>";
}

// CSRF Test
if (isset($_POST['csrf_test'])) {
    if (isset($_POST['csrf_token'])) {
        if (validate_csrf_token($_POST['csrf_token'], 'csrf_form')) {
            $csrf_status = "<div class='result-box success'>";
            $csrf_status .= "<h4>CSRF Protection Working!</h4>";
            $csrf_status .= "<p>The token was validated successfully.</p>";
            $csrf_status .= "</div>";
        } else {
            $csrf_status = "<div class='result-box error'>";
            $csrf_status .= "<h4>Invalid CSRF Token!</h4>";
            $csrf_status .= "<p>The token was invalid or expired.</p>";
            $csrf_status .= "</div>";
        }
    } else {
        $csrf_status = "<div class='result-box error'>";
        $csrf_status .= "<h4>Missing CSRF Token!</h4>";
        $csrf_status .= "<p>No CSRF token was provided with the form submission.</p>";
        $csrf_status .= "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Testing Page</title>
    <link rel="stylesheet" href="../Lab5/Exercise1,2,3,4(Final)/css/style.css">
    <style>
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 10px var(--shadow-medium);
        }
        .test-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
        }
        .result-box {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 4px solid var(--info-color);
        }
        .result-box.success {
            border-left: 4px solid var(--secondary-color);
            background-color: rgba(39, 174, 96, 0.1);
        }
        .result-box.error {
            border-left: 4px solid var(--danger-color);
            background-color: rgba(192, 57, 43, 0.1);
        }
        pre {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            margin-right: 15px;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Security Testing Page</h1>
        <p>This page demonstrates common web security vulnerabilities and how to protect against them.</p>
        
        <div class="nav-links">
            <a href="../Lab5/Exercise1,2,3,4(Final)/dashboard.php">Back to Dashboard</a>
        </div>
        
        <!-- SQL Injection Test -->
        <div class="test-section">
            <h2>SQL Injection Test</h2>
            <p>Try entering a SQL injection payload like: <code>a' OR '1'='1</code></p>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="sql_input">Search for a book:</label>
                    <input type="text" id="sql_input" name="sql_input" class="form-control" 
                           placeholder="Enter book title or SQL injection" 
                           value="<?php echo isset($_POST['sql_input']) ? htmlspecialchars($_POST['sql_input']) : ''; ?>">
                </div>
                <button type="submit" name="sql_test" class="btn btn-primary">Test SQL Injection</button>
            </form>
            
            <?php echo $sql_result; ?>
        </div>
        
        <!-- XSS Test -->
        <div class="test-section">
            <h2>Cross-Site Scripting (XSS) Test</h2>
            <p>Try entering an XSS payload like: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></p>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="xss_input">Enter text with script tags:</label>
                    <input type="text" id="xss_input" name="xss_input" class="form-control" 
                           placeholder="Enter text or XSS payload" 
                           value="<?php echo isset($_POST['xss_input']) ? htmlspecialchars($_POST['xss_input']) : ''; ?>">
                </div>
                <button type="submit" name="xss_test" class="btn btn-primary">Test XSS</button>
            </form>
            
            <?php echo $xss_output; ?>
        </div>
        
        <!-- CSRF Test -->
        <div class="test-section">
            <h2>CSRF Protection Test</h2>
            <p>This form is protected with a CSRF token. Try submitting, then refresh and submit again.</p>
            
            <form method="post" action="">
                <?php echo csrf_token_input('csrf_form'); ?>
                <div class="form-group">
                    <label for="csrf_demo">Demo field (not used):</label>
                    <input type="text" id="csrf_demo" name="csrf_demo" class="form-control" value="Protected form">
                </div>
                <button type="submit" name="csrf_test" class="btn btn-primary">Test CSRF Protection</button>
            </form>
            
            <?php echo $csrf_status; ?>
        </div>
    </div>
</body>
</html>