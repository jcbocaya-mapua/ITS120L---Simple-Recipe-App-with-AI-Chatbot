<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <br><title>Create Recipe</title>
    <link rel="stylesheet" href="styles/style.css">
    <script>
        function addIngredientField() {
            const container = document.getElementById("ingredientContainer");
            const ingredientDiv = document.createElement("div");
            ingredientDiv.className = "ingredient-item";
            ingredientDiv.innerHTML = `
                <input type="number" name="quantity[]" step="0.01" placeholder="Quantity" required>
                <input type="text" name="unit[]" placeholder="Unit (e.g., grams)" required>
                <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
            `;
            container.appendChild(ingredientDiv);
        }
    </script>
</head>
<body>
<div class="form-container">
    <h2>Create a New Recipe</h2><br>
    <form action="functions/create_recipe.php" method="post">
        <label for="name">Recipe Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="servings">Servings:</label><br>
        <input type="number" id="servings" name="servings" required><br><br>

        <label for="tags">Tags (comma-separated):</label><br><br>
        <input type="text" id="tags" name="tags"><br><br>

        <label for="instructions">Instructions:</label><br>
        <textarea id="instructions" name="instructions" rows="5" required></textarea><br><br>

        <h3>Ingredients</h3>
        <div id="ingredientContainer">
            <div class="ingredient-item">
                <input type="number" name="quantity[]" step="0.01" placeholder="Quantity" required>
                <input type="text" name="unit[]" placeholder="Unit (e.g., grams)" required>
                <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
            </div>
        </div>
        <button type="button" onclick="addIngredientField()" class="add-button">
    Add Another Ingredient
</button>
<br><br>

<input type="submit" value="Create Recipe" class="submit-button">

    </form>
    </div>
</body>

</html>