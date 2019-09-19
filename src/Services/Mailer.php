<?php

namespace App\Services;

use Symfony\Component\Templating\EngineInterface;
use Twig\Environment;

/**
 * Class Mailer
 */
class Mailer
{
    private $engine;
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, Environment $engine)
    {
        $this->engine = $engine;
        $this->mailer = $mailer;
    }

    public function sendAdminMessage($from, $to, $subject, $body, $attachement = null)
    {
        /*  Fonction qui permet d'envoyer un message grâce aux clients
        */
        $mail = (new \Swift_Message($subject))
            ->setFrom("hello@hedee.co")
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setReplyTo($from)
            ->setContentType('text/html');
            if($attachement != null){
                $mail->attach(new \Swift_Attachment($attachement, "test.pdf", 'application/pdf'));
            }
            

        $this->mailer->send($mail);
    }

    public function sendMessage($from, $to, $subject, $body, $attachement = null)
    {
        /*  Fonction qui permet aux clients d'envoyer un message à hedee
        */
        $mail = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setReplyTo($from)
            ->setContentType('text/html');
            

        $this->mailer->send($mail);
    }

    public function createBodyMail($view, array $parameters)
    {
        /*  Fonction qui écrit le contenu du mail à partir d'un fichier HTML
        */
        return $this->engine->render($view, $parameters);
    }
}
