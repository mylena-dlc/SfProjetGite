
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


/* API Leaflet */ 


    // Créez une icône personnalisée avec une couleur différente
    var customIcon = L.icon({
        iconUrl: '../img/icon-localisation.png',  // Remplacez par le chemin de votre propre icône
        iconSize: [38, 38],  // Taille de l'icône en pixels
        iconAnchor: [16, 32],  // Point d'ancrage de l'icône par rapport à son coin inférieur gauche
        popupAnchor: [0, -32],  // Point d'ancrage du popup par rapport à son coin supérieur gauche
    });

    // Initialisez la carte avec l'icône personnalisée
    var map = L.map('map').setView([48.116933, 7.140431], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 13,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // Utilisez l'icône personnalisée pour le marqueur
    var marker = L.marker([48.116933, 7.140431], { icon: customIcon }).addTo(map);
    marker.bindPopup("Le gîte du Rain du Pair").openPopup();




        // Bouton checkbox pour la vue de l'admin
        document.addEventListener('DOMContentLoaded', function () {
            const customCheckboxes = document.querySelectorAll('.custom-checkbox');

            customCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('click', () => {
                    if (checkbox.classList.contains('verrouille')) {
                        checkbox.classList.remove('verrouille');
                        checkbox.classList.add('actif');
                        const label = checkbox.nextElementSibling;
                        label.innerHTML = '<i class="fa-solid fa-check"></i> Vue !';
                    } else {
                        checkbox.classList.remove('actif');
                        checkbox.classList.add('verrouille');
                        const label = checkbox.nextElementSibling;
                        label.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Non vue !';
                    }
                    showSubmitButton(checkbox);
                });
            });

            function showSubmitButton(checkbox) {
                const checkboxWrapper = checkbox.closest('.checkbox-wrapper');
                const submitButton = checkboxWrapper.querySelector('.submit-checkbox');
                submitButton.style.display = 'block';
            }
        });
        
// JavaScript
// document.addEventListener('DOMContentLoaded', function() {
//     const galleryItems = document.querySelectorAll('.gallery-item');
//     const modal = document.getElementById('imageModal');
//     const modalContent = document.getElementById('modalImage');
//     const closeModalButton = document.getElementById('closeModal');

//     galleryItems.forEach(item => {
//         item.addEventListener('click', () => {
//             const imageUrl = item.querySelector('img').src;
//             modalContent.src = imageUrl;
//             modal.style.display = 'block';
//         });
//     });

    // closeModalButton.addEventListener('click', () => {
    //     modal.style.display = 'none';
    // });

//     window.addEventListener('click', (event) => {
//         if (event.target === modal) {
//             modal.style.display = 'none';
//         }
//     });
// });



// Plugin Rate Yo pour l'affichage des étoiles de la notation des avis

$(document).ready(function () {
    $("#rating").rateYo({
        rating: 0, // la valeur initiale
        starWidth: "20px",
        precision: 0, // Désactive les demi-étoiles

        onChange: function (rating, rateYoInstance) {
            // Mettre à jour la valeur du champ caché avec la note sélectionnée
            $("input[name='review[rating]']").val(rating);
        }
    });
});
  


