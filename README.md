# Simple Recipe App with AI Chatbot

This project is a **Simple Recipe App** that allows users to create, edit, and view recipes, adjust ingredient quantities based on servings, and analyze nutritional information using an AI Nutrition Chatbot powered by the **Gemini API**. The chatbot can answer questions about calories, fats, protein, and more based on the ingredients in each recipe.

## Features
- **Recipe Creation**: Users can create new recipes with ingredients, quantities, and instructions.
- **Recipe Editing**: Existing recipes can be edited or deleted.
- **Recipe Viewing**: Users can view recipes and adjust ingredient quantities based on serving size.
- **AI Nutrition Chatbot**: Users can ask the chatbot for nutritional information about the recipe's ingredients, like calorie content and macronutrient breakdown.

## Requirements
- **XAMPP**: Version 8.2.12 (PHP 8.2.12)
- **Gemini API Key**: Required to power the AI Nutrition Chatbot
- **.env File**: For securely storing sensitive environment variables (e.g., API key)

## Project Structure
```bash
recipemodifier/
├── functions/                 # PHP scripts for backend operations
│   ├── chatbot.php            # Backend for the AI Nutrition Chatbot
│   ├── create_recipe.php      # Script to create a new recipe
│   ├── dbconn.php             # Database connection script
│   ├── delete_recipe.php      # Script to delete a recipe
│   └── update_recipe.php      # Script to update an existing recipe
│
├── styles/                    # Folder for CSS styles
│   └── style.css              # CSS file for styling all pages
│
├── vendor/                    # Composer dependencies (for Gemini API and dotenv)
│
├── .env                       # Environment variables (not committed to Git)
├── .gitignore                 # Git ignore file to exclude files from version control
├── composer.json              # Composer configuration file
├── composer.lock              # Composer lock file for dependencies
├── index.php                  # Home page
├── navbar.php                 # Navbar included in each page
├── readme.md                  # Project documentation
├── recipecreation.php         # Recipe creation page
├── recipeedit.php             # Recipe editing page
├── recipeview.php             # Recipe viewing page with chatbot
└── search.php                 # Recipe search page

```



## Setup Instructions

### 1. Install XAMPP
- Download and install **XAMPP** version 8.2.12 from [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html).
- Start the **Apache** and **MySQL** services from the XAMPP Control Panel.

### 2. Clone the Repository
- Place the project folder inside XAMPP's `htdocs` directory, usually found at:
```
C:\xampp\htdocs\recipemodifier
```

### 3. Set Up the MySQL Database
1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your browser.
2. Create a new database named `recipe_modifier`.
3. Run the following SQL commands to create the necessary tables:

```sql
CREATE TABLE recipes (
     recipe_id INT AUTO_INCREMENT PRIMARY KEY,
     name VARCHAR(255) NOT NULL,
     instructions TEXT,
     tags VARCHAR(255),
     servings INT NOT NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
 );

 CREATE TABLE ingredients (
     ingredient_id INT AUTO_INCREMENT PRIMARY KEY,
     recipe_id INT NOT NULL,
     ingredient_name VARCHAR(255) NOT NULL,
     quantity DECIMAL(10, 2) NOT NULL,
     unit VARCHAR(50),
     FOREIGN KEY (recipe_id) REFERENCES recipes(recipe_id) ON DELETE CASCADE
 );
```

### 4. Configure Environment Variables
1. Sign up for the Gemini API and obtain your API key from [https://aistudio.google.com/app/apikey](https://aistudio.google.com/app/apikey).
2. In the root of your project directory, create a file named `.env`.
3. Add your environment variables to the `.env` file as follows:
```env
# .env file

# Gemini API Key
GEMINI_API_KEY=your_actual_gemini_api_key_here
```
 
### 5. Run the Application
1. Open your web browser and navigate to [http://localhost/recipe-modifier-app/index.php](http://localhost/recipe-modifier-app/index.php).
2. You should see the home page of the Recipe Modifier App with links to create, edit, view, and search for recipes.

## Usage Guide

### Creating a Recipe
1. Go to the **Create Recipe** page.
2. Fill in the recipe details, including the name, servings, instructions, and ingredients with their quantities and units.
3. Click **Save** to store the recipe in the database.

### Editing a Recipe
1. Go to the **Edit Recipe** page.
2. Select a recipe to modify by searching or selecting from a list.
3. Update the details as needed, such as changing ingredients, updating instructions, or adjusting servings.
4. Click **Save Changes** to update the recipe or **Delete** to remove it from the database.

### Viewing a Recipe with the Chatbot
1. Go to the **View Recipe** page and select a recipe to view.
2. On this page, you can:
   - See the recipe details, including ingredients and instructions.
   - Adjust ingredient quantities dynamically by changing the serving size.
3. **Nutrition Info Chatbot**:
   - At the bottom of the recipe view page, you’ll find an AI-powered chatbot, **Chef Gemini**.
   - Type questions about the nutritional content of the recipe (e.g., “How many calories?” or “What’s the protein content?”).
   - Chef Gemini will provide nutritional information based on the recipe’s ingredients.

### Searching for Recipes
1. Go to the **Search Recipes** page.
2. Use the search bar to find recipes by name, ingredients, or tags.
3. Click on any recipe in the results to view it in detail.

## Troubleshooting

1. **Database Connection Issues**: 
   - Ensure the database credentials in your PHP code are correct and match your MySQL setup.
   
2. **Gemini API Errors**: 
   - Verify that your Gemini API key is correctly set in the `.env` file.
   - Check for any errors in the console or log files for additional troubleshooting information.

## License
This project is open-source and available for educational and non-commercial use. Please consult the Gemini API terms of service for details on their API usage restrictions.
