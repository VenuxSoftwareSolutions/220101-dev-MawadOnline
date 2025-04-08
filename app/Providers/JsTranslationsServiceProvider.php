<?php

// @see https://gist.github.com/nadyshalaby/669e6f251b942e7d91576f49cda6e879

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class JsTranslationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot()
    {
        Blade::directive('translations', function () {
            $translations = [];

            // Get all of the language files for the given locale
            $files = array_merge(
                glob(lang_path(app()->getLocale() . "/*.php")),
                glob(lang_path(app()->getLocale() . ".json"))
            );

            // Loop through each language file and merge the translations
            foreach ($files as $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                if ($extension === 'php') {
                    $translations = array_merge($translations, require $file);
                } elseif ($extension === 'json') {
                    $translations = array_merge($translations, json_decode(file_get_contents($file), true));
                }
            }

            return "<script>\n" .
                "var translations = " . json_encode($translations) . ";\n" .
                "function __(key, parameters = []) {\n" .
                "    var keys = key.split('.');\n" .
                "    var result = translations;\n" .
                "    for (var i = 0; i < keys.length; i++) {\n" .
                "        if (!result.hasOwnProperty(keys[i])) {\n" .
                "            return key;\n" .
                "        }\n" .
                "        result = result[keys[i]];\n" .
                "    }\n" .
                "    if (typeof result === 'string' && parameters.length) {\n" .
                "        parameters.forEach(function (param, index) {\n" .
                "            result = result.replace(':' + index, param);\n" .
                "        });\n" .
                "    }\n" .
                "    return result;\n" .
                "}\n" .
                "</script>\n";
        });
    }

}
