// // Fonction pour appelée lorsqu'on clique sur '+' ou '-' pour le nombre de personnes dans la recherche de résa

// window.increment = function(inputId) {
//     const inputElement = document.getElementById(inputId);
//     const currentValue = parseInt(inputElement.value, 10);

//     if (inputId === 'numberAdult' && currentValue >= 5) {
//         return; // Ne rien faire si le nombre d'adultes atteint 5
//     }

//     if (inputId === 'numberKid' && currentValue >= 5) {
//         return; // Ne rien faire si le nombre d'enfants atteint 5
//     }

//     if (inputId === 'numberAdult' && currentValue === 0) {
//         // S'assurer qu'il y a au moins un adulte
//         inputElement.value = 1;
//     } else {
//         inputElement.value = currentValue + 1;
//     }

//     updateTotalPerson();
// }

// window.decrement = function(inputId) {
//     const inputElement = document.getElementById(inputId);
//     const currentValue = parseInt(inputElement.value, 10);

//     if (currentValue > 0) { // Vérifie que la valeur actuelle est supérieure à 0
//         inputElement.value = currentValue - 1;
//         if (inputId === 'numberAdult' && document.getElementById('numberKid').value === '0') {
//             // S'assurer qu'il y a au moins un adulte si tous les adultes sont retirés
//             document.getElementById('numberAdult').value = 1;
//         }
//         updateTotalPerson();
//     }
// }

// // Fonction pour calculer le nombre total de voyageurs
// function updateTotalPerson() {
//     const numberAdult = parseInt(document.getElementById('numberAdult').value, 10);
//     const numberKid = parseInt(document.getElementById('numberKid').value, 10);
//     const totalPerson = numberAdult + numberKid;

//     if (totalPerson > 6) {
//         // Si le total dépasse 6, réduisez le nombre d'adultes ou d'enfants si nécessaire
//         if (numberAdult > 1) {
//             document.getElementById('numberAdult').value = 1;
//         } else if (numberKid > 1) {
//             document.getElementById('numberKid').value = 1;
//         }
//     }

//     // Met à jour le champ "Voyageurs" avec le nombre total
//     document.getElementById('totalPerson').value = totalPerson;
// }

// // Appeler la fonction pour initialiser le total
// updateTotalPerson();





// document.addEventListener("DOMContentLoaded", function () {
//     const reservedDates = {{ reservedDates|json_encode|raw }};
    
//     flatpickr("#start", {
//         altInput: true,
//         altFormat: "j F Y",
//         dateFormat: "Y-m-d",
//         minDate: "today",
//         locale: "fr",
//         disable: reservedDates
//     });

//     flatpickr("#end", {
//         altInput: true,
//         altFormat: "j F Y",
//         dateFormat: "Y-m-d",
//         locale: "fr",
//         disable: reservedDates
//     });
// });


// Fonction d'incrémentation
function increment(inputId, totalId, maxAllowed) {
    const inputElement = document.getElementById(inputId);
    const totalElement = document.getElementById(totalId);
    
    if (parseInt(inputElement.value) < maxAllowed && parseInt(totalElement.value) < 6) {
        inputElement.value = parseInt(inputElement.value) + 1;
        totalElement.value = parseInt(totalElement.value) + 1;
    }
}

// Fonction de décrémentation
function decrement(inputId, totalId, minAllowed) {
    const inputElement = document.getElementById(inputId);
    const totalElement = document.getElementById(totalId);
    
    if (parseInt(inputElement.value) > minAllowed) {
        inputElement.value = parseInt(inputElement.value) - 1;
        totalElement.value = parseInt(totalElement.value) - 1;
    }
}

// Associer les fonctions aux éléments + et - pour les adultes
document.querySelector('.adult .increment').onclick = function () {
    increment('numberAdult', 'totalPerson', 6);
};
document.querySelector('.adult .decrement').onclick = function () {
    decrement('numberAdult', 'totalPerson', 1);
};

// Associer les fonctions aux éléments + et - pour les enfants
document.querySelector('.kid .increment').onclick = function () {
    increment('numberKid', 'totalPerson', 5);
};
document.querySelector('.kid .decrement').onclick = function () {
    decrement('numberKid', 'totalPerson', 0);
};

