Инструкция по развёртыванию:

Развернуть файлы проекта на сервер. Зайти по ссылке на сайт, файл index.php в корне отвечает за визуальную часть загрузки данных в базу данных.
Поэтому, в начале, создать базу данных, в корне есть файл base.sql, просто взять оттуда код и исполнить в phpMyAdmin sql.

Далее, в концигурационном файле нужно указать настройки подключения к БД.
Файл конфигурации: /lib/config_class.php , там я думаю понятно будет.

Затем можно загружать данные. В визуальной части будет всё понятно как загружать.

Ну и затем в принципе проверять запросы согласно ТЗ.

Если не захотите разворачивать, можно глянуть тут: http://testwork.office5c.beget.tech/

Удачи!
