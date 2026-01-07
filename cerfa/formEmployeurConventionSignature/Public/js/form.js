function redirection(){
  window.location.replace('home');
 }
     
 
 function ModalOpen(){
   var modal = document.getElementById("myModal");
   modal.style.display = "block"; 
 }
 
 function closeModal() {
   // Récupérer la modale
   var modal = document.getElementById("myModal");
   // Masquer la modale
   modal.style.display = "none";
 }
 
 
 
 
 function load() {
  initializeSignaturePad();
   var url = document.getElementById("url").value;
   const documentContainer = document.getElementById("pdfViewer");
   const fileType = url.split('.').pop().toLowerCase();
   documentContainer.innerHTML = '';
 
   if (fileType === 'pdf') {
     const loadingTask = pdfjsLib.getDocument(url);
     loadingTask.promise.then(function(pdf) {
       // Fetch all pages
       for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
         pdf.getPage(pageNum).then(function(page) {
           const scale = 1.5;
           const viewport = page.getViewport({scale: scale});
           const canvas = document.createElement('canvas');
           const context = canvas.getContext('2d');
           canvas.height = viewport.height;
           canvas.width = viewport.width;
 
           // Append the canvas to the container
           documentContainer.appendChild(canvas);
 
           // Render the page into the canvas context
           const renderContext = {
             canvasContext: context,
             viewport: viewport
           };
           page.render(renderContext);
         });
       }
     }, function(reason) {
       console.error(reason);
     });
   } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
     const imageViewer = document.createElement('img');
     imageViewer.src = url;
     imageViewer.style.maxWidth = "100%";
     imageViewer.style.height = "auto";
     documentContainer.appendChild(imageViewer);
   } else {
     documentContainer.innerHTML = 'Unsupported file type.';
   }
 }
 
 
 function file() {
   const fileInput = document.getElementById('file');
   fileInput.click();
 
   fileInput.addEventListener('change', handleFileChange);
 }
 
 function handleFileChange(event) {
   const file = event.target.files[0];
   if (file) {
     const fileType = file.type;
     const validImageTypes = ['image/jpeg', 'image/png','image/jpg'];
 
     if (!validImageTypes.includes(fileType)) {
       toastr.error("Veuillez sélectionner un fichier image valide (JPEG,JPEG, PNG).", "Erreur", { "iconClass": 'customer-error' });
       return;
     }
 
     const reader = new FileReader();
     reader.onload = function(e) {
       const fileContent = e.target.result.split(',')[1]; // Récupérer uniquement les données base64
       sendFileContent(fileContent, file.name, fileType);
     };
     reader.readAsDataURL(file);
   }
 }
 
 
 function sendFileContent(fileContent, fileName, fileType) {
   const target = $('#loader');
   $('.sendBtn').prop('disabled', true);
 
   // Créer l'instance du loader
   const spinner = new Spinner().spin(target[0]);
   var url = 'formSignature'; // L'URL à laquelle vous souhaitez envoyer les données
   var data = {
     fileContent: fileContent,
     fileName: fileName,
     fileType: fileType
   };
 
   // Envoi de la requête POST avec jQuery AJAX
   $.ajax({
     url: url,
     type: 'POST',
     data: data,
     dataType: 'json',
     success: function(response) {
       toastr.options.timeOut = 2000;
       if (response.error) {
         toastr.error(response.message, "Erreur", { "iconClass": 'customer-error' });
         $('#file').val('');
         closeModal();
         setTimeout(function() {
           location.reload();
         }, 2500);
       } else {
         toastr.success(response.message, "Succès", { "iconClass": 'customer-success' });
         toastr.info("Merci pour votre collaboration. Nous reviendrons vers vous au plus vite", "Formulaire soumis avec succès", { "iconClass": 'customer-authentification' });
         $('#file').val('');
         closeModal();
         setTimeout(function() {
           location.reload();
         }, 2500);
       }
     },
     error: function(xhr, status, error) {
       toastr.error("Une erreur s'est produite lors de la soumission du formulaire.", "Erreur", { "iconClass": 'customer-error' });
       $('#file').val('');
       closeModal();
       setTimeout(function() {
         location.reload();
       }, 2500);
     },
     complete: function() {
       // Fonction à exécuter une fois la requête terminée
       spinner.stop();
       closeModal();
     }
   });
 }
 

 function ModalOpenSignature(){
  closeModal();
  var modal = document.getElementById("myModalSignature");
  modal.style.display = "block"; 
  initializeSignaturePad();
}   
 
 
function closeModalSignature() {
  ModalOpen();
  var modal = document.getElementById("myModalSignature");
  modal.style.display = "none";
}

function closeModalSignatureSigne() {
  var modal = document.getElementById("myModalSignature");
  modal.style.display = "none";
}




function initializeSignaturePad() {
  const canvas = document.getElementById('signaturepad');
  const ctx = canvas.getContext('2d');
  let writingMode = false;

  // Gestion du dessin
  canvas.addEventListener('pointerdown', handlePointerDown, { passive: true });
  canvas.addEventListener('pointerup', handlePointerUp, { passive: true });
  canvas.addEventListener('pointermove', handlePointerMove, { passive: true });

  function handlePointerDown(event) {
    writingMode = true;
    ctx.beginPath();
    const [positionX, positionY] = getCursorPosition(event);
    ctx.moveTo(positionX, positionY);
  }

  function handlePointerUp() {
    writingMode = false;
  }

  function handlePointerMove(event) {
    if (!writingMode) return;
    const [positionX, positionY] = getCursorPosition(event);
    ctx.lineTo(positionX, positionY);
    ctx.stroke();
  }

  function getCursorPosition(event) {
    const rect = canvas.getBoundingClientRect(); // Récupère les dimensions et la position du canvas
    const scaleX = canvas.width / rect.width;   // Facteur d'échelle horizontal
    const scaleY = canvas.height / rect.height; // Facteur d'échelle vertical

    // Calcul des positions X et Y ajustées
    const positionX = (event.clientX - rect.left) * scaleX;
    const positionY = (event.clientY - rect.top) * scaleY;
    return [positionX, positionY];
  }

  // Paramètres de style pour le dessin
  ctx.lineWidth = 3;
  ctx.lineJoin = ctx.lineCap = 'round';

  return {
    clearSignaturePad: function() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
    },
    getSignatureDataURL: function() {
      return canvas.toDataURL();
    }
  };
}


function clearCanvas(){
  const signaturePad = initializeSignaturePad();
  signaturePad.clearSignaturePad();
}
function sendData() {
  const target = $('#loader');
  $('.sendBtn').prop('disabled', true);
  const signaturePad = initializeSignaturePad();

  const imageURL = signaturePad.getSignatureDataURL();

  const formData = new FormData();
  formData.append('signature', imageURL);

  const spinner = new Spinner().spin(target[0]);
  const submitButton = document.getElementById('circle');
  submitButton.disabled = true;

  $.ajax({
    url: 'formSignatureManuelle',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      toastr.options.timeOut = 2000;
      submitButton.disabled = false;
      if (response.error) {
        toastr.error(response.message, "Erreur", { "iconClass": 'customer-error' });
        closeModal();
        closeModalSignatureSigne();
        setTimeout(function() {
          location.reload();
        }, 2500);
      } else {
        toastr.success(response.message, "Succès", { "iconClass": 'customer-success' });
        toastr.info("Merci pour votre collaboration. Nous reviendrons vers vous au plus vite", "Formulaire soumis avec succès", { "iconClass": 'customer-authentification' });
        closeModal();
        closeModalSignatureSigne();
        setTimeout(function() {
          location.reload();
        }, 2500);
      }
    },
    error: function(xhr, status, error) {
      submitButton.disabled = false;
      toastr.error("Une erreur s'est produite lors de la soumission du formulaire.", "Erreur", { "iconClass": 'customer-error' });
      closeModal();
      closeModalSignatureSigne();
      setTimeout(function() {
        location.reload();
      }, 2500);
    },
    complete: function() {
      spinner.stop();
      closeModal();
      closeModalSignatureSigne();
    }
  });

  return false;
}
