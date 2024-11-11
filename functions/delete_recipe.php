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

// Get recipe ID from form
$recipe_id = $_POST['recipe_id'];

// Delete recipe and ingredients
$delete_recipe_sql = "DELETE FROM recipes WHERE recipe_id = ?";
$delete_recipe_stmt = $conn->prepare($delete_recipe_sql);
$delete_recipe_stmt->bind_param("i", $recipe_id);
$delete_recipe_stmt->execute();

$delete_ingredients_sql = "DELETE FROM ingredients WHERE recipe_id = ?";
$delete_ingredients_stmt = $conn->prepare($delete_ingredients_sql);
$delete_ingredients_stmt->bind_param("i", $recipe_id);
$delete_ingredients_stmt->execute();

echo "Recipe deleted successfully!";
$delete_recipe_stmt->close();
$delete_ingredients_stmt->close();
$conn->close();
?>
