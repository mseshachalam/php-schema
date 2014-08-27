<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SchemaOrgModel\Command;

use SchemaOrgModel\CardinalitiesExtractor;
use SchemaOrgModel\GoodRelationsBridge;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Extract cardinality command
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ExtractCardinalitiesCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('schema:extract-cardinalities')
            ->setDescription('Extract properties\' cardinality')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaOrg = new \EasyRdf_Graph();
        $schemaOrg->load(SCHEMA_ORG_RDFA_URL, 'rdfa');
        $goodRelations = new \SimpleXMLElement(GOOD_RELATIONS_OWL_URL, 0, true);

        $goodRelationsBridge = new GoodRelationsBridge($goodRelations);
        $cardinalitiesExtractor = new CardinalitiesExtractor($schemaOrg, $goodRelationsBridge);
        $result = $cardinalitiesExtractor->extract();

        $output->writeln(json_encode($result, JSON_PRETTY_PRINT));
    }
}