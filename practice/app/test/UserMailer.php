<?php

namespace App\test;

class UserMailer
{
    protected $mailer;
    protected $logger;
    protected $slack;
    public function __construct(Mailer $mailer, Logger $logger, Slack $slack)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->slack = $slack;
    }
    public function hello()
    {
        return 'Hello';
    }
}
