<?php

/*

This file include all of files which are in these folder which are listed in the array $paths

*/

// pay attention of order of this list. consider that this is the order of importation
$paths = ['util', 'config', 'controller', 'model', 'serializer'];


foreach ($paths as $path) {
    // this code variable iclude all file which respect the nomenclature of files per folder
    /*
        for exemple, in the folder model/ all file must follow this namenclature file.model.php

        make sûr all your include model, for example, follow that nomenclature to be consider by the app.
    */
    $files = glob(__DIR__ . '/' . $path . 's' . '/*.' . $path . '.php');
    foreach ($files as $file) {
        include_once $file;
    }
}
