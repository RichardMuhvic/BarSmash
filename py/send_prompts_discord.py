import discord
import asyncio
import pandas as pd
from discord.ext import commands

# 🔹 Remplace ces valeurs avec les tiennes 🔹
TOKEN = "MTM0MjI2Njk2NTk3NjI4OTM3MA.Gbky73.jEKCcIA-W8ok8W_krSpTaaSZDu2FTnxI6fyz9M"  # ⚠️ Change ton token immédiatement ⚠️
CHANNEL_ID = 1250766438055022615  # 🔹 Remplace avec l’ID du channel MidJourney

# Charger le fichier CSV avec les prompts
csv_file = "C:/xampp/htdocs/BarSmash/cocktail_prompts.csv"
df = pd.read_csv(csv_file)

# Initialiser le bot avec les intents adaptés
intents = discord.Intents.default()
bot = commands.Bot(command_prefix="!", intents=intents)

@bot.event
async def on_ready():
    print(f'✅ Connecté en tant que {bot.user}')
    
    channel = bot.get_channel(CHANNEL_ID)

    if channel:
        print(f'📢 Envoi des prompts MidJourney dans le channel : {channel.name}')

        for index, row in df.iterrows():
            cocktail_name = row['Cocktail Name']
            prompt = row['MidJourney Prompt']

            # Simule une commande slash avec un format correct
            message = f"/imagine prompt: {prompt}"

            await channel.send(message)
            print(f"✅ Prompt envoyé pour : {cocktail_name}")

            await asyncio.sleep(5)  # ⏳ Pause pour éviter le spam

        print("🚀 Tous les prompts ont été envoyés !")
    else:
        print("❌ Erreur : Channel introuvable.")

# Démarrer le bot
bot.run(TOKEN)
