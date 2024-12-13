<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php

include'connectdb.php';

// pagination.php
function getDishes($page, $perPage, $searchKeyword = null) {
    global $conn;
    $start = ($page - 1) * $perPage;
    $sql = "SELECT * FROM menu WHERE dish_name LIKE ? LIMIT $start, $perPage";
    $result = $conn->query($sql);

    if ($searchKeyword) {
        $sql = "SELECT * FROM menu WHERE dish_name LIKE '%$searchKeyword%' LIMIT $start, $perPage";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchKeyword);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    $dishes = [];
    while ($row = $result->fetch_assoc()) {
        $dishes[] = $row;
    }
    return $dishes;
}

function getTotalPages($perPage, $searchKeyword = null) {
    global $conn;
    $sql = "SELECT COUNT(*) FROM menu";
    if ($searchKeyword) {
        $sql = "SELECT COUNT(*) FROM menu WHERE dish_name LIKE '%$searchKeyword%'";
    }
    $result = $conn->query($sql);
    $row = $result->fetch_row();
    return ceil($row[0] / $perPage);
}

function updateAmount($dishId, $newAmount) {
    global $conn;
    $sql = "UPDATE menu SET amount = ? WHERE dish_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newAmount, $dishId);
    $stmt->execute();
}

?>
<h2>Menu</h2>

<input type="text" id="searchKeyword" placeholder="Search dishes..." onkeyup="searchDishes(event)">

<table>
    <thead>
        <tr>
            <th>Dish Name</th>
            <th>Price</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="menuTable">
        <!-- Dishes will be loaded here -->
    </tbody>
</table>

<div class="pagination" id="pagination">
    <!-- Pagination buttons will be loaded here -->
</div>

<script>
const perPage = 10; // Number of dishes per page
let currentPage = 1;

function fetchDishes(page) {
    fetch(`pagination.php?page=${page}&perPage=${perPage}`)
        .then(response => response.json())
        .then(data => {
            const menuTable = document.getElementById('menuTable');
            menuTable.innerHTML = '';
            data.dishes.forEach(dish => {
                const row = `<tr>
                    <td>${dish.dish_name}</td>
                    <td>${dish.price}</td>
                    <td><input type="number" value="${dish.amount}" onchange="updateAmount(${dish.dish_id}, this.value)"></td>
                    <td>${dish.description}</td>
                </tr>`;
                menuTable.innerHTML += row;
            });
            updatePagination(data.totalPages);
        });
}

function updatePagination(totalPages) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = `<span onclick="fetchDishes(${i})">${i}</span>`;
        pagination.innerHTML += pageButton + ' ';
    }
}

function updateAmount(dishId, newAmount) {
    fetch('update_amount.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `dishId=${dishId}&newAmount=${newAmount}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Amount updated successfully');
        } else {
            console.error('Failed to update amount');
        }
    });
}

function fetchDishes(page, searchKeyword) {
    fetch(`pagination.php?page=${page}&perPage=${perPage}&searchKeyword=${searchKeyword}`)
        .then(response => response.json())
        .then(data => {
            const menuTable = document.getElementById('menuTable');
            menuTable.innerHTML = '';
            data.dishes.forEach(dish => {
                const row = `<tr>
                    <td>${dish.dish_name}</td>
                    <td>${dish.price}</td>
                    <td><input type="number" value="${dish.amount}" onchange="updateAmount(${dish.dish_id}, this.value)"></td>
                    <td>${dish.description}</td>
                </tr>`;
                menuTable.innerHTML += row;
            });
            updatePagination(data.totalPages, searchKeyword);
        });
}

function updatePagination(totalPages, searchKeyword) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = `<span onclick="fetchDishes(${i}, '${searchKeyword}')">${i}</span>`;
        pagination.innerHTML += pageButton + ' ';
    }
}

function searchDishes(event) {
    if (event.key === 'Enter') {
        const searchKeyword = document.getElementById('searchKeyword').value;
        fetchDishes(currentPage, searchKeyword);
    }
}

// Initial fetch
fetchDishes(currentPage);

</script>

</body>
</html>