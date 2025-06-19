window.onload = function() {
    const testCocktailElement = document.createElement('div');
    testCocktailElement.classList.add('cocktail');

    // Création de l'image test
    const testImageElement = document.createElement('img');
    testImageElement.src = "assets/images/cocktails/martini.png";  // Change ici pour tester une image existante
    testImageElement.alt = "Martini";

    // Création du texte
    const testInfoElement = document.createElement('div');
    testInfoElement.innerHTML = `
        <h3>Martini</h3>
        <p>Alcool : Alcoholic</p>
        <p>Saveur : Amer, salé</p>
        <p>Instructions : Mélanger et servir frais</p>
    `;

    // Ajouter l’image et le texte
    testCocktailElement.appendChild(testImageElement);
    testCocktailElement.appendChild(testInfoElement);

    // Ajouter au DOM
    document.getElementById('cocktail-list').appendChild(testCocktailElement);
    
    console.log("✅ Test : Carte cocktail ajoutée manuellement !");
};
