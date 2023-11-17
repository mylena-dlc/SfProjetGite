
document.addEventListener('DOMContentLoaded', function() {

// Récupérez les éléments HTML
 const numberAdultInput = document.getElementById('numberAdult');
 const numberKidInput = document.getElementById('numberKid');
 const totalPersonInput = document.getElementById('totalPerson');

 // Récupérez les boutons d'incrémentation et de décrémentation
 const incrementAdultButton = document.querySelector('.adult .increment');
 const decrementAdultButton = document.querySelector('.adult .decrement');
 const incrementKidButton = document.querySelector('.kid .increment');
 const decrementKidButton = document.querySelector('.kid .decrement');

  // Vérifiez si les éléments existent avant d'attacher des écouteurs d'événements
  if (numberAdultInput && numberKidInput && totalPersonInput && incrementAdultButton && decrementAdultButton && incrementKidButton && decrementKidButton) {
 // Ajoutez des gestionnaires d'événements pour les boutons
 incrementAdultButton.addEventListener('click', function() {
     incrementInput(numberAdultInput);
 });

 decrementAdultButton.addEventListener('click', function() {
     decrementInput(numberAdultInput);
 });

 incrementKidButton.addEventListener('click', function() {
     incrementInput(numberKidInput);
 });

 decrementKidButton.addEventListener('click', function() {
     decrementInput(numberKidInput);
 });
  }
 // Fonction pour incrémenter l'input et mettre à jour totalPerson
 function incrementInput(inputElement) {
     if (inputElement.value < inputElement.max && getTotalPersons() < 6) {
         inputElement.value++;
         updateTotalPerson();
     }
 }

 // Fonction pour décrémenter l'input et mettre à jour totalPerson
 function decrementInput(inputElement) {
     if (inputElement.value > inputElement.min) {
         inputElement.value--;
         updateTotalPerson();
     }
 }

 // Fonction pour mettre à jour totalPerson en additionnant les valeurs d'adult et kid
 function updateTotalPerson() {
     totalPersonInput.value = Number(numberAdultInput.value) + Number(numberKidInput.value);
 }

 // Fonction pour obtenir la valeur totale des personnes
 function getTotalPersons() {
     return Number(numberAdultInput.value) + Number(numberKidInput.value);
 }


 // Fonction pour valider la soumission du formulaire
 function validateForm() {
    var startDate = document.getElementById('start').value;
    var endDate = document.getElementById('end').value;

    // Vérifier si les dates d'arrivée et de départ sont sélectionnées
    if (!startDate || !endDate) {
        Swal.fire({
            icon: "error",
            title: "Erreur",
            text: "Veuillez sélectionner les dates d'arrivée et de départ."
        });
        return false; // Empêcher la soumission du formulaire si les dates ne sont pas sélectionnées
    }

    return true; // Autoriser la soumission du formulaire si tout est valide
}

});


/* Bouton scroll haut de page */


const btn = document.querySelector('.btn-scroll-to-top');
btn.addEventListener('click', () => {

    window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth" // pour adoucir l'effet
    })
})