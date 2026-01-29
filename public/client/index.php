<?php
  session_start();

  // Проверка авторизации
  if (!isset($_SESSION['user_id'])) {
      // Перенаправление на страницу входа
      header('Location: login.php');
      exit();
  }

  include_once 'header.php';
?>
  <div class="tasks__container"></div>

<?php
  include_once 'footer.php';
?>