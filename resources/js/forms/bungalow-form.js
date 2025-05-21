// resources/js/forms/bungalow-form.js
document.addEventListener('DOMContentLoaded', function () {
    // Éléments du formulaire
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const bungalowMerInput = document.getElementById('bungalowMer');
    const bungalowJardinInput = document.getElementById('bungalowJardin');
    const bungalowMerContainer = document.getElementById('bungalowMerContainer');
    const bungalowJardinContainer = document.getElementById('bungalowJardinContainer');
    const bungalowMerSelect = document.getElementById('bungalowMerSelect');
    const bungalowJardinSelect = document.getElementById('bungalowJardinSelect');
    const personCount = document.getElementById('personCount');

    // Définir la date minimale (aujourd'hui) pour les champs de date
    const today = new Date().toISOString().split('T')[0];
    if (startDateInput) {
        startDateInput.min = today;
    }
    if (endDateInput) {
        endDateInput.min = today;
    }

    // Mise à jour des dates
    if (startDateInput) {
        startDateInput.addEventListener('change', function () {
            if (endDateInput) {
                endDateInput.min = startDateInput.value;

                if (endDateInput.value && new Date(endDateInput.value) < new Date(startDateInput.value)) {
                    endDateInput.value = startDateInput.value;
                }
            }

            updateBungalowAvailability();
        });
    }

    if (endDateInput) {
        endDateInput.addEventListener('change', function () {
            updateBungalowAvailability();
        });
    }

    // Fonction pour actualiser les options de bungalow en fonction du choix
    function updateBungalowOptions() {
        if (bungalowMerInput && bungalowJardinInput) {
            if (bungalowMerInput.checked) {
                bungalowMerContainer.classList.remove('d-none');
                bungalowJardinContainer.classList.add('d-none');
                // Réinitialiser la sélection de jardin
                if (bungalowJardinSelect) {
                    bungalowJardinSelect.selectedIndex = 0;
                }
            } else if (bungalowJardinInput.checked) {
                bungalowMerContainer.classList.add('d-none');
                bungalowJardinContainer.classList.remove('d-none');
                // Réinitialiser la sélection de mer
                if (bungalowMerSelect) {
                    bungalowMerSelect.selectedIndex = 0;
                }
            } else {
                bungalowMerContainer.classList.add('d-none');
                bungalowJardinContainer.classList.add('d-none');
            }
            updatePersonCount();
        }
    }

    // Fonction pour mettre à jour les options de nombre de personnes
    function updatePersonCount() {
        const personCountSelect = document.getElementById('personCount');
        const isJardin = bungalowJardinInput && bungalowJardinInput.checked;

        if (personCountSelect) {
            // Conserver la sélection actuelle si possible
            const currentValue = personCountSelect.value;

            // Vider le select
            personCountSelect.innerHTML = '';

            // Déterminer le max en fonction du type de bungalow
            const maxPersons = isJardin ? 4 : 2;

            // Recréer les options
            for (let i = 1; i <= maxPersons; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i + ' ' + (i > 1 ? 'personnes' : 'personne');
                personCountSelect.appendChild(option);
            }

            // Restaurer la valeur précédente si possible, sinon utiliser la valeur max
            if (parseInt(currentValue) <= maxPersons) {
                personCountSelect.value = currentValue;
            } else {
                personCountSelect.value = maxPersons;
            }

            // Mettre à jour le texte d'information de capacité
            const capaciteInfoElement = personCountSelect.parentElement.nextElementSibling;
            if (capaciteInfoElement && capaciteInfoElement.tagName.toLowerCase() === 'p') {
                capaciteInfoElement.querySelector('small').textContent = isJardin
                    ? 'Les bungalows Jardin peuvent accueillir jusqu\'à 4 personnes'
                    : 'Les bungalows Mer sont limités à 2 personnes maximum';
            }
        }
    }

    // Mettre à jour la disponibilité des bungalows en fonction des dates
    function updateBungalowAvailability() {
        const startDate = startDateInput ? startDateInput.value : null;
        const endDate = endDateInput ? endDateInput.value : null;

        if (!startDate || !endDate) return;

        // Cette fonction devrait faire une requête AJAX pour vérifier la disponibilité
        // Pour l'instant, on simule simplement le comportement
        console.log(`Vérification de disponibilité pour la période du ${startDate} au ${endDate}`);

        // Dans une implémentation complète, on ferait une requête AJAX ici
        // fetch('/api/check-availability?startDate=' + startDate + '&endDate=' + endDate)
        //     .then(response => response.json())
        //     .then(data => {
        //         // Mettre à jour l'interface avec les données reçues
        //     });
    }

    // Initialisation
    updateBungalowOptions();

    // Ajouter les écouteurs d'événements
    if (bungalowMerInput) {
        bungalowMerInput.addEventListener('change', updateBungalowOptions);
    }

    if (bungalowJardinInput) {
        bungalowJardinInput.addEventListener('change', updateBungalowOptions);
    }

    // Recharger l'état du formulaire si des valeurs existent déjà (par exemple, après validation échouée)
    if ((bungalowMerInput && bungalowMerInput.checked) || (bungalowJardinInput && bungalowJardinInput.checked)) {
        updateBungalowOptions();
    }

    // Vérifier la disponibilité initiale
    updateBungalowAvailability();
});
