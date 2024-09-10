<?php
session_start();
require_once 'db.php';

// Redirect to login page if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Initialize form token if not already set
if (empty($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

// Handle car upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form token
    if (isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['form_token']) {
        // Unset the token to prevent resubmission
        unset($_SESSION['form_token']);

        // Sanitize and retrieve form data
        $model = htmlspecialchars($_POST['model']);
        $description = htmlspecialchars($_POST['description']);
        $price_per_day = floatval($_POST['price_per_day']);
        $image = $_FILES['car_image'];

        // Validate image file
        $target_dir = "images/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            $uploadOk = 0;
            $error = "File is not an image.";
        }

        // Check file size (5MB max)
        if ($image["size"] > 5000000) {
            $uploadOk = 0;
            $error = "Sorry, your file is too large.";
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $uploadOk = 0;
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Check for existing car
                $stmt = $pdo->prepare("SELECT * FROM cars WHERE model = :model");
                $stmt->execute(['model' => $model]);
                if ($stmt->rowCount() > 0) {
                    $error = "Car with this model already exists.";
                } else {
                    // Insert car details into the database
                    $query = "INSERT INTO cars (model, description, price_per_day, image) VALUES (:model, :description, :price_per_day, :image)";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        'model' => $model,
                        'description' => $description,
                        'price_per_day' => $price_per_day,
                        'image' => basename($image["name"])
                    ]);
                    $success = "Car added successfully.";
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $error = "Invalid form submission.";
    }
}

// Fetch all cars for display
$query = "SELECT * FROM cars";
$stmt = $pdo->query($query);
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Car Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        nav a {
            color: #fff;
            margin: 0 15px;
            text-decoration: none;
        }
        main {
            padding: 20px;
        }
        h2, h3 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        button {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        .car-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* Reduced gap between cards */
        }
        .car-item {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: calc(16.66% - 10px); /* Adjusted width for six items per row */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            box-sizing: border-box;
        }
        .car-item img {
            max-width: 100%;
            height: auto;
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px;
        }
        .car-details h3 {
            margin: 10px 0;
            font-size: 14px;
            color: #333;
        }
        .car-details p {
            font-size: 12px;
        }
        .price {
            color: #555;
            font-weight: bold;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Home</a>
            <a href="admin.php">Admin</a>
        </nav>
    </header>
    <main>
        <h2>Admin - Car Management</h2>
        
        <!-- Car Upload Form -->
        <h3>Add New Car</h3>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="form_token" value="<?php echo htmlspecialchars($_SESSION['form_token']); ?>">
            
            <label for="model">Car Model:</label>
            <input type="text" name="model" id="model" required>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            
            <label for="price_per_day">Price Per Day ($):</label>
            <input type="number" step="0.01" name="price_per_day" id="price_per_day" required>
            
            <label for="car_image">Car Image:</label>
            <input type="file" name="car_image" id="car_image" accept="image/*" required>
            
            <button type="submit" name="submit">Add Car</button>
        </form>
        
        <!-- View Cars -->
        <h3>Available Cars</h3>
        <div class="car-list">
            <?php foreach ($cars as $car): ?>
                <div class="car-item">
                    <img src="images/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
                    <div class="car-details">
                        <h3><?php echo htmlspecialchars($car['model']); ?></h3>
                        <p><?php echo htmlspecialchars($car['description']); ?></p>
                        <p class="price">₹<?php echo htmlspecialchars($car['price_per_day']); ?> per day</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
