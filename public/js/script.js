const searchButton = document.querySelector('#search');

searchButton.addEventListener('click', function() {
    // Récupérez les critères de recherche depuis les champs du formulaire (date, nombre d'adultes, nombre d'enfants, etc.)
    const startDate = document.querySelector('#start').value;
    const endDate = document.querySelector('#end').value;
    const numberAdult = document.querySelector('#numberAdult').value;
    const numberKid = document.querySelector('#numberKid').value;

    // Effectuez la recherche avec ces critères et mettez à jour le calendrier
    performSearch(startDate, endDate, numberAdult, numberKid);
});




