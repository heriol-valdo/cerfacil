<?php



use Projet\Model\App;
use Projet\Model\Paginator;
use Projet\Model\StringHelper;
use Projet\Model\FileHelper;
use Projet\Model\Session;


$url = substr(explode('?', $_SERVER["REQUEST_URI"])[0], 1);

$ticketDetails = $resultTicket['ticketDetails'];
$dateCreation = $resultTicket['dateCreation'];
$ticketExchangeDetails = $resultTicket['ticketExchangeDetails'];



App::setTitle("Detail assistance");
App::setNavigation("Detail assistance");
App::setBreadcumb("<li class='active'>Detail assistance</li>");
App::addScript('assets/js/assistance.js',true);
App::addScript("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css")
?>
<style>
.tickets-ex {
    max-width: 800px;
    margin: 40px auto;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #667eea;
    position: relative;
}

.tickets-ask {
    text-align: center;
    padding: 20px;
}

.tickets-ask h1 {
    color: #2d3748;
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 25px;
    position: relative;
    display: inline-block;
}

.tickets-ask h1:after {
    content: "";
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: #667eea;
    border-radius: 3px;
}

/* Animation de chargement */
.tickets-ask h1:before {
    content: "⏳";
    display: inline-block;
    margin-right: 10px;
    animation: pulse 1.5s infinite;
}

/* Bouton Retour */
.back-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin: 30px auto 0;
    padding: 10px 20px;
    background-color: #f1f5f9;
    color: #334155;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.back-button:hover {
    background-color: #e2e8f0;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.back-button:active {
    transform: translateY(0);
    box-shadow: 0 2px 3px rgba(0,0,0,0.1);
}

.back-button svg {
    transition: transform 0.3s ease;
}

.back-button:hover svg {
    transform: translateX(-3px);
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

/* Responsive */
@media (max-width: 768px) {
    .tickets-ex {
        margin: 20px;
        padding: 20px;
    }
    
    .tickets-ask h1 {
        font-size: 1.4rem;
    }
    
    .back-button {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}


/* Conteneur principal */
.ticket-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin: 30px auto;
    padding: 20px;
}

/* Partie informations du ticket */
.ticket-asking {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    height: fit-content;
}

/* Partie messagerie */
.ticket-answer {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    height: 500px;
}

/* Styles communs */
.ticket-section-title {
    font-weight: 800;
    color: #4a5568;
    margin-bottom: 8px;
    font-size: 2rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ticket-asking-text {
    margin-bottom: 20px;
    color: #2d3748;
    font-size: 1.5rem;
    line-height: 1.5;
}

.ticket-asking-text-description {
    margin-bottom: 20px;
    color: #2d3748;
    font-size: 1.5rem;
    line-height: 1.6;
    white-space: pre-wrap;
}

/* Zone des messages */
.ticket-answers-container {
    flex-grow: 1;
    overflow-y: auto;
    padding-right: 10px;
    margin-bottom: 20px;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f1f5f9;
}

.ticket-answers-container::-webkit-scrollbar {
    width: 6px;
}

.ticket-answers-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.ticket-answers-container::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 10px;
}

/* Style des messages individuels */
.ticket-answer-text {
    background: #f8fafc;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 3px solid #667eea;
}

.ticket-answer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.ticket-answer-author {
    font-weight: 600;
    color: #2d3748;
}

.ticket-answer-date {
    color: #718096;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.ticket-answer-content {
    color: #4a5568;
    line-height: 1.6;
    white-space: pre-wrap;
    font-size: 1.5rem;
    
}

/* Boutons */
.bottom-btn {
    display: flex;
    justify-content: flex-end;
    margin-top: 15px;
}

.custom-btn {
    background-color: #667eea;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.custom-btn:hover {
    background-color: #5a67d8;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.chat-btn {
    background: none;
    border: none;
    color: #e53e3e;
    cursor: pointer;
    padding: 5px;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}

.chat-btn:hover {
    color: #c53030;
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 992px) {
    .ticket-container {
        grid-template-columns: 1fr;
    }
    
    .ticket-answer {
        height: auto;
        max-height: 60vh;
    }
}

@media (max-width: 576px) {
    .ticket-asking, .ticket-answer {
        padding: 15px;
    }
    
    .ticket-answer-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .ticket-answer-date {
        margin-top: 5px;
    }
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-dark" style="border-radius: 10px;border: 5px solid #fff;">
            <div class="panel-heading">
                <h5 class="panel-title">
                Detail assistance 
                </h5>
                <div class="panel-control">
                        <!-- <a href="javascript:void(0);" data-toggle="tooltip" id="add" data-original-title="Nouveau message">
                        <i class="icon-plus text-success fa-2x" style="display: none;"></i>
                        </a> -->
                    <!-- <a href="javascript:void(0);" data-toggle="tooltip" class="panel-collapse" data-original-title="Reduire/Agrandir">
                        <i class="icon-arrow-down fa-2x"></i>
                    </a> -->
                   
                </div>
            </div>
            <div class="panel-body">
                
                     <div class="row m-t-sm" style="min-height: 470px;">
                        <div class="col-md-12">
                            <div class="table-responsive project-stats">
                                  <?php    if($bool):  ?>
                                    <div class="ticket-exchange-details">
                                        <div class="ticket-container">
                                            <div class="ticket-asking">
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
                                                    <div class="ticket-asking-text"><?= StringHelper::$tabetatticket[$ticketDetails->data->etat] ?></div>
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
                                                            <?php
                                                            $dateMessage = new DateTime($message->dateCreation);
                                                            $dateMessage = $dateMessage->format('d-m-Y H:i:s'); 
                                                            ?>
                                                            <div class="ticket-answer-text">
                                                                <div class="ticket-answer-header">
                                                                   <div class="ticket-answer-author">
                                                                        <div><?= ($message->id_users == $user->data->id_users) ? "Moi" : htmlspecialchars($message->firstname)." ".htmlspecialchars(strtoupper($message->lastname)) ?></div>
                                                                    </div>
                                                                    <div class="ticket-answer-date">
                                                                        <?php if ((($message->id_users == $user->data->id_users) && ($ticketDetails->data->etat == 'En cours de traitement')) || $user->data->role == 1): ?>
                                                                            <button class="chat-btn trash" data-id="<?= $message->id; ?>" onclick="confirmDeleteElement(<?= $message->id_users; ?>)">
                                                                                <!-- Icône poubelle classique -->
                                                                                <i class="fa fa-trash text-danger fa-2x"></i>
                                                                            </button>
                                                                        <?php endif; ?>
                                                                        <?= $dateMessage ?>
                                                                    </div>
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
                                                
                                                <?php if($user->data->role == 1 || $ticketDetails->data->etat == 'Envoyé' || $ticketDetails->data->etat == 'En cours de traitement' ): ?>
                                                    <div class="bottom-btn">
                                                        <button class="custom-btn" data-bs-toggle="modal" data-bs-target="#addMessageModal" style="width:150px;" id="adds">
                                                            Répondre
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                  <?php    else:  ?>  
                                  <div class="tickets-ex">
                                        <div class="tickets-ask">
                                            <h1>Ticket en cours de traitement veuillez patienter</h1>
                                        </div>
                                        <button class="back-button" onclick="history.back()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                                            </svg>
                                            Retour
                                        </button>
                                    </div>

                                  <?php    endif;  ?> 
                            </div>
                        </div>
                     </div>
            </div>
        </div>
    </div>
   
</div>


<div class="modal fade" id="news" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"style="border-radius: 10px;border: 5px  #fff;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="modal-title" id="intros">Enregistrer une formation</h2>
            </div>
            <form action="<?= App::url('assistance/saveMessage') ?>" id="newFroms" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="idElements" name="idElements" value="<?=$id?>">
                    <input type="hidden" id="actions" name="actions">
                    <p class="mainColor text-right">* Champs obligatoires</p>
                    
                        
                    <div class="row">
                    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label"> Message  <b>*</b></label>
                                <textarea id="messages" rows="4" name="messages" class="form-control" required style="border-radius: 5px;"></textarea>
                            </div>
                        </div>
                   
                     </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="confirms" class="newBtn btn btn-default" style="border-radius: 5px;">AJOUTER</button>
                    <button type="button" class="btn btn-warning" data-dismiss="modal" style="border-radius: 5px;">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>


