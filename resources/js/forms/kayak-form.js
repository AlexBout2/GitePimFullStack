document.addEventListener("DOMContentLoaded", function () {
    // Éléments du formulaire
    const sejourInput = document.getElementById("sejour-number");
    const sejourHidden = document.getElementById("sejour-number-hidden");
    const sejourValidationBtn = document.querySelector(".sejour-validation");
    const kayakFormContainer = document.getElementById("kayak-form-container");
    const kayakForm = document.getElementById("kayak-reservation-form");
    const dateInput = document.getElementById("date");
    const nbPersonnesSelect = document.getElementById("nbrPersonnes");  // Attention au nom du champ
    const nbKayakSimpleInput = document.getElementById("nbr_kayaks_simples");  // Ajuster selon vos noms de champs
    const nbKayakDoubleInput = document.getElementById("nbr_kayaks_doubles");  // Ajuster selon vos noms de champs
    const heureDebutSelect = document.getElementById("heure_debut");
    const kayakError = document.getElementById("kayak-error");
    const hourAvailabilityMessage = document.getElementById("hour-availability-message");

    // Initialisation des éléments de formulaire
    if (!kayakForm) return; // Quitter si le formulaire n'existe pas

    // Vérification du numéro de séjour
    if (sejourValidationBtn) {
        sejourValidationBtn.addEventListener("click", function () {
            const sejourNumber = sejourInput.value.trim();

            if (!sejourNumber) {
                sejourInput.classList.add("is-invalid");
                return;
            }

            // Il y a un conflit entre les deux approches de validation dans votre code
            // Utilisons l'approche directe plutôt que l'event binding jQuery
            validateSejour(sejourNumber);
        });
    }

    function validateSejour(sejourNumber) {

        // Récupérer le token CSRF depuis la balise meta
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Préparer les données
        const activityDate = dateInput ? dateInput.value : '';

        // Utiliser l'API Fetch avec les principes de Laravel
        fetch('/sejour/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sejour_number: sejourNumber,
                date: activityDate
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    showKayakForm(sejourNumber);
                } else {
                    sejourInput.classList.add("is-invalid");
                    const feedback = sejourInput.nextElementSibling;
                    if (feedback) feedback.textContent = data.message || "Numéro de séjour invalide";
                }
            })
            .catch(error => {
                console.error('Erreur lors de la validation du séjour:', error);
                sejourInput.classList.add("is-invalid");
                const feedback = sejourInput.nextElementSibling;
                if (feedback) feedback.textContent = "Erreur lors de la validation. Veuillez réessayer.";
            });
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
    if (nbPersonnesSelect) nbPersonnesSelect.addEventListener("change", validateKayakDistribution);
    if (nbKayakSimpleInput) nbKayakSimpleInput.addEventListener("change", validateKayakDistribution);
    if (nbKayakDoubleInput) nbKayakDoubleInput.addEventListener("change", validateKayakDistribution);

    // Validation de la disponibilité des horaires
    if (heureDebutSelect) heureDebutSelect.addEventListener("change", validateHoraireDisponibilite);
    if (dateInput) dateInput.addEventListener("change", validateHoraireDisponibilite);

    // Validation du formulaire avant soumission
    if (kayakForm) {
        kayakForm.addEventListener("submit", function (event) {
            // Réexécuter les validations
            const kayakValid = validateKayakDistribution();
            const horaireValid = validateHoraireDisponibilite();

            // Empêcher la soumission si une validation échoue
            if (!kayakValid || !horaireValid) {
                event.preventDefault();
            }
        });
    }

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
        if (!nbPersonnesSelect || !nbKayakSimpleInput || !nbKayakDoubleInput) return true;

        const nbPersonnes = parseInt(nbPersonnesSelect.value);
        const nbKayakSimple = parseInt(nbKayakSimpleInput.value);
        const nbKayakDouble = parseInt(nbKayakDoubleInput.value);

        // Réinitialiser l'erreur
        if (kayakError) kayakError.textContent = "";

        // Aucun kayak sélectionné
        if (nbKayakSimple === 0 && nbKayakDouble === 0) {
            if (kayakError) kayakError.textContent = "Veuillez sélectionner au moins un kayak";
            return false;
        }

        // Capacité totale des kayaks
        const capaciteSimple = nbKayakSimple;
        const capaciteDouble = nbKayakDouble * 2;
        const capaciteTotale = capaciteSimple + capaciteDouble;

        // Vérifier si la capacité correspond au nombre de personnes
        if (capaciteTotale < nbPersonnes) {
            if (kayakError) kayakError.textContent = `Les kayaks sélectionnés ne peuvent accueillir que ${capaciteTotale} personne(s)`;
            return false;
        }

        if (capaciteTotale > nbPersonnes) {
            if (kayakError) kayakError.textContent = `${nbPersonnes} personne(s) pour ${capaciteTotale} places. Ajoutez des personnes ou réduisez le nombre de kayaks.`;
            return false;
        }

        // Vérifier si une personne seule essaie de réserver un kayak double
        if (nbPersonnes === 1 && nbKayakDouble > 0 && nbKayakSimple === 0) {
            if (kayakError) kayakError.textContent = "Les personnes seules ne peuvent pas réserver uniquement des kayaks doubles";
            return false;
        }

        // Tout est valide
        return true;
    }

    // Validation de la disponibilité de l'horaire
    function validateHoraireDisponibilite() {
        if (!dateInput || !heureDebutSelect || !dateInput.value || !heureDebutSelect.value) {
            // Pas assez d'informations pour valider
            if (hourAvailabilityMessage) hourAvailabilityMessage.textContent = "";
            return true;
        }

        // Pour l'exemple, nous considérons tous les horaires comme disponibles
        if (hourAvailabilityMessage) hourAvailabilityMessage.textContent = "";
        return true;
    }
});
