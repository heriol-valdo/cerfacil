function openNav() {
  document.getElementById("infouser").style.marginRight = "17%";
  document.getElementById("mySidenav").style.width = "200px";
  document.getElementById("main").style.marginLeft = "0px";
  document.getElementById("open").setAttribute("onclick","closeNav()");
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
function closeNav() {
   document.getElementById("infouser").style.marginRight = "1%";
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft = "0";
  document.getElementById("open").setAttribute("onclick","openNav()");
}

function logout() {
   // Détruire la session en supprimant la variable de session 'email'
   deleteSessionVariable('email');

   // Rediriger ou afficher un message de déconnexion réussie
   window.location.replace("auth");
}

function deleteSessionVariable(variableName) {
   // Utilisez cette fonction pour supprimer une variable de session
   if (typeof Storage !== "undefined") {
       // Supprimez la variable de session côté client
       sessionStorage.removeItem(variableName);
   } else {
       // Supprimez la variable de session côté serveur (méthode moins courante)
       var xhr = new XMLHttpRequest();
       xhr.open("GET", "clear_session.php?variable=" + variableName, true);
       xhr.send();
   }
}


function dashbord(){
   $( "#conteneur" ).load( "dashbord" );
  
}

function admin(){

   $( "#conteneur" ).load( "admin" );
}
function pharmacie(){
   $( "#conteneur" ).load( "../dom/pharmacie.php" );
}




function administration(){
   $( "#conteneur" ).load( "../dom/administration.php" );
}


