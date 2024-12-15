<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <script src="./jquery.min.js"></script>
    <link rel="icon" href="qiao_logo.svg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php
include "connectdb.php";

// Define filters and pagination parameters
$filters = []; // e.g., ["category = 'appetizer'", "price < 20"]
$params = []; // e.g., [':category' => 'appetizer', ':price' => 20]
$dishesPerPage = 100; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $dishesPerPage;

// Construct the SQL query for pagination
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
    echo "<div style='margin-left: auto; margin-right: auto; text-align: center; width: 100%; '><h2 style='text-align: center; '>Admin Page</h2>";
    echo "<h3 style='text-align: center; color: #ff6699; '>After editing, please click <span style='font-weight: bolder; font-size:150%; color: #00aeec; '>Confirm</span> button to save changes.</h3>";
    echo "<div><table id='inventoryTable' style='border: 5px #00aeec solid; border-radius: 8px; '>";

    // Print the heading of table
    echo "<tr>";
    foreach (['dish_id', 'dish_name', 'ingredient', 'discription', 'type', 'amount', 'note', 'image'] as $column) {
        echo "<th style='width: 200px; text-align: center; border: 1px #00aeec solid; border-radius: 8px; '><span style='margin-top: 10px; margin-bottom: 10px; '>" . ucwords(str_replace('_', ' ', $column)) . "</span></th>";
    }
    echo "<th style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; '><span style='margin-top: 10px; margin-bottom: 10px; '>operation</span></th>";
    echo "</tr>";

    // Get each row data
    foreach ($dishes as $row) {
        echo "<tr>";
        for ($f = 0; $f < 7; $f++) { // 7 columns excluding image
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '>" . htmlspecialchars($row[array_keys($row)[$f]] ?? '') . "</td>";
        }
        // Handle image field
        if (isset($row['image'])) {
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' style='width: 175px; height: auto; '></td>";
        } else {
            echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '>No Image</td>";
        }
        echo "<td style='border: 1px #00aeec solid; border-radius: 8px; width: 200px; text-align: center; '><button style='border: 1px white solid; border-radius: 5px; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white; ' onclick=\"EditRow(this)\">Edit</button><br><button style='border: 1px white solid; border-radius: 5px; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white; ' onclick=\"DeletRow(this)\">Delete</button></td>";
        echo "</tr>";
    }

    echo "</table></div></div>";

    // Pagination links
    echo "<div style='text-align: center; margin: 15px; '>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i' style='text-decoration: none; border-radius:5px; border: 1px white solid; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white; '>$i</a>";
    }
    echo "</div>";

    echo "<div style='text-align: center; margin: 15px; '><button style='border-radius:5px; border: 1px white solid; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white; ' id='send' name='submit' value='confirm'>Confirm</button></div>";

} else {
    echo "<div style='text-align: center; margin: 15px; '>No dishes found</div>";
}
?>

<div style="text-align: center; margin: 15px; "><a href="add_new_item_admin.php" style='text-decoration: none; border-radius:5px; border: 1px white solid; padding: 10px; margin: 10px; width: 80px; background-color: #007bff; color: white;'>Add Item</a></div>

<script>
    function update() {
        var table = document.getElementById('inventoryTable');
        var rowCount = table.rows.length;
        var tableData = [];

        // Loop through table rows and gather data
        for (var i = 1; i < rowCount; i++) {
            var row = table.rows[i];
            var rowData = {};
            for (var j = 0; j < row.cells.length - 2; j++) { // -2 to exclude the image and operation columns
                rowData[j] = row.cells[j].innerText;
            }
            tableData.push(rowData);
        }

        // Send data to server
        $.ajax({
            url: 'process.php',
            type: 'POST',
            data: { data: tableData },
            success: function(response) {
                console.log('Response from PHP: ' + response);
                alert('Database updated successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error occurred: ' + status + ' ' + error);
            }
        });
    }

    $(document).ready(function() {
        $('#send').click(function() {
            update();
        });
    });

    function DeletRow(button) {
        if (confirm("Are you sure to delete this row?") == true) {
            let row = button.parentNode.parentNode;
            var itemId = row.cells[0].innerText;
            row.parentNode.removeChild(row);
            $.ajax({
                url: 'delete_item.php',
                type: 'POST',
                data: { id: itemId },
                success: function(response) {
                    alert('Item deleted successfully!');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred: ' + status + ' ' + error);
                }
            });
        } else {
            alert("Operation cancelled! ");
        }
    }

    function EditRow(button) {
        let row = button.parentNode.parentNode;
        let cells = row.cells;

        for (let i = 0; i < cells.length - 2; i++) { // -2 to exclude the image and operation columns
            let cell = cells[i];
            let inputValue = prompt("Please enter new value for " + cell.innerText, cell.innerText);
            if (inputValue !== null && inputValue !== "") {
                cell.innerText = inputValue;
            }
        }
    }
</script>
</body>
</html>
