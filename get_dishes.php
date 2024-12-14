<?php
include 'connectdb.php';

$dishesPerPage = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $dishesPerPage;

$filters = array();
$params = array();

// Add filtering logic here (if needed)

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

// Fetch total number of dishes
$totalSql = "SELECT COUNT(*) AS total FROM menu";
if (!empty($filters)) {
    $totalSql .= " WHERE " . implode(" AND ", $filters);
}
$totalStmt = $conn->prepare($totalSql);
foreach ($params as $key => $value) {
    $totalStmt->bindValue($key, $value);
}
$totalStmt->execute();
$total = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

$totalPages = ceil($total / $dishesPerPage);

// Return the results as JSON
echo json_encode([
    'dishes' => $dishes,
    'totalPages' => $totalPages
]);
?>
