<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css"> <!-- Link to your CSS file -->
</head>

<body>
    <nav class="navbar">
        <a href="index.php" class="nav-logo">Recipe Modifier</a>
        <span class="toggle-button" onclick="toggleNavbar()">☰</span>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="recipecreation.php">Create Recipe</a></li>
            <li><a href="search.php">Search Recipes</a></li>
        </ul>
    </nav>

    <script>
        function toggleNavbar() {
            const navLinks = document.getElementById("navLinks");
            navLinks.classList.toggle("show");
        }
    </script>

</body>
</html>