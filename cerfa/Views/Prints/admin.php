<?php

use Projet\Model\DateParser;
use Projet\Model\FileHelper;
use Projet\Model\StringHelper;
?>

<html>
<head>
    <style>
       * {
            margin: 0;
            padding: 0;
        }

        body {
           

            'font: 13px/1.4 dejavusanscondensed;';


           
        }

        #page-wrap {
            width: 90%;
            margin: 0 auto;
        }

        table {
            border-collapse: collapse;
        }

        table td, table th {
            border: 1px solid black;
            padding: 4px;
        }

        #customer {
            overflow: hidden;
        }

        #logo {
            text-align: right;
            float: right;
            position: relative;
            border: 1px solid #fff;
            max-width: 540px;
            overflow: hidden;
        }

        #meta {
            margin-top: 1px;
            width: 30%;
        }

        #meta td {
            text-align: left;
        }

        #meta td.meta-head {
            text-align: right;
            background: #eee;
        }

        #meta td textarea {
            width: 100%;
            height: 20px;
            text-align: right;
        }

        #items {
            clear: both;
            width: 100%;
            margin: 10px 0 0 0;
            border: 1px solid black;
        }

        #items th {
            background: #eee;
        }

        #items textarea {
            width: 80px;
            height: 50px;
        }

        #items tr.item-row td {
            vertical-align: top;
        }

        #items td.description {
            width: 300px;
        }

        #items td.item-name {
            width: 175px;
        }

        #items td.description textarea, #items td.item-name textarea {
            width: 100%;
        }

        #items td.total-line {
            border-right: 0;
            text-align: right;
        }

        #items td.total-value {
            border-left: 0;
            padding: 2px;
        }

        #items td.total-value textarea {
            height: 20px;
            background: none;
        }

        #items td.balance {
            background: #eee;
        }

        #items td.blank {
            border: 0;
        }

        #terms {
            text-align: left;
            margin: 10px 0 0 0;
        }

        #terms h5 {
            text-transform: uppercase;
            font: 13px <?php echo $config['pdf_font']; ?>;
            letter-spacing: 10px;
            border-bottom: 1px solid black;
            padding: 0 0 8px 0;
            margin: 0 0 8px 0;
        }

        #terms textarea {
            width: 100%;
            text-align: center;
        }
        .footer{
            margin: 1px;
            font-size: 9px;
            text-align: center;
        }
        td.noBorder{
            border: 0 solid #ffffff;
        }
        @page {
            footer: footer;
        }

    </style>
</head>
<body style="font-family:dejavusanscondensed">
    <htmlpagefooter name="footer">
        <div class="" style="text-align: center; border-top: 2px dotted #ddd">
            <p class="footer">34 QUAI D'ALLIER VICHY(03200)</p>
            <p class="footer" style="margin-bottom: 10px">BP. : 03200 • Tél. : 04.63.88.41.34 • E-mail : jerome.creation@lgx-france.fr • Web : https://my-security-job.com/</p>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="footer" page="all" value="on" />
    <div id="page-wrap">
        <table width="100%">
            <tr>
                <td style="border: 0;" width="50%">
                    <b>Généré le <?= DateParser::DateShort(DATE_COURANTE,1); ?></b>
                </td>
                <td style="border: 0;text-align: right" width="50%">
                    <div id="logo">
                        <img id "image" src="<?= FileHelper::url('assets/img/lgxlogo.png') ?>" style="height: 100px" alt="logo"/>
                    </div>
                </td>
            </tr>
        </table>
        <div style="clear:both; margin-bottom: 0"></div>
        <table width="100%">
            <tr>
                <td style="border: 0;  text-align: left" width="100%" align="center">
                    <span style="font-size: 18px; color: #2f4f4f"><u><b>LISTE DES ADMINS (<?= count($admins); ?>)</b></u></span>
                </td>
            </tr>
        </table >
        
        <table width="100%">
            <tr>
                <th class="text-center">Nom </th>
                <th class="text-center">Email</th>
                <th class="text-center">Profil</th>
            </tr>
            <?php
            foreach ($admins as $admin) {
               
                echo
                    '<tr>
                        <th class="text-center">' . StringHelper::isEmpty($admin->nom) . '</td>
                        <th class="text-center">' . StringHelper::isEmpty($admin->email)  . '</td>
                        <th class="text-center">' . StringHelper::isEmpty($admin->libProfile) . '</td>
                    </tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>
