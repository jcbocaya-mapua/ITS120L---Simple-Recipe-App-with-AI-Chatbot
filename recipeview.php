<?php
include 'navbar.php';
include 'functions/dbconn.php';

// Get recipe ID from URL parameter
$recipe_id = isset($_GET['recipe_id']) ? (int) $_GET['recipe_id'] : 0;

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

// Fetch ingredients with nutritional data
$ingredients_sql = "
    SELECT i.ingredient_name, i.quantity, i.unit
    FROM ingredients i
    WHERE i.recipe_id = ?";
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
    <title>View Recipe</title>
    <link rel="stylesheet" href="styles/style.css">
    <script>
        function adjustServingSize() {
            const baseServings = <?= $recipe['servings'] ?>;
            const newServings = document.getElementById("servingSize").value;
            const ratio = newServings / baseServings;

            const ingredientQuantities = document.getElementsByClassName("ingredient-quantity");
            for (let i = 0; i < ingredientQuantities.length; i++) {
                const baseQuantity = ingredientQuantities[i].getAttribute("data-base-quantity");
                ingredientQuantities[i].textContent = (baseQuantity * ratio).toFixed(2);
            }

            const nutritionValues = document.getElementsByClassName("nutrition-value");
            for (let i = 0; i < nutritionValues.length; i++) {
                const baseValue = nutritionValues[i].getAttribute("data-base-value");
                nutritionValues[i].textContent = (baseValue * ratio).toFixed(2);
            }
        }
    </script>
</head>
<body>
<div class="view-container">
    <br><h2><?= htmlspecialchars($recipe['name']) ?></h2>
    <p style="font-size:10px"><a href="recipeedit.php?recipe_id=<?= $recipe_id ?>">Edit recipe</a></p>
    <p>Servings: <?= $recipe['servings'] ?></p>
    <h3>Instructions:</h3> <p><?= nl2br(htmlspecialchars($recipe['instructions'])) ?></p>

    <h3>Adjust Serving Size</h3>
    <input type="number" id="servingSize" value="<?= $recipe['servings'] ?>" oninput="adjustServingSize()">
        <br>
    <h3>Ingredients</h3>
    <ul>
        <?php if (!empty($ingredients)): ?>
            <?php foreach ($ingredients as $ingredient): ?>
                <br>
                    
                    <span class="ingredient-quantity" data-base-quantity="<?= $ingredient['quantity'] ?>">
                        <?= $ingredient['quantity'] ?>
                    </span> <?= htmlspecialchars($ingredient['unit']) ?>
                    <?= htmlspecialchars($ingredient['ingredient_name']) ?>
                </br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No ingredients found for this recipe.</p>
        <?php endif; ?>
    </ul>
    <br>
</div>
    <div class="chat-container">
        <h3>Chef Gemini Chatbot</h3>
        <div id="chat-box">
            <p><strong>Chef Gemini:</strong> <br>Hello! Ask me about the nutritional info of this recipe!</p>
        </div>
        <form id="chat-form">
            <input type="text" id="user-input" placeholder="Ask about calories, fats, etc.">
            <button type="button" onclick="sendMessage()">Send</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.10/marked.min.js"></script>
    <script>
        async function sendMessage() {
            const userInput = document.getElementById("user-input").value;
            if (userInput.trim() === "") return;

            // Clear user input
            document.getElementById("user-input").value = "";
            
            const recipeId = <?= json_encode($recipe['recipe_id']) ?>; // Pass recipe ID to JavaScript

            // Display user message in chat box
            const chatBox = document.getElementById("chat-box");
            const userMessage = document.createElement("p");
            userMessage.innerHTML = `<strong>You:\n</strong> ${userInput}`;
            chatBox.appendChild(userMessage);

            // Send the message and recipe ID to the server via AJAX
            const response = await fetch("functions/chatbot.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message: userInput,
                    recipe_id: recipeId
                })
            });
            const data = await response.json();

            // Display chatbot response
            const botMessage = document.createElement("p");
            botMessage.innerHTML = `<strong>Chef Gemini:</strong> ${marked.parse(data.response)}`; // Convert Markdown to HTML
            chatBox.appendChild(botMessage);

            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
</body>

</html>