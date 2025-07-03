<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SignIn</title>
  <link rel="stylesheet" type="text/css" href="/assets/css/login.css" />
  <!-- <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script> -->
</head>
<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <!-- Formulaire de connexion modifié -->
        <form class="user" method="post" action="/login">
          <h2 class="title">Connexion</h2>
          <div class="form-group input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="identifiant" class="form-control" placeholder="Email ou nom d'utilisateur" />
          </div>
          <div class="form-group input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" />
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
              <input type="checkbox" class="custom-control-input" id="customCheck">
              <label class="custom-control-label" for="customCheck">Se souvenir de moi</label>
            </div>
          </div>
          <div class="form-group">
            <input type="submit" value="Soumettre" class="btn solid" />
          </div>
        </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Connectez-vous</h3>
          <p>En tant qu'administrateur ou employé</p>
        </div>
        <img src="/assets/img/log.svg" class="image" alt="Image" />
      </div>
    </div>
  </div>

  <script src="/assets/js/app.js"></script>
</body>
</html>
