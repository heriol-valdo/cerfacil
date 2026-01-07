 let currentStep = 1;

        function nextStep(step) {
            if (!validateStep(step)) {
                return;
            }

            // Masquer la section actuelle
            document.getElementById(`section${step}`).classList.remove('active');
            document.getElementById(`step${step}`).classList.remove('active');
            document.getElementById(`step${step}`).classList.add('completed');

            // Afficher la section suivante
            currentStep = step + 1;
            document.getElementById(`section${currentStep}`).classList.add('active');
            document.getElementById(`step${currentStep}`).classList.add('active');
        }

        function prevStep(step) {
            // Masquer la section actuelle
            document.getElementById(`section${step}`).classList.remove('active');
            document.getElementById(`step${step}`).classList.remove('active');

            // Afficher la section précédente
            currentStep = step - 1;
            document.getElementById(`section${currentStep}`).classList.add('active');
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.getElementById(`step${currentStep}`).classList.remove('completed');
        }

        function validateStep(step) {
            switch(step) {
                case 1:
                    const typeContrat = document.querySelector('input[name="typeContrat"]:checked');
                    const zone = document.querySelector('input[name="zone"]:checked');
                    const age = document.getElementById('age').value;
                    const dateDebut = document.getElementById('dateDebut').value;

                    if (!typeContrat || !zone || !age || !dateDebut) {
                        toastr.error("Veuillez remplir tous les champs obligatoires.",'Oups!');
                        return false;
                    }
                    return true;

                case 2:
                    const anneeApprentissage = document.getElementById('anneeApprentissage').value;
                    const duree = document.getElementById('duree').value;

                    if (!anneeApprentissage || !duree) {
                        toastr.error("Veuillez sélectionner l\'année d\'apprentissage et la durée.",'Oups!');
                        return false;
                    }
                    return true;

                case 3:
                    const codeRNCP = document.getElementById('codeRNCP').value;
                    const coutFormation = document.getElementById('coutFormation').value;

                    if (!codeRNCP || !coutFormation) {
                         toastr.error("Veuillez remplir le code RNCP et le coût de formation.",'Oups!');
                        return false;
                    }
                    return true;
            }
            return true;
        }

        function updateDureeOptions() {
            const annee = document.getElementById('anneeApprentissage').value;
            const dureeSelect = document.getElementById('duree');
            
            dureeSelect.innerHTML = '<option value="">Sélectionnez la durée</option>';

            if (annee) {
                let maxMois;
                switch(annee) {
                    case '1': maxMois = 36; break;
                    case '2': maxMois = 24; break;
                    case '3': maxMois = 12; break;
                    default: maxMois = 36;
                }

                for (let i = 1; i <= maxMois; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `${i} mois`;
                    dureeSelect.appendChild(option);
                }
            }
        }

        async function calculer() {
            if (!validateStep(3)) {
                return;
            }

            // Masquer la section actuelle
            document.getElementById('section3').classList.remove('active');
            document.getElementById('step3').classList.remove('active');
            document.getElementById('step3').classList.add('completed');

            // Afficher la section résultats
            currentStep = 4;
            document.getElementById('section4').classList.add('active');
            document.getElementById('step4').classList.add('active');

            // Afficher le loading
            document.getElementById('loading').style.display = 'block';
            document.getElementById('results').style.display = 'none';

            try {
                // Récupérer le plafond OPCO
                const codeRNCP = document.getElementById('codeRNCP').value;
                const plafondResponse = await fetch('simulateur_generated_rncp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ codeRNCP: codeRNCP })
                });

                const PLAFOND_DEFAUT = 7000;
                let message = false;
                let  resultats;

                const plafondData = await plafondResponse.json();

                 // Calculer les résultats
                if (plafondData.success) {
                   resultats = calculerCouts(plafondData.data.plafond);
                }else {
                    resultats = calculerCouts(PLAFOND_DEFAUT);
                    message = true;
                }

               
               
                
                // Afficher les résultats
                afficherResultats(resultats,message);

            } catch (error) {
                console.error('Erreur:', error);
                document.getElementById('results').innerHTML = `
                    <div class="alert" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                        <strong>Erreur :</strong> ${error.message}
                    </div>
                `;
                document.getElementById('results').style.display = 'block';
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        }

        function calculerCouts(plafondOPCO) {
            // Récupérer les données du formulaire
            const typeContrat = document.querySelector('input[name="typeContrat"]:checked').value;
            const zone = document.querySelector('input[name="zone"]:checked').value;
            const age = parseInt(document.getElementById('age').value);
            const anneeApprentissage = parseInt(document.getElementById('anneeApprentissage').value);
            const duree = parseInt(document.getElementById('duree').value);
            const coutFormation = parseFloat(document.getElementById('coutFormation').value);

            // Calcul de l'aide à l'embauche
            const aideEmbauche = zone === 'hexagonale' ? 6000 : 8000;

            // Calcul du salaire (pourcentage du SMIC selon l'âge et l'année)
            const smic = 1801.80; // SMIC mensuel 2025
            let pourcentageSmic;
            let chargesPatronales;

            if (typeContrat === 'apprentissage') {
                if (age < 18) {
                    pourcentageSmic = anneeApprentissage === 1 ? 0.27 : (anneeApprentissage === 2 ? 0.39 : 0.55);
                } else if (age < 21) {
                    pourcentageSmic = anneeApprentissage === 1 ? 0.43 : (anneeApprentissage === 2 ? 0.51 : 0.67);
                } else if (age < 26) {
                    pourcentageSmic = anneeApprentissage === 1 ? 0.53 : (anneeApprentissage === 2 ? 0.61 : 0.78);
                } else {
                    pourcentageSmic = 1; // 100% du SMIC
                }
            } else { // professionnalisation
                if (age < 21) {
                    pourcentageSmic = 0.55;
                } else if (age < 26) {
                    pourcentageSmic = 0.70;
                } else {
                    pourcentageSmic = 0.85;
                }
            }

            const salaireMensuel = smic * pourcentageSmic;
            const salaireTotal = salaireMensuel * duree;

            // Charges patronales (environ 42% du salaire brut)
            if(typeContrat === 'apprentissage'){
                 chargesPatronales = salaireTotal * 0.075;

            }else{
                  chargesPatronales = salaireTotal * 0.20;
            }
           

            // Coût total à l'embauche
            const coutEmbauche = Math.max(0, (salaireTotal + chargesPatronales) - aideEmbauche);

            // Coût pédagogique (reste à charge)
            const coutPedagogique = Math.max(0, coutFormation - plafondOPCO);

            // Coût total
            const coutTotal = coutEmbauche + coutPedagogique;

            return {
                aideEmbauche,
                plafondOPCO,
                salaireMensuel,
                salaireTotal,
                chargesPatronales,
                coutEmbauche,
                coutFormation,
                coutPedagogique,
                coutTotal,
                duree,
                pourcentageSmic,
                typeContrat
            };
        }

        function afficherResultats(resultats,message) {
            const resultsDiv = document.getElementById('results');
            
            resultsDiv.innerHTML = `
                <div class="result-card">
                    <div class="result-title">💰 Aides financières</div>
                    <div class="result-amount positive">+${resultats.aideEmbauche.toLocaleString('fr-FR')} €</div>
                    <div class="result-detail">Aide à l'embauche</div>
                </div>

                <div class="result-card">
                    <div class="result-title">🎓 Aide pédagogique OPCO</div>
                    <div class="result-amount positive">+${resultats.plafondOPCO.toLocaleString('fr-FR')} €</div>
                    <div class="result-detail">Prise en charge de la formation</div>
                </div>

                <div class="result-card">
                    <div class="result-title">👤 Coût salarial</div>
                    <div class="result-amount neutral">${resultats.salaireTotal.toLocaleString('fr-FR')} €</div>
                    <div class="result-detail">
                        ${resultats.salaireMensuel.toLocaleString('fr-FR')} €/mois × ${resultats.duree} mois
                        <br>Soit ${(resultats.pourcentageSmic * 100).toFixed(0)}% du SMIC
                    </div>
                </div>

                <div class="result-card">
                    <div class="result-title">🏢 Charges patronales</div>
                    <div class="result-amount neutral">${resultats.chargesPatronales.toLocaleString('fr-FR')} €</div>
                    <div class="result-detail">${(resultats.typeContrat = 'apprentissage')? "Environ 7.5% du salaire brut" : "Environ 20% du salaire brut"}</div>
                </div>

                <div class="result-card">
                    <div class="result-title">💼 Coût à l'embauche (net)</div>
                    <div class="result-amount ${resultats.coutEmbauche > 0 ? 'negative' : 'positive'}">
                        ${resultats.coutEmbauche.toLocaleString('fr-FR')} €
                    </div>
                    <div class="result-detail">
                        Salaire + charges - aide à l'embauche
                    </div>
                </div>

                <div class="result-card">
                    <div class="result-title">📚 Coût pédagogique (reste à charge)</div>
                    <div class="result-amount ${resultats.coutPedagogique > 0 ? 'negative' : 'positive'}">
                        ${resultats.coutPedagogique.toLocaleString('fr-FR')} €
                    </div>
                   <div class="result-detail">
                        Coût formation : 
                        ${resultats.coutFormation.toLocaleString('fr-FR')} € 
                        - aide OPCO
                        ${message ? "<em style='color: red;'>le montant réel de l'aide n'a pas pu être récupéré, celui-ci est par défaut.</em>" : ""}
                    </div>

                </div>

                <div class="result-card" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <div class="result-title">🎯 COÛT TOTAL</div>
                    <div class="result-amount" style="color: white; font-size: 2.5rem;">
                        ${resultats.coutTotal.toLocaleString('fr-FR')} €
                    </div>
                    <div class="result-detail" style="color: rgba(255,255,255,0.8);">
                        Coût réel pour l'entreprise sur ${resultats.duree} mois
                    </div>
                </div>

                 <div class="alert alert-info">
                    <strong>💡 Information :</strong> Ce calcul n’inclut pas l’aide au maître d’apprentissage ni les éventuelles aides supplémentaires 
                    liées à la situation personnelle ou physique de l’alternant.
                </div>
            `;

            resultsDiv.style.display = 'block';
        }

        function recommencer() {
            // Reset du formulaire
            document.querySelectorAll('.form-control').forEach(input => {
                input.value = '';
            });
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.checked = false;
            });

            // Reset des étapes
            document.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active', 'completed');
            });
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });

            // Retour à la première étape
            currentStep = 1;
            document.getElementById('step1').classList.add('active');
            document.getElementById('section1').classList.add('active');
        }