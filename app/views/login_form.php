<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>RuangAdmin - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 100%);
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 450px;
      width: 100%;
    }

    .login-header {
      background: linear-gradient(135deg, rgb(109, 136, 199) 0%, rgb(109, 136, 199) 100%);
      color: white;
      padding: 50px 30px 30px;
      text-align: center;
      position: relative;
    }

    .login-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
      position: relative;
      z-index: 1;
    }

    .login-header p {
      margin: 10px 0 0;
      opacity: 0.9;
      font-size: 0.95rem;
      position: relative;
      z-index: 1;
    }

    .login-body {
      padding: 40px 30px;
    }

    .form-floating {
      margin-bottom: 20px;
    }

    .form-floating .form-control {
      border-radius: 12px;
      border: 2px solid #e9ecef;
      padding: 1rem 0.75rem;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .form-floating .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
    }

    .form-floating label {
      color: #6c757d;
      font-weight: 500;
    }

    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, rgb(109, 136, 199) 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      padding: 14px 0;
      font-size: 1rem;
      width: 100%;
      transition: all 0.3s ease;
      margin-top: 10px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
      color: white;
    }

    .form-check {
      margin: 20px 0;
    }

    .form-check-input:checked {
      background-color: rgb(84, 106, 204);
      border-color: #667eea;
    }

    .form-check-input:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
    }

    .form-check-label {
      font-size: 0.9rem;
      color: #6c757d;
    }

    .divider {
      position: relative;
      margin: 30px 0;
      text-align: center;
    }

    .divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: #e9ecef;
    }

    .divider span {
      background: white;
      padding: 0 20px;
      color: #6c757d;
      font-size: 0.9rem;
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
    }

    .register-link a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .register-link a:hover {
      color: #764ba2;
    }

    .forgot-password {
      text-align: center;
      margin-top: 15px;
    }

    .forgot-password a {
      color: #6c757d;
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.3s ease;
    }

    .forgot-password a:hover {
      color: #667eea;
    }

    @media (max-width: 576px) {
      .login-header {
        padding: 30px 20px 20px;
      }

      .login-header h1 {
        font-size: 1.5rem;
      }

      .login-body {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <!-- En-tête -->
      <div class="login-header">
        <h1><i class="fas fa-user-shield me-2"></i>Cyber</h1>
      </div>

      <!-- Corps du formulaire -->
      <div class="login-body">
        <form method="post" action="/login">
          <div class="form-floating">
            <input type="text" name="identifiant" class="form-control" id="identifiant" placeholder="Email ou nom d'utilisateur" required>
            <label for="identifiant"><i class="fas fa-envelope me-2"></i>Email ou nom d'utilisateur</label>
          </div>

          <div class="form-floating">
            <input type="password" name="password" class="form-control" id="password" placeholder="Mot de passe" required>
            <label for="password"><i class="fas fa-lock me-2"></i>Mot de passe</label>
          </div>

          <button type="submit" class="btn btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>