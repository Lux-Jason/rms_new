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
        /* Pagination buttons */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a,
        .pagination button {
            padding: 8px 16px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            border: none;
        }
        .pagination a:hover,
        .pagination button:hover {
            background-color: #0056b3;
        }
        .pagination .disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }
        /* General button styling */
        .btn {
            border-radius: 5px;
            border: 1px solid white;
            padding: 10px;
            margin: 10px;
            width: 80px;
            background-color: #007bff;
            color: white;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            position: relative;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
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
if(isset($_FILES['image']) && isset($_POST['dish_id'])) {
    $dish_id = $_POST['dish_id'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    $stmt = $conn->prepare("UPDATE menu SET image = ? WHERE dish_id = ?");
    $stmt->bind_param("si", $image, $dish_id);
    if($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
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
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' style='width: 175px; height: auto; '><br></td>";
        } else {
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '>No Image<br><input type='file' class='image-upload' data-dishid='" . $row['dish_id'] . "' /></td>";
        }
        echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><button class='btn' onclick=\"EditRow(this)\">Edit</button><br><button class='btn' onclick=\"DeleteRow(this)\">Delete</button></td>";
        echo "</tr>";
    }
    echo "</table></div></div>";
    // Pagination controls
    echo "<div class='pagination'>";
    // First page button
    echo ($page > 1) ? "<a href='?page=1&search=$searchTerm'>First</a>" : "<button class='disabled'>First</button>";
    // Previous page button
    echo ($page > 1) ? "<a href='?page=" . ($page - 1) . "&search=$searchTerm'>Prev</a>" : "<button class='disabled'>Prev</button>";
    // Next page button
    echo ($page < $totalPages) ? "<a href='?page=" . ($page + 1) . "&search=$searchTerm'>Next</a>" : "<button class='disabled'>Next</button>";
    // Last page button
    echo ($page < $totalPages) ? "<a href='?page=$totalPages&search=$searchTerm'>Last</a>" : "<button class='disabled'>Last</button>";
    echo "</div>";

} else {
    echo "<div style='text-align: center; margin: 15px;'>No dishes found</div>";
}
?>
<div style="text-align: center; margin: 15px;">
    <a href="add_new_dish.php" class="btn">Add Item</a>
</div>
<!-- View History button placed at the top right -->
<a href="admin_view_his.php" class="view-history-button">View History</a>
<script>
    $(document).ready(function() {
        // 确保在页面加载后绑定事件
        $(document).on('click', '.btn', function() {
            var buttonText = $(this).text().trim();
            if (buttonText === "Edit") {
                EditRow(this);
            } else if (buttonText === "Delete") {
                DeletRow(this);
            }
        });
        // Handle image upload
        // Add this to your existing jQuery document ready function
        $('.image-upload').change(function() {
            var dishId = $(this).data('dishid');
            var formData = new FormData();
            formData.append('image', this.files[0]);
            formData.append('dish_id', dishId);
            $.ajax({
                url: 'upload_image.php',
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Image updated successfully!');
                    location.reload(); // Refresh to show new image
                },
                error: function() {
                    alert('Error uploading image');
                }
            });
        });
    });
    function EditRow(button) {
        var row = $(button).closest('tr');
        var dishId = row.find('td').first().text();
        $.ajax({
            url: 'get_dish_details.php',
            type: 'GET',
            data: { id: dishId },
            success: function(response) {
                var data = JSON.parse(response);
                // Create a modal dialog for editing with a close button
                var editDialog = `
            <div class="modal edit-modal">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <h2>Edit Dish</h2>
                    <p>Please update the fields below and click "Save Changes" to update the dish information.</p>
                    <form id="editForm">
                        <input type="hidden" name="dish_id" value="${dishId}">
                        <div>
                            <label for="dish_name">Dish Name:</label>
                            <input type="text" name="dish_name" id="dish_name" value="${data.dish_name}" placeholder="Enter dish name">
                        </div>
                        <div>
                            <label for="ingredient">Ingredients:</label>
                            <textarea name="ingredient" id="ingredient" placeholder="List ingredients">${data.ingredient}</textarea>
                        </div>
                        <div>
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" placeholder="Enter description">${data.description}</textarea>
                        </div>
                        <div>
                            <label for="type">Type:</label>
                            <input type="text" name="type" id="type" value="${data.type}" placeholder="Enter type">
                        </div>
                        <div>
                            <label for="amount">Amount:</label>
                            <input type="number" name="amount" id="amount" value="${data.amount}" placeholder="Enter amount">
                        </div>
                        <div>
                            <label for="note">Note:</label>
                            <input type="text" name="note" id="note" value="${data.note}" placeholder="Enter any notes">
                        </div>
                        <div>
                            <label for="image">Image:</label>
                            <input type="file" name="image" id="image" accept="image/*">
                        </div>
                        <button type="submit">Save Changes</button>
                    </form>
                </div>
            </div>`;
                $('body').append(editDialog);
                $('.edit-modal').show();

                // Close modal when the close button is clicked
                $('.close-button').on('click', function() {
                    $('.edit-modal').remove();
                });
            }
        });
    }

    // Ensure the form submission is handled
    $(document).on('submit', '#editForm', function(e) {
        e.preventDefault();
        submitEditForm();
    });

    // Ensure the form submission is handled
    $(document).on('submit', '#editForm', function(e) {
        e.preventDefault();
        submitEditForm();
    });

    // Ensure the form submission is handled
    $(document).on('submit', '#editForm', function(e) {
        e.preventDefault();
        submitEditForm();
    });
    function DeleteRow(button) {
        if (confirm('Are you sure you want to delete this dish?')) {
            var row = $(button).closest('tr');
            var dishId = row.find('td').first().text();
            $.ajax({
                url: 'admin_delete_item.php',
                type: 'POST',
                data: { id: dishId },
                success: function(response) {
                    alert('Dish deleted successfully!');
                    row.remove();
                },
                error: function(xhr, status, error) {
                    alert('Failed to delete dish: ' + error);
                }
            });
        }
    }
    function submitEditForm() {
        var formData = $('#editForm').serialize(); // Assuming you have a form with id 'editForm'
        $.ajax({
            url: 'admin_edit_item.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert('Dish updated successfully!');
                $('#editModal').modal('hide'); // Close the modal
                location.reload(); // Reload the page to reflect changes
            },
            error: function(xhr, status, error) {
                alert('Error updating dish: ' + error);
            }
        });
    }
    // Bind this function to the form submit event
    $('#editForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        submitEditForm();
    });
</script>
</body>
</html>