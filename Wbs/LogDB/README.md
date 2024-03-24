### LogDB - Logging in der Datenbank

Die Anbindung an Doctrine wurde gewählt, um die Tabelle einfach erzeugen zu können.
Könnte auch demnächst wieder rausfliegen.

Die Klasse LogDBController ist kompatibel zu psr-3.

## Beispiel Anbindung an einen Controller

``` php
    /**
     * @return \wbs\Framework\LogDB\LogDBController
     */
    public function log()
    {
        if (is_null($this->log)) {
            $this->log = new \wbs\LogDB\LogDBController($this->wbs());
            $this->log->setTableName('MyLogTable');
            $this->log->checkTableExistance('MyLogTable');
            $this->log->setProjectDefault('Station');
            $this->log->setControllerDefault('Station');
        }
        return $this->log;
    }
```

