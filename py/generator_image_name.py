import os
import mysql.connector

# 📌 Connexion à MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",  # Mets ton user MySQL si différent
    password="",  # Mets ton mot de passe MySQL si différent
    database="barsmash_cocktails"
)
cursor = conn.cursor()

# 📌 Dossier contenant les images
image_folder = "images/cocktails"

# 📌 Fonction de normalisation des noms
def normalize_filename(name):
    name = name.lower().strip()
    name = name.replace(" ", "_").replace("-", "_").replace("'", "")
    return name

# 📌 Récupérer les cocktails
cursor.execute("SELECT id, name FROM cocktails;")
cocktails = cursor.fetchall()

# 📌 Liste des images trouvées
image_files = {normalize_filename(f.split(".")[0]): f for f in os.listdir(image_folder)}

# 📌 Mettre à jour MySQL avec les bonnes images
for cocktail_id, cocktail_name in cocktails:
    normalized_name = normalize_filename(cocktail_name)

    # Vérifier si une image existe pour ce cocktail
    if normalized_name in image_files:
        image_path = f"images/cocktails/{image_files[normalized_name]}"
        cursor.execute("UPDATE cocktails SET image = %s WHERE id = %s", (image_path, cocktail_id))
        print(f"✅ Image associée : {cocktail_name} → {image_path}")
    else:
        print(f"⚠️ Aucune image trouvée pour {cocktail_name}, mise à jour ignorée.")

# 📌 Valider et fermer la connexion
conn.commit()
cursor.close()
conn.close()

print("🚀 Mise à jour des images terminée !")
