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

    console.log("Éléments du formulaire:", {
        sejourInput, sejourHidden, sejourValidationBtn,
        kayakFormContainer, kayakForm,
        nbPersonnesSelect, nbKayakSimpleInput, nbKayakDoubleInput,
        heureDebutSelect
    });

    // Initialisation des éléments de formulaire
    if (!kayakForm) return; // Quitter si le formulaire n'existe pas

    // Vérification du numéro de séjour
    if (sejourValidationBtn) {
        sejourValidationBtn.addEventListener("click", function () {
            const sejourNumber = sejourInput.value.trim();
            console.log("Bouton de validation cliqué, numéro de séjour:", sejourNumber);

            if (!sejourNumber) {
                sejourInput.classList.add("is-invalid");
                return;
            }

            validateSejour(sejourNumber);
        });
    }

    // Fonction de validation du numéro de séjour
    function validateSejour(sejourNumber) {
        // Récupérer le token CSRF depuis la balise meta
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        console.log("Validation du séjour:", sejourNumber);
        console.log("kayakFormContainer existe:", !!kayakFormContainer);

        // Utiliser l'API Fetch pour valider uniquement le numéro de séjour
        fetch('/validate-sejour-number', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sejour_number: sejourNumber
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log("Réponse de validation:", data);

                if (data.valid) {
                    // Séjour valide, afficher le formulaire de kayak
                    sejourInput.classList.remove("is-invalid");
                    sejourInput.classList.add("is-valid");

                    // Important: Stocker l'ID de séjour dans le champ caché
                    if (sejourHidden) {
                        sejourHidden.value = sejourNumber;
                    }

                    // Afficher le formulaire de réservation de kayak
                    if (kayakFormContainer) {
                        console.log("Affichage du formulaire de kayak");
                        kayakFormContainer.classList.remove("d-none");
                        // Si vous utilisez style.display au lieu de classList
                        kayakFormContainer.style.display = "block";

                        // Faire défiler jusqu'au formulaire
                        kayakFormContainer.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        console.error("kayakFormContainer est introuvable");
                    }
                } else {
                    // Séjour non valide, afficher message d'erreur
                    sejourInput.classList.add("is-invalid");
                    if (kayakError) {
                        kayakError.innerText = data.message || 'Numéro de séjour invalide';
                        kayakError.classList.remove("d-none");
                    }
                }
            })
            .catch(error => {
                console.error('Erreur lors de la validation du séjour:', error);
                if (kayakError) {
                    kayakError.innerText = "Erreur de communication avec le serveur";
                    kayakError.classList.remove("d-none");
                }
            });
    }

    function validateDateForSejour() {
        const sejourNumber = sejourHidden.value;
        const selectedDate = dateInput.value;

        if (!sejourNumber || !selectedDate) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/validate-sejour', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                sejour_number: sejourNumber,
                date: selectedDate
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.valid) {
                    dateInput.classList.add("is-invalid");
                    // Afficher le message d'erreur près du champ de date
                    const dateErrorElement = document.getElementById("date-error") ||
                        document.createElement("div");

                    dateErrorElement.id = "date-error";
                    dateErrorElement.className = "invalid-feedback d-block text-center";
                    dateErrorElement.textContent = data.message;

                    if (!document.getElementById("date-error")) {
                        dateInput.parentNode.appendChild(dateErrorElement);
                    }
                } else {
                    dateInput.classList.remove("is-invalid");
                    const dateError = document.getElementById("date-error");
                    if (dateError) dateError.remove();
                }
            })
            .catch(error => {
                console.error('Erreur lors de la validation de la date:', error);
            });
    }

    // Ajouter un écouteur d'événement pour la date
    if (dateInput) {
        dateInput.addEventListener('change', validateDateForSejour);
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
            // Empêcher la soumission par défaut
            event.preventDefault();

            console.log("Formulaire soumis"); // Logs pour debug

            // Réexécuter les validations
            const kayakValid = validateKayakDistribution();
            const horaireValid = validateHoraireDisponibilite();
            const dateValid = !!dateInput.value && !dateInput.classList.contains("is-invalid");

            console.log("Validations:", { kayakValid, horaireValid, dateValid });

            // Si toutes les validations sont réussies
            if (kayakValid && horaireValid && dateValid) {
                // Vérifier si tous les champs requis sont remplis
                const nbKayakSimples = parseInt(nbKayakSimpleInput?.value || 0);
                const nbKayakDoubles = parseInt(nbKayakDoubleInput?.value || 0);

                const allFieldsFilled = [
                    dateInput.value,
                    heureDebutSelect.value,
                    nbPersonnesSelect.value,
                    // Au moins un des deux types de kayak doit être > 0
                    (nbKayakSimples > 0 || nbKayakDoubles > 0)
                ].every(Boolean);

                console.log("Tous les champs remplis:", allFieldsFilled);

                if (allFieldsFilled) {
                    console.log("Soumission du formulaire...");
                    // Assurez-vous que le formulaire est soumis correctement
                    kayakForm.submit(); // Soumettre le formulaire explicitement
                } else {
                    // Afficher un message d'erreur général
                    alert('Veuillez remplir tous les champs requis.');
                }
            } else {
                // Faire défiler jusqu'à la première erreur
                const firstError = document.querySelector(".is-invalid, .text-danger:not(:empty)");
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
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
