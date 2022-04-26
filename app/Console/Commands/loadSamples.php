<?php

namespace App\Console\Commands;

use App\Models\SampleType;
use ArrayObject;
use Illuminate\Console\Command;

class loadSamples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected
        $signature = 'db:load-samples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected
        $description = 'Load the soil samples from beCrop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public
    function handle()
    {

        $authKey = env('API_BECROP_KEY', '');
        $url = "https://api.biomemakers.com/v1/products/becrop/sample_types?locale=%s";
        $languages = array('en', 'es', 'fr');
        $output_samples = array();

        if(!$authKey){
            $this->error("No API_BECROP_KEY_PROVIDED");
            return -1;
        }

        foreach ($languages as $language) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, sprintf($url, $language));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $authKey
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            if (!$result = curl_exec($curl)) {
                trigger_error(curl_error($curl));
                return 1;
            }
            curl_close($curl);
            $json = json_decode($result, true);
            if(array_key_exists('error', $json)){
                $this->error("API brecrop authentification error.");
                return 1;

            }
            array_push($output_samples, $json);
        }

        $obj = new ArrayObject($output_samples[0]);
        $itEn = $obj->getIterator();
        $obj = new ArrayObject($output_samples[1]);
        $itEs = $obj->getIterator();
        $obj = new ArrayObject($output_samples[2]);
        $itFr = $obj->getIterator();

        $localSamples = SampleType::all();

        while ($itEn->valid()) {
            $key = $itEn->key();
            $enRemoteSample = $itEn->current();
            $code = $enRemoteSample["id"];
            $enName = $enRemoteSample["name"];
            $esName = $itEs->current()["name"];
            $frName = $itFr->current()["name"];

            $localSample = $localSamples->where('code', $code);

            if ($localSample->isEmpty()) {

                SampleType::create([
                    'code' => $code,
                    'name' => $enName,
                    'es_name' => $esName,
                    'fr_name' => $frName
                ]);
            }else if($localSample->count() == 1){
                $soilFound = $localSample->first();
                $soilFound->name = $enName;
                $soilFound->es_name = $esName;
                $soilFound->fr_name = $frName;
                $soilFound->save();

                $localSamples.forget($key);
            }else{
                $this->error("Corrupted database");
                return 1;
            }
            $itEn->next();
            $itEs->next();
            $itFr->next();
        }
        foreach ($localSamples as $deletedLocalSample){
            $deletedLocalSample->delete();
    }
        return 0;
    }
}
