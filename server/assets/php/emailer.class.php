<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Emailer {
    protected PHPMailer $PHPMailerObj;
    public function __construct() {
        $this->PHPMailerObj = new PHPMailer(true);
//        $this->PHPMailerObj->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->PHPMailerObj->isSMTP();
        $this->PHPMailerObj->Host = getenv("SMTP_HOST");
        $this->PHPMailerObj->SMTPAuth = true;
        $this->PHPMailerObj->Username = getenv("SMTP_USER");
        $this->PHPMailerObj->Password = getenv("SMTP_PASSWORD");
        $this->PHPMailerObj->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->PHPMailerObj->Port = getenv("SMTP_PORT");
        $this->PHPMailerObj->isHTML();
    }

    public function setFrom(string $FromEmailAddressStr, string $RecipientNameStr): bool {
        return $this->PHPMailerObj->setFrom($FromEmailAddressStr, $RecipientNameStr);
    }

    public function send(string $ToEmailAddressStr, string $SubjectStr, string $BodyHtmlStr): bool {
        $this->PHPMailerObj->addAddress($ToEmailAddressStr, "To Whom it may concern");
        $this->PHPMailerObj->Subject = $SubjectStr;
        $this->PHPMailerObj->Body = $BodyHtmlStr;
        $SendingStatusBool = $this->PHPMailerObj->send();
        $this->PHPMailerObj->smtpClose();
        return $SendingStatusBool;
    }
}