<?php

namespace wbs\Framework\LogDB;

use Doctrine\ORM\Mapping as ORM;

/**
 *  Zugriff auf einen Eintrag in der LogDb
 *
 * @ORM\Table(name="md_log",indexes={
 *     @ORM\Index(name="md_log_auftrag_nr_index", columns={"auftrag_nr"}),
 *     @ORM\Index(name="md_log_level_index", columns={"level"}),
 *     @ORM\Index(name="md_log_position_id_index", columns={"position_id"}),
 *     @ORM\Index(name="md_log_project_index", columns={"project"})
 * })
 *
 * @ORM\Entity (repositoryClass="wbs\Framework\LogDB\LogDBRepository")
 */
class LogDBEntity
{
    /**
     * @var int
     *
     * @ORM\Id ()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id; // DB identifier, Primary Key
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=false,length=9, options={"comment":"Level des Logs"})
     */
    private $level; // [TEST,INFO,DEBUG,WARNING,ERROR,EMERGENCY, ...] https://www.php-fig.org/psr/psr-3/
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=false,length=4096, options={"comment":"Logmeldung"})
     */
    private $message; // Log Message
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=true, length=32, options={"comment":"Benutzername, optional"})
     */
    private $user; // Username, if exists
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=true,length=56, options={"comment":"Ip, optional"})
     */
    private $ip;
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=true,length=32, options={"comment":"Name des Projektes (optional)"})
     */
    private $project; // [Backend, Shop,  Station, WWS, ..]
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=true,length=32, options={"comment":"Name des Controllers (optional)"})
     */
    private $controller; // Controller, if exists
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=true,length=32, options={"comment":"Name der Action (optional)"})
     */
    private $action; // Action Handler, if exists
    /**
     * @var string
     *
     * @ORM\Column (type="string", nullable=true,length=16, options={"comment":"Auftragsnummer (optional)"})
     */
    private $auftrag_nr; // if relevant
    /**
     * @var int
     *
     * @ORM\Column (type="integer", nullable=true,options={"default"=0,"comment":"Auftragsid (optional)"})
     */
    private $auftrag_id; // if relevant
    /**
     * @var int
     *
     * @ORM\Column (type="integer",  nullable=true,options={"default"=0,"comment":"Auftragsid (optional)"})
     */
    private $position_id; // if relevant
    /**
     * @var \DateTime
     *
     * @ORM\Column (type="datetime", nullable=false ,options={"comment":"Zeitpunkt des Logs"})
     */
    private $created; // Datetime
}