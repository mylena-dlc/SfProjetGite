// Ajout des gestionnaires d'événements
document.querySelectorAll('.decrement').forEach(button => {
    button.addEventListener('click', decrement);
});

document.querySelectorAll('.increment').forEach(button => {
    button.addEventListener('click', increment);
});

function increment(event) {
    const input = event.target.closest('.action').querySelector('input');
    const inputId = input.getAttribute('id');
    const maxAllowed = (inputId === 'numberAdult') ? 6 : 5; // Maximum 6 personnes en total
    const totalPersonInput = document.getElementById('totalPerson');
    const total = parseInt(totalPersonInput.value);

    if (total < 6 && parseInt(input.value) < maxAllowed) {
        input.value = (parseInt(input.value) + 1).toString();
        updateTotalPerson(1);
    }
}

function decrement(event) {
    const input = event.target.closest('.action').querySelector('input');
    const inputId = input.getAttribute('id');
    const minAllowed = (inputId === 'numberAdult') ? 1 : 0; // Minimum 1 personne en total
    const totalPersonInput = document.getElementById('totalPerson');
    const total = parseInt(totalPersonInput.value);

    if (total > 1 && parseInt(input.value) > minAllowed) {
        input.value = (parseInt(input.value) - 1).toString();
        updateTotalPerson(-1);
    }
}

function updateTotalPerson(change) {
    const numberAdult = parseInt(document.getElementById('numberAdult').value);
    const numberKid = parseInt(document.getElementById('numberKid').value);
    const total = numberAdult + numberKid + change + 1; // Prend en compte la valeur initiale de 1

    // Limiter totalPerson à 6
    if (total > 6) {
        return;
    }

    const totalPersonInput = document.getElementById('totalPerson');
    totalPersonInput.value = total.toString();
}

// Exécutez la mise à jour initiale de totalPerson
updateTotalPerson(0);







// document.getElementById('addPeriodLink').addEventListener('click', function(e) {
     
//     // Récupérez l'élément HTML de la div addPeriodSection
//     var addPeriodSection = document.querySelector('.addPeriodSection');

//     // Affichez la div en changeant la propriété display à 'block'
//     addPeriodSection.style.display = 'block';
// });





// // AFFICHAGE DU CALENDRIER DANS LA BARRE DE RECHERCHE

// // recherche des éléments HTML
// const startInput = document.getElementById("start");
// const endInput = document.getElementById("end");

// // On récupère les dates déjà réservées depuis l'attribut "data-reserved-dates" 
// const reservedDatesDataStart = startInput.getAttribute("data-reserved-dates");
// const reservedDatesDataEnd = endInput.getAttribute("data-reserved-dates");
// // On analyse cette chaîne JSON en objet JS
// const reservedDatesStart = JSON.parse(reservedDatesDataStart);
// const reservedDatesEnd = JSON.parse(reservedDatesDataEnd);

// console.log(reservedDatesStart);
// console.log(reservedDatesEnd);

//   // Configuration du calendrier pour le champs date d'arrivée
//   const startPicker = flatpickr(startInput, {
//   altInput: true,
//   altFormat: "j F Y",
//   dateFormat: "Y-m-d",
//   minDate: "today",
//   locale: {
//     firstDayOfWeek: 1,
//     weekdays: {
//       shorthand: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
//       longhand: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
//     },
//     months: {
//       shorthand: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"],
//       longhand: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
//     },
//   }, 
//   disable: reservedDatesStart.map(date => ({
//       from: date.arrivalDate,
//       // On enlève un jour à la date de départ afin qu'elle reste disponible pour une autre arrivée
//        to: date.departureDate
//       to: new Date(new Date(date.departureDate).getTime() - 86400000) // Soustraire un jour en millisecondes - on doit enveloppé avec 'new Date()' pour être sûre que date.departureDate soit un objet 'Date'
//     }))
//   // plugins: [new rangePlugin({ input: "#end" })],
// });


//   // Configuration du calendrier pour le champs date de départ
//   const endPicker = flatpickr(endInput, {
//   altInput: true,
//   altFormat: "j F Y",
//   dateFormat: "Y-m-d",
//   minDate: startInput.value,   
//    locale: {
//     firstDayOfWeek: 1,
//     weekdays: {
//       shorthand: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
//       longhand: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"]
//     },
//     months: {
//       shorthand: ["Jan", "Fév", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"],
//       longhand: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
//     }
//   },
//  disable: reservedDatesEnd.map(date => ({
//       from: date.arrivalDate,
//       // On enlève un jour à la date de départ afin qu'elle reste disponible pour une autre arrivée
      
//       to: date.departureDate
//     })),




// // on empêche la sélection de date de départ avant la date d'arrivée
//   onOpen: function(selectedDates, dateStr, instance) {
//     const selectedArrivalDate = startPicker.selectedDates[0];
//     if (selectedArrivalDate) {
//       instance.set("minDate", selectedArrivalDate);
//     } else {
//       // Si aucune date d'arrivée n'est sélectionnée, on n'autorisez pas la sélection
//       instance.close();
//     }
//   }
// });

// // On désactive l'entrée de date de départ au chargement de la page
// endInput.disabled = true;

// // Changement du champ de la date d'arrivée
// startInput.addEventListener("change", function () {
//   const selectedDate = startPicker.selectedDates[0];
//   if (selectedDate) {
//       // Si une date d'arrivée est sélectionnée
//     const nextDay = new Date(selectedDate);
//     nextDay.setDate(selectedDate.getDate() + 1);

//      // On active l'input de la date de départ
//   endInput.disabled = false;
//   // On réinitialise le champ de date de départ
//   endInput.value = nextDay.toLocaleDateString('en-GB'); // Formatage de la date (assurez-vous d'utiliser le format approprié)

//   // On met à jour la date minimale pour la date de départ
//   endPicker.set("minDate", nextDay);

//   // On empêche de sélectionner la même date que la date d'arrivée
//   endPicker.set("disable", [{ from: selectedDate, to: selectedDate }]);

//   // On met également à jour la date de départ automatiquement à la date de départ choisie + 1 jour
//   endPicker.setDate(nextDay, false);
// } else {
//   // Si aucune date d'arrivée est sélectionnée, on désactive l'input de la date de départ
//   endInput.disabled = true;
//   endInput.value = "";
//   endPicker.clear(); // Effacer la date de départ
// }
// });
