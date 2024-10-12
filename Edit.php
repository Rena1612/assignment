<?php
include 'db.php';

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    $sql = "UPDATE users SET name='$name', email='$email', age='$age' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form method="POST" action="">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
        <label>Age:</label><br>
        <input type="number" name="age" value="<?php echo $user['age']; ?>" required><br><br>
        <input type="submit" name="submit" value="Update">
    </form>
</body>
</html>
