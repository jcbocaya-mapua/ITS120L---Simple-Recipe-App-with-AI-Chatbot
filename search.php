<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Search</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
    <div class="search-container">
    <h2>Search Recipes</h2>
    <form method="get" action="search.php">
        <label for="search">Search by Recipe Name, Ingredient, or Tag:</label><br><br>
        <input type="text" id="search" name="search" placeholder="Enter keyword" required><br>
        <button type="submit">Search</button><br><br>
    </form>
    <h3>Results</h3><br>
    <ul>
        <?php
        // Only perform search if there's input
        if (isset($_GET['search'])) {
            // Database connection
            $servername = "localhost";
            $username = "root"; // Replace with your username
            $password = ""; // Replace with your password
            $dbname = "recipe_modifier"; // Replace with your database name

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL query to search by name, ingredient, or tag
            $search = "%" . $_GET['search'] . "%";

            $sql = "SELECT DISTINCT r.recipe_id, r.name, r.tags 
                    FROM recipes r
                    LEFT JOIN ingredients i ON r.recipe_id = i.recipe_id 
                    WHERE r.name LIKE ? OR i.ingredient_name LIKE ? OR r.tags LIKE ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $search, $search, $search);

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<a href='recipeview.php?recipe_id=" . $row['recipe_id'] . "'>" . htmlspecialchars($row['name']) . "</a> - Tags: " . htmlspecialchars($row['tags']) . "<br><br>";
                }
            } else {
                echo "<p>No recipes found.</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </ul>
    </div>
</body>
</html>
