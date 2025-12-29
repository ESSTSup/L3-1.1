<?php 
session_start(); 
$type = $_SESSION['login_type'] ?? null;

if (!$type) {
    header("Location: Login.php");
    exit; 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_user_id'])) {
    $_SESSION['selected_user_id'] = (int) $_POST['selected_user_id'];
    http_response_code(200);
    exit;
}

if (!isset($_SESSION['login_type'])) {
    header("Location: Login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - Clinique</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f6f6f6;
      margin: 0;
      padding: 0;
    }
    main {
      padding: 30px;
      text-align: center;
    }
    h2 {
      color: #A6615A;
      margin-top: 40px;
    }
    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }
    .card {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 180px;
      padding: 15px;
      transition: transform 0.2s, box-shadow 0.2s;
      cursor: pointer;
      text-align: center;
    }
    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }
    .card img {
      width: 100%;
      border-radius: 50%;
    }
    .card p {
      margin-top: 10px;
      font-weight: bold;
      color: #333;
    }
  </style>
</head>

<body>

<header>
  <h1>Équipe Médicale</h1>
</header>

<main class="profiles">

<?php 
if ($type === 'doctor' || $type === 'admin'): ?>
<section class="doctors">
  <h2>Médecins</h2>
  <div class="grid">

    <div class="card" onclick="selectUser(2)">
      <img src="https://randomuser.me/api/portraits/women/45.jpg">
      <p>Dre MOUFOUKI</p>
    </div>

    <div class="card" onclick="selectUser(3)">
      <img src="https://randomuser.me/api/portraits/women/40.jpg">
      <p>Dre DJEDJIG</p>
    </div>

    <div class="card" onclick="selectUser(4)">
      <img src="https://randomuser.me/api/portraits/women/42.jpg">
      <p>Dre HELLAL</p>
    </div>

    <div class="card" onclick="selectUser(5)">
      <img src="https://randomuser.me/api/portraits/women/41.jpg">
      <p>Dre MEKLATI</p>
    </div>

    <div class="card" onclick="selectUser(6)">
      <img src="https://randomuser.me/api/portraits/women/43.jpg">
      <p>Dre LACHI</p>
    </div>

    <div class="card" onclick="selectUser(7)">
      <img src="https://randomuser.me/api/portraits/men/60.jpg">
      <p>Dr MAJED</p>
    </div>

  </div>
</section>
<?php endif; ?>

<?php if ($type === 'assistant'): ?>
<section class="assistants">
  <h2>Assistants</h2>
  <div class="grid">

    <div class="card" onclick="selectUser(101)">
      <img src="https://randomuser.me/api/portraits/men/30.jpg">
      <p>Assis BERRAHMEN</p>
    </div>

    <div class="card" onclick="selectUser(102)">
      <img src="https://randomuser.me/api/portraits/men/31.jpg">
      <p>Assis Slimani</p>
    </div>

  </div>
</section>
<?php endif; ?>

</main>

</body>
</html>
