<?php

namespace EmailLabs;

class Email
{
    const VAR_BCC = 'bbc';
    const VAR_BCC_NAME = 'bbc_name';
    const VAR_CC = 'cc';
    const VAR_CC_NAME = 'cc_name';
    const VAR_FILES = 'files';
    const VAR_FILES_CONTENT = 'content';
    const VAR_FILES_INLINE = 'inline';
    const VAR_FILES_MIME = 'mime';
    const VAR_FILES_NAME = 'name';
    const VAR_FROM = 'from';
    const VAR_FROM_NAME = 'from_name';
    const VAR_HEADERS = 'headers';
    const VAR_HTML = 'html';
    const VAR_MESSAGE_ID = 'message_id';
    const VAR_REPLY_TO = 'reply_to';
    const VAR_SMTP_ACCOUNT = 'smtp_account';
    const VAR_SUBJECT = 'subject';
    const VAR_TAGS = 'tags';
    const VAR_TEMPLATE_ID = 'template_id';
    const VAR_TEXT = 'text';
    const VAR_TO = 'to';
    const VAR_VARS = 'vars';

    /**
     * Adres e-mail odbiorcy wiadomości e-mail ( w formie tablicy )
     * @required
     * @var array
     */
    private $to = [];

    /**
     * Konto SMTP przez które chcesz wysłać wiadomość
     * @required
     * @var string
     */
    private $smtp_account = '';

    /**
     * Temat wiadomości ( max. 128 znaków )
     * @required
     * @var string
     */
    private $subject = '';

    /**
     * Id szablonu wiadomości
     * @required wymagane jeżeli nie podano pola html i txt
     * @var integer
     */
    private $template_id;

    /**
     * Wiadomość w formacie HTML
     * @required wymagane jeżeli nie występuje parametr text
     * @var string
     */
    private $html = '';

    /**
     * Wiadomość w formacie tekstowym
     * @required wymagane jeżeli nie występuje parametr html,
     * w przypadku wystąpienia obu parametrów text zostanie ustawione jako wiadomość alternatywna
     * @var string
     */
    private $text = '';

    /**
     * Adres e-mail nadawcy wiadomości
     * @required
     * @var string
     */
    private $from = '';

    /**
     * Adres e-mail nadawcy wiadomości
     * @required
     * @var string
     */
    private $from_name = '';

    /**
     * Dodatkowe nagłówki w formie tablicy jako
     * nazwa_nagłówka => wartość
     * @var array
     */
    private $headers = [];

    /**
     * Adres e-mail na który zostanie wysłana kopia wiadomości
     * @var string
     */
    private $cc = '';

    /**
     * Nazwa odbiorcy wiadomości ( max. 128 znaków )
     * @var string
     */
    private $cc_name = '';

    /**
     * Adres e-mail na który zostanie wysłana kopia wiadomości ( ukryty adres )
     * @var string
     */
    private $bcc = '';

    /**
     * Nazwa odbiorcy wiadomości ( max. 128 znaków )
     * @var string
     */
    private $bcc_name = '';

    /**
     * Adres e-mail “odpowiedź do”
     * @var string
     */
    private $reply_to = '';

    /**
     * Tagi wiadomości w formie tablicy ( razem max. 128 znaków )
     * @var array
     */
    private $tags = [];

    /**
     * Załączniki jakie mają zostać dodane do pliku w formie tablicy
     * @var array
     */
    private $files = [];

    public function __construct()
    {

    }

    /**
     * @param array $files
     * @return Email
     */
    public function setFiles($files)
    {
        $this->files[] = $files;
        return $this;
    }

    /**
     * @param string $to
     * @return Email
     */
    public function setTo($to)
    {
        $this->to[] = $to;
        return $this;
    }

    /**
     * @param string $smtp_account
     * @return Email
     */
    public function setSmtpAccount($smtp_account)
    {
        $this->smtp_account = $smtp_account;
        return $this;
    }

    /**
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param int $template_id
     * @return Email
     */
    public function setTemplateId($template_id)
    {
        $this->template_id = $template_id;
        return $this;
    }

    /**
     * @param string $html
     * @return Email
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @param string $text
     * @return Email
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string $from
     * @return Email
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param string $from_name
     * @return Email
     */
    public function setFromName($from_name)
    {
        $this->from_name = $from_name;
        return $this;
    }

    /**
     * @param array $headers
     * @return Email
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param string $cc
     * @return Email
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * @param string $cc_name
     * @return Email
     */
    public function setCcName($cc_name)
    {
        $this->cc_name = $cc_name;
        return $this;
    }

    /**
     * @param string $bcc
     * @return Email
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * @param string $bcc_name
     * @return Email
     */
    public function setBccName($bcc_name)
    {
        $this->bcc_name = $bcc_name;
        return $this;
    }

    /**
     * @param string $reply_to
     * @return Email
     */
    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
        return $this;
    }

    /**
     * @param string $tag
     * @return Email
     */
    public function setTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * Dodanie dodatkowych parametrów odbiorcy
     * vars = Tablica ze zmiennymi do podmiany w szablonie
     * message_id = Własne message-id
     * @param $email
     * @param array $vars
     * @param bool $messageId
     * @return $this
     */
    public function setToExtended($email, $vars = [], $messageId = false)
    {
        $this->to[] = $email;

        if (!empty($vars))
            $this->to[$email][self::VAR_VARS] = $vars;

        if ($messageId)
            $this->to[$email][self::VAR_MESSAGE_ID] = $messageId;

        return $this;
    }

    /**
     * @param string $name Nazwa pliku
     * @param string $mime Typ pliku w formie rfc2045
     * @param string $content Zawartość pliku zakodowana w base64
     * @param string $inline Umożliwia osadzenie obrazka w treści wiadomości HTML ( obrazek powinien zawierać następującą notyfikację <img src=”cid:nazwa_pliku”/> )
     * @return $this
     */
    public function setFileExtended($name, $mime, $content, $inline)
    {
        $file = [];
        $file[self::VAR_FILES_NAME] = $name;

        if ($mime) {
            $file[self::VAR_FILES_MIME] = $mime;
        }

        if ($content) {
            $file[self::VAR_FILES_CONTENT] = $content;
        }

        if ($inline) {
            $file[self::VAR_FILES_INLINE] = $inline;
        }

        $this->files[] = $file;
        return $this;
    }


    public function getMessage()
    {
        $message = array(
            self::VAR_TO => $this->to,
            self::VAR_FROM => $this->from,
            self::VAR_FROM_NAME => $this->from_name,
            self::VAR_TEMPLATE_ID => $this->template_id,
            self::VAR_HTML => $this->html,
            self::VAR_TEXT => $this->text,
            self::VAR_SMTP_ACCOUNT => $this->smtp_account,
            self::VAR_SUBJECT => $this->subject
        );

        if ($this->headers) {
            $message[self::VAR_HEADERS] = $this->headers;
        }
        if ($this->cc) {
            $message[self::VAR_CC] = $this->cc;
        }
        if ($this->cc_name) {
            $message[self::VAR_CC_NAME] = $this->cc_name;
        }
        if ($this->bcc) {
            $message[self::VAR_BCC] = $this->bcc;
        }
        if ($this->bcc_name) {
            $message[self::VAR_BCC_NAME] = $this->bcc_name;
        }
        if ($this->reply_to) {
            $message[self::VAR_REPLY_TO] = $this->reply_to;
        }
        if ($this->tags) {
            $message[self::VAR_TAGS] = $this->tags;
        }
        if ($this->files) {
            $message[self::VAR_FILES] = $this->files;
        }

        return $message;
    }
}