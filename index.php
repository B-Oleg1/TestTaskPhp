<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show entries</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>
    <?php static $counter = 0; ?>
    <header>
        <div class="container">
            <nav>
                <ul class="navigation">
                    <li class="navigation_item"><a class="navigation_link disabled">Посмотреть список записей</a></li>
                    <li class="navigation_item"><a class="navigation_link" href="addEntries.php" href="">Добавить запись</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <?php

        $offset = 0;
        $maxLimit = 10;

        if (isset($_GET['order']))
        {
            $order = $_GET['order'];
        }
        else
        {
            $order = 'id';
        }

        if (isset($_GET['sort']))
        {
            $sort = $_GET['sort'];
        }
        else
        {
            $sort = 'DESC';
        }

        require('db.php');

        $count_rows = mysqli_query($link, "SELECT count(*) FROM messages");

        if (isset($_GET['limit']))
        {
            // Переход на пред стр
            if ($_GET['limit'] == 0)
            {
                if (isset($_GET['offset']) && $_GET['offset'] >= 10)
                {
                    $offset = $_GET['offset'] - 10;
                }
            }
            // Переход на след стр
            else if ($_GET['limit'] == 2)
            {
                if (isset($_GET['offset']) && $_GET['offset'] < $count_rows)
                {
                    $offset = $_GET['offset'] + 10;
                }
            }
        }

        // Поменять сортировку, если нажали на ячейку
        if (isset($_COOKIE['Order']) && $_COOKIE['Order'] == $order && isset($_GET['limit']) && $_GET['limit'] == 1)
        {
            if ($sort == 'DESC')
            {
                $sort = 'ASC';
            }
            else if ($sort == 'ASC')
            {
                $sort = 'DESC';
            }
        }
        else if (isset($_COOKIE['Order']) && $_COOKIE['Order'] != $order && isset($_GET['limit']) && $_GET['limit'] == 1)
        {
            $sort = 'ASC';
        }

        setcookie("Sort", $sort);
        setcookie("Order", $order);

        $result = mysqli_query($link, "SELECT * FROM messages ORDER BY $order $sort LIMIT $offset,$maxLimit");

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        echo 
            "<table class='entries_table'>
                <tr>
                    <th><a href='?order=userName&&sort=$sort&&limit=1&&offset=$offset'>User name</a></th>
                    <th><a href='?order=email&&sort=$sort&&limit=1&&offset=$offset'>E-Mail</th>
                    <th><a href='?order=homePage&&sort=$sort&&limit=1&&offset=$offset'>Homepage</th>
                    <th><a href='?order=message&&sort=$sort&&limit=1&&offset=$offset'>Text</th>
                </tr>";

        $count_item = 0;
        foreach ($rows as $row)
        {
            if ($count_item % 2 != 0)
            {
                echo "<tr class='tr_bg'>";
            }
            else
            {
                echo "<tr>";
            }
            
            echo
                "<td>" . $row['userName'] . "</td>" .
                "<td>" . $row['email'] . "</td>" .
                "<td>" . $row['homePage'] . "</td>" .
                "<td>" . $row['message'] . "</td>" .
            "</tr>";

            $count_item++;
        }

        mysqli_close($link);

        echo
            "</table>";

        echo
            "<div class='change_list'>
                <a class='change_list_button' href='?order=$order&&sort=$sort&&limit=0&&offset=$offset'>Prev</a>
                <a class='change_list_button' href='?order=$order&&sort=$sort&&limit=2&&offset=$offset'>Next</a>
            </div>";

    ?>

</body>
</html>