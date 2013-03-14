<?php

//argv[1] = file to read
//argv[2] = insert statement ie 'INSERT INTO cars (year, make, model) VALUES

$directory = $argv[1];
$statement = 'INSERT INTO cars (year, make, model) VALUES';
$inserts = array();
$years = array();

if (!is_dir($directory)) {
    echo "Please provide a valid directory!";
    exit;
}

if (count($argv) != 2) {
    echo "Incorrect command structure.  Please check the documentation for the correct procedure!";
    exit;
}

if (is_dir($directory)) {
    if ($dh = opendir($directory)) {
        while (($dir = readdir($dh)) !== false) {
            if (($dir === '.') || ($dir === '..') || ($dir === '.DS_Store')) {

            } else {
                $years[] = $dir;
            }
        }
        closedir($dh);

        for ($i = 0; $i < count($years); $i++) {
            $files = array();
            if (is_dir($directory . $years[$i])) {
               if ($dh2 = opendir($directory . $years[$i])) {
                   while (($dir = readdir($dh2)) !== false) {
                       if (($dir === '.') || ($dir === '..') || ($dir === '.DS_Store')) {

                       } else {
                           $files[] = $dir;
                       }
                   }
                   closedir($dh2);
               }
            }

            //read each file here
            for ($j = 0; $j < count($files); $j++) {
                $filename = $directory . $years[$i] . '/' . $files[$j];
                $models = array();

                if ((file_exists($filename)) && (is_readable($filename))) {
                    $models = explode("\n", file_get_contents($filename));
                }

                foreach ($models as $model) {
                    $inserts[] = '(\'' . $years[$i] . '\', \'' . $files[$j] . '\', \'' . $model . '\'),';
                }
            }
        }

        $inserts[count($inserts) - 1] = substr($inserts[count($inserts) - 1], 0, -1);
        $inserts[count($inserts) - 1] .= ';';

        if (($fw = fopen('output.sql', 'w')) !== false) {
            fwrite($fw, $statement . "\n");

            for ($i = 0; $i < count($inserts); $i++) {
                fwrite($fw, $inserts[$i]);

                if ($i < count($inserts) - 1) {
                    fwrite($fw, "\n");
                }
            }

            fclose($fw);
            echo 'output.sql was written to!' . "\n";
        }
    }
} else {
    echo "Pleas input a valid directory to compile!";
    exit;
}