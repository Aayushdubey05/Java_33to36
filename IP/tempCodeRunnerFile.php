<?php
$servername = "localhost";
$username = "root";
$password = "Beechem@31426";
$dbname = "form_data";

// Function to connect to the database
function connectDatabase() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Function to insert data into the database
function insertData($year, $month, $title, $file_path) {
    $conn = connectDatabase();
    $sql = "INSERT INTO FacultySubmissions (year, month, title, file_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $year, $month, $title, $file_path);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $title = $_POST['title'];
    $file_path = 'uploads/' . basename($_FILES["file"]["name"]);

    // Check if the uploads directory exists
    if (!is_dir('uploads')) {
        if (!mkdir('uploads', 0755, true)) {
            die("Failed to create directory.");
        }
    }

    // Save the uploaded file to the server
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
        insertData($year, $month, $title, $file_path);
    } else {
        echo "Sorry, there was an error uploading your file.";
        echo "Error details: ";
        print_r(error_get_last());
    }
}

// Function to retrieve data from the database
function retrieveData() {
    $conn = connectDatabase();
    $sql = "SELECT id, year, month, title, file_path FROM FacultySubmissions";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"] . " - Year: " . $row["year"] . " - Month: " . $row["month"] . " - Title: " . $row["title"] . " - File Path: " . $row["file_path"] . "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
}

// Function to update data in the database
function updateData($id, $new_title) {
    $conn = connectDatabase();
    $sql = "UPDATE FacultySubmissions SET title=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_title, $id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Function to delete data from the database
function deleteData($id) {
    $conn = connectDatabase();
    $sql = "DELETE FROM FacultySubmissions WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>