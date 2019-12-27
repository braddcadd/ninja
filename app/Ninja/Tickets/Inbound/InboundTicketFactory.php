<?php

namespace App\Ninja\Tickets\Inbound;

    /**
     * Class InboundTicketFactory
     * @package App\Ninja\Tickets\Inbound
     */
/**
 * Class InboundTicketFactory
 * @package App\Ninja\Tickets\Inbound
 */
class InboundTicketFactory {

    /**
     * @var bool
     */
    public $json;

    /**
     * @var mixed
     */
    public $source;


    /**
     * InboundTicketFactory constructor.
     * @param bool $json
     * @throws \Exception
     */
    public function __construct($json = FALSE)
    {

        if(empty($json))
            throw new \Exception('Invalid source');

        $this->json = $json;
        $this->source = $this->jsonToArray();

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function jsonToArray()
    {

        $this->source = json_decode($this->json, FALSE);

        switch (json_last_error())
        {

            case JSON_ERROR_NONE:
                return $this->source;
                break;

            default:
                throw new \Exception('Postmark Inbound Error: Json format error');
                break;

        }

    }

    /**
     * @param $name
     * @param $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {

        $name = ucfirst($name);

        return ($this->source->$name) ? $this->source->$name : FALSE;

    }

    /**
     * Returns "to" email address
     */
    public function to() : string
    {

        return str_replace('"', "", $this->source->To);

    }

    /**
     * @return string
     */
    public function originalRecipient() : string
    {

        return $this->source->OriginalRecipient;

    }

    /**
     * @return string
     */
    public function fromEmail() : string
    {

        return $this->source->FromFull->Email;

    }

    /**
     * @return string
     */
    public function fromFull() : string
    {

        return $this->source->FromFull->Name . ' <' . $this->source->FromFull->Email . '>';

    }

    /**
     * @return string
     */
    public function fromName() : string
    {

        return $this->source->FromFull->Name;

    }

    /**
     * @param string $name
     * @return bool|string
     */
    public function headers($name = 'X-Spam-Status')
    {

        foreach ($this->source->Headers as $header)
        {

            if (isset($header->Name) AND $header->Name == $name)
            {

                if ($header->Name == 'Received-SPF')
                {

                    return self::_parseReceivedSpf($header->Value);

                }

                return $header->Value;

            }

        }

        return FALSE;

    }

    /**
     * @param $header
     * @return string
     */
    private static function _parseReceivedSpf($header) : string
    {

        preg_match_all('/^(\w+\b.*?){1}/', $header, $matches);

        return strtolower($matches[1][0]);

    }

    /**
     * @return array
     */
    public function recipients() : array
    {

        return self::_parseRecipients($this->source->ToFull);

    }

    /**
     * @return array
     */
    public function undisclosedRecipients() : array
    {

        return self::_parseRecipients($this->source->CcFull);

    }

    /**
     * @param $recipients
     * @return array
     */
    private static function _parseRecipients($recipients) : array
    {

        $objects = array_map(function ($object)
        {
            $object = get_object_vars($object);

            if( ! empty($object['Name']))
                $object['Name'] = $object['Name'];
            else
                $object['Name'] = FALSE;

            return (object)$object;

        }, $recipients);

        return $objects;
    }

    /**
     * @return Attachments
     */
    public function attachments()
    {

        return new Attachments($this->source->Attachments);

    }

    /**
     * @return Subject string
     */
    public function subject() : string
    {

        return $this->source->Subject;

    }

    /**
     * @return bool
     */
    public function hasAttachments() : bool
    {

        return count($this->source->Attachments) > 0 ? TRUE : FALSE;

    }
}