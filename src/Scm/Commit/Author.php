<?php

namespace Martha\Scm\Commit;

/**
 * Class Author
 * @package Martha\Scm\Commit
 */
class Author
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $nick;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $nick
     * @return $this
     */
    public function setNick($nick)
    {
        $this->nick = $nick;
        return $this;
    }

    /**
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
