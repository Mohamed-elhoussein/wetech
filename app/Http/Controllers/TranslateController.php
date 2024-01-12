<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TranslateController extends Controller
{
    public function index(Request $request)
    {
  if (!File::exists(resource_path('lang'))) {
            File::makeDirectory(resource_path('lang'), 0777, true, true);
        }

        $translationsFiles = File::files(resource_path('lang'));
        $translations = [];

        foreach ($translationsFiles as $translationFile) {
            if ($translationFile->getExtension() !== 'json') continue;
            $lang = explode('.', $translationFile->getFilename(), 2)[0];
            $content = json_decode(file_get_contents($translationFile));
            $translations[$lang] = $content;
        }


        return view('translate',compact('translations'));
    }
    public function store(Request $request)
    {
       $langs = array_keys($request->except('_token', 'keys'));
        $translations = [];

        foreach ($request->keys as $index => $key) {
            foreach ($langs as $lang) {
                $translations[$lang][$key] = $request->all()[$lang][$index] ?? '';
            }
        }

        $langsFolder = resource_path('lang');
        foreach ($translations as $lang => $translation) {
            $fp = fopen($langsFolder . DIRECTORY_SEPARATOR . $lang . '.json', 'w');
            fwrite($fp, json_encode($translation, JSON_UNESCAPED_UNICODE));
            fclose($fp);
        }

        return back();
    }
}
