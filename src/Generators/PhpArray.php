<?php

namespace Gettext\Generators;

use Gettext\Translations;

class PhpArray extends Generator implements GeneratorInterface
{
    /**
     * {@inheritDoc}
     */
    public static function toString(Translations $translations)
    {
        $array = self::toArray($translations);

        return '<?php return ' . var_export($array, true) . '; ?>';
    }

    /**
     * Generates an array with the translations
     *
     * @param Translations $translations
     *
     * @return array
     */
    public static function toArray(Translations $translations)
    {
        $array = [];

        $context_glue = "\004";

        foreach ($translations as $translation) {
            $key = ($translation->hasContext() ? $translation->getContext() . $context_glue : '') . $translation->getOriginal();
            $entry = [$translation->getPlural(), $translation->getTranslation()];

            if ($translation->hasPluralTranslation()) {
                $entry = array_merge($entry, $translation->getPluralTranslation());
            }

            $array[$key] = $entry;
        }

        $domain = $translations->getDomain() ?: 'messages';
        $lang = $translations->getLanguage() ?: 'en';

        $fullArray = [
            $domain => [
                '' => [
                    'domain' => $domain,
                    'lang' => $lang,
                    'plural-forms' => 'nplurals=2; plural=(n != 1);',
                ],
            ],
        ];

        if (null !== $translations->getHeader('Plural-Forms')) {
            $fullArray[$domain]['']['plural-forms'] = $translations->getHeader('Plural-Forms');
        }

        $fullArray[$domain] = array_merge($fullArray[$domain], $array);

        return $fullArray;
    }
}
