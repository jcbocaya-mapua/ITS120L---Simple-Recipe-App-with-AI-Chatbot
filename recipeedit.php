<?php include 'navbar.php'; ?>
<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "recipe_modifier"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get recipe ID from URL parameter
$recipe_id = isset($_GET['recipe_id']) ? (int)$_GET['recipe_id'] : 0;

// Fetch recipe details
$recipe_sql = "SELECT * FROM recipes WHERE recipe_id = ?";
$recipe_stmt = $conn->prepare($recipe_sql);
$recipe_stmt->bind_param("i", $recipe_id);
$recipe_stmt->execute();
$recipe_result = $recipe_stmt->get_result();
$recipe = $recipe_result->fetch_assoc();

if (!$recipe) {
    die("Recipe not found.");
}

// Fetch ingredients for the recipe
$ingredients_sql = "SELECT * FROM ingredients WHERE recipe_id = ?";
$ingredients_stmt = $conn->prepare($ingredients_sql);
$ingredients_stmt->bind_param("i", $recipe_id);
$ingredients_stmt->execute();
$ingredients_result = $ingredients_stmt->get_result();

$ingredients = [];
while ($row = $ingredients_result->fetch_assoc()) {
    $ingredients[] = $row;
}

$recipe_stmt->close();
$ingredients_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <link rel="stylesheet" href="styles/style.css">
    <script>
        function addIngredientField(name = '', quantity = '', unit = '') {
            const container = document.getElementById("ingredientContainer");
            const ingredientDiv = document.createElement("div");
            ingredientDiv.className = "ingredient-item";
            ingredientDiv.innerHTML = `
                <input type="text" name="ingredient_name[]" value="${name}" placeholder="Ingredient Name" required>
                <input type="number" name="quantity[]" value="${quantity}" step="0.01" placeholder="Quantity" required>
                <input type="text" name="unit[]" value="${unit}" placeholder="Unit (e.g., grams)" required>
                <button type="button" onclick="removeIngredientField(this)" style="background-color: #9b111f; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 14px;">
                Remove</button>
            `;
            container.appendChild(ingredientDiv);
        }

        function removeIngredientField(button) {
            button.parentElement.remove();
        }

        // Populate initial ingredients
        window.onload = function() {
            <?php foreach ($ingredients as $ingredient): ?>
                addIngredientField("<?= htmlspecialchars($ingredient['ingredient_name']) ?>", "<?= $ingredient['quantity'] ?>", "<?= htmlspecialchars($ingredient['unit']) ?>");
            <?php endforeach; ?>
        };
    </script>
</head>
<body>
<div class="edit-container">
    <br><h2>Edit Recipe: <?= htmlspecialchars($recipe['name']) ?></h2><br>
    <form action="functions/update_recipe.php" method="post">
        <input type="hidden" name="recipe_id" value="<?= $recipe['recipe_id'] ?>">

        <label for="name">Recipe Name:</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($recipe['name']) ?>" required><br><br>

        <label for="servings">Servings:</label><br>
        <input type="number" id="servings" name="servings" value="<?= $recipe['servings'] ?>" required><br><br>

        <label for="instructions">Instructions:</label><br>
        <textarea id="instructions" name="instructions" rows="5" required><?= htmlspecialchars($recipe['instructions']) ?></textarea><br>

        <h3>Ingredients</h3>
        <div id="ingredientContainer"></div>

        <div class="recipeedit-container">
        <button type="button" onclick="addIngredientField()">Add Another Ingredient</button><br><br>

        <input type="submit" value="Save Changes"></div>
        
    </form>
    <!-- Delete Recipe Button -->
    <form action="functions/delete_recipe.php" method="post" style="margin-top: 20px;">
        <input type="hidden" name="recipe_id" value="<?= $recipe['recipe_id'] ?>">
        <button type="submit" style="color: white;">Delete Recipe</button>
    </form>
    </div>
</body>
</html>
