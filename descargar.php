<?php
// Verifica si el usuario ha proporcionado un archivo en la URL mediante el parámetro "file"
if (isset($_GET['file'])) {
    //  Obtiene el nombre del archivo y previene ataques de path traversal (.. para salir de directorios)
    $archivo = basename($_GET['file']); // basename evita rutas maliciosas

    //  Define la ruta completa del archivo en el servidor (NO en la PC del usuario)
    $ruta = __DIR__ . "/images/ticket/" . $archivo; 

    //  Verifica si el archivo realmente existe en el servidor antes de proceder
    if (file_exists($ruta)) {
        //  Configura los encabezados HTTP para forzar la descarga en el navegador
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $archivo . '"'); // Fuerza la descarga
        header('Expires: 0'); // Evita almacenamiento en caché
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($ruta)); // Tamaño del archivo

        // Limpia la salida del búfer para evitar datos corruptos
        ob_clean();
        flush();

        // Lee el archivo y lo envía al usuario
        readfile($ruta);

        // Finaliza la ejecución del script para evitar contenido adicional
        exit;
    } else {
        //  Si el archivo no existe, muestra un mensaje de error
        die('Error: El archivo no existe.');
    }
} else {
    //  Si no se proporciona un archivo en la URL, muestra un mensaje de error
    die('Error: No se especificó un archivo para descargar.');
}
?>
