<?php

use EmailLabs\Response;
use EmailLabs\Email;

class EmailLabs
{
    /**
     * MAIN URL
     */
    const URL = 'https://api.emaillabs.net.pl/api/';

    /**
     * METHOD URLS
     */
    const URL_ADD_TEMPLATE = 'add_template';
    const URL_AGREGATE = 'agregate';
    const URL_AGREGATE_TAGS = 'agregate_tags';
    const URL_BLACKLIST = 'blacklists';
    const URL_BLACKLIST_REASONS = 'blacklist_reasons';
    const URL_BLACKLIST_EMAIL = 'blacklists/email/';
    const URL_CLICKS = 'clicks';
    const URL_EMAILS = 'emails';
    const URL_OPENS = 'opens';
    const URL_SEND_MAIL = 'sendmail';
    const URL_SEND_MAIL_TEMPLATE = 'sendmail_templates';
    const URL_SMTP = 'smtp';
    const URL_TEMPORARY_EMAIL = 'is_mail_tmp/email/';

    /**
     * REQUEST TYPE
     */
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_PUT = 'PUT';
    const REQUEST_METHOD_DELETE = 'DELETE';

    private
        $appKey,
        $curl,
        $data,
        $params,
        $request_type,
        $secret,
        $url;

    public function __construct()
    {
        $this->_curlInit();
    }

    /**
     * @param mixed $appKey
     * @return EmailLabs
     */
    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
        return $this;
    }

    /**
     * @param mixed $secret
     * @return EmailLabs
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @param mixed $url
     * @return EmailLabs
     */
    public function setUrl($url)
    {
        $this->url = self::URL . $url;
        return $this;
    }

    /**
     * @param mixed $request_type
     * @return EmailLabs
     */
    public function setRequestType($request_type)
    {
        $this->request_type = $request_type;
        return $this;
    }

    /**
     * @param mixed $data
     * @return EmailLabs
     */
    public function setData($data)
    {
        $this->data = http_build_query($data);
        return $this;
    }

    /**
     * @param array $params
     * @return EmailLabs
     */
    public function setParams($params)
    {
        $this->params = '?' . http_build_query($params);
        return $this;
    }

    private function _curlInit()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @throws Exception
     */
    private function _type()
    {
        if (!$this->request_type) {
            throw new \Exception('Set request type');
        }

        switch ($this->request_type) {
            case self::REQUEST_METHOD_DELETE:
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case self::REQUEST_METHOD_POST:
                curl_setopt($this->curl, CURLOPT_POST, true);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);
                break;
            case self::REQUEST_METHOD_PUT:
                $fh = fopen('php://temp', 'rw');
                fwrite($fh, $this->data);
                rewind($fh);
                curl_setopt($this->curl, CURLOPT_INFILE, $fh);
                curl_setopt($this->curl, CURLOPT_INFILESIZE, strlen($this->data));
                curl_setopt($this->curl, CURLOPT_PUT, true);
                break;
            case self::REQUEST_METHOD_GET:
            default:
                break;
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function _exec()
    {
        if (!$this->appKey || !$this->secret) {
            throw new \Exception('Set Auth data first');
        }
        curl_setopt($this->curl, CURLOPT_USERPWD, "$this->appKey:$this->secret");
        curl_setopt($this->curl, CURLOPT_URL, $this->url . $this->params);
        $response = curl_exec($this->curl);
        return $response;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function request()
    {
        $this->_type();
        return (new Response($this->_exec()));
    }

    /**
     * Dodawanie szablonu wiadomości
     * http://docs.emaillabs.pl/api/dodawanie-szablonu-wiadomosci/
     * @param string $html
     * @param bool|string $text
     * @return Response
     */
    public function addTemplate($html, $text = false)
    {
        if (!$html || !is_string($html))
            return false;

        $template = [];
        $template['html'] = $html;

        if ($text)
            $template['text'] = $text;

        return $this
            ->setUrl(self::URL_ADD_TEMPLATE)
            ->setRequestType(self::REQUEST_METHOD_POST)
            ->setData($template)
            ->request();
    }

    /**
     * Umożliwia pobranie wiadomości e-mail wysłanych z pośrednictwem kont SMTP
     * @param integer $offset
     * @param integer $limit
     * @param integer|bool $sort
     * @param integer|bool $filter
     * @return Response
     */
    public function getEmails($offset = 0, $limit = 100, $sort = false, $filter = false)
    {
        $params = [];
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        if ($sort)
            $params['sort'] = $sort;
        if ($filter)
            $params['filter'] = $filter;

        return $this
            ->setUrl(self::URL_EMAILS)
            ->setParams($params)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala pobrać listę kont SMTP przypisanych do danego panelu.
     */
    public function getSmtp()
    {
        return $this
            ->setUrl(self::URL_SMTP)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Umożliwia pobranie otwarć wiadomości przez użytkowników.
     * @param integer $offset
     * @param integer $limit
     * @param integer|bool $sort
     * @param integer|bool $filter
     * @return Response
     */
    public function getOpens($offset = 0, $limit = 100, $sort = false, $filter = false)
    {
        $params = [];
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        if ($sort)
            $params['sort'] = $sort;
        if ($filter)
            $params['filter'] = $filter;

        return $this
            ->setUrl(self::URL_OPENS)
            ->setParams($params)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Umożliwia pobranie ilości kliknięć w linki użytych we wiadomościach e-mail.
     * @param integer $offset
     * @param integer $limit
     * @param integer|bool $sort
     * @param integer|bool $filter
     * @return Response
     */
    public function getClicks($offset = 0, $limit = 100, $sort = false, $filter = false)
    {
        $params = [];
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        if ($sort)
            $params['sort'] = $sort;
        if ($filter)
            $params['filter'] = $filter;

        return $this
            ->setUrl(self::URL_CLICKS)
            ->setParams($params)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala pobrać listę powodów odrzuceń na czarnej liście.
     * @return Response
     */
    public function getBlacklistReasons()
    {
        return $this
            ->setUrl(self::URL_BLACKLIST_REASONS)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala sprawdzić czy na czarniej liście znajduję się zadany adres e-mail.
     * @param string $email
     * @return bool|Response
     */
    public function getBlacklistEmail($email)
    {
        if (!$email || !is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;


        return $this
            ->setUrl(self::URL_BLACKLIST_EMAIL . $email)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala pobrać czarną listę adresów e-mail, do których nie będzie dostarczana poczta.
     * @param integer|bool $offset
     * @param integer|bool $limit
     * @param integer|bool $sort
     * @param integer|bool $filter
     * @return Response
     */
    public function getBlacklists($offset = 0, $limit = 100, $sort = false, $filter = false)
    {
        $params = [];
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        if ($sort)
            $params['sort'] = $sort;
        if ($filter)
            $params['filter'] = $filter;

        return $this
            ->setUrl(self::URL_BLACKLIST)
            ->setParams($params)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala dodać adres e-mail do czarnej listy
     * @param string $account
     * @param string $email
     * @param string $reason
     * @return bool|Response
     */
    public function addBlackList($account, $email, $reason)
    {
        if (!$account || !$email || !$reason)
            return false;

        $blacklist = [];
        $blacklist['account'] = $account;
        $blacklist['email'] = $email;
        $blacklist['reason'] = $reason;

        return $this
            ->setUrl(self::URL_BLACKLIST)
            ->setRequestType(self::REQUEST_METHOD_POST)
            ->setData($blacklist)
            ->request();
    }

    /**
     * Pozwala wysyłać wiadomości e-mail za pośrednictwem API
     * @param Email $email
     * @return Response
     */
    public function sendMail(Email $email)
    {
        return $this
            ->setUrl(self::URL_SEND_MAIL_TEMPLATE)
            ->setRequestType(self::REQUEST_METHOD_POST)
            ->setData($email->getMessage())
            ->request();
    }

    /**
     * Pozwala wysyłać wiadomości e-mail za pośrednictwem API z wykorzystaniem szablonów
     * @param Email $email
     * @return Response
     */
    public function sendEmailWithTemplate(Email $email)
    {
        return $this
            ->setUrl(self::URL_SEND_MAIL_TEMPLATE)
            ->setRequestType(self::REQUEST_METHOD_POST)
            ->setData($email->getMessage())
            ->request();
    }

    /**
     * Pozwala pobrać dane zagregowane np. na potrzeby stworzenia wykresu
     * Różnica pomiędzy date_from a date_to nie może wynosić więcej niż 62 dni
     * @param string $smtp_account
     * @param integer $date_from timestamp
     * @param integer $date_to timestamp
     * @return bool|Response
     */
    public function getAgregate($smtp_account, $date_from, $date_to)
    {
        if (!$smtp_account || !$date_from || !is_numeric($date_from) || !$date_to && !is_numeric($date_to))
            return false;

        $agregate = [];
        $agregate['smtp_account'] = $smtp_account;
        $agregate['date_from'] = $date_from;
        $agregate['date_to'] = $date_to;

        return $this
            ->setUrl(self::URL_AGREGATE)
            ->setParams($agregate)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala pobrać dane zagregowane np. na potrzeby stworzenia wykresu z podziałem na tagi
     * Różnica pomiędzy date_from a date_to nie może wynosić więcej niż 62 dni
     * @param string $smtp_account
     * @param integer $date_from timestamp
     * @param integer $date_to timestamp
     * @return bool|Response
     */
    public function getAgregateTags($smtp_account, $date_from, $date_to)
    {
        if (!$smtp_account || !$date_from || !is_numeric($date_from) || !$date_to && !is_numeric($date_to))
            return false;

        $agregate = [];
        $agregate['smtp_account'] = $smtp_account;
        $agregate['date_from'] = $date_from;
        $agregate['date_to'] = $date_to;

        return $this
            ->setUrl(self::URL_AGREGATE_TAGS)
            ->setParams($agregate)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }

    /**
     * Pozwala usunąć adres e-mail z czarnej listy
     * @param string $email
     * @return bool|Response
     */
    public function deleteEmailBlacklist($email)
    {
        if (!$email || !is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;

        return $this
            ->setUrl(self::URL_BLACKLIST_EMAIL . $email)
            ->setRequestType(self::REQUEST_METHOD_DELETE)
            ->request();
    }

    /**
     * Pozwala sprawdzić czy adres e-mail należy do serwisów tymczasowych
     * @param string $email
     * @return bool|Response
     */
    public function isEmailTemporary($email)
    {
        if (!$email || !is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;

        return $this
            ->setUrl(self::URL_TEMPORARY_EMAIL . $email)
            ->setRequestType(self::REQUEST_METHOD_GET)
            ->request();
    }
}
