// ✅ Vérification que le script est bien chargé
console.log("✅ Script chargé et en cours d'exécution");

// Gestion de la sélection des options
document.querySelectorAll('.multi-select .option').forEach(option => {
    option.addEventListener('click', () => {
        option.classList.toggle('selected');
    });
});

// Table de traduction des saveurs en français
const flavorTranslations = {
    fruity: "Fruité",
    sweet: "Sucré",
    acidic: "Acide",
    bitter: "Amer",
    spicy: "Épicé",
    salty: "Salé",
    floral: "Floral",
    herbaceous: "Herbacé",
    creamy: "Crémeux",
    smoky: "Fumé"
};

// 🔹 Recherche de cocktails par NOM 🔍
document.getElementById("search-button").addEventListener("click", function() {
    const searchQuery = document.getElementById("search-cocktail").value.trim();

    console.log("🔍 Recherche en cours pour :", searchQuery); 

    if (searchQuery === "") {
        alert("Veuillez entrer un nom de cocktail.");
        return;
    }

    fetch(`./php/barsmash_cocktails_connexion.php?search=${encodeURIComponent(searchQuery)}`)
        .then(response => response.json())
        .then(data => {
            console.log("🔍 Données reçues du serveur :", JSON.stringify(data, null, 2));

            const resultsContainer = document.getElementById('cocktail-list');
            resultsContainer.innerHTML = ''; 

            if (data.length === 0) {
                resultsContainer.innerHTML = '<p>Aucun cocktail trouvé.</p>';
            } else {
                data.forEach(cocktail => {
                    const cocktailElement = document.createElement('div');
                    cocktailElement.classList.add('cocktail');

                    // Vérification et correction du chemin de l'image
                    // Correction du chemin pour correspondre au bon dossier
                    let imageSrc = cocktail.image ? `/BarSmash/${cocktail.image}` : "/BarSmash/images/default-placeholder.png";
                    console.log("🖼️ Chemin de l'image :", imageSrc);

                    // Création de l'élément image
                    const imageElement = document.createElement('img');
                    imageElement.src = imageSrc;
                    imageElement.alt = cocktail.name;
                    imageElement.style.maxWidth = "100%";
                    imageElement.style.height = "auto";

                    // Vérification console
                    console.log("🖼️ Balise image créée :", imageElement.outerHTML);

                    // Création des autres informations
                    const infoElement = document.createElement('div');
                    infoElement.innerHTML = `
                        <h3>${cocktail.name}</h3>
                        <p>Alcool : ${cocktail.alcoholic || 'Sans alcool'}</p>
                        <p>Saveur : ${cocktail.flavor}</p>
                        <p>${cocktail.instructions}</p>
                    `;

                    // Ajout de l'image et du texte dans la carte cocktail
                    cocktailElement.appendChild(imageElement);
                    cocktailElement.appendChild(infoElement);

                    // Vérification du HTML final généré
                    console.log("🔄 HTML final généré :", cocktailElement.outerHTML);

                    resultsContainer.appendChild(cocktailElement);
                });
            }
        })
        .catch(error => {
            console.error("❌ Erreur :", error);
            document.getElementById("cocktail-list").innerHTML = "<p>Erreur lors de la recherche.</p>";
        });
});

// 🔹 Recherche de cocktails par ALCOOL et SAVEUR
document.querySelector('.find-cocktails').addEventListener('click', (event) => {
    event.preventDefault();

    const type = document.getElementById('alcohol-type').value;
    const selectedAlcohols = Array.from(document.querySelectorAll('#alcohol-options .option.selected'))
        .map(option => option.dataset.value);

    const selectedFlavors = Array.from(document.querySelectorAll('#flavors-options .option.selected'))
        .map(option => flavorTranslations[option.dataset.value] || option.dataset.value);

    console.log("📤 Données envoyées au serveur :", { type, alcohols: selectedAlcohols, flavors: selectedFlavors });

    fetch('./php/barsmash_cocktails_connexion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type, alcohols: selectedAlcohols, flavors: selectedFlavors })
    })
    .then(response => response.json())
    .then(data => {
        console.log("🔍 Données reçues du serveur :", data);

        const resultsContainer = document.getElementById('cocktail-list');
        resultsContainer.innerHTML = '';

        if (!Array.isArray(data)) {
            resultsContainer.innerHTML = `<p>Une erreur est survenue : ${data.error || 'Réponse invalide du serveur.'}</p>`;
            return;
        }

        if (data.length === 0) {
            resultsContainer.innerHTML = '<p>Aucun cocktail trouvé pour ces critères.</p>';
        } else {
            data.forEach(cocktail => {
                const cocktailElement = document.createElement('div');
                cocktailElement.classList.add('cocktail');

                let imageSrc = cocktail.image ? `/${cocktail.image}` : "/images/default-placeholder.png";
                console.log("🖼️ Chemin de l'image :", imageSrc);

                const imageElement = document.createElement('img');
                imageElement.src = imageSrc;
                imageElement.alt = cocktail.name;
                imageElement.style.maxWidth = "100%";
                imageElement.style.height = "auto";

                console.log("🖼️ Balise image créée :", imageElement.outerHTML);

                const infoElement = document.createElement('div');
                infoElement.innerHTML = `
                    <h3>${cocktail.name}</h3>
                    <p>Alcool : ${cocktail.alcoholic || 'Sans alcool'}</p>
                    <p>Saveur : ${cocktail.flavor}</p>
                    <p>${cocktail.instructions}</p>
                `;

                cocktailElement.appendChild(imageElement);
                cocktailElement.appendChild(infoElement);

                console.log("🔄 HTML final généré :", cocktailElement.outerHTML);

                resultsContainer.appendChild(cocktailElement);
            });
        }
    })
    .catch(error => {
        console.error('❌ Erreur :', error);
        document.getElementById('cocktail-list').innerHTML = '<p>Une erreur est survenue lors de la recherche.</p>';
    });
});

// 🔹 PAGINATION
let currentPage = 1;

function loadCocktails(page) {
    fetch(`./php/barsmash_cocktails_connexion.php?page=${page}`)
        .then(response => response.json())
        .then(data => {
            displayResults(data);
            currentPage = page;
        });
}

document.getElementById("next-page").addEventListener("click", () => loadCocktails(currentPage + 1));
document.getElementById("prev-page").addEventListener("click", () => {
    if (currentPage > 1) loadCocktails(currentPage - 1);
});

// 🔹 Gestion du bouton "Cocktail Surprise"
document.getElementById("random-cocktail").addEventListener("click", function () {
    const url = "/barsmash/php/random_cocktails.php?random=true";
    console.log("Requête envoyée à :", url);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert("Erreur : " + data.error);
            } else {
                displayRandomCocktail(data);
            }
        })
        .catch(error => {
            console.error("Erreur Fetch :", error);
            document.getElementById("random-result").innerHTML = "<p>Une erreur s'est produite. Vérifiez la console.</p>";
        });
});

function displayRandomCocktail(cocktail) {
    const resultContainer = document.getElementById("random-result");

    const placeholderImage = "https://source.unsplash.com/400x300/?cocktail,drink";

    resultContainer.innerHTML = `
        <div class="cocktail-card">
            <img src="${placeholderImage}" alt="Cocktail image" class="cocktail-img">
            <div class="cocktail-info">
                <h3>${cocktail.name}</h3>
                <p><strong>Type :</strong> ${cocktail.alcoholic}</p>
                <p><strong>Saveur :</strong> ${cocktail.flavor}</p>
                <p><strong>Instructions :</strong> ${cocktail.instructions}</p>
            </div>
        </div>
    `;
}
