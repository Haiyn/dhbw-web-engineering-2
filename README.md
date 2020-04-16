# Web Engineering 2
> Eine Planning Poker Website, gehostet in Docker containern mithilfe folgenden
Services:

![PHP Version][php-image]
![Apache Version][apache-image]
![MySQL Version][mysql-image]
![Docker Version][docker-image]
![Composer Version][composer-image]

## Development Setup

Bitte folgen sie diesen einzelnen Punkten in der angegeben Reihenfolge um das Projekt zu starten:

### 1. Abhängigkeiten verwalten
![Composer Version][composer-image] benötigt

Die Projekt-Dependencies (packages, PHP-Erweiterungen, Frameworks) werden mit Composer verwaltet. Sie werden mithilfe
der `composer.json` definiert und heruntergeladen. Nach der Installation werden sie durch Kopierbefehle von
 ```/vendor/<package>``` nach ```/src/resources/assets/<package>``` kopiert, damit sie auch auf dem Server zur Verfügung
 stehen.

Um alle Abhängigkeiten zu installieren, muss vor dem Starten der Docker Container folgende Befehle ausgeführt werden:

```
composer update && composer install
```

Läuft alles erfolgreich durch, stehen alle Abhängigkeiten in `src/resources/assets` zur Verfügung.

### 2. Docker-Container starten
![Docker Version][docker-image] benötigt

#### Das Projekt starten
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

Diese Befehle sind auch als JetBrains PHPStorm Konfiguration verfügbar (siehe PHPStorm).

Läuft alles erfolgreich durch, ist die Website unter `localhost:8081` und Adminer unter `localhost:8082` erreichbar.

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

## PHPStorm

### Docker

#### Dockerfile

Dockerfile ausführen um ein image zu erstellen:

1. Erstelle neue "Dockerfile" Konfiguration
2. Kontext Ordner: "."
3. Image tag: "web-engineering:2020"

#### Docker-compose

Alle container mit docker-compose ausüfhren:

1. Erstelle neue "Docker-compose" Konfiguration
2. Compose Datei(en): "./docker-compose.yml;"

### Debugging

Die Web-Andwendung kann mithilfe von XDebug in PHPStorm gedebuggt werden:

Installation:
1. Erstelle "PHP Remote Debug" Konfiguration
2. Installiere [Xdebug Browser-Erweiterung](https://www.jetbrains.com/help/phpstorm/2019.3/browser-debugging-extensions.html?utm_campaign=PS&utm_content=2019.3&utm_medium=link&utm_source=product)
3. Führe neue Debug-Konfiguration in PHPStorm aus
4. Öffne die Browser-Erweiterung und wähle "Debug" aus
5. Akzeptiere die Debug-Anfrage vom Browser in PHPStorm

<!-- Markdown link & Image definitions-->
[php-image]: https://img.shields.io/badge/php-v7.4.3-brightgreen?style=flat-square&logo=php
[composer-image]: https://img.shields.io/badge/composer-v1.9.3-brightgreen?style=flat-square&logo=composer
[bootstrap-image]: https://img.shields.io/badge/bootstrap-v4.3.1-brightgreen?style=flat-square&logo=bootstrap
[mysql-image]: https://img.shields.io/badge/mysql-v8.0.19-brightgreen?style=flat-square&logo=mysql
[docker-image]: https://img.shields.io/badge/docker-v19.03.6+-brightgreen?style=flat-square&logo=docker
[apache-image]: https://img.shields.io/badge/apache-v2.4.41+-brightgreen?style=flat-square&logo=apache
