<?php
include 'db.php';

// Handle insert logic
if (isset($_POST['insert'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "INSERT INTO users (name, email, age) VALUES ('$name', '$email', '$age')";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Pagination logic
$limit = 5; // Number of users per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search logic
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $search_query = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $search_query = "";
}

// Fetch total count of users
$total_result = $conn->query("SELECT COUNT(*) AS total FROM users $search_query")->fetch_assoc();
$total_users = $total_result['total'];
$total_pages = ceil($total_users / $limit);

// Fetch paginated and filtered users
$sql = "SELECT * FROM users $search_query LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Application</title>
    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        /* Container for the whole content */
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* Form Styling */
        form {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin: 5px 0;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="number"],
        form input[type="submit"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #218838;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f8f9fa;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Pagination */
        .pagination {
            text-align: center;
            margin: 20px 0;
        }

        .pagination a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            margin: 0 5px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        /* Action buttons */
        .actions a {
            padding: 8px 15px;
            text-decoration: none;
            background-color: #17a2b8;
            color: white;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .actions a:hover {
            background-color: #138496;
        }

        .actions a.delete {
            background-color: #dc3545;
        }

        .actions a.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Users List</h1>

        <!-- Insert New User Form -->
        <h2>Add New User</h2>
        <form method="POST" action="">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Age:</label>
            <input type="number" name="age" required>

            <input type="submit" name="insert" value="Insert">
        </form>

        <!-- Search form -->
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search by name or email" value="<?php echo $search; ?>">
            <input type="submit" value="Search">
            <a href="index.php">Reset</a>
        </form>

        <!-- Users Table -->
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No users found</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>

</body>
</html>
