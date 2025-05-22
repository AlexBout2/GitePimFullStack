document.addEventListener("DOMContentLoaded", function () {
    // Éléments du formulaire
    const sejourInput = document.getElementById("sejour-number");
    const sejourHidden = document.getElementById("sejour-number-hidden");
    const sejourValidationBtn = document.querySelector(".sejour-validation");
    const kayakFormContainer = document.getElementById("kayak-form-container");
    const kayakForm = document.getElementById("kayak-reservation-form");
    const dateInput = document.getElementById("date");
    const nbPersonnesSelect = document.getElementById("nb_personnes");
    const nbKayakSimpleInput = document.getElementById("nb_kayak_simple");
    const nbKayakDoubleInput = document.getElementById("nb_kayak_double");
    const heureDebutSelect = document.getElementById("heure_debut");
    const kayakError = document.getElementById("kayak-error");
    const hourAvailabilityMessage = document.getElementById("hour-availability-message");

    // Initialisation des éléments de formulaire
    if (!kayakForm) return; // Quitter si le formulaire n'existe pas

    // Vérification du numéro de séjour
    sejourValidationBtn.addEventListener("click", function () {
        const sejourNumber = sejourInput.value.trim();

        if (!sejourNumber) {
            sejourInput.classList.add("is-invalid");
            return;
        }

        // Validation du séjour (simulée ici, à remplacer par appel AJAX)
        validateSejour(sejourNumber);
    });

    function validateSejour(sejourNumber) {
        // Simulation de vérification du séjour
        // Dans une implémentation réelle, on ferait une requête AJAX au serveur

        // Exemple de requête AJAX (à décommenter et adapter)
        /*
        fetch('/api/verify-sejour/' + sejourNumber)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    showKayakForm(sejourNumber);
                } else {
                    sejourInput.classList.add("is-invalid");
                }
            })
            .catch(() => {
                // Gérer les erreurs
                sejourInput.classList.add("is-invalid");
            });
        */

        // Pour l'exemple, nous acceptons tout numéro
        showKayakForm(sejourNumber);
    }

    function showKayakForm(sejourNumber) {
        sejourInput.classList.remove("is-invalid");
        sejourHidden.value = sejourNumber;

        // Afficher le formulaire de réservation kayak
        kayakFormContainer.style.display = "block";

        // Faire défiler jusqu'au formulaire de réservation
        kayakFormContainer.scrollIntoView({ behavior: 'smooth' });
    }

    // Configuration des compteurs de kayaks
    setupKayakCounters();

    // Validation dynamique
    nbPersonnesSelect.addEventListener("change", validateKayakDistribution);
    nbKayakSimpleInput.addEventListener("change", validateKayakDistribution);
    nbKayakDoubleInput.addEventListener("change", validateKayakDistribution);

    // Validation de la disponibilité des horaires
    heureDebutSelect.addEventListener("change", validateHoraireDisponibilite);
    dateInput.addEventListener("change", validateHoraireDisponibilite);

    // Validation du formulaire avant soumission
    kayakForm.addEventListener("submit", function (event) {
        // Réexécuter les validations
        const kayakValid = validateKayakDistribution();
        const horaireValid = validateHoraireDisponibilite();

        // Empêcher la soumission si une validation échoue
        if (!kayakValid || !horaireValid) {
            event.preventDefault();
        }
    });

    // Configuration des compteurs de kayaks
    function setupKayakCounters() {
        const kayakBtns = document.querySelectorAll('.kayak-counter-btn');

        kayakBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.target);
                const action = this.dataset.action;
                const currentValue = parseInt(target.value);

                if (action === 'increment' && currentValue < parseInt(target.max)) {
                    target.value = currentValue + 1;
                } else if (action === 'decrement' && currentValue > parseInt(target.min)) {
                    target.value = currentValue - 1;
                }

                target.dispatchEvent(new Event('change'));
            });
        });
    }

    // Validation de la distribution des kayaks
    function validateKayakDistribution() {
        const nbPersonnes = parseInt(nbPersonnesSelect.value);
        const nbKayakSimple = parseInt(nbKayakSimpleInput.value);
        const nbKayakDouble = parseInt(nbKayakDoubleInput.value);

        // Réinitialiser l'erreur
        kayakError.textContent = "";

        // Aucun kayak sélectionné
        if (nbKayakSimple === 0 && nbKayakDouble === 0) {
            kayakError.textContent = "Veuillez sélectionner au moins un kayak";
            return false;
        }

        // Capacité totale des kayaks
        const capaciteSimple = nbKayakSimple;
        const capaciteDouble = nbKayakDouble * 2;
        const capaciteTotale = capaciteSimple + capaciteDouble;

        // Vérifier si la capacité correspond au nombre de personnes
        if (capaciteTotale < nbPersonnes) {
            kayakError.textContent = `Les kayaks sélectionnés ne peuvent accueillir que ${capaciteTotale} personne(s)`;
            return false;
        }

        if (capaciteTotale > nbPersonnes) {
            kayakError.textContent = `${nbPersonnes} personne(s) pour ${capaciteTotale} places. Ajoutez des personnes ou réduisez le nombre de kayaks.`;
            return false;
        }

        // Vérifier si une personne seule essaie de réserver un kayak double
        if (nbPersonnes === 1 && nbKayakDouble > 0 && nbKayakSimple === 0) {
            kayakError.textContent = "Les personnes seules ne peuvent pas réserver uniquement des kayaks doubles";
            return false;
        }

        // Tout est valide
        return true;
    }

    // Validation de la disponibilité de l'horaire (simulée)
    function validateHoraireDisponibilite() {
        if (!dateInput.value || !heureDebutSelect.value) {
            // Pas assez d'informations pour valider
            hourAvailabilityMessage.textContent = "";
            return true;
        }

        const selectedDate = dateInput.value;
        const selectedHour = heureDebutSelect.value;

        // Simulation de vérification de disponibilité
        // Dans une implémentation réelle, on ferait une requête AJAX

        // Exemple de requête AJAX (à décommenter et adapter)
        /*
        fetch(`/api/check-kayak-availability?date=${selectedDate}&hour=${selectedHour}`)
            .then(response => response.json())
            .then(data => {
                if (!data.available) {
                    hourAvailabilityMessage.textContent = "Cet horaire n'est plus disponible. Veuillez en choisir un autre.";
                    return false;
                } else {
                    hourAvailabilityMessage.textContent = "";
                    return true;
                }
            })
            .catch(() => {
                // Gérer les erreurs
                hourAvailabilityMessage.textContent = "Erreur lors de la vérification de disponibilité.";
                return false;
            });
        */

        // Pour l'exemple, nous considérons tous les horaires comme disponibles
        hourAvailabilityMessage.textContent = "";
        return true;
    }
});
