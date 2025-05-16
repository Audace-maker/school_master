<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-image: url('.jpg'); /* Remplacez par l'URL de votre image */
            background-size: cover;
            background-position: center;
            color: #333;
            overflow: hidden;
            transition: background-color 0.5s ease;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s ease;
        }
        .logo img {
            height: 45px; /* Ajustez la taille du logo */
        }
        .login-button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
            text-decoration: none; /* Supprime le soulignement */
            display: inline-block;
        }
        .login-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .content {
            text-align: center;
            margin-top: 100px;
            color: white;
            opacity: 0;
            animation: fadeIn 1s forwards;
        }
        h1 {
            font-size: 48px;
            margin: 0;
            animation: slideIn 1s forwards;
        }
        p {
            font-size: 22px;
            animation: slideIn 1s forwards;
            animation-delay: 0.5s;
        }
        footer {
            text-align: center;
            padding: 5px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo"><img src="images/logom.jpg" alt="Logo de l'application"></div> <!-- Remplacez par l'URL de votre logo -->
        <a href="<?= base_url() ?>Login" class="login-button">Connexion</a> <!-- Remplacez par le lien de connexion -->
    </div>
    <div class="content">
        <h1>Bienvenue dans notre application</h1>
        <p>Gérez votre école facilement et efficacement.</p>
    </div>
    <footer>
        <p>Email : accesschool@gmail.com | Téléphone : 22 14 34 26 37</p>
    </footer>
</body>
</html>