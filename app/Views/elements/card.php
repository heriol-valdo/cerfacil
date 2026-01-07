<?php
// Fonction pour générer un badge
function generateBadge($text) {
    return "<span class='centre-details-badge'>$text</span>";
}

// Fonction pour générer une carte
function generateCard($icon, $title,$href, $footerText) {
    $html = "<div class='centre-details-card'>";
    $html .= "<h3 class='centre-details-card-header'><i class='$icon' style='font-size:2em'></i></h3>";
    $html .= "<div class='centre-details-card-content'><h3>$title</h3></div>";
    $html .= "<div class='centre-details-card-footer'>";
    $html .= "<a href=".$href." class='card-button'>$footerText</a>";
    $html .= "</div>";
    $html .= "</div>";
    return $html;
}

// Fonction pour générer les styles CSS
function generateStyles() {
    return "
    <style>
        .centre-details-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1rem;
            margin-right: 1rem;
            position: relative;
            width: 45%;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .centre-details-card-header {
            padding: 1rem;
          
        }

        .centre-details-card-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .centre-details-card-content {
            padding: 0 1rem;
        }

        .centre-details-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
        }

        .centre-details-badge {
            background-color: #e5e7eb;
            color: #4b5563;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .centre-details-card-footer {
          text-align: right;
          padding: 1rem;
        }

        .centre-details-button {
            background-color: #273B4A;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .centre-details-button:hover {
            opacity: 0.8;
        }
    </style>
    ";
}

echo generateStyles();

