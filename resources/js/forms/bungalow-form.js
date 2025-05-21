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

    // Réinitialiser l'état de disponibilité
    function resetBungalowAvailability() {
        // Réinitialiser les options de bungalow mer
        if (bungalowMerSelect) {
            Array.from(bungalowMerSelect.options).forEach(option => {
                if (option.value) { // Ignore l'option placeholder
                    option.disabled = false;
                    option.removeAttribute('title');
                    option.style.color = '';
                }
            });
        }

        // Réinitialiser les options de bungalow jardin
        if (bungalowJardinSelect) {
            Array.from(bungalowJardinSelect.options).forEach(option => {
                if (option.value) { // Ignore l'option placeholder
                    option.disabled = false;
                    option.removeAttribute('title');
                    option.style.color = '';
                }
            });
        }

        // Effacer les messages d'erreur
        const merMessage = document.getElementById('mer-availability-message');
        const jardinMessage = document.getElementById('jardin-availability-message');

        if (merMessage) merMessage.textContent = '';
        if (jardinMessage) jardinMessage.textContent = '';
    }

    // Mettre à jour la disponibilité des bungalows en fonction des dates
    function updateBungalowAvailability() {
        const startDate = startDateInput ? startDateInput.value : null;
        const endDate = endDateInput ? endDateInput.value : null;

        if (!startDate || !endDate) return;

        // Réinitialiser l'état des options de bungalow
        resetBungalowAvailability();

        // Vérifier la disponibilité pour les bungalows Mer
        if (bungalowMerSelect) {
            Array.from(bungalowMerSelect.options).forEach(option => {
                if (option.value) { // Ignorer l'option placeholder
                    checkSingleBungalow(option.value, startDate, endDate, option, 'mer');
                }
            });
        }

        // Vérifier la disponibilité pour les bungalows Jardin
        if (bungalowJardinSelect) {
            Array.from(bungalowJardinSelect.options).forEach(option => {
                if (option.value) { // Ignorer l'option placeholder
                    checkSingleBungalow(option.value, startDate, endDate, option, 'jardin');
                }
            });
        }
    }

    // Vérifier la disponibilité d'un seul bungalow
    function checkSingleBungalow(bungalowId, startDate, endDate, optionElement, type) {
        fetch(`/check-bungalow-availability?bungalowId=${bungalowId}&startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    // Bungalow non disponible
                    optionElement.disabled = true;
                    optionElement.setAttribute('title', 'Non disponible pour ces dates');
                    optionElement.style.color = '#aaa';

                    // Mettre à jour le message si ce bungalow est sélectionné
                    const selectedValue = (type === 'mer' && bungalowMerSelect) ?
                        bungalowMerSelect.value :
                        (type === 'jardin' && bungalowJardinSelect) ? bungalowJardinSelect.value : '';

                    if (selectedValue === bungalowId) {
                        const messageContainer = type === 'mer' ?
                            document.getElementById('mer-availability-message') :
                            document.getElementById('jardin-availability-message');

                        if (messageContainer) {
                            messageContainer.textContent = 'Ce bungalow n\'est pas disponible pour les dates sélectionnées';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Erreur lors de la vérification de disponibilité:', error);
            });
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

    // Vérifier la disponibilité initiale si les dates sont déjà définies
    if (startDateInput && startDateInput.value && endDateInput && endDateInput.value) {
        updateBungalowAvailability();
    }
});
