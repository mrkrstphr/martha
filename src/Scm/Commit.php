<?php

namespace Martha\Scm;

/**
 * Class Commit
 * @package Martha\Scm
 */
class Commit
{
    /**
     * @var string
     */
    protected $revisionNumber;

    /**
     * @var Commit\Author
     */
    protected $author;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $addedFiles = [];

    /**
     * @var array
     */
    protected $modifiedFiles = [];

    /**
     * @var array
     */
    protected $removedFiles = [];

    /**
     * @param string $revisionNumber
     * @return $this
     */
    public function setRevisionNumber($revisionNumber)
    {
        $this->revisionNumber = $revisionNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getRevisionNumber()
    {
        return $this->revisionNumber;
    }

    /**
     * @param string Commit\Author $author
     * @return $this
     */
    public function setAuthor(Commit\Author $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Commit\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function addAddedFile($file)
    {
        $this->addedFiles[] = $file;
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function addRemovedFile($file)
    {
        $this->removedFiles[] = $file;
        return $this;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function addModifiedFile($file)
    {
        $this->modifiedFiles[] = $file;
        return $this;
    }
}
