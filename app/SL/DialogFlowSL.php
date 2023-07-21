<?php

namespace App\SL;

use Illuminate\Support\Facades\Storage;

use Google\Cloud\Dialogflow\V2\Intent;
use Google\Cloud\Dialogflow\V2\IntentsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase\Part;
use Google\Cloud\Dialogflow\V2\Intent\TrainingPhrase;
use Google\Cloud\Dialogflow\V2\Intent\Message\Text;
use Google\Cloud\Dialogflow\V2\Intent\Message;


class DialogFlowSL
{
    private $session;
    private $sessionsClient;

    public function __construct($sessionId = null)
    {
        $test = array('credentials' =>Storage::disk('local')->get('client-secret.json'));
        $this->sessionsClient = new SessionsClient($test);
        $this->session = $this->sessionsClient->sessionName('chatislam', $sessionId ?? uniqid());
    }

    public function getResponse($text)
    {

        // create text input
        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode('en-US');

        // create query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        // get response and relevant info
        $response = $this->sessionsClient->detectIntent($this->session, $queryInput);
        $queryResult = $response->getQueryResult();
        $queryText = $queryResult->getQueryText();
        $intent = $queryResult->getIntent();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfilmentText = $queryResult->getFulfillmentText();
        $this->sessionsClient->close();

       $this->intent_list('chatislam');


        return [
            'queryText' => $queryText,
            'displayName' => $displayName,
            'confidence' => $confidence,
            'fulfilmentText' => $fulfilmentText
        ];


    }


    function intent_list($projectId)
    {
        // get intents
        $intentsClient = new IntentsClient();
        $parent = $intentsClient->agentName($projectId);
        $intents = $intentsClient->listIntents($parent);

        foreach ($intents->iterateAllElements() as $intent) {
            // print relevant info
            print(str_repeat('=', 20) . PHP_EOL);
            printf('Intent name: %s' . PHP_EOL, $intent->getName());
            printf('Intent display name: %s' . PHP_EOL, $intent->getDisplayName());
            printf('Action: %s' . PHP_EOL, $intent->getAction());
            printf('Root followup intent: %s' . PHP_EOL,
                $intent->getRootFollowupIntentName());
            printf('Parent followup intent: %s' . PHP_EOL,
                $intent->getParentFollowupIntentName());
            print(PHP_EOL);

            print('Input contexts: ' . PHP_EOL);
            foreach ($intent->getInputContextNames() as $inputContextName) {
                printf("\t Name: %s" . PHP_EOL, $inputContextName);
            }

            print('Output contexts: ' . PHP_EOL);
            foreach ($intent->getOutputContexts() as $outputContext) {
                printf("\t Name: %s" . PHP_EOL, $outputContext->getName());
            }
        }
        $intentsClient->close();
    }


    public function intent_create($projectId, $displayName, $trainingPhraseParts = [],
                           $messageTexts = [])
    {
        $intentsClient = new IntentsClient();

        // prepare parent
        $parent = $intentsClient->agentName($projectId);

        // prepare training phrases for intent
        $trainingPhrases = [];
        foreach ($trainingPhraseParts as $trainingPhrasePart) {
            $part = (new Part())
                ->setText($trainingPhrasePart);

            // create new training phrase for each provided part
            $trainingPhrase = (new TrainingPhrase())
                ->setParts([$part]);
            $trainingPhrases[] = $trainingPhrase;
        }

        // prepare messages for intent
        $text = (new Text())
            ->setText($messageTexts);
        $message = (new Message())
            ->setText($text);

        // prepare intent
        $intent = (new Intent())
            ->setDisplayName($displayName)
            ->setTrainingPhrases($trainingPhrases)
            ->setMessages([$message]);

        // create intent
        $response = $intentsClient->createIntent($parent, $intent);
        printf('Intent created: %s' . PHP_EOL, $response->getName());

        $intentsClient->close();
    }


}
