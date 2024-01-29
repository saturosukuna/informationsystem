<?php
// Check if image data was sent
if (isset($_POST['imageData'])) {
    // Decode base64 image data
    $imageData = base64_decode(str_replace('data:image/jpeg;base64,', '', $_POST['imageData']));

    // Specify the directory where you want to save the images (optional)
    $uploadDir = 'uploads/';
    
    // Generate a unique filename
    $filename = uniqid('image_') . '.jpg';
    
    // Save the image to the specified directory (optional)
    file_put_contents($uploadDir . $filename, $imageData);

    $servername = "localhost";
    $username = "root";
    $password = "Rajesh@123";
    $dbname = "rajesh";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $insertSql = "INSERT INTO uploaded_images (image_data, filename) VALUES (?, ?)";
    $stmt = $conn->prepare($insertSql);

    // Bind parameters with type specifier 'bs' (blob and string)
    $stmt->bind_param('bs', $imageData, $filename);

    if ($stmt->execute()) {
        // Display the uploaded image
        echo "<h2>Image uploaded and saved successfully</h2>";
        echo "<img src='display_image.php?filename=$filename' alt='Uploaded Image' style='width: 300px;'>";
    } else {
        echo "<h2>Failed to insert image information into the database</h2>";
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
} else {
    echo "<h2>No image data received</h2>";
}
?>
