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
		   style="font-style: oblique; font-weight: normal;"class="text-center ">  Remplissez ce formulaire pour l'etablissement de votre cerfa 
            <p style="color: red;">(*) Champs obligatoires</p>
 </h6></p>
        </div>
		
        <div>
        	 <form  onsubmit="return sendData();" method="POST"  id="myForm">
             <div class="row">
                       
                        <div class="col-md-3  col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Type d’employeur:  <b>*</b></label>
                                <select id="typeE" name="typeE" class="form-control" required style="border-radius: 5px;">
                                     <option value="">_________</option>
                                    <optgroup label="Privé">
                                        <option value="11">11 :Entreprise inscrite au répertoire des métiers ou au registre des entreprises pour l’Alsace-Moselle</option>
                                        <option value="12">12 :Entreprise inscrite uniquement au registre du commerce et des sociétés</option>
                                        <option value="13">13 :Entreprises dont les salariés relèvent de la mutualité sociale agricole</option>
                                        <option value="14">14 :Profession libérale</option>
                                        <option value="15">15 :Association</option>
                                        <option value="16">16 :Autre employeur privé</option>
                                    </optgroup>
                                    <optgroup label="Public">
                                        <option value="21">21 :Service de l’Etat (administrations centrales et leurs services déconcentrés de la fonction publique d’Etat)</option>
                                        <option value="22">22 :Commune</option>
                                        <option value="23">23 :Département</option>
                                        <option value="24">24 :Région</option>
                                        <option value="25">25 :Etablissement public hospitalier</option>
                                        <option value="26">26 :Etablissement public local d’enseignement</option>
                                        <option value="27">27 :Etablissement public administratif de l’Etat</option>
                                        <option value="28">28 :Etablissement public administratif local (y compris établissement public de coopération intercommunale EPCI)</option>
                                        <option value="29">29 :Autre employeur public</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Employeur spécifique :  <b>*</b> </label>
                                <select id="specifiqueE" name="specifiqueE" class="form-control" required style="border-radius: 5px;">
                                    <option value="">_________</option>
                                    <option value="1">1 :Entreprise de travail temporaire</option>
                                    <option value="2">2 :Groupement d’employeurs</option>
                                    <option value="3">3 :Employeur saisonnier</option>
                                    <option value="4">4 :Apprentissage familial : l’employeur est un ascendant de l’apprenti</option>
                                    <option value="0">0 :Aucun de ces cas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Effectif total salariés : <b>*</b> </label>
                                <input type="number" id="totalE"  name="totalE" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label style="font-size:11px;" class="control-label">N°SIRET de l’établissement d’exécution du contrat : <b>*</b></label>
                                <input type="number" id="siretE"  name="siretE" class="form-control" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Le numéro SIRET doit contenir exactement 14 chiffres</small>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                     
                       
                        <div class="col-md-3">
                              <div class="form-group">
                                <label style="font-size:11px;"class="control-label">Code activité de l’entreprise (NAF) : <b>*</b> </label>
                                <input type="text" id="codeaE"  name="codeaE" class="form-control" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Le code NAF ne doit pas dépasser 6 caractères</small>
                                
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Code IDCC de la convention collective applicable : <b>*</b></label>
                                <input type="text" id="codeiE" name="codeiE" class="form-control" required style="border-radius: 5px;">
                            </div>
                        </div>
                       
                    </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left  ">Adresse de l’établissement d’exécution du contrat : <b>*</b></p>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="rueE" name="rueE" class="form-control" placeholder="N° "  style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <input type="text" id="voieE" name="voieE" class="form-control"  placeholder="Voie *"  required style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                               <input type="text" id="complementE"  name="complementE" class="form-control" placeholder="Complement" style="border-radius: 5px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="number" id="postalE"  name="postalE" class="form-control" placeholder="Code Postal *" required style="border-radius: 5px;">
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      
                       
                      <div class="col-md-4">
                          <div class="form-group">
                               <input type="text" id="communeE" name="communeE" class="form-control"  placeholder="Commune *"   required style="border-radius: 5px;">
                          </div>
                      </div>
                     
                      <div class="col-md-4">
                          <div class="form-group">
                              
                              <input type="number" id="numeroE"  name="numeroE" class="form-control"  placeholder="Téléphone *" required style="border-radius: 5px;">
                              <small class="form-text text-muted">Veuillez entrer exactement 10 chiffres</small>
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

                   
                   