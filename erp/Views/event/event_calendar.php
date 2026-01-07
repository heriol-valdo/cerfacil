<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . "/../../controller/User/validTokenController.php";
require_once __DIR__ . '/../../controller/Event/getEventController.php';
require_once __DIR__ . '/../../controller/Sessions/getMenuSessionsController.php';
require_once __DIR__ . '/../../controller/Salle/getMenuSalles.php';
require_once __DIR__ . '/../../controller/Salle/listSallesByCentreController.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset='utf-8' />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
  <script src='../../controller/Event/dist/index.global.min.js'></script>
  <link rel="stylesheet" href="../event/assets/css/modal_event_add.css" />
  <!-- FullCalendar CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.bootstrap.min.css"
    rel="stylesheet">
  <link rel="stylesheet" href="../event/assets/css/event_calendar.css">

  <!-- FullCalendar JS -->
  <script src=" https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js "></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale-all.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.bootstrap.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

  <link rel="stylesheet" href="../../assets/style/modals.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <script>const calendarInfos = <?= is_array($formattedInfos) ? json_encode($formattedInfos) : null; ?>
  </script>
</head>

<?php if (!is_array($formattedInfos)): ?>
  <?php $formattedInfos = null; ?>
  <script>const calendarInfos = null;</script>
<?php endif; ?>
<div id='calendar'></div>

<?php require_once __DIR__ . "/php/modal_event_details.php"; ?>
<?php require_once __DIR__ . "/php/modal_event_add.php"; ?>
<?php require_once __DIR__ . "/php/modal_event_delete.php"; ?>
<?php require_once __DIR__ . "/php/modal_event_update.php"; ?>
<?php require_once __DIR__ . "/php/modal_cours_add.php"; ?>

<script src="../../assets/script/toast.js"></script>
<script>
  var selectedUser = "<?php echo isset($selectedUser) ? $selectedUser : ''; ?>";
  var selectedSession = "<?php echo isset($selectedSession) ? $selectedSession : ''; ?>";
  var centreId =  "<?php echo isset($_GET['centreId']) ? $_GET['centreId'] : ''; ?>";
  const userRole = <?php echo json_encode($_SESSION['user']['role']); ?>;
</script>
<script src="../event/js/event_calendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>