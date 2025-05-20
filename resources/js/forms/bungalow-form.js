document.addEventListener('DOMContentLoaded', function () {
    // Éléments DOM
    const bungalowMer = document.getElementById('bungalowMer');
    const bungalowJardin = document.getElementById('bungalowJardin');
    const bungalowMerContainer = document.getElementById('bungalowMerContainer');
    const bungalowJardinContainer = document.getElementById('bungalowJardinContainer');
    const bungalowMerSelect = document.getElementById('bungalowMerSelect');
    const bungalowJardinSelect = document.getElementById('bungalowJardinSelect');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const personSelect = document.getElementById('nbrPersonnes');

    // Fonction pour mettre à jour la date de fin minimale
    function updateEndDateMin() {
        if (startDateInput && endDateInput && startDateInput.value) {
            endDateInput.min = startDateInput.value;
            // Si la date de fin est avant la date de début, réinitialiser
            if (endDateInput.value && new Date(endDateInput.value) < new Date(startDateInput.value)) {
                endDateInput.value = startDateInput.value;
            }
        }
    }

    // Fonction pour l'affichage des options de bungalow et gestion des sélecteurs
    function updateBungalowOptions() {
        if (!bungalowMer || !bungalowJardin || !bungalowMerContainer || !bungalowJardinContainer) return;

        if (bungalowMer.checked) {
            // Afficher le conteneur mer et cacher le conteneur jardin
            bungalowMerContainer.classList.remove('d-none');
            bungalowJardinContainer.classList.add('d-none');

            // Gérer les sélecteurs si présents
            if (bungalowMerSelect && bungalowJardinSelect) {
                bungalowMerSelect.disabled = false;
                bungalowJardinSelect.disabled = true;
                bungalowJardinSelect.value = '';
            }
        } else if (bungalowJardin.checked) {
            // Afficher le conteneur jardin et cacher le conteneur mer
            bungalowMerContainer.classList.add('d-none');
            bungalowJardinContainer.classList.remove('d-none');

            // Gérer les sélecteurs si présents
            if (bungalowMerSelect && bungalowJardinSelect) {
                bungalowJardinSelect.disabled = false;
                bungalowMerSelect.disabled = true;
                bungalowMerSelect.value = '';
            }
        } else {
            // Si aucun n'est coché, tout cacher
            bungalowMerContainer.classList.add('d-none');
            bungalowJardinContainer.classList.add('d-none');
        }

        updatePersonCount();
    }

    // Fonction pour l'affichage dynamique du nombre de personnes
    function updatePersonCount() {
        if (!bungalowJardin || !personSelect) return;

        const isTerre = bungalowJardin.checked;
        const maxPersons = isTerre ? 4 : 2; // 4 pour jardin, 2 pour mer

        // Vider le sélecteur
        while (personSelect.options.length > 0) {
            personSelect.options.remove(0);
        }

        // Ajouter les options selon le type de bungalow
        for (let i = 1; i <= maxPersons; i++) {
            let opt = document.createElement('option');
            opt.value = i;
            opt.innerHTML = i + (i > 1 ? ' personnes' : ' personne');
            personSelect.appendChild(opt);
        }
    }

    // Fonction pour afficher l'image du bungalow sélectionné
    function showBungalowImage(selectElement, type) {
        const containerId = `bungalow${type.charAt(0).toUpperCase() + type.slice(1)}ImageContainer`;
        const imageId = `bungalow${type.charAt(0).toUpperCase() + type.slice(1)}Image`;

        const container = document.getElementById(containerId);
        const imageElement = document.getElementById(imageId);

        if (!container || !imageElement) return;

        if (selectElement.value) {
            // Une option est sélectionnée, afficher l'image
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const imagePath = selectedOption.getAttribute('data-image');

            if (imagePath) {
                imageElement.src = imagePath;
                container.classList.remove('d-none');
            } else {
                container.classList.add('d-none');
            }
        } else {
            // Aucune option sélectionnée, cacher l'image
            container.classList.add('d-none');
        }
    }

    // Initialiser l'affichage des images au chargement (si un bungalow est déjà sélectionné)
    if (bungalowMerSelect && bungalowMerSelect.value) {
        showBungalowImage(bungalowMerSelect, 'mer');
    }

    if (bungalowJardinSelect && bungalowJardinSelect.value) {
        showBungalowImage(bungalowJardinSelect, 'jardin');
    }

    // Ajouter les écouteurs pour les changements de sélection
    if (bungalowMerSelect) {
        bungalowMerSelect.addEventListener('change', function () {
            showBungalowImage(this, 'mer');
        });
    }

    if (bungalowJardinSelect) {
        bungalowJardinSelect.addEventListener('change', function () {
            showBungalowImage(this, 'jardin');
        });
    }

    // Ajouter les écouteurs d'événements
    if (startDateInput) {
        startDateInput.addEventListener('change', updateEndDateMin);
        updateEndDateMin(); // Initialisation
    }

    if (bungalowMer) {
        bungalowMer.addEventListener('change', updateBungalowOptions);
    }

    if (bungalowJardin) {
        bungalowJardin.addEventListener('change', updateBungalowOptions);
    }

    // Initialiser l'état d'affichage
    updateBungalowOptions();

    // Si on a une session flash, faire défiler jusqu'à la confirmation
    const confirmElement = document.querySelector('.confirm-resa');
    if (confirmElement) {
        confirmElement.scrollIntoView({ behavior: 'smooth' });
    }
});
