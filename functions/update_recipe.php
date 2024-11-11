<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recipe_modifier";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$recipe_id = $_POST['recipe_id'];
$name = $_POST['name'];
$servings = $_POST['servings'];
$instructions = $_POST['instructions'];
$ingredient_names = $_POST['ingredient_name'];
$quantities = $_POST['quantity'];
$units = $_POST['unit'];

// Update recipe details
$recipe_sql = "UPDATE recipes SET name = ?, servings = ?, instructions = ? WHERE recipe_id = ?";
$recipe_stmt = $conn->prepare($recipe_sql);
$recipe_stmt->bind_param("sisi", $name, $servings, $instructions, $recipe_id);
$recipe_stmt->execute();

// Delete old ingredients and insert new ones
$delete_ingredients_sql = "DELETE FROM ingredients WHERE recipe_id = ?";
$delete_stmt = $conn->prepare($delete_ingredients_sql);
$delete_stmt->bind_param("i", $recipe_id);
$delete_stmt->execute();

$ingredient_sql = "INSERT INTO ingredients (recipe_id, ingredient_name, quantity, unit) VALUES (?, ?, ?, ?)";
$ingredient_stmt = $conn->prepare($ingredient_sql);

for ($i = 0; $i < count($ingredient_names); $i++) {
    $ingredient_name = $ingredient_names[$i];
    $quantity = $quantities[$i];
    $unit = $units[$i];

    $ingredient_stmt->bind_param("isds", $recipe_id, $ingredient_name, $quantity, $unit);
    $ingredient_stmt->execute();
}

echo "Recipe updated successfully!";
header("location: ../recipeedit.php?recipe_id=".$recipe_id);
$recipe_stmt->close();
$ingredient_stmt->close();
$conn->close();
?>
