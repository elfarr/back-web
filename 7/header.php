<?php 
if(isset($_SESSION['login'])) {
                echo '<div class="container"><a href="logout.php" class="btn btn-danger" type="reset"">Выйти</a>  </div>';
         
            } else {
                echo '<div class="container"><a href="login.php" class="btn btn-info">Войти</a>
                <a href="admin.php" class="btn btn-outline-info">Я администратор</a> </div>';
            }
  