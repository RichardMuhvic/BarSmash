import csv
import mysql.connector

# Connexion à la base de données
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="barsmash_cocktails"
)
cursor = conn.cursor()

# Récupérer les cocktails avec leurs descriptions
cursor.execute("SELECT name, flavor, ingredients, instructions FROM cocktails")
cocktails = cursor.fetchall()

# Dictionnaire de verres associés aux cocktails
glass_types = {
    "Martini": "martini glass",
    "Margarita": "margarita glass",
    "Old Fashioned": "lowball glass",
    "Daiquiri": "coupette glass",
    "Mojito": "highball glass",
    "Negroni": "old-fashioned glass",
    "Whiskey Sour": "rocks glass",
    "Gin Sour": "sour glass",
    "Cosmopolitan": "martini glass",
    "Mai Tai": "tiki mug",
    "Pina Colada": "hurricane glass",
    "Moscow Mule": "copper mug",
}

# Générer les prompts
csv_filename = "cocktail_prompts.csv"

with open(csv_filename, "w", newline="", encoding="utf-8") as file:
    writer = csv.writer(file)
    writer.writerow(["Cocktail Name", "MidJourney Prompt"])  # En-têtes du CSV

    for name, flavor, ingredients, instructions in cocktails:
        glass = glass_types.get(name, "cocktail glass")  # Verre par défaut si non trouvé
        garnish = "a fresh fruit slice" if "fruité" in flavor.lower() else "a minimalist garnish"
        
        # Construire un prompt optimisé
        prompt = (
            f"Flat lay, vintage speakeasy style. High-quality photo of a {name}, served in a {glass}, "
            f"featuring {flavor} notes. Made with {ingredients}. "
            f"Instructions: {instructions}. Garnished with {garnish}, placed on a dark wooden bar counter, moody lighting --v 5"
        )
        
        writer.writerow([name, prompt])

print(f"✅ {len(cocktails)} prompts générés et enregistrés dans {csv_filename} !")

# Fermer la connexion
cursor.close()
conn.close()
