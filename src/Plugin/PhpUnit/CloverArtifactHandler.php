<?php

namespace Martha\Plugin\PhpUnit;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Plugin\AbstractArtifactHandler;
use Martha\Core\Plugin\ArtifactHandlers\TextBasedResultInterface;

/**
 * Class CloverArtifactHandler
 * @package Martha\Plugin\PhpCodeSniffer
 */
class CloverArtifactHandler extends AbstractArtifactHandler implements TextBasedResultInterface
{
    /**
     * @var string
     */
    protected $name = 'phpunit-clover';

    /**
     * @var Build
     */
    protected $build;

    /**
     * @var bool|\SimpleXmlElement
     */
    protected $artifact;

    /**
     * {@inheritDoc}
     */
    public function parseArtifact(Build $build, $artifact)
    {
        $this->build = $build;
        $this->artifact = $artifact ? new \SimpleXMLElement($artifact) : false;
    }

    /**
     * {@inheritDoc}
     */
    public function getSimpleTextResult()
    {
        $totalMethods = 0;
        $coveredMethods = 0;
        $totalStatements = 0;
        $coveredStatements = 0;

        if ($this->artifact) {
            foreach ($this->artifact->xpath('//file/class/metrics') as $metric) {
                $totalMethods += (int)$metric['methods'];
                $coveredMethods += (int)$metric['coveredmethods'];
                $totalStatements += (int)$metric['statements'];
                $coveredStatements += (int)$metric['coveredstatements'];
            }
        }

        $coverageMethods = round($coveredMethods / $totalMethods * 100, 2);
        $coverageStatements = round($coveredStatements / $totalStatements * 100, 2);

        $result = "Code Coverage: \n" .
            " - Methods: {$coverageMethods}%\n" .
            " - Statements: {$coverageStatements}%\n";

        return $result;
    }
}
