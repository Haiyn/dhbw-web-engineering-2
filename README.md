# Web Engineering 2
> Eine Planning Poker Website, gehostet in Docker containern mithilfe folgenden
Services:

![PHP Version][php-image]
![Apache Version][apache-image]
![MySQL Version][mysql-image]
![Docker Version][docker-image]
![Composer Version][composer-image]

## Abgabeinformationen

In der Github Repo sind alle **Quellcode-Dateien** enthalten. 

Die benötigten **Dependencies** sind bereits in `src/resources/assets` vorhanden. 

Der **Datenbank-Dump** ist in `db` vorhanden.

Die ZIP-Version dieser Abgabe enthält die **Projekt-Dokumentation**.

## Development Setup

Bitte folgen Sie diesen einzelnen Punkten in der angegeben Reihenfolge um das Projekt zu starten:

### Docker-Container starten
![Docker Version][docker-image] benötigt

Zum Starten des gesamten Projekts wird Docker benötigt. Eine Dockerfile und docker-compose.yml sind im Projekt-Root
verfügbar. Diese starten Webserver, MySQL Datenbank und das Adminer DBMS.

Um das Projekt zu starten, müssen folgende Befehle im Projekt-Root ausgeführt werden:
1. Webserver image erstellen:
```
docker build -t web-engineering:2020 .
```

2. docker-compose ausführen:
```
docker-compose up -d
```

Läuft alles erfolgreich durch, ist die Website unter `localhost:8081` und Adminer unter `localhost:8082` erreichbar.

### (falls nötig) Abhängigkeiten verwalten
![Composer Version][composer-image] benötigt

Die Projekt-Dependencies (packages, PHP-Erweiterungen, Frameworks) werden mit Composer verwaltet. Sie werden mithilfe
der `composer.json` definiert und heruntergeladen. Nach der Installation werden sie durch Kopierbefehle von
 ```/vendor/<package>``` nach ```/src/resources/assets/<package>``` kopiert, damit sie auch auf dem Server zur Verfügung
 stehen.

Um alle Abhängigkeiten zu installieren, muss vor dem Starten der Docker Container folgende Befehle ausgeführt werden:

```
composer install
```

Läuft alles erfolgreich durch, stehen alle Abhängigkeiten in `src/resources/assets` zur Verfügung.

#### (falls benötigt) Aufräumen
Um alle container, images und volumes aufzuräumen und vom Anfang an zu starten:
1. Alle container stoppen:
```
docker kill $(docker ps -a -q)
```
2. Alle container entfernen:
```
docker rm $(docker ps -a -q)
```
3. Alle images entfernen
```
docker rmi $(docker images -q)
```

4. Alle volumes entfernen:
```
docker volume ls -qf dangling=true | xargs -r docker volume rm
```

<!-- Markdown link & Image definitions-->
[php-image]: https://img.shields.io/badge/php-v7.4.3-brightgreen?style=flat-square&logo=php
[composer-image]: https://img.shields.io/badge/composer-v1.9.3-brightgreen?style=flat-square&logo=composer
[bootstrap-image]: https://img.shields.io/badge/bootstrap-v4.3.1-brightgreen?style=flat-square&logo=bootstrap
[mysql-image]: https://img.shields.io/badge/mysql-v8.0.19-brightgreen?style=flat-square&logo=mysql
[docker-image]: https://img.shields.io/badge/docker-v19.03.6+-brightgreen?style=flat-square&logo=docker
[apache-image]: https://img.shields.io/badge/apache-v2.4.41+-brightgreen?style=flat-square&logo=apache
