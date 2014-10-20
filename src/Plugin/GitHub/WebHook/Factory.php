<?php

namespace Martha\Plugin\GitHub\WebHook;

use Martha\Scm\ChangeSet\ChangeSet;
use Martha\Scm\Commit;
use Martha\Scm\Repository;

/**
 * Class Factory
 * @package Martha\Plugin\GitHub\WebHook
 */
class Factory
{
    /**
     * Accepts an array of information posted by GitHub about a particular push, and parses it into a GitHubWebHook
     * object.
     *
     * @param array $hook
     * @return WebHook
     */
    public static function createHook(array $hook)
    {
        $repositoryData = isset($hook['repository']) ? $hook['repository'] : false;
        $commits = isset($hook['commits']) ? $hook['commits'] : false;

        if (!($repositoryData && $commits)) {
            // todo fixme throw some kind of exception here
        }

        $trigger = new WebHook();
        $trigger->setHook($hook);

        $repository = new Repository();
        $repository->setType('git')
            ->setName($repositoryData['full_name'])
            ->setDescription($repositoryData['description'])
            ->setPath($repositoryData['ssh_url']);

        if ($hook['pull_request']['head']['repo']['full_name'] != $repository->getName()) {
            $trigger->setFork($hook['pull_request']['head']['repo']['ssh_url']);
        }

        $branch = basename($hook['pull_request']['head']['ref']);
        $trigger->setBranch($branch);
        $trigger->setRevisionNumber($hook['pull_request']['head']['sha']);

        $changeSet = new ChangeSet();

        /*foreach ($commits as $commitData) {
            $author = new Commit\Author();
            $author->setName($commitData['author']['name'])
                ->setNick($commitData['author']['username'])
                ->setEmail($commitData['author']['email']);

            $commit = new Commit();
            $commit->setRevisionNumber(($commitData['id']))
                ->setMessage($commitData['message'])
                ->setAuthor($author)
                ->setDate(new \DateTime($commitData['timestamp']));

            foreach ($commitData['added'] as $fileName) {
                $commit->addAddedFile($fileName);
            }

            foreach ($commitData['removed'] as $fileName) {
                $commit->addRemovedFile($fileName);
            }

            foreach ($commitData['modified'] as $fileName) {
                $commit->addModifiedFile($fileName);
            }

            $changeSet->addCommit($commit);
        }*/

        $trigger->setRepository($repository)
            ->setChangeSet($changeSet);

        return $trigger;
    }
}

