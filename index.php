<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Modifier Home</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="home-container">
        <h1>Welcome to Recipe Modifier</h1>
        <p>Your ultimate tool to customize recipes to fit your dietary needs, ingredients, and portions. Create, edit, and search for recipes in just a few clicks!</p>

        <h2>Featured Recipes</h2>
        <div class="featured-recipes">
            <?php
            // Connect to the database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "recipe_modifier";
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch a few random recipes for display
            $sql = "SELECT recipe_id, name, tags FROM recipes ORDER BY RAND() LIMIT 3";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='recipe-card'>";
                    echo "<h3><a href='recipeview.php?recipe_id=" . $row['recipe_id'] . "'>" . htmlspecialchars($row['name']) . "</a></h3>";
                    echo "<p>Tags: " . htmlspecialchars($row['tags']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No recipes to display yet.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>