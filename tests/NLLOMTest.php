<?php

use PHPUnit\Framework\TestCase;

class NLLOMTest extends TestCase
{
    public function testCreate()
    {
        // Do not output spaces in document, so it's easier to match with correct xml file
        $lom = new \Kennisnet\NLLOM\NLLOM([
            'preserve_whitespace' => false,
            'format_output' => false
        ]);

        $lom->setGeneralTitle('Test <strike>test</strike>');
        $lom->setGeneralDescription('één beschrijving met speciale tekens');

        $lom->addGeneralIdentifier('uri', 'urn:isbn:9789034553966');
        $lom->addGeneralIdentifier('uri', 'https://delen.edurep.nl/download.php?id=1c1aad84-a96b-4efe-8d23-25c870566497&test=1');

        $lom->addGeneralLanguage('nl');
        $lom->addGeneralLanguage('en');
        $lom->addGeneralKeyword('Nederlands');
        $lom->addGeneralKeyword('Engels');
        $lom->setGeneralAggregationLevel(2);

        $lom->setLifecycleVersion('07122005 124436');
        $lom->setLifecycleStatus('final');

        $vcard = <<<VCARD
BEGIN:VCARD
VERSION:3.0
N:Digischool
FN:Digischool
ORG:Digischool
EMAIL;TYPE=INTERNET,PREF:redactie@digischool.nl
URL:http://www.digischool.nl/
END:VCARD
VCARD;

        $dt = new \DateTime('1999-06-01');

        $lom->setLifecycleAuthor($dt, $vcard, 'Omschrijving');
        $lom->setPublisher($dt);

        $lom->setCreator($dt);
        $lom->setMetametadataValidator($dt, $vcard);
        $lom->setMetametadataLanguage('nl');

        $lom->setTechnicalFormat('application/pdf');
        $lom->setTechnicalSize(555666);
        $lom->setTechnicalLocation('https://delen.edurep.nl/download.php?id=1c1aad84-a96b-4efe-8d23-25c870566497&test=1');
        $lom->setTechnicalDuration('PT1H30M', 'Tijd inc aftiteling');

        $lom->addEducationalLearningResourceType('http://purl.edustandaard.nl/vdex_learningresourcetype_czp_20060628.xml', 'open opdracht');
        $lom->addEducationaIntendedUserRole('learner');
        $lom->addEducationalContext('http://purl.edustandaard.nl/vdex_context_czp_20060628.xml', 'VO');
        $lom->addEducationalTypicalAgeRange('8-13');
        $lom->setEducationalDifficulty('http://purl.edustandaard.nl/vdex_difficulty_lomv1p0_20060628.xml', 'very easy');

        $lom->setRightsCost('no');
        $lom->setRightsCopyright('http://purl.edustandaard.nl/copyrightsandotherrestrictions_nllom_20110411', 'cc-by-30');
        $lom->setRightsDescription('Anderen mogen het werk gebruiken');

        $lom->addRelation('embed', 'uri', 'http://www.vimdeo.com/dummy1', [
            [
                'language' => 'nl',
                'value' => 'Test 1'
            ],
            [
                'language' => 'en',
                'value' => 'Test 2'
            ],
        ]);

        $lom->addRelation('basedon', 'uri', 'http://www.vimdeo.com/dummy2', [
            [
                'language' => 'nl',
                'value' => 'Test 3'
            ],
            [
                'language' => 'en',
                'value' => 'Test 4'
            ],
        ]);

        $lom->addClassification([
            'source' => 'http://download.edustandaard.nl/vdex/vdex_classification_purpose_czp_20060628.xml',
            'value' => 'discipline'
        ], [[
            'source' => 'http://download.edustandaard.nl/vdex/vdex_classification_vakaanduidingen_vo_20071115.xml',
            'taxons' => [
                [
                    'id' => 'Duits',
                    'value' => 'Duits',
                    'language' => 'nl'
                ]
            ]
        ]]);

        $lom->addClassification([
            'source' => 'LOMv1.0',
            'value' => 'educational level'
        ], [
            [
                'source' => 'http://download.edustandaard.nl/vdex/vdex_classification_educationallevel_czp_20071115.xml',
                'taxons' => [
                    [
                        'id' => 'VO',
                        'value' => 'Voortgezet Onderwijs',
                        'language' => 'nl'
                    ],
                    [
                        'id' => 'vmbo',
                        'value' => 'VMBO',
                        'language' => 'nl'
                    ]]
            ],
            [
                'source' => 'http://download.edustandaard.nl/vdex/vdex_classification_educationallevel_czp_20071115.xml',
                'taxons' => [
                    [
                        'id' => 'Duits',
                        'value' => 'Duits',
                        'language' => 'nl'
                    ],
                    [
                        'id' => 'vmbo_kl2',
                        'value' => 'VMBO kaderberoepsgerichte leerweg, 2',
                        'language' => 'nl'
                    ]
                ]
            ]
        ]);


        $result = $lom->saveAsXML();

        $correctResult = file_get_contents(__DIR__.'/result.xml');

        // Dump result when setting up new tests
        /*
        $fh = fopen('text.xml', 'w');
        fwrite($fh, $result);
        fclose($fh);
        */

        $this->assertEquals($correctResult, $result);
    }

    public function testEmptyTechnical()
    {
        // Do not output spaces in document, so it's easier to match with correct xml file
        $lom = new \Kennisnet\NLLOM\NLLOM([
            'preserve_whitespace' => false,
            'format_output' => false
        ]);

        $lom->setGeneralTitle('Test2');
        $lom->setGeneralDescription('één beschrijving met speciale tekens');

        $lom->addGeneralIdentifier('uri', 'urn:isbn:9789034553966');
        $lom->setGeneralAggregationLevel(2);

        $lom->setLifecycleVersion('07122005 124436');

        $dt = new \DateTime('1999-06-01');
        $lom->setPublisher($dt);

        $lom->setCreator($dt);
        $lom->setMetametadataLanguage('nl');

        $lom->setRightsCost('no');
        $lom->setRightsCopyright('http://purl.edustandaard.nl/copyrightsandotherrestrictions_nllom_20110411', 'cc-by-30');
        $lom->setRightsDescription('Anderen mogen het werk gebruiken');

        $result = $lom->saveAsXML();

        $correctResult = file_get_contents(__DIR__.'/result_no_technical.xml');

        $this->assertEquals($correctResult, $result);
    }
}
