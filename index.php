<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/style.css">
    <title>Test work!</title>
</head>
<body>

<div class="wrapper">
    <header>
        <div class="main-center">
            <h1>Загрузка данных для тестовой работы.</h1>
        </div>
    </header>

    <section id="content">
        <div class="main-center">
            <form id = "loadData" method = "post">
                <p>Загрузите файл в формате json. Данные будут загружены в БД.</p>
                <div class="fields">
                    <div class="field">
                        <input type="file" name = "file" id = "file">
                    </div>
                    <div class="field">
                        <p>
                            <input type="radio" name = "table" value = "categories" id = "table">
                            Категории
                        </p>
                        <p>
                            <input type="radio" name = "table" value = "products">
                            Продукты
                        </p>
                    </div>
                    <div class="field">
                        <button type = "submit">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <footer>
        <div class="main-center">
            <div class="copy">
                @<?=date("Y");?> Все права защищены.
            </div>
        </div>
    </footer>
</div>

<script src = "https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src = "assets/script.js"></script>

</body>
</html>