<?php

namespace Gettext\Extractors;

use Gettext\Translations;

/**
 * Class to get gettext strings from plain json
 */
class JsonDictionary extends Extractor implements ExtractorInterface
{
    /**
     * {@inheritDoc}
     */
    public static function fromString($string, Translations $translations = null, $file = '')
    {
        if (null === $translations) {
            $translations = new Translations();
        }

        if (($entries = json_decode($string, true))) {
            foreach ($entries as $original => $translation) {
                $translations->insert(null, $original)->setTranslation($translation);
            }
        }

        return $translations;
    }
}
