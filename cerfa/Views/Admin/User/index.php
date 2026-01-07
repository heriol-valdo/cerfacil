<?php


use Projet\Database\Profil;
use Projet\Model\App;
use Projet\Model\Privilege;

App::setTitle("Tableau de bord");
App::setNavigation("Tableau de bord");
App::setBreadcumb("");
App::addScript('assets/plugins/flot/jquery.flot.min.js',true);
App::addScript('assets/plugins/flot/jquery.flot.tooltip.min.js',true);

App::addStyle('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',true);
App::addScript('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',true);
App::addScript('https://cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js',true);
App::addScript('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',true);
App::addStyle('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css',true);

$cerfas = 0;
$nbreconventions = 0;
$nbrefactures = 0;
if(!empty($nbrecerfas)){
    foreach($nbrecerfas as $nbrecerfa){
        if(!empty($nbrecerfa->conventionOpco)){
            $nbreconventions += 1;
        }
    
        if(!empty($nbrecerfa->numeroInterne)){
            $cerfas +=1;
        }
    
        if(!empty($nbrecerfa->factureOpco)){
            $nbrefactures += 1;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
        .col-lg-3, .col-md-3 {
            margin-top: 20px;
            padding-right: 5px !important;
            padding-left: 5px !important;
           
        }

        .col-lg-6, .col-md-6 {
            margin-top: 55px;
            padding-right: 5px !important;
            padding-left: 5px !important;
        }

        .panel {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .panel-body {
            height: 125px;
            margin-top: 20px;
            border-radius: 15px;
        }

        .info-box-title a {
            text-decoration: none;
        }

        .info-box-icon {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .chart-container {
            background-color: #fff;
            height:auto;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chart-container-circular {
            background-color: #fff;
            height:600px;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* .chart-container,
        .chart-container-circular {
            background-color: #fff;
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%; 
        } */
    </style>
</head>
<body>

    <div class="row"> 
        <div class="col-lg-8 col-md-8">
            <div class="chart-container">
                <form action="<?= App::url('searchhome') ?>" id="newForm" method="post">
                    <div class="row"> 
                        <div class="col-md-3 form-group">
                            <label for="Facture">Du:<b>* </b> </label>
                            <input type="date" class="form-control" id="date_debut"  name="date_debut"  required style="border-radius: 5px;">
                        </div>
                       


                        <div class="col-md-3 form-group">
                            <label for="Facture">Au:<b>* </b> </label>
                            <input type="date" class="form-control" id="date_fin"  name="date_fin"  required style="border-radius: 5px;">
                        </div>   

                        <div class="col-md-3 form-group">
                            <button type="submit" class="btn btn-warning" style="border-radius: 5px;margin-top:23px;">Chercher</button>
                        </div>

                    </div>
                </form>
                <canvas id="myChart"></canvas>
               
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <div class="panel info-box panel-dark">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p></p>
                        <span class="info-box-title">
                            Cerfas (
                            <a href="<?= App::url('cerfas') ?>">
                                <small><?= empty($nbrecerfas)? "0" : thousand(count($nbrecerfas)); ?></small><i class="text-xs"> total</i>
                            </a>
                            )
                        </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="icon-doc" style="color: #F93100;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <div class="panel info-box panel-dark">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p></p>
                        <span class="info-box-title">
                            Conventions (
                            <a href="<?= App::url('cerfas') ?>">
                                <small><?= thousand($nbreconventions); ?></small><i class="text-xs"> total</i>
                            </a>
                            )
                        </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="icon-doc" style="color: #F93100;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3">
            <div class="panel info-box panel-dark">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p></p>
                        <span class="info-box-title">
                            Factures (
                            <a href="<?= App::url('cerfas') ?>">
                                <small><?= thousand($nbrefactures); ?></small><i class="text-xs"> total</i>
                            </a>
                            )
                        </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="fa fa-vial" style="color: #F93100;"></i>
                    </div>
                </div>
            </div>
        </div>
       

       
    </div>
    <div class="row">
      

        <!-- <div class="col-lg-3 col-md-3">
            <div class="panel info-box panel-dark">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p></p>
                        <span class="info-box-title">
                            Opco (
                            <a href="<?= App::url('opco') ?>">
                                <small><?= thousand($nbreopco); ?></small><i class="text-xs"> total</i>
                            </a>
                            )
                        </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="info-box-icon" style="color: #F93100;"></i>
                    </div>
                </div>
            </div>
        </div> -->

        <?php if(!$user->data->role == 'Gestionnaire de centre'){ ?>
                       
            <div class="col-lg-3 col-md-3">
            <div class="panel info-box panel-dark">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p></p>
                        <span class="info-box-title">
                            Administrateurs (
                            <a href="<?= App::url('admins') ?>">
                                <small><?= thousand($nbreadmins); ?></small><i class="text-xs"> total</i>
                            </a>
                            )
                        </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="icon-users" style="color: #F93100;"></i>
                    </div>
                </div>
            </div>
        </div>   
                    
                      
                      
        <?php } ?>

        

      


        <!-- <div class="col-lg-3 col-md-3">
            <div class="panel info-box panel-dark">
                <div class="panel-body">
                    <div class="info-box-stats">
                        <p></p>
                        <span class="info-box-title">
                            Produits (
                            <a href="<?= App::url('produits') ?>">
                                <small><?= thousand($nbreproduits); ?></small><i class="text-xs"> total</i>
                            </a>
                            )
                        </span>
                    </div>
                    <div class="info-box-icon">
                        <i class="fa fa-key" style="color: #F93100;"></i>
                    </div>
                </div>
            </div>
        </div> -->
    </div>

   

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Cerfas', 'Conventions', 'Factures'],
                    datasets: [{
                        label: 'Total Count',
                        data: [
                            <?= thousand($cerfas); ?>,
                            <?= thousand($cerfas); ?>,
                            <?= thousand($nbrefactures); ?>,
                           
                          
                           
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            
    $(document).on('submit', '#newForm', function (e) {
        e.preventDefault();
        var $form = $(this),
            url = $(this).attr('action'),
            date_debut = $('#date_debut').val(),
            date_fin = $('#date_fin').val(),
           
            act = $('.btn').html();
        if (date_debut != ''  && date_fin != ''  && url != '') {
            if(date_debut > date_fin){
                toastr.error('La date de début ne peut pas être supérieure à la date de fins', 'Oups!');
                return;
            }
            $.ajax({
                type: 'post',
                url: url,
                data: 'date_debut='+date_debut+'&date_fin='+date_fin,
                datatype: 'json',
                beforeSend: function () {
                    $('.btn').html('<i class="fa fa-refresh fa-spin fa-2x"></i>').prop('disabled', true);
                },
                success: function (json) {
                    if (json.statuts === 0) {
                        console.log( json.nbrecerfas);
                        if (myChart) {
                            myChart.destroy();
                        }
                        myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Cerfas', 'Conventions', 'Factures'],
                                        datasets: [{
                                            label: 'Total Count',
                                            data: [
                                                json.nbrecerfas,
                                                json.nbrecerfas,
                                                json.nbrefactures,
                                            ],
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.2)',
                                                'rgba(54, 162, 235, 0.2)',
                                                'rgba(255, 206, 86, 0.2)',
                                                'rgba(75, 192, 192, 0.2)'
                                            ],
                                            borderColor: [
                                                'rgba(255, 99, 132, 1)',
                                                'rgba(54, 162, 235, 1)',
                                                'rgba(255, 206, 86, 1)',
                                                'rgba(75, 192, 192, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });

                        
                        
                    } else {
                        toastr.error(json.mes,'Oups!');
                    }
                },
                complete: function () {
                    $('.btn').html('chercher').prop('disabled', false);
                },
                error: function (jqXHR, textStatus, errorThrown) {}
            });
        } else {
            toastr.error('Veuillez remplir correctement tous les champs requis','Oups!');
            showAlert($form,2,'Veuillez remplir correctement tous les champs requis');
        }
    });
          



         
        });
    </script>
</body>
</html>
