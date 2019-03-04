<?php

namespace Gettext\Utils;

class PhpFunctionsScanner extends FunctionsScanner
{
    protected $tokens;

    /**
     * Constructor
     *
     * @param string $code The php code to scan
     */
    public function __construct($code)
    {
        $this->tokens = token_get_all($code);
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        $count = count($this->tokens);
        $bufferFunctions = [];
        $functions = [];

        for ($k = 0; $k < $count; $k++) {
            $value = $this->tokens[$k];

            //close the current function
            if (is_string($value)) {
                if (')' === $value && isset($bufferFunctions[0])) {
                    $functions[] = array_shift($bufferFunctions);
                }

                continue;
            }

            //add an argument to the current function
            if (isset($bufferFunctions[0]) && (T_CONSTANT_ENCAPSED_STRING === $value[0])) {
                $val = $value[1];

                if ('"' === $val[0]) {
                    $val = str_replace('\\"', '"', $val);
                } else {
                    $val = str_replace("\\'", "'", $val);
                }

                $bufferFunctions[0][2][] = mb_substr($val, 1, -1);
                continue;
            }

            //new function found
            if ((T_STRING === $value[0]) && is_string($this->tokens[$k + 1]) && ('(' === $this->tokens[$k + 1])) {
                array_unshift($bufferFunctions, [$value[1], $value[2], []]);
                $k++;

                continue;
            }
        }

        return $functions;
    }
}
