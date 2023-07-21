<?php

namespace App\Http\Controllers;


use App\Models\Hadith;
use App\SL\DialogFlowSL;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Illuminate\Support\Facades\Storage;

class DialogFlowController extends Controller
{


    public function lol()
    {
        // Laravel Access
        $test = array('credentials' => Storage::disk('local')->get('client-secret.json'));
        $sessionsClient = new SessionsClient($test);
        $session = $sessionsClient->sessionName('chatislam', uniqid());
        printf('Session path: %s' . PHP_EOL, $session);

        $text = 'Should ';

        // create text input
        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode('en-US');

        // create query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        // get response and relevant info
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();
        $queryText = $queryResult->getQueryText();
        $intent = $queryResult->getIntent();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfilmentText = $queryResult->getFulfillmentText();

        // output relevant info
        print(str_repeat("=", 20) . PHP_EOL);
        printf('Query text: %s' . PHP_EOL, $queryText);
        printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName,
            $confidence);
        print(PHP_EOL);
        printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);

        $sessionsClient->close();

    }

    public function test()
    {

//        Hadith::where('id', 1)->update(['hadith' => 'test']);

        $dialogFlowSL = new DialogFlowSL();
        // $dialogFlowSL->getResponse('Hello');

        return $dialogFlowSL->intent_create('chatislam', 'test');

    }

    public function storeDataFromJSON()
    {
        $filename = 'clean_data_v3_summarized.json';
        $fileContents = file_get_contents(base_path($filename));
        $jsonData = json_decode($fileContents, true);


//        $dialogFlowSL = new DialogFlowSL();

        $count = 0;
        $questionSummary = array();

        foreach ($jsonData as $key => $value) {
//            $question = $value['question'];
//            $answer = $value['summary'];
//            if (strlen($question) <= 150) {
//                $dialogFlowSL->intent_create('chatislam', 'q' . $count, [$question], [$answer]);
//                $count++;
//            }
            $question = $value['question'];
            $answer = $value['summary'];
            $ref = $value['url'];

            $questionSummary[] = array('question' => $question, 'summary' => $answer,'ref' => $ref);

            if($count == 240)
            {
                return $questionSummary;
                break;
            }

            $count++;
        }



        return 'done';

    }

    public function filter()
    {
        $filename = '11clean_data_v3_summarized.json';
        $fileContents = file_get_contents(base_path($filename));
        $jsonData = json_decode($fileContents, true);


        return $jsonData;
        //create an array of only question and summary
        $questionSummary = array();
        foreach ($jsonData as $key => $value) {
            $question = $value['question'];
            $answer = $value['summary'];
            $questionSummary[] = array('question' => $question, 'summary' => $answer);
        }

        return $questionSummary;


    }
}
