<?php

include 'db_config.php';

// Récupérer les paramètres de mois et d'année depuis la requête GET
$month = isset($_GET['month']) ? intval($_GET['month']) : date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Connexion à la base de données et récupération des rendez-vous
try {
    $db = getPDOConnection();

    // Calculer la date de début et de fin du mois pour la requête
    $start_date = "$year-$month-01";
    $end_date = date("Y-m-t", strtotime($start_date)); // Dernier jour du mois

    // Requête SQL pour récupérer les rendez-vous du mois spécifié
    $stmt = $db->prepare("SELECT * FROM appointments WHERE appointment_date BETWEEN :start_date AND :end_date");
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->execute();

    // Récupérer les résultats et les organiser par date
    $appointments = [];
    while ($row = $stmt->fetch()) {
        $date = $row['appointment_date'];
        $appointments[$date][] = [
            'name' => $row['patient_name'],
            'time' => $row['appointment_time'],
            'type' => $row['appointment_type'] // Morning or Afternoon
        ];
    }
} catch (Exception $e) {
    $appointments = ['error' => 'Failed to retrieve appointments: ' . $e->getMessage()];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Schedule Calendar</title>
  <link rel="stylesheet" href="calender.css">
</head>
<body>
  <div class="container">
    <h2>Confirmed Appointments Calendar</h2>

    <div class="calendar-navigation">
      <a href="#" class="nav-button" id="prevMonth">&larr; October</a>
      <div class="calendar-title" id="currentMonth">November 2025</div>
      <a href="#" class="nav-button" id="nextMonth">December &rarr;</a>
    </div>

    <div class="legend">
      <div class="legend-item">
        <div class="legend-color" style="background-color: #17a2b8;"></div>
        <span>Morning (9AM–12PM)</span>
      </div>
      <div class="legend-item">
        <div class="legend-color" style="background-color: #ffc107;"></div>
        <span>Afternoon (2PM–5PM)</span>
      </div>
    </div>

    <div class="calendar" id="calendarGrid">
      <?php
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        foreach ($days as $day) {
          echo "<div class='calendar-header'>$day</div>";
        }

        $firstDay = new DateTime("$year-$month-01");
        $lastDay = new DateTime("$year-$month-" . date("t", strtotime("$year-$month-01")));
        
        // Ajouter des espaces vides avant le premier jour du mois
        for ($i = 0; $i < $firstDay->format('w'); $i++) {
          echo "<div class='calendar-day'></div>";
        }

        // Afficher les jours du mois
        for ($day = 1; $day <= $lastDay->format('d'); $day++) {
          $dateString = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);
          $appointmentsForDay = isset($appointments[$dateString]) ? $appointments[$dateString] : [];
          $isToday = (new DateTime())->format('Y-m-d') === $dateString;
          
          echo "<div class='calendar-day" . ($isToday ? " today" : "") . "'>";
          echo "<div class='day-number'>$day</div>";

          if ($appointmentsForDay) {
            foreach ($appointmentsForDay as $appointment) {
              echo "<div class='appointment {$appointment['type']}-slot'>";
              echo "<strong>{$appointment['name']}</strong>";
              echo "<div class='timeslot'>{$appointment['time']}</div>";
              echo "</div>";
            }
          } else {
            echo "<div class='no-appointments'>No confirmed appointments</div>";
          }

          echo "</div>";
        }
      ?>
    </div>

    <a href="Dash.html" class="back-button">← Back to Dashboard</a>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const currentDate = new Date(2025, 10, 1); // Initialiser à Novembre 2025
      const currentMonthElement = document.getElementById('currentMonth');
      const prevMonthButton = document.getElementById('prevMonth');
      const nextMonthButton = document.getElementById('nextMonth');

      const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

      prevMonthButton.addEventListener('click', function(e) {
        e.preventDefault();
        currentDate.setMonth(currentDate.getMonth() - 1);
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        window.location.href = `consultation.php?month=${month}&year=${year}`;
      });

      nextMonthButton.addEventListener('click', function(e) {
        e.preventDefault();
        currentDate.setMonth(currentDate.getMonth() + 1);
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        window.location.href = `consultation.php?month=${month}&year=${year}`;
      });

      // Mettre à jour le titre du mois affiché
      const month = currentDate.getMonth();
      const year = currentDate.getFullYear();
      currentMonthElement.textContent = `${monthNames[month]} ${year}`;
    });
  </script>
</body>
</html>


