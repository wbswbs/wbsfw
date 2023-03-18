
# Wbs Framework  wbsfw

Das Framework bietet verschiedene Funktionen für Wenanwendungen:

* Konfiguration über .env
* Eine vorgegebene Struktur
* E-Mail Versand über SMTP
* Datenbankzugriff
* Smarty Template Engine
* Logging

## Voraussetzungen:

* Eine PHP Version >= PHP 8.0
* composer

## Anwendung

- Einbindung über composer.json
- Im ersten Aufruf muss der Root Pfad des Projektes übergeben werden,
  alle anderen Pfade leiten sich von dort ab.

``` php
  require __DIR__ . "/../vendor/autoload.php";

  $wbs = new Wbs('../');
```

## Definierte Pfade

Alle Pfade in der Hauptklasse kommen mit einem endenen Slash zurück

* getRootPath() -> /
* /bin
* getConfigPath() -> /config
* getPublicPath() -> /public
* getTemplatePath() -> /templates
* getCachePath() -> /var/cache
* getDataPath() -> /var/data
* getLogPath() -> /var/log