<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <script src="./jquery.min.js"></script>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Position the "查看历史" button at the top right */
        .view-history-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        /* Add some space for better positioning of the content */
        body {
            margin-top: 50px; /* Adjust this value as needed */
        }
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit();
}
include "connectdb.php";

// Define filters and pagination parameters
$filters = []; // e.g., ["category = 'appetizer'", "price < 20"]
$params = []; // e.g., [':category' => 'appetizer', ':price' => 20]
$dishesPerPage = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $dishesPerPage;

// Add search or filter functionality
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($searchTerm)) {
    // Modify the filter to search across all fields
    $filters[] = "(dish_name LIKE :searchTerm OR ingredient LIKE :searchTerm OR description LIKE :searchTerm OR type LIKE :searchTerm OR note LIKE :searchTerm)";
    $params[':searchTerm'] = '%' . $searchTerm . '%';
}

// Construct the SQL query for pagination and filtering
$sql = "SELECT * FROM menu";
if (!empty($filters)) {
    $sql .= " WHERE " . implode(" AND ", $filters);
}
$sql .= " LIMIT :offset, :limit";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $dishesPerPage, PDO::PARAM_INT);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$dishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total dishes for pagination
$totalDishes = $conn->query("SELECT COUNT(*) FROM menu")->fetchColumn();
$totalPages = ceil($totalDishes / $dishesPerPage);

if (!empty($dishes)) {
    echo "<div style='margin-left: auto; margin-right: auto; text-align: center; width: 100%; '><h2>Admin Page</h2>";
    echo "<h3 style='color: #ff6699;'>After editing, click <span style='font-weight: bolder; font-size:150%; color: #00aeec;'>Confirm</span> to save changes.</h3>";

    // Search form
    echo "<form method='GET' style='text-align: center; margin-bottom: 20px;'>";
    echo "<input type='text' name='search' value='" . htmlspecialchars($searchTerm) . "' placeholder='Search by any field' style='padding: 10px; width: 300px; border-radius: 5px;'>";
    echo "<button type='submit' style='padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px;'>Search</button>";
    echo "</form>";

    echo "<div><table id='inventoryTable' style='border: 5px #00aeec solid; border-radius: 8px;'>";

    // Table Header
    echo "<tr>";
    foreach (['dish_id', 'dish_name', 'ingredient', 'description', 'type', 'amount', 'note', 'image'] as $column) {
        echo "<th style='width: 200px; text-align: center; border: 1px #00aeec solid; border-radius: 8px;'>". ucwords(str_replace('_', ' ', $column)) ."</th>";
    }
    echo "<th style='border: 1px #00aeec solid; border-radius: 8px; width: 200px;'>Operation</th>";
    echo "</tr>";

    // Display dishes
    foreach ($dishes as $row) {
        echo "<tr>";
        for ($f = 0; $f < 7; $f++) { // 7 columns excluding image
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><div style='overflow: hidden; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical; '>" . htmlspecialchars($row[array_keys($row)[$f]] ?? '') . "</div></td>";
        }

        // Handle image field
        if (isset($row['image'])) {
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' style='width: 175px; height: auto; '><br><input type='file' class='image-upload' data-dishid='" . $row['dish_id'] . "' /></td>";
        } else {
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '>No Image<br><input type='file' class='image-upload' data-dishid='" . $row['dish_id'] . "' /></td>";
        }
        echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><button style='border: 1px white solid; border-radius: 5px; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white; ' onclick=\"EditRow(this)\">Edit</button><br><button style='border: 1px white solid; border-radius: 5px; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white; ' onclick=\"DeletRow(this)\">Delete</button></td>";
        echo "</tr>";
    }

    echo "</table></div></div>";

    // Pagination links
    echo "<div style='text-align: center; margin: 15px; '>Pages: ";
    echo "<a href='?page=1' style='text-decoration: none; padding: 5px; margin: 5px; background-color: #007bff; color: white; border-radius: 5px;'>1</a>";
    echo "<a href='?page=1&search=$searchTerm' style='text-decoration: none; border-radius:5px; border: 1px white solid; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white;'><< First</a>";

    // Other pagination buttons...

    echo "</div>";

    echo "<div style='text-align: center; margin: 15px;'><button style='border-radius:5px; border: 1px white solid; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white;' id='send' name='submit' value='confirm'>Confirm</button></div>";

} else {
    echo "<div style='text-align: center; margin: 15px;'>No dishes found</div>";
}

?>

<div style="text-align: center; margin: 15px;">
    <a href="add_new_item_admin.php" style='text-decoration: none; border-radius:5px; border: 1px white solid; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white;'>Add Item</a>
</div>

<!-- View History button placed at the top right -->
<a href="admin_view_his.php" class="view-history-button">View History</a>

<script>
    $(document).ready(function() {
        $('#send').click(function() {
            update();
        });

        // Handle image upload
        $('.image-upload').change(function() {
            var dishId = $(this).data('dishid');
            var formData = new FormData();
            formData.append('dish_id', dishId);
            formData.append('image', this.files[0]);

            $.ajax({
                url: 'upload_image.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Image uploaded successfully');
                },
                error: function(xhr, status, error) {
                    alert('Error uploading image');
                }
            });
        });
    });

    function EditRow(button) {
        // Implement edit functionality
    }

    function DeletRow(button) {
        // Implement delete functionality
    }

    function update() {
        // Implement update functionality for modified data
    }
</script>

</body>
</html>
