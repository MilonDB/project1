<?php

class fieldVal
{

    public function filled_fields($fields)
    {
        if (is_array($fields)) {

            $error = False;

            // Loopt over de field heen om te kijken of velden ingevuld zijn. Zo nee, wordt $error true.
            foreach ($fields as $fieldname) {
                if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
                    echo "Error gevonden, velden zijn niet correct ingevuld!";
                    $error = True;
                }
            }

            if (!$error) {
                return true;
            }
            return false;
        } else {
            echo "Geen array gevonden.";
        }
    }


}
