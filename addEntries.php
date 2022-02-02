<!DOCTYPE HTML>  
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add entry</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>

    <?php
        session_start();

        $nameErr = $emailErr = $homepageErr = $textErr = "";

        $ip_address  = @$_SERVER['REMOTE_ADDR'];
        $browser = $_SERVER["HTTP_USER_AGENT"];

        $correct_data = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(empty($_POST["name"]))
            {
                $nameErr = "Пустое поле";
                $correct_data = false;
            }
            else
            {
                $name = htmlentities($_POST["name"]);
            }

            if (empty($_POST["email"]))
            {
                $emailErr = "Пустое поле";
                $correct_data = false;
            }
            else if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) === false)
            {
                $emailErr = "Некорректный email";
                $correct_data = false;
            }
            else
            {
                $email = htmlentities($_POST["email"]);
            }

            if (empty($_POST["homepage"]))
            {
                $homepage = "";
            }
            else if (filter_var($_POST["homepage"], FILTER_VALIDATE_URL) === false){
                $homepageErr = "Некорректная ссылка";
                $correct_data = false;
            }
            else
            {
                $homepage = htmlentities($_POST["homepage"]);
            }

            if (empty($_POST["text"]))
            {
                $textErr = "Пустое поле";
                $correct_data = false;
            }
            else
            {
                $text = htmlentities($_POST["text"]);
            }

            if ($correct_data)
            {
                require "db.php";

                $sql = mysqli_query($link, "INSERT INTO `messages` (`userName`, `email`, `homePage`, `message`, `ipAddress`, `browser`) VALUES
                                                                ('{$name}', '{$email}', '$homepage', '$text', '$ip_address', '$browser')");

                mysqli_close($link);

                $name = $email = $homepage = $text = "";
            }
        }
    ?>

    <header>
        <div class="container">
            <nav>
                <ul class="navigation">
                    <li class="navigation_item"><a class="navigation_link" href="index.php">Посмотреть список записей</a></li>
                    <li class="navigation_item"><a class="navigation_link disabled" href="">Добавить запись</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section>
        <div class="container">
            <form class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="form_item">
                    <div class="form_item_text">User name</div>
                    <input class="form_item_input" type="text" name="name" placeholder="User name" autocomplete="off" value="<?= $name ?? "" ?>" />
                    <span class="error">* <?php echo $nameErr;?></span>
                </div>

                <div class="form_item">
                    <div class="form_item_text">E-mail</div>
                    <input class="form_item_input" type="text" name="email" placeholder="E-mail" autocomplete="off" value="<?= $email ?? "" ?>" />
                    <span class="error">* <?php echo $emailErr;?></span>
                </div>

                <div class="form_item">
                    <div class="form_item_text">Homepage</div>
                    <input class="form_item_input" type="text" name="homepage" placeholder="Homepage" autocomplete="off" value="<?= $homepage ?? "" ?>" />
                    <span class="error"><?php echo $homepageErr;?></span>
                </div>

                <div class="form_item">
                    <div class="form_item_text">Text</div>
                    <textarea name="text" cols="30" rows="3" placeholder="Text" autocomplete="off"><?= $text ?? "" ?></textarea>
                    <span class="error"><?php echo $textErr;?></span>
                </div>

                <input class="form_button" type="submit" value="Отправить">
            </form>
        </div>
    </section>

</body>
</html>