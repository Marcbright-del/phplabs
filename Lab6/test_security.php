<?php
require_once 'csrf_token.php';
<<<<<<< HEAD
require_once '../Lab5/Exercise4/includes/db_connect.php';
=======
require_once '../Lab5/Exercise1,2,3,4(Final)/includes/db_connect.php';
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df

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
<<<<<<< HEAD
    <title>Security Testing - Lab 6</title>
=======
    <title>Security Testing Page</title>
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
    <link rel="stylesheet" href="../Lab5/Exercise1,2,3,4(Final)/css/style.css">
    <style>
        .container {
            max-width: 1000px;
<<<<<<< HEAD
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-radius: 8px;
        }

        .page-header h1 {
            margin: 0 0 10px 0;
            font-size: 2rem;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
        }

        .nav-breadcrumb {
            margin-bottom: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .nav-breadcrumb a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }

        .nav-breadcrumb a:hover {
            text-decoration: underline;
        }

        .test-section {
            margin-bottom: 40px;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
        }

        .test-section h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.4rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .test-description {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }

        .test-form {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .result-box {
            margin-top: 20px;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #3498db;
            background: #f8f9fa;
        }

        .result-box.success {
            border-left-color: #27ae60;
            background: #d5f4e6;
        }

        .result-box.error {
            border-left-color: #e74c3c;
            background: #fdeaea;
        }

        .result-box h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }

        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 13px;
            margin: 10px 0;
        }

        code {
            background: #f1f2f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #e74c3c;
        }

        .security-tip {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
        }

        .security-tip h5 {
            margin: 0 0 10px 0;
            color: #856404;
        }

        .security-tip ul {
            margin: 0;
            padding-left: 20px;
        }

        .security-tip li {
            margin-bottom: 5px;
=======
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
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
        }
    </style>
</head>
<body>
    <div class="container">
<<<<<<< HEAD
        <div class="page-header">
            <h1>🔒 Security Testing</h1>
            <p>Test common web vulnerabilities and learn how to protect against them</p>
        </div>

        <div class="nav-breadcrumb">
            <a href="../Lab5/Exercise1,2,3,4(Final)/dashboard.php">← Back to Dashboard</a>
=======
        <h1>Security Testing Page</h1>
        <p>This page demonstrates common web security vulnerabilities and how to protect against them.</p>
        
        <div class="nav-links">
            <a href="../Lab5/Exercise1,2,3,4(Final)/dashboard.php">Back to Dashboard</a>
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
        </div>
        
        <!-- SQL Injection Test -->
        <div class="test-section">
            <h2>SQL Injection Test</h2>
<<<<<<< HEAD

            <div class="test-description">
                <p><strong>What is SQL Injection?</strong></p>
                <p>SQL injection is a vulnerability that allows attackers to manipulate database queries by injecting malicious SQL code through user inputs.</p>
                <p>Try entering: <code>a' OR '1'='1</code> to see how it's handled.</p>
            </div>

            <div class="test-form">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="sql_input">Search for a book:</label>
                        <input type="text" id="sql_input" name="sql_input" class="form-control"
                               placeholder="Enter book title (try: a' OR '1'='1)"
                               value="<?php echo isset($_POST['sql_input']) ? htmlspecialchars($_POST['sql_input']) : ''; ?>">
                    </div>
                    <button type="submit" name="sql_test" class="btn btn-primary">Test SQL Injection</button>
                </form>
            </div>

            <?php echo $sql_result; ?>

            <div class="security-tip">
                <h5>🛡️ Protection Methods</h5>
                <ul>
                    <li><strong>Prepared Statements:</strong> Use parameterized queries</li>
                    <li><strong>Input Validation:</strong> Validate and sanitize all user inputs</li>
                    <li><strong>Least Privilege:</strong> Use database accounts with minimal permissions</li>
                </ul>
            </div>
=======
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
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
        </div>
        
        <!-- XSS Test -->
        <div class="test-section">
            <h2>Cross-Site Scripting (XSS) Test</h2>
<<<<<<< HEAD

            <div class="test-description">
                <p><strong>What is XSS?</strong></p>
                <p>Cross-Site Scripting allows attackers to inject malicious scripts into web pages. These scripts can steal data or perform actions on behalf of users.</p>
                <p>Try entering: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code> to see how it's handled.</p>
            </div>

            <div class="test-form">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="xss_input">Enter text with script tags:</label>
                        <input type="text" id="xss_input" name="xss_input" class="form-control"
                               placeholder="Try: &lt;script&gt;alert('XSS')&lt;/script&gt;"
                               value="<?php echo isset($_POST['xss_input']) ? htmlspecialchars($_POST['xss_input']) : ''; ?>">
                    </div>
                    <button type="submit" name="xss_test" class="btn btn-primary">Test XSS</button>
                </form>
            </div>

            <?php echo $xss_output; ?>

            <div class="security-tip">
                <h5>🛡️ Protection Methods</h5>
                <ul>
                    <li><strong>Output Encoding:</strong> Use htmlspecialchars() to escape output</li>
                    <li><strong>Content Security Policy:</strong> Implement CSP headers</li>
                    <li><strong>Input Validation:</strong> Validate and filter user inputs</li>
                </ul>
            </div>
=======
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
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
        </div>
        
        <!-- CSRF Test -->
        <div class="test-section">
            <h2>CSRF Protection Test</h2>
<<<<<<< HEAD

            <div class="test-description">
                <p><strong>What is CSRF?</strong></p>
                <p>Cross-Site Request Forgery tricks users into performing unwanted actions on applications where they're authenticated.</p>
                <p>This form is protected with a CSRF token. Try submitting, then refresh and submit again.</p>
            </div>

            <div class="test-form">
                <form method="post" action="">
                    <?php echo csrf_token_input('csrf_form'); ?>
                    <div class="form-group">
                        <label for="csrf_demo">Demo field (protected by CSRF token):</label>
                        <input type="text" id="csrf_demo" name="csrf_demo" class="form-control"
                               value="Protected form submission">
                    </div>
                    <button type="submit" name="csrf_test" class="btn btn-primary">Test CSRF Protection</button>
                </form>
            </div>

            <?php echo $csrf_status; ?>

            <div class="security-tip">
                <h5>🛡️ Protection Methods</h5>
                <ul>
                    <li><strong>CSRF Tokens:</strong> Include unique tokens in forms</li>
                    <li><strong>SameSite Cookies:</strong> Use SameSite cookie attribute</li>
                    <li><strong>Referer Validation:</strong> Check the HTTP Referer header</li>
                </ul>
            </div>
=======
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
>>>>>>> 262e7bb692948c8d24078d0b5cb8f1daf4c050df
        </div>
    </div>
</body>
</html>