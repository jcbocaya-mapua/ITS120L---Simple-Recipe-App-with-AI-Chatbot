<?php
require 'dbconn.php';
require '../vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$apiKey = $_ENV['GEMINI_API_KEY'];

// Initialize the Gemini client with the API key directly
$client = Gemini::client($apiKey);
// Get user message from the frontend
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data['message'] ?? '';
$recipeId = $data['recipe_id'];

// Fetch full recipe details
$recipe_sql = "SELECT * FROM recipes WHERE recipe_id = ?";
$stmt = $conn->prepare($recipe_sql);
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$recipe_result = $stmt->get_result();
$recipe = $recipe_result->fetch_assoc();
$stmt->close();

// Fetch ingredients for the recipe
$ingredients_sql = "SELECT ingredient_name, quantity, unit FROM ingredients WHERE recipe_id = ?";
$stmt = $conn->prepare($ingredients_sql);
$stmt->bind_param("i", $recipeId);
$stmt->execute();
$ingredients_result = $stmt->get_result();

$ingredients = [];
while ($row = $ingredients_result->fetch_assoc()) {
    $ingredients[] = $row;
}
$stmt->close();
$conn->close();

// Format the ingredients into a readable text
$ingredientText = "";
foreach ($ingredients as $ingredient) {
    $ingredientText .= "{$ingredient['quantity']} {$ingredient['unit']} of {$ingredient['ingredient_name']}, ";
}

// Compose a prompt for the Gemini model
$prompt = "You are Chef Gemini, a knowledgeable cooking assistant who specializes in providing nutritional information and cooking advice. You are viewing a recipe page.

Recipe title: '{$recipe['name']}'
Number of servings: '{$recipe['servings']}'
Instructions: {$recipe['instructions']}
Ingredients: $ingredientText

The user said: '$userMessage'.

Please respond based on these guidelines:
1. If the user requests nutritional information (such as calories, fat content, or carbs), provide only the relevant nutritional data that you can calculate directly from the listed ingredients. The recipes do not include nutritional information, you are supposed to use your knowledge base to calculate nutritional data based on the ingredients list.
Carbohydrates: 4 calories per gram
Protein: 4 calories per gram
Fat: 9 calories per gram
2. If the user asks a general question about the recipe (such as cooking tips, ingredient information, or substitutions), respond as a friendly cooking assistant and offer helpful information.
3. If the user's question is unclear, politely ask for clarification.

Respond concisely, focusing only on the user's query.";

// Send the prompt to Gemini API and get the response
try {
    $response = $client->geminiPro()->generateContent($prompt); // Directly pass the prompt string
    echo json_encode(['response' => $response->text()]);
} catch (Exception $e) {
    echo json_encode(["response" => "Error with Gemini API: " . $e->getMessage()]);
}
