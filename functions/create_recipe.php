<?php
require_once 'dbconn.php';

// Collect form data
$name = $_POST['name'];
$servings = $_POST['servings'];
$tags = $_POST['tags'];
$instructions = $_POST['instructions'];
$ingredient_names = $_POST['ingredient_name'];
$quantities = $_POST['quantity'];
$units = $_POST['unit'];

// Insert recipe details
$sql = "INSERT INTO recipes (name, servings, tags, instructions) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siss", $name, $servings, $tags, $instructions);

if ($stmt->execute()) {
    $recipe_id = $stmt->insert_id;

    // Insert each ingredient
    $ingredient_sql = "INSERT INTO ingredients (recipe_id, ingredient_name, quantity, unit) VALUES (?, ?, ?, ?)";
    $ingredient_stmt = $conn->prepare($ingredient_sql);

    for ($i = 0; $i < count($ingredient_names); $i++) {
        $ingredient_name = $ingredient_names[$i];
        $quantity = $quantities[$i];
        $unit = $units[$i];

        $ingredient_stmt->bind_param("isds", $recipe_id, $ingredient_name, $quantity, $unit);
        $ingredient_stmt->execute();
    }

    echo "Recipe created successfully!";
    header("location: ../recipecreation.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
