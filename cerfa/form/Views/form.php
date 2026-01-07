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
<main class="bg-white"  > 
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
		   style="font-style: oblique; font-weight: normal;"class="text-center ">   Remplissez ce formulaire pour l'etablissement de votre cerfa 
            <p style="color: red;">(*) Champs obligatoires</p>
 </h6></p>
        </div>
		
        <div>
        	 <form  onsubmit="return sendData();" method="POST"  id="myForm">
             <div class="row">
                       
                        <div class="col-md-3  col-xs-12">
                            <div class="form-group">
                                <label class="control-label"  style="font-size: 13px;">Nom de naissance de l’apprenti(e) :<b>*</b></label>
                                <input type="text" id="nomA" name="nomA" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Nom d’usage :  </label>
                                <input type="text" id="nomuA" name="nomuA" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label"  style="font-size: 13px;">Le premier prénom de l’apprenti(e)<b>*</b></label>
                                <input type="text" id="prenomA" name="prenomA" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label class="control-label">Sexe :  <b>*</b></label>
                                <select  id="sexeA"  name="sexeA" class="form-control" required>
                                <option value="">__</option>
                                <option value="M">M</option>
                                <option value="F">F</option>
                               
                            </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                     
                       
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Date de naissance :   <b>*</b></label>
                                <input type="date" id="naissanceA" name="naissanceA" class="form-control"  required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Département de naissance : <b>*</b></label>
                                <select id="departementA" name="departementA" class="form-control" required>
                                    <option value="">_______</option>
                                    <option value="01">01 - Ain - Bourg-en-Bresse</option>
                                    <option value="02">02 - Aisne - Laon</option>
                                    <option value="03">03 - Allier - Moulins</option>
                                    <option value="04">04 - Alpes-de-Haute-Provence - Digne-les-Bains</option>
                                    <option value="05">05 - Hautes-Alpes - Gap</option>
                                    <option value="06">06 - Alpes-Maritimes - Nice</option>
                                    <option value="07">07 - Ardèche - Privas</option>
                                    <option value="08">08 - Ardennes - Charleville-Mézières</option>
                                    <option value="09">09 - Ariège - Foix</option>
                                    <option value="10">10 - Aube - Troyes</option>
                                    <option value="11">11 - Aude - Carcassonne</option>
                                    <option value="12">12 - Aveyron - Rodez</option>
                                    <option value="13">13 - Bouches-du-Rhône - Marseille</option>
                                    <option value="14">14 - Calvados - Caen</option>
                                    <option value="15">15 - Cantal - Aurillac</option>
                                    <option value="16">16 - Charente - Angoulême</option>
                                    <option value="17">17 - Charente-Maritime - La Rochelle</option>
                                    <option value="18">18 - Cher - Bourges</option>
                                    <option value="19">19 - Corrèze - Tulle</option>
                                    <option value="2a">2A - Corse-du-Sud - Ajaccio</option>
                                    <option value="2b">2B - Haute-Corse - Bastia</option>
                                    <option value="21">21 - Côte-d'Or - Dijon</option>
                                    <option value="22">22 - Côtes-d'Armor - Saint-Brieuc</option>
                                    <option value="23">23 - Creuse - Guéret</option>
                                    <option value="24">24 - Dordogne - Périgueux</option>
                                    <option value="25">25 - Doubs - Besançon</option>
                                    <option value="26">26 - Drôme - Valence</option>
                                    <option value="27">27 - Eure - Évreux</option>
                                    <option value="28">28 - Eure-et-Loir - Chartres</option>
                                    <option value="29">29 - Finistère - Quimper</option>
                                    <option value="30">30 - Gard - Nîmes</option>
                                    <option value="31">31 - Haute-Garonne - Toulouse</option>
                                    <option value="32">32 - Gers - Auch</option>
                                    <option value="33">33 - Gironde - Bordeaux</option>
                                    <option value="34">34 - Hérault - Montpellier</option>
                                    <option value="35">35 - Ille-et-Vilaine - Rennes</option>
                                    <option value="36">36 - Indre - Châteauroux</option>
                                    <option value="37">37 - Indre-et-Loire - Tours</option>
                                    <option value="38">38 - Isère - Grenoble</option>
                                    <option value="39">39 - Jura - Lons-le-Saunier</option>
                                    <option value="40">40 - Landes - Mont-de-Marsan</option>
                                    <option value="41">41 - Loir-et-Cher - Blois</option>
                                    <option value="42">42 - Loire - Saint-Étienne</option>
                                    <option value="43">43 - Haute-Loire - Le Puy-en-Velay</option>
                                    <option value="44">44 - Loire-Atlantique - Nantes</option>
                                    <option value="45">45 - Loiret - Orléans</option>
                                    <option value="46">46 - Lot - Cahors</option>
                                    <option value="47">47 - Lot-et-Garonne - Agen</option>
                                    <option value="48">48 - Lozère - Mende</option>
                                    <option value="49">49 - Maine-et-Loire - Angers</option>
                                    <option value="50">50 - Manche - Saint-Lô</option>
                                    <option value="51">51 - Marne - Châlons-en-Champagne</option>
                                    <option value="52">52 - Haute-Marne - Chaumont</option>
                                    <option value="53">53 - Mayenne - Laval</option>
                                    <option value="54">54 - Meurthe-et-Moselle - Nancy</option>
                                    <option value="55">55 - Meuse - Bar-le-Duc</option>
                                    <option value="56">56 - Morbihan - Vannes</option>
                                    <option value="57">57 - Moselle - Metz</option>
                                    <option value="58">58 - Nièvre - Nevers</option>
                                    <option value="59">59 - Nord - Lille</option>
                                    <option value="60">60 - Oise - Beauvais</option>
                                    <option value="61">61 - Orne - Alençon</option>
                                    <option value="62">62 - Pas-de-Calais - Arras</option>
                                    <option value="63">63 - Puy-de-Dôme - Clermont-Ferrand</option>
                                    <option value="64">64 - Pyrénées-Atlantiques - Pau</option>
                                    <option value="65">65 - Hautes-Pyrénées - Tarbes</option>
                                    <option value="66">66 - Pyrénées-Orientales - Perpignan</option>
                                    <option value="67">67 - Bas-Rhin - Strasbourg</option>
                                    <option value="68">68 - Haut-Rhin - Colmar</option>
                                    <option value="69">69 - Rhône - Lyon</option>
                                    <option value="70">70 - Haute-Saône - Vesoul</option>
                                    <option value="71">71 - Saône-et-Loire - Mâcon</option>
                                    <option value="72">72 - Sarthe - Le Mans</option>
                                    <option value="73">73 - Savoie - Chambéry</option>
                                    <option value="74">74 - Haute-Savoie - Annecy</option>
                                    <option value="75">75 - Paris - Paris</option>
                                    <option value="76">76 - Seine-Maritime - Rouen</option>
                                    <option value="77">77 - Seine-et-Marne - Melun</option>
                                    <option value="78">78 - Yvelines - Versailles</option>
                                    <option value="79">79 - Deux-Sèvres - Niort</option>
                                    <option value="80">80 - Somme - Amiens</option>
                                    <option value="81">81 - Tarn - Albi</option>
                                    <option value="82">82 - Tarn-et-Garonne - Montauban</option>
                                    <option value="83">83 - Var - Toulon</option>
                                    <option value="84">84 - Vaucluse - Avignon</option>
                                    <option value="85">85 - Vendée - La Roche-sur-Yon</option>
                                    <option value="86">86 - Vienne - Poitiers</option>
                                    <option value="87">87 - Haute-Vienne - Limoges</option>
                                    <option value="88">88 - Vosges - Épinal</option>
                                    <option value="89">89 - Yonne - Auxerre</option>
                                    <option value="90">90 - Territoire de Belfort - Belfort</option>
                                    <option value="91">91 - Essonne - Évry</option>
                                    <option value="92">92 - Hauts-de-Seine - Nanterre</option>
                                    <option value="93">93 - Seine-Saint-Denis - Bobigny</option>
                                    <option value="94">94 - Val-de-Marne - Créteil</option>
                                    <option value="95">95 - Val-d'Oise - Pontoise</option>
                                    <option value="971">971 - Guadeloupe - Basse-Terre</option>
                                    <option value="972">972 - Martinique - Fort-de-France</option>
                                    <option value="973">973 - Guyane - Cayenne</option>
                                    <option value="974">974 - La Réunion - Saint-Denis</option>
                                    <option value="976">976 - Mayotte - Dzaoudzi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Commune de naissance :   <b>*</b></label>
                                <input type="text" id="communeNA" name="communeNA" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">Nationalité :  <b>*</b></label>
                             <select id="nationaliteA" name="nationaliteA" class="form-control" required>
                                <option value="">_______</option>
                                <option value="1">1 : Française</option>
                                <option value="2">2 : Union Européenne</option>
                                <option value="3">3 : Étranger hors Union Européenne</option>
                             </select>
                         </div>
                     </div>
                    </div>


                    <div class="row">
                     
                     
                     <div class="col-md-2">
                         <div class="form-group">
                             <label class="control-label">Régime social :    <b>*</b></label>
                             <select id="regimeA" name="regimeA" class="form-control" required>
                                <option value="">_______</option>
                                <option value="1">1 : MSA</option>
                                <option value="2">2 : URSSAF</option>
                            </select>
                         </div>
                     </div>
                     <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">Situation avant ce contrat :  <b>*</b></label>
                             <select id="situationA" name="situationA" class="form-control" required>
                                <option value="">_______</option>
                                <option value="1">1 : Scolaire</option>
                                <option value="2">2 : Prépa apprentissage</option>
                                <option value="3">3 : Étudiant</option>
                                <option value="4">4 : Contrat d’apprentissage</option>
                                <option value="5">5 : Contrat de professionnalisation</option>
                                <option value="6">6 : Contrat aidé</option>
                                <option value="7">7 : En formation au CFA avant signature d’un contrat d’apprentissage (L6222-12-1 du code du travail)</option>
                                <option value="8">8 : En formation, au CFA, sans contrat, suite à rupture (5° de L6231-2 du code du travail)</option>
                                <option value="9">9 : Stagiaire de la formation professionnelle</option>
                                <option value="10">10 : Salarié</option>
                                <option value="11">11 : Personne à la recherche d’un emploi (inscrite ou non au Pôle Emploi)</option>
                                <option value="12">12 : Inactif</option>
                            </select>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Dernier diplôme ou titre préparé :   <b>*</b></label>
                             <select id="titrePA" name="titrePA" class="form-control" required>
                                 <option value="">_______</option>
                                <optgroup label="Diplôme ou titre de niveau bac +5 et plus">
                                    <option value="80">80 : Doctorat</option>
                                    <option value="71">71 : Master professionnel/DESS</option>
                                    <option value="72">72 : Master recherche/DEA</option>
                                    <option value="73">73 : Master indifférencié</option>
                                    <option value="74">74 : Diplôme d'ingénieur, diplôme d'école de commerce</option>
                                    <option value="79">79 : Autre diplôme ou titre de niveau bac+5 ou plus</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac +3 et 4">
                                    <option value="61">61 : 1ère année de Master</option>
                                    <option value="62">62 : Licence professionnelle</option>
                                    <option value="63">63 : Licence générale</option>
                                    <option value="69">69 : Autre diplôme ou titre de niveau bac +3 ou 4</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac +2">
                                    <option value="54">54 : Brevet de Technicien Supérieur</option>
                                    <option value="55">55 : Diplôme Universitaire de technologie</option>
                                    <option value="58">58 : Autre diplôme ou titre de niveau bac+2</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac">
                                    <option value="41">41 : Baccalauréat professionnel</option>
                                    <option value="42">42 : Baccalauréat général</option>
                                    <option value="43">43 : Baccalauréat technologique</option>
                                    <option value="49">49 : Autre diplôme ou titre de niveau bac</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau CAP/BEP">
                                    <option value="33">33 : CAP</option>
                                    <option value="34">34 : BEP</option>
                                    <option value="35">35 : Mention complémentaire</option>
                                    <option value="38">38 : Autre diplôme ou titre de niveau CAP/BEP</option>
                                </optgroup>
                                <optgroup label="Aucun diplôme ni titre">
                                    <option value="25">25 : Diplôme national du Brevet (DNB)</option>
                                    <option value="26">26 : Certificat de formation générale</option>
                                    <option value="13">13 : Aucun diplôme ni titre professionnel</option>
                                   
                                </optgroup>
                            </select>
                         </div>
                     </div>
                     <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">Dernière classe / année suivie :  <b>*</b></label>
                             <select id="derniereCA" name="derniereCA" class="form-control" required>
                                <option value="">______</option>
                                <option value="1">1 : l’apprenti a suivi la dernière année du cycle de formation et a obtenu le diplôme ou titre</option>
                                <option value="11">11 : l’apprenti a suivi la 1ère année du cycle et l’a validée (examens réussis mais année non diplômante)</option>
                                <option value="12">12 : l’apprenti a suivi la 1ère année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                <option value="21">21 : l’apprenti a suivi la 2è année du cycle et l’a validée (examens réussis mais année non diplômante)</option>
                                <option value="22">22 : l’apprenti a suivi la 2è année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                <option value="31">31 : l’apprenti a suivi la 3è année du cycle et l’a validée (examens réussis mais année non diplômante, cycle adapté)</option>
                                <option value="32">32 : l’apprenti a suivi la 3è année du cycle mais ne l’a pas validée (échec aux examens, interruption ou abandon de formation)</option>
                                <option value="40">40 : l’apprenti a achevé le 1er cycle de l’enseignement secondaire (collège)</option>
                                <option value="41">41 : l’apprenti a interrompu ses études en classe de 3ème</option>
                            </select>
                         </div>
                     </div>
                 </div>

                 <div class="row">
                 
                 <div class="col-md-3">
                         <div class="form-group">
                             <label class="control-label">numero securite social :  <b>*</b></label>
                             <input type="number" id="securiteA" name="securiteA" class="form-control" required>
                             <small class="form-text text-muted"> Le numéro de sécurité sociale de l'apprenti doit contenir entre 13 et 15  caractères</small>
                         </div>
                     </div>
                     
                     <div class="col-md-5">
                         <div class="form-group">
                             <label class="control-label">Intitulé précis du dernier diplôme ou titre préparé :    <b>*</b></label>
                             <input type="text" id="intituleA"   name="intituleA" class="form-control" required>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label">Diplôme ou titre le plus élevé obtenu :  <b>*</b></label>
                             <select id="titreOA" name="titreOA" class="form-control" required>
                                  <option value="">_______</option>
                                <optgroup label="Diplôme ou titre de niveau bac +5 et plus">
                                    <option value="80">80 : Doctorat</option>
                                    <option value="71">71 : Master professionnel/DESS</option>
                                    <option value="72">72 : Master recherche/DEA</option>
                                    <option value="73">73 : Master indifférencié</option>
                                    <option value="74">74 : Diplôme d'ingénieur, diplôme d'école de commerce</option>
                                    <option value="79">79 : Autre diplôme ou titre de niveau bac+5 ou plus</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac +3 et 4">
                                    <option value="61">61 : 1ère année de Master</option>
                                    <option value="62">62 : Licence professionnelle</option>
                                    <option value="63">63 : Licence générale</option>
                                    <option value="69">69 : Autre diplôme ou titre de niveau bac +3 ou 4</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac +2">
                                    <option value="54">54 : Brevet de Technicien Supérieur</option>
                                    <option value="55">55 : Diplôme Universitaire de technologie</option>
                                    <option value="58">58 : Autre diplôme ou titre de niveau bac+2</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau bac">
                                    <option value="41">41 : Baccalauréat professionnel</option>
                                    <option value="42">42 : Baccalauréat général</option>
                                    <option value="43">43 : Baccalauréat technologique</option>
                                    <option value="49">49 : Autre diplôme ou titre de niveau bac</option>
                                </optgroup>
                                <optgroup label="Diplôme ou titre de niveau CAP/BEP">
                                    <option value="33">33 : CAP</option>
                                    <option value="34">34 : BEP</option>
                                    <option value="35">35 : Mention complémentaire</option>
                                    <option value="38">38 : Autre diplôme ou titre de niveau CAP/BEP</option>
                                </optgroup>
                                <optgroup label="Aucun diplôme ni titre">
                                    <option value="25">25 : Diplôme national du Brevet (DNB)</option>
                                    <option value="26">26 : Certificat de formation générale</option>
                                    <option value="13">13 : Aucun diplôme ni titre professionnel</option>
                                   
                                </optgroup>
                            </select>
                         </div>
                     </div>
                     
                 </div>




                    <div class="row">
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label"  style="font-size: 12px;">Déclare être inscrit sur la liste des sportifs de haut  niveau : <b>*</b></label>
                             <select  id="declareSA"  name="declareSA" class="form-control" required>
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label"  style="font-size: 12px;">Déclare bénéficier de la reconnaissance travailleur handicapé : <b>*</b></label>
                             <select  id="declareHA" name="declareHA" class="form-control" required>
                                <option value="">__</option>
                                <option value="oui">Oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                         </div>
                     </div>
                     <div class="col-md-4">
                         <div class="form-group">
                             <label class="control-label"   style="font-size: 12px;">Déclare avoir un projet de création ou de reprise d’entreprise : <b>*</b> </label>
                             <select  id="declareRA" id="declareRA" class="form-control" required>
                                <option value="">__</option>
                                <option value="oui">oui</option>
                                <option value="non">Non</option>
                               
                            </select>
                         </div>
                     </div>
                 </div>


                    <div class="row">
                        <p style="margin-left:15px; "   class=" text-left">Adresse de l’apprenti(e) :  <b>*</b> </p>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="rueA"   name="rueA" class="form-control" placeholder="N°  *" required >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <input type="text" id="voieA"  name="voieA" class="form-control"  placeholder="Voie  *"  required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                               <input type="text" id="complementA"  name="complementA" class="form-control" placeholder="Complement" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="number" id="postalA" name="postalA" class="form-control" placeholder="Code Postal *" required >
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres</small>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                       <div class="col-md-6">
                            <div class="form-group">
                                 <input type="text" id="communeA"  name="communeA" class="form-control"  placeholder="Commune *" required >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                 <input type="number" id="numeroA"  name="numeroA" class="form-control"  placeholder="Téléphone :  *"  required>
                                 <small class="form-text text-muted">Veuillez entrer exactement 10 chiffres sans espaces</small>
                            </div>
                        </div>
                       
                       
                    </div>


                      <!-- informations representant legal  -->

                    <div class="row">
                        <p class="mainColor text-left"> <b>Représentant légal (à renseigner si l’apprenti est mineur non  émancipé) </b></p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Nom de naissance  : </label>
                                <input type="text" id="nomR" name="nomR" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Prénom : </label>
                                <input type="text" id="prenomR" name="prenomR" class="form-control" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <label class="control-label">Courriel : </label>
                                 <input type="text" id="emailR"  name="emailR" class="form-control"  placeholder="Courriel"  >
                            </div>
                        </div>
                       
                      
                    </div>

                    <div class="row">
                        <p style="margin-left:10px; "   class=" text-left">Adresse du représentant légal :  </p>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" id="rueR"  name="rueR" class="form-control" placeholder="N°" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <input type="text" id="voieR"  name="voieR" class="form-control"  placeholder="Voie"  >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               <input type="text" id="complementR"  name="complementR" class="form-control" placeholder="Complement">
                            </div>
                        </div>
                    </div>
                    <div class="row"  style="margin-top:10px;">
                      
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" id="postalR"  name="postalR" class="form-control" placeholder="Code Postal" >
                                <small class="form-text text-muted">Veuillez entrer exactement 5 chiffres</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                 <input type="text" id="communeR" name="communeR" class="form-control"  placeholder="Commune " >
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

                   
                   