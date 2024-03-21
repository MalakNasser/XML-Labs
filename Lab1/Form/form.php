<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .employee-details {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
        }

        .d-flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .flex-column-center {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #search-button {
            margin-top: 15px;
        }

        .search-results {
            width: 50%;
        }

        .search-results h2 {
            margin-bottom: 10px;
        }

        .search-results .employee-details {
            background-color: #f8f9fa;
        }

        #employeeForm {
            width: 50%;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }
    </style>
</head>

<body class="d-flex-center">

    <?php
    function loadEmployees()
    {
        $xml = simplexml_load_file('Employee Data.xml');
        return $xml;
    }

    function saveEmployees($xml)
    {
        $xml->asXML('Employee Data.xml');
    }

    function displayEmployee($employee)
    {
        echo "<h2>employee Details</h2>";
        echo "Name: {$employee->name}<br>";
        echo "Phone: {$employee->phone}<br>";
        echo "Address: {$employee->address}<br>";
        echo "Email: {$employee->email}<br>";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        $employees = loadEmployees();
        if (isset($_POST['index']))
            $index = (int)$_POST['index'];

        switch ($action) {
            case 'insert':
                $newEmployee = $employees->addChild('employee');
                $newEmployee->addChild('name', $_POST['name']);
                $newEmployee->addChild('phone', $_POST['phone']);
                $newEmployee->addChild('address', $_POST['address']);
                $newEmployee->addChild('email', $_POST['email']);
                break;
            case 'update':
                if (isset($employees->employee[$index])) {
                    $employees->employee[$index]->name = $_POST['name'];
                    $employees->employee[$index]->phone = $_POST['phone'];
                    $employees->employee[$index]->address = $_POST['address'];
                    $employees->employee[$index]->email = $_POST['email'];
                }
                break;
            case 'delete':
                unset($employees->employee[$index]);
                if (count($employees->employee) > 1)
                    $index = ($index + 1) % count($employees->employee);
                break;
            case 'search':
                $searchTerm = $_POST['search_term'];
                $foundEmployees = array();
                foreach ($employees->employee as $employee) {
                    if (stripos($employee->name, $searchTerm) !== false) {
                        $foundEmployees[] = $employee;
                    }
                }
                echo "<div class='search-results'>";
                echo "<h2>Search Results</h2>";
                if (!empty($foundEmployees)) {
                    echo "<p>Total Results: " . count($foundEmployees) . "</p>";
                    foreach ($foundEmployees as $foundEmployee) {
                        echo "<div class='employee-details'>";
                        echo "<h3>{$foundEmployee->name}</h3>";
                        echo "<p><strong>Phone:</strong> {$foundEmployee->phone}</p>";
                        echo "<p><strong>Address:</strong> {$foundEmployee->address}</p>";
                        echo "<p><strong>Email:</strong> {$foundEmployee->email}</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No employee found matching the search term '{$searchTerm}'.</p>";
                }
                echo "</div>";
                exit;
                break;
            case 'next':
                $index = $index + 1;
                break;
            case 'prev':
                $index = $index - 1;
                break;
        }

        saveEmployees($employees);

        header("Location: {$_SERVER['PHP_SELF']}?index=$index");
        exit;
    }

    $employees = loadEmployees();
    $totalEmployees = count($employees->employee);

    if (isset($_GET['index'])) {
        $index = (int)$_GET['index'];
    } else {
        $index = 0;
    }

    $employee = isset($employees->employee[$index]) ? $employees->employee[$index] : null;
    ?>

    <div class="container flex-column-center">
        <h2 class="mb-4">Employee Management System</h2>
        <form id="searchForm" method='post' class="d-flex align-items-center justify-cont">
            <div class="form-group mr-2">
                <label for="search_term">Search by name:</label>
                <input type='text' name='search_term' id='search_term' class="form-control" placeholder='Enter name'>
            </div>
            <button type="submit" name='action' value='search' id='search-button' class="btn btn-primary">Search</button>
        </form>

        <form id="employeeForm" method='post'>
            <input type='hidden' name='index' value='<?php echo $index; ?>'>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type='text' name='name' id='name' class="form-control" placeholder='Enter name' value='<?php echo $employee ? $employee->name : ''; ?>'>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type='text' name='phone' id='phone' class="form-control" placeholder='Enter phone' value='<?php echo $employee ? $employee->phone : ''; ?>'>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type='text' name='address' id='address' class="form-control" placeholder='Enter address' value='<?php echo $employee ? $employee->address : ''; ?>'>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type='text' name='email' id='email' class="form-control" placeholder='Enter email' value='<?php echo $employee ? $employee->email : ''; ?>'>
            </div>
            <div class="buttons">
                <button type='submit' name='action' value='insert' class="btn btn-success">Insert</button>
                <button type='submit' name='action' value='update' class="btn btn-primary">Update</button>
                <button type='submit' name='action' value='delete' class="btn btn-danger">Delete</button>
                <button type="submit" name="action" value="prev" id="prevButton" class="btn btn-secondary <?php echo $index == 0 || $totalEmployees <= 1 ? 'disabled' : ''; ?>" <?php echo $totalEmployees <= 1 ? 'disabled' : ''; ?>>Prev</button>
                <button type="submit" name="action" value="next" id="nextButton" class="btn btn-secondary <?php echo $index == $totalEmployees - 1 || $totalEmployees <= 1 ? 'disabled' : ''; ?>" <?php echo $totalEmployees <= 1 ? 'disabled' : ''; ?>>Next</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('prevButton').addEventListener('click', function(event) {
                if (this.classList.contains('disabled')) {
                    event.preventDefault();
                }
            });

            document.getElementById('nextButton').addEventListener('click', function(event) {
                if (this.classList.contains('disabled')) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>