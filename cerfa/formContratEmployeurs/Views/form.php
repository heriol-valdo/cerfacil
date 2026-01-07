<!Doctype html>
<html lang="fr">
<head>
<link rel="icon" type="image/png" href="./Public/img/favicon.png" >
<script src="./Public/assets/jquery/jquery.min.js" type="text/javascript"></script>
<script src="./Public/assets/jquery/toastr/toastr.js" type="text/javascript"></script>
<meta charset="utf-8">
<link href="./Public/css/font-awesome/materiel/materielindigo.min.css?ver=1.3.0" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="./Public/css/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./Public/assets/bootstrap/css/bootstrap.css">
<link href="./Public/assets/bootstrap/css/bootstrap.min.css?ver=1.2.0" rel="stylesheet">
<link href="./Public/assets/jquery/toastr/toastr.min.css" rel="stylesheet">
<script src="./Public/js/form.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="./Public/css/form.css">


<title>CerFacil-FORM</title>
</head>

<body>
<main class="bg-white" > 
	<div>
		<div>
			<figure>
				<img src="./Public/img/lgxlogo.png" alt="icon entreprise Cerfacil" class="imagestruct">
			</figure>
		</div>
        <div>
			<h2 class="imagestructs">CerFacil</h2>
		</div>
        <div>
			<p style=" margin-top: 20px;"><h6 
		   style="font-style: oblique; font-weight: normal;"class="text-center "><?= $_COOKIE['info'] ?> Remplissez ce formulaire pour l'etablissement de votre cerfa 
            <p style="color: red;">(*) Champs obligatoires</p>
 </h6></p>
        </div>
		
        <div>
        	 <form  onsubmit="return sendData();" method="POST"  id="myForm">
             <div class="row">
                       
                        <div class="col-md-3  col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Mode Contractuel d'apprentissage:  <b>*</b></label>
                                     <select id="modeC" name="modeC" class="form-control"  required style="border-radius: 5px;">
                                        <option value="">______</option>
                                        <option value="1">1 : À durée limitée</option>
                                        <option value="2">2 : Dans le cadre d’un CDI</option>
                                        <option value="3">3 : Entreprise de travail temporaire</option>
                                        <option value="4">4 : Activités saisonnières à deux employeurs</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Travail sur machines dangereuses ou exposition à des risques particuliers :  <b>*</b> </label>
                                <select  id="travailC" name="travailC" class="form-control"  required style="border-radius: 5px;">
                                        <option value="">__</option>
                                        <option value="oui">Oui</option>
                                        <option value="non">Non</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Type de dérogation : à renseigner si une dérogation existe pour ce contrat : </label>
                                    <select id="derogationC" name="derogationC"   class="form-control" style="border-radius: 5px;">
                                        <option value="">______________</option>
                                        <option value="11">11 : Age de l’apprenti inférieur à 16 ans</option>
                                        <option value="12">12 : Age supérieur à 29 ans : cas spécifiques prévus dans le code du travail</option>
                                        <option value="21">21 : Réduction de la durée du contrat ou de la période d’apprentissage</option>
                                        <option value="22">22 : Allongement de la durée du contrat ou de la période d’apprentissage</option>
                                        <option value="50">50 : Cumul de dérogations</option>
                                        <option value="60">60 : Autre dérogation</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label  class="control-label">Numéro du contrat précédent ou du contrat sur lequel porte l’avenant: </label>
                                <input type="number" id="numeroC"  name="numeroC"  class="form-control" style="border-radius: 5px;">
                            </div>
                        </div>
                    </div>


                    <div class="row" style="margin-top:13px;">
                     
                       
                        <div class="col-md-3">
                              <div class="form-group">
                                <label style="font-size:11px;"class="control-label">Date de conclusion : (Date de signature du présent contrat):<b>*</b> </label>
                                <input type="date" id="conclusionC"  name="conclusionC"  class="form-control" required style="border-radius: 5px;">
                                
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de début de formation pratique  chez l’employeur:<b>*</b></label>
                                <input type="date" id="debutC" name="debutC"    class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Date de fin du contrat ou de la période d’apprentissage:<b>*</b></label>
                                <input type="date" id="finC"  name="finC" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Si avenant, date d’effet :</label>
                                <input type="date" id="avenantC"  name="avenantC"   class="form-control" style="border-radius: 5px;">
                            </div>
                        </div> 

                    </div> 

                    <div class="row" style="margin-top:13px;">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de début d’exécution du contrat: <b>*</b> </label>
                                <input type="date" id="executionC"  name="executionC"   class="form-control" style="border-radius: 5px;" required>
                            </div>
                        </div> 


                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Durée hebdomadaire du travail:(En heures) :  <b>*</b> </label>
                                    <input type="number" id="dureC" name="dureC"   class="form-control" required style="border-radius: 5px;">
                                </div>
                        </div> 

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Durée hebdomadaire du travail:(En minutes):  <b>*</b> </label>
                                    <input type="number" id="dureCM" name="dureCM"   class="form-control" required style="border-radius: 5px;">
                                </div>
                        </div> 

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Type de contrat ou d’avenant:  <b>*</b> </label>
                                    <select id="typeC" name="typeC" class="form-control" required style="border-radius: 5px;">
                                        <option value="">__________</option>
                                        <optgroup label="Contrat initial">
                                            <option value="11">Premier contrat d’apprentissage de l’apprenti</option>
                                        </optgroup>
                                        <optgroup label="Succession de contrats">
                                            <option value="21">Nouveau contrat avec un apprenti qui a terminé son précédent contrat auprès d’un même employeur</option>
                                            <option value="22">Nouveau contrat avec un apprenti qui a terminé son précédent contrat auprès d’un autre employeur</option>
                                            <option value="23">Nouveau contrat avec un apprenti dont le précédent contrat auprès d’un autre employeur a été rompu</option>
                                        </optgroup>
                                        <optgroup label="Avenant : modification des conditions du contrat">
                                            <option value="31">Modification de la situation juridique de l’employeur</option>
                                            <option value="32">Changement d’employeur dans le cadre d’un contrat saisonnier</option>
                                            <option value="33">Prolongation du contrat suite à un échec à l’examen de l’apprenti</option>
                                            <option value="34">Prolongation du contrat suite à la reconnaissance de l’apprenti comme travailleur handicapé</option>
                                            <option value="35">Modification du diplôme préparé par l’apprenti</option>
                                            <option value="36">Autres changements : changement de maître d’apprentissage, de durée de travail hebdomadaire, réduction de durée, etc.</option>
                                            <option value="37">Modification du lieu d’exécution du contrat</option>
                                        </optgroup>
                                    </select>
                                </div>
                        </div> 
                       
                    </div>


                   

                    <div class="row" style="margin-top:13px;">

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Salaire brut mensuel à l’embauche :  <b>*</b> </label>
                                    <input type="text" id="salaireC" name="salaireC"  class="form-control" style="border-radius: 5px;" required>
                                </div>
                        </div> 

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Caisse de retraite complémentaire :  <b>*</b> </label>
                                    <input type="text" id="caisseC" name="caisseC"  class="form-control" style="border-radius: 5px;" required>
                                </div>
                        </div> 

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Logement:€/mois :  </label>
                                    <input type="number" id="logementC" name="logementC"  style="border-radius: 5px;" class="form-control" >
                                </div>
                        </div> 

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Avantages en nature, le cas échéant:Nourriture:€/repas :  </label>
                                    <input type="number" id="avantageC" name="avantageC"  style="border-radius: 5px;" class="form-control" >
                                </div>
                        </div> 
                    </div>

                    <div class="row" style="margin-top:13px;">

                        <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Autre :  </label>
                                    <select  id="autreC" name="autreC"   style="border-radius: 5px;" class="form-control">
                                        <option value="">__</option>
                                        <option value="oui">Oui</option>
                                        <option value="non">Non</option>
                                    </select>
                                </div>
                        </div> 


                       

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">entreprise prive ou public <b>*</b></label>
                                <select  id="priveO"  name="priveO" required style="border-radius: 5px;" class="form-control">
                                    <option value="">__</option>
                                    <option value="oui">prive</option>
                                    <option value="non">public</option>
                                </select>
                            </div>
                        </div> 

                    </div>

                    

                     <!-- informations Remuneration  -->

                    <div class="row" style="margin-top:10px;">
                        <p class="mainColor text-left"> <b>Rémunération</b></p>
                     </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">1er annee  <b>*</b> </p>
                       
                    </div>

                    <div class="row">
                       <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Du</label>
                                <input type="date" id="rdC" name="rdC" class="form-control"  placeholder="du" required style="border-radius: 5px;">
                            </div>
                        </div> 
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Au</label>
                                <input type="date" id="raC" name="raC"  class="form-control" placeholder="au" required style="border-radius: 5px;">
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Pourcentage</label>
                                <input type="number" id="rpC" name="rpC" class="form-control"  placeholder="pourcentage" required style="border-radius: 5px;">
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <select id="rsC" name="rsC"  class="form-control" style="border-radius: 5px;">
                                    <option value="SMIC">salaire minimum interprofessionnel de croissance</option>
                                    <option value="SMC">salaire minimum conventionnel</option>
                                </select>
                            </div>
                        </div> 
                       
                    </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">2eme annee   </p>
                       
                    </div>

                    <div class="row">
                       <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Du</label>
                                <input type="date" id="rdC1" name="rdC1"  class="form-control" placeholder="du" style="border-radius: 5px;">
                            </div>
                        </div> 
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Au</label>
                                <input type="date" id="raC1" name="raC1"  class="form-control" placeholder="au" style="border-radius: 5px;">
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Pourcentage</label>
                                <input type="number" id="rpC1" name="rpC1" class="form-control"  placeholder="pourcentage" style="border-radius: 5px;">
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <select id="rsC1" name="rsC1" class="form-control" style="border-radius: 5px;">
                                    <option value="">______________</option>
                                    <option value="SMIC">salaire minimum interprofessionnel de croissance</option>
                                    <option value="SMC">salaire minimum conventionnel</option>
                                </select>
                            </div>
                        </div> 
                       
                    </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">3eme annee   </p>
                       
                    </div>

                    <div class="row">
                       <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Du</label>
                                <input type="date" id="rdC2" name="rdC2"  class="form-control" placeholder="du" style="border-radius: 5px;">
                            </div>
                        </div> 
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Au</label>
                                <input type="date" id="raC2" name="raC2"  class="form-control" placeholder="au" style="border-radius: 5px;">
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Pourcentage</label>
                                <input type="number" id="rpC2" name="rpC2"  class="form-control" placeholder="pourcentage" style="border-radius: 5px;">
                            </div>
                        </div> 

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"></label>
                                <select id="rsC2" name="rsC2"   class="form-control" style="border-radius: 5px;">
                                   <option value="">______________</option>
                                    <option value="SMIC">salaire minimum interprofessionnel de croissance</option>
                                    <option value="SMC">salaire minimum conventionnel</option>
                                </select>
                            </div>
                        </div> 
                       
                    </div>




                      <!-- informations Maitre apprentissage   -->

                      <div class="row" style="margin-top:10px;">
                        <p class="mainColor text-left"> <b>LE MAÎTRE D’APPRENTISSAGE</b></p>
                     </div>

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Maître d’apprentissage n°1  <b>*</b> </p>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance :  <b>*</b> </label>
                                <input type="text" id="nomM"   name="nomM"  class="form-control" required >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prénom :  <b>*</b></label>
                                <input type="text" id="prenomM" name="prenomM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de naissance :   <b>*</b></label>
                                <input type="date" id="naissanceM" name="naissanceM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">NIR :   <b>*</b></label>
                                <input type="number" id="securiteM"  name="securiteM" class="form-control" required >
                                <small class="form-text text-muted"> Le numéro de sécurité sociale du maitre de stage doit contenir entre 13 et 15  caractères</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Courriel :  <b>*</b></label>
                                <input type="email" id="emailM" name="emailM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Emploi occupé :  <b>*</b></label>
                                <input type="text" id="emploiM"  name="emploiM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" style="font-size: 11px;">Diplôme ou titre le plus élevé obtenu :   <b>*</b></label>
                                <input type="text" id="diplomeM" name="diplomeM" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" >
                                <label class="control-label"  style="font-size: 10px;">Niveau de diplôme ou titre le plus élevé obtenu :  <b>*</b></label>
                                <select id="niveauM" name="niveauM" class="form-control" required>
                                    <option value="">________</option>
                                    <option value="1">CAP, BEP</option>
                                    <option value="2">Baccalauréat</option>
                                    <option value="3">DEUG, BTS, DUT, DEUST</option>
                                    <option value="4">Licence, Licence professionnelle, BUT, Maîtrise</option>
                                    <option value="5">Master, DEA, DESS, Diplôme d'ingénieur</option>
                                    <option value="6">Doctorat, Habilitation à diriger des recherches</option>
                                </select>
                            </div>
                        </div>   
                    </div>

                 

                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Maître d’apprentissage n°2   </p>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance :  </label>
                                <input type="text" id="nomM1"   name="nomM1"  class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Prénom :  </label>
                                <input type="text" id="prenomM1" name="prenomM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de naissance : </label>
                                <input type="date" id="naissanceM1" name="naissanceM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">NIR :   </label>
                                <input type="number" id="securiteM1"  name="securiteM1" class="form-control" >
                                <small class="form-text text-muted"> Le numéro de sécurité sociale du maitre de stage doit contenir entre 13 et 15  caractères</small>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Courriel :  </label>
                                <input type="email" id="emailM1" name="emailM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Emploi occupé :   </label>
                                <input type="text" id="emploiM1"  name="emploiM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" style="font-size: 11px;">Diplôme ou titre le plus élevé obtenu :  </label>
                                <input type="text" id="diplomeM1" name="diplomeM1" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" >
                                <label class="control-label"  style="font-size: 10px;">Niveau de diplôme ou titre le plus élevé obtenu :  </label>
                                <select id="niveauM1" name="niveauM1" class="form-control" >
                                    <option value="">________</option>
                                    <option value="1">CAP, BEP</option>
                                    <option value="2">Baccalauréat</option>
                                    <option value="3">DEUG, BTS, DUT, DEUST</option>
                                    <option value="4">Licence, Licence professionnelle, BUT, Maîtrise</option>
                                    <option value="5">Master, DEA, DESS, Diplôme d'ingénieur</option>
                                    <option value="6">Doctorat, Habilitation à diriger des recherches</option>
                                </select>
                            </div>
                        </div>   
                    </div>


                    <button type="submit"  id="circle"   class="sendBtn btn  btn-lg btn-rounded  text-center" name="submit_form" >Envoyer</button>
            </form>

                  
        </div>
	</div>
</main>





<script src="./Public/assets/bootstrap/js/bootstrap.bundle.min.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.bundle.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.min.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.esm.js?ver=1.2.0"></script>
<script src="./Public/assets/bootstrap/js/bootstrap.esm.min.js?ver=1.2.0"></script>
</body>
</html>

                   
                   