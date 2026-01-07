<?php

include __DIR__ . '/../elements/header.php';

require_once __DIR__ . '/../../Controller/AssistanceController.php';
$resultTicket = AssistanceController::details();
$ticketDetails = $resultTicket['ticketDetails'];
$dateCreation = $resultTicket['dateCreation'];
$ticketExchangeDetails = $resultTicket['ticketExchangeDetails'];


?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <title>Demande d'assistance | ErpFacil</title>
  <link rel="stylesheet" href="../../erp/assets/style/assistanceStyle.css" />
  <link rel="stylesheet" href="../../erp/assets/style/contentContainer.css" />
  <link rel="stylesheet" href="../../erp/assets/style/cardStyle.css" />
  <link rel="stylesheet" href="../../erp/assets/style/tableStyle.css" />
  <link rel="stylesheet" href="../../erp/assets/style/profilstyle.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../erp/assets/style/listeEtudiants.css" />
  <link rel="stylesheet" href="../../erp/assets/style/modals.css" />

  <!-- Google Font Link for Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
</head>

<body>
  <div class="content-container">
    <header>
      <div class="header-container">
        <i class="fa-solid fa-question header-icon"></i>
        <div class="title">
          <h1>Demande d'assistance</h1>
        </div>
        <div class="back-icon-container">
          <a href="assistance"><i class="fa-solid fa-arrow-left-long fa-2xl" style="color: #263b4a;"></i></a>
        </div>
      </div>
    </header>
    <main>
      <div class="ticket-details-container">
        <div class="ticket-exchange-details">
          <div class="ticket-asking">
            <?php if ($_SESSION['user']['role'] == 1): ?>
              <div>
                <div class="ticket-section-title">
                  <p>Auteur du ticket</p>
                </div>
                <div class="ticket-asking-text"><?= $ticketDetails->data->firstname ?>
                  <?= strtoupper($ticketDetails->data->lastname) ?>
                </div>
              </div>
            <?php endif; ?>
            <div>
              <div class="ticket-section-title">
                <p>Date de création du ticket</p>
              </div>
              <div class="ticket-asking-text"><?= $dateCreation ?></div>
            </div>
            <div>
              <div class="ticket-section-title">
                <p>État</p>
              </div>
              <div class="ticket-asking-text"><?= $ticketDetails->data->etat ?></div>
            </div>
            <div>
              <div class="ticket-section-title">
                <p>Objet</p>
              </div>
              <div class="ticket-asking-text"><?= $ticketDetails->data->objet ?></div>
            </div>
            <div>
              <div class="ticket-section-title">
                <p>Description du problème</p>
              </div>
              <div class="ticket-asking-text-description"><?= $ticketDetails->data->description ?></div>
            </div>
          </div>
          <!---------------------
           Partie Messagerie
           ---------------------->
          <div class="ticket-answer">
            <div class="ticket-section-title">
              <p>Messages</p>
            </div>
            <div class="ticket-answers-container">
              <?php if (!isset($ticketExchangeDetails->erreur)): ?>
                <?php 
                  $messages = $ticketExchangeDetails->data;
                  usort($messages, function($a, $b) {
                      return strtotime($b->dateCreation) - strtotime($a->dateCreation);
                  });
                  
                  foreach ($messages as $message): ?>
                  <?php  // Formatage date à la FR
                    $dateMessage = new DateTime($message->dateCreation);
                    $dateMessage = $dateMessage->format('d-m-Y H:i:s'); 
                  ?>
                  <div class="ticket-answer-text">
                    <div class="ticket-answer-header">
                      <div class="ticket-answer-author">
                        <div><?= $message->id_users == $_SESSION['user']['id'] ? "Moi" : htmlspecialchars($ticketDetails->data->firstname)." ".htmlspecialchars(strtoupper($ticketDetails->data->lastname)) ?> </div>
                      </div>
                      <div class="ticket-answer-date"><?php if ((($message->id_users == $_SESSION['user']['id']) && ($ticketDetails->data->etat == 'Envoyé')) || $_SESSION['user']['role'] == 1): ?>
                        <div>
                          <button class="chat-btn" onclick="confirmDeleteElement(<?= $message->id; ?>)">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </div>
                      <?php endif; ?><?= $dateMessage ?></div>
                    </div>
                    <div class="ticket-answer-content"><?= $message->contenu ?></div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="ticket-answer-text">
                  <p><?= $ticketExchangeDetails->erreur; ?></p>
                </div>
              <?php endif; ?>
            </div>
            <?php if($_SESSION['user']['role'] == 1 || $ticketDetails->data->etat == 'Envoyé' || $ticketDetails->data->etat == 'En cours de traitement' ): ?>
            <div>
              <div class="bottom-btn">
                <button class="custom-btn" data-bs-toggle="modal" data-bs-target="#addMessageModal"
                  style="width:150px;">Répondre</button>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Inclusion des modales -->
        <?php require_once 'modalAddMessage.php'; ?>
        <?php require_once 'modelDeleteMessage.php'; ?>
        <?php if ($_SESSION['user']['role'] != 1): ?>
          <!--div class="assistance-button-centre">
            <a href="assistance.php" class="custom-button">Retour à la liste des tickets</a>
          </div-->
        <?php endif; ?>

        <script> // modalDelete.php
          // Variables pour modalDelete.php
          const modalDeleteController = "deleteMassageTicket";
          const modalDeleteMessage = "Voulez-vous supprimer ce message ?";
          const modalDeleteSuccessHeader = "assistance";
        </script>
     
      </div>

      <!-- Script showToast -->
      <script src="../../erp/assets/script/toast.js"></script>

      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </main>
    <footer>
      <?php
      include __DIR__ . '/../elements/footer.php';
      ?>
    </footer>
  </div>
</body>