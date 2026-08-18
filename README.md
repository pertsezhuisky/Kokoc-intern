# Задание №1
<b>С чем столкниулся</b><br>
При создании контейнера была указана src директория по умолчанию, при попытке просмотра страницы index.php выходила ошибка 404 - по пути var/www/html/index.php<br><br>
<b>Как решил</b><br>
Чтобы мы смогли увидеть старницу index.php нам нужно указать в docker-compose.yml (поле volumes) ```- ./src/public:/var/www/html``` вместо стандартного ```- ./srс:/var/www/html``` при условии нахождения страницы index.php по пути src/public/index.php.<br>
Более комплексный подход предполагает создание файла dockerfile с измененными параметрами Apache DocumentRoot:<br>
```
FROM php:8.2-apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf
```
В таком случае структура приложения внутри контейнера сохраняется ```/var/www/html/public/index.php```, а Apache настраивается таким образом, чтобы использовать ```/var/www/html/public``` в качестве DocumentRoot. 
<br>Это более комплексный и предпочтительный подход для приложений с разделением публичной и внутренней частей, так как директория public становится единственной директорией, доступной через веб-сервер. При этом остальные директории приложения (app, config, storage и т. д.) не должны напрямую раздаваться Apache.

