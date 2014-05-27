<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Year: 2014
 */

class Tm_Session extends Tm_Session_Abstract
{
    protected $timeZone = 0;
    protected $mSessionId;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->mSessionId = '';
        $this->timeZone = '';
    }

    // Serialize based on SID
    public function SaveToSID()
    {
        // Save the session in the PHP Session
        $_SESSION['Session'] = $this;
        return true;
    }

    /**
     * @param string $timeZone
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }
}