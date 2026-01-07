<?php
function renderSelect($name, $options, $defaultText = 'Sélectionnez une option', $selectedValue = null) {
    $html = <<<HTML
    <div class="select-wrapper">
        <select name="{$name}" id="{$name}">
            <option value="" disabled selected>{$defaultText}</option>
HTML;

    foreach ($options as $value => $text) {
        $selected = ($selectedValue !== null && $selectedValue == $value) ? 'selected' : '';
        $html .= "<option value=\"{$value}\" {$selected}>{$text}</option>";
    }

    $html .= <<<HTML
        </select>
    </div>
HTML;

    return $html;
}

// Styles CSS
$styles = <<<CSS
<style>
    .select-wrapper {
        position: relative;
        width: 250px;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        width: 100%;
        padding: 12px 40px 12px 20px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: white;
        color: #333;
        cursor: pointer;
        outline: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .select-wrapper select:hover,
    .select-wrapper select:focus {
        border-color: #4a90e2;
        box-shadow: 0 2px 8px rgba(74, 144, 226, 0.2);
    }

    .select-wrapper::after {
        content: '\2BC6';
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        color: #4a90e2;
        pointer-events: none;
        transition: all 0.3s ease;
        font-size: 12px;
    }

    .select-wrapper:hover::after {
        color: #2c3e50;
    }

    .select-wrapper select option {
        background-color: white;
        color: #333;
        padding: 10px;
    }
</style>
CSS;

// Afficher les styles CSS
echo $styles;

// Exemple d'utilisation :
// $options = [
//     '1' => 'Option 1',
//     '2' => 'Option 2',
//     '3' => 'Option 3',
//     '4' => 'Option 4'
// ];
// echo renderSelect('exemple-select', $options, 'Choisissez une option', '2');
?>