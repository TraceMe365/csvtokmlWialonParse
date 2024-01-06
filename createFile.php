<?php 
    $fileName = 'geofences.kml';
    $content = $_POST['data'];
    file_put_contents($fileName, $content);
    echo "Success";    
?>