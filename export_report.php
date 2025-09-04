<?php
// export_report.php
session_start();
include "config/config.php"; // ajusta la ruta si es distinta

// Seguridad básica: si no hay sesión, corta.
if (empty($_SESSION['user_id'])) {
  http_response_code(401);
  echo "No autorizado";
  exit;
}

$user_id = (int)$_SESSION['user_id'];

// Obtiene rol desde BD (por si no está en sesión)
$qr = mysqli_query($con, "SELECT tipousuario FROM user WHERE id = $user_id");
$ud = mysqli_fetch_assoc($qr);
$user_type = $ud ? (int)$ud['tipousuario'] : 0; // 0 usuario, 1 admin, 2 agente (ajusta a tu modelo)

// Helpers de validación
$fecha_ok = function($d){
  return preg_match('/^\d{4}-\d{2}-\d{2}$/', $d);
};

// Construye condiciones
$conditions = [];

// Permisos por rol
if ($user_type === 2) {            // agente: solo asignados a él
  $conditions[] = "t.asigned_id = ".$user_id;
} elseif ($user_type === 0) {      // usuario: solo los que creó
  $conditions[] = "t.user_id = ".$user_id;
} // admin (1): ve todo

// Filtros GET (castea ints)
if (!empty($_GET["status_id"]))   $conditions[] = "t.status_id = ".(int)$_GET["status_id"];
if (!empty($_GET["kind_id"]))     $conditions[] = "t.kind_id = ".(int)$_GET["kind_id"];
if (!empty($_GET["project_id"]))  $conditions[] = "t.project_id = ".(int)$_GET["project_id"];
if (!empty($_GET["priority_id"])) $conditions[] = "t.priority_id = ".(int)$_GET["priority_id"];

// Fechas (usa la columna correcta; aquí supongo t.created_at)
if (!empty($_GET["start_at"]) && $fecha_ok($_GET["start_at"])) {
  $conditions[] = "t.created_at >= '".$_GET["start_at"]." 00:00:00'";
}
if (!empty($_GET["finish_at"]) && $fecha_ok($_GET["finish_at"])) {
  $conditions[] = "t.created_at <= '".$_GET["finish_at"]." 23:59:59'";
}

// La q es un parametro para pasar al php los valores del dataTable para poder aplicar los filtros aplicados en la exportación a excel
if (!empty($_GET['q'])) {
  $q = mysqli_real_escape_string($con, $_GET['q']);
  // Ajusta columnas a las que quieras buscar
  $conditions[] = "(t.id LIKE '%$q%' 
                    OR t.title LIKE '%$q%' 
                    OR p.name LIKE '%$q%' 
                    OR c.name LIKE '%$q%' 
                    OR pr.name LIKE '%$q%' 
                    OR s.name LIKE '%$q%')";
}

$where = count($conditions) ? ("WHERE ".implode(" AND ", $conditions)) : "";



// Query con JOINs (trae nombres ya resueltos)
$sql = "
  SELECT
    t.id,
    t.title,
    p.name  AS project,
    k.name  AS kind,
    c.name  AS category,
    pr.name AS priority,
    s.name  AS status,
    t.created_at,
    t.updated_at
  FROM ticket t
  LEFT JOIN project  p  ON p.id  = t.project_id
  LEFT JOIN kind     k  ON k.id  = t.kind_id
  LEFT JOIN category c  ON c.id  = t.category_id
  LEFT JOIN priority pr ON pr.id = t.priority_id
  LEFT JOIN status   s  ON s.id  = t.status_id
  $where
  ORDER BY t.created_at desc
";

$res = mysqli_query($con, $sql);

// Prepara cabeceras para descarga CSV
$filename = "tickets_".date("Ymd_His").".csv";
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// BOM para que Excel reconozca UTF-8
echo "\xEF\xBB\xBF";

// Abre “output” como archivo de escritura
$out = fopen("php://output", "w");

// Cabecera del archivo
$headers = ["Folio","Asunto","Proyecto","Tipo","Categoria","Prioridad","Estado","Fecha creación","Última actualización"];
fputcsv($out, $headers);

// Filas
if ($res && mysqli_num_rows($res) > 0) {
  while ($row = mysqli_fetch_assoc($res)) {
    // Arma la fila; usa el mismo orden que cabeceras
    $line = [
      $row['id'],
      $row['title'],
      $row['project'],
      $row['kind'],
      $row['category'],
      $row['priority'],
      $row['status'],
      $row['created_at'],
      $row['updated_at'],
    ];
    // Si tu Excel usa separador decimal con coma, conviene CSV con ;:
    // fputcsv($out, $line, ';');
    fputcsv($out, $line); // por defecto coma ,
  }
} else {
  // sin datos
  fputcsv($out, ["Sin registros"]);
}

fclose($out);
exit;
