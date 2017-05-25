<?php
/*
 * Copyright 2015 Sven Sanzenbacher
 *
 * This file is part of the naucon package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Naucon\Logger;

/**
 * Format Helper Class
 *
 * @package    Logger
 * @author     Sven Sanzenbacher
 */
class FormatHelper
{
    /**
     * @param       string      $sting              string
     * @param	    int         $stringLength       string lenght
     * @param	    string      $etc                optional et cetera string eg. "..."
     * @param	    bool        $withinWord         optional truncate within word, default = false
     * @return	    string                          string with new length
     */
    public function truncate($sting, $stringLength, $etc = '...', $withinWord = false)
    {
        $sting			= html_entity_decode((string)$sting);	// eliminate html code
        $stringLength 	= (int)$stringLength;

        // check on reasonable string length
        if ( $stringLength > 0)
        {
            // execute truncate on strings witch are longer than definied string length.
            if ( strlen($sting) > $stringLength )
            {
                // consider et cetera string on string length
                $stringLength -= strlen($etc);

                // do not truncate within a word. Truncate on last space in string.
                if (!$withinWord)
                {
                    $stringLength = strrpos(substr($sting, 0, $stringLength), ' ');
                }

                return rtrim(substr($sting, 0, $stringLength) ) . $etc;	// eliminate all spaces at the end of the string
            }
            else
            {
                return $sting;
            }
        }
        return '';
    }

    /**
     * @param       string      $string                 string
     * @param	    int         $newStringLength        string lenght
     * @param	    string      $padding                optional padding eg. " "
     * @return	    string	                            string with new length
     */
    public function pad($string, $newStringLength, $padding=' ')
    {
        $string 	     = (string)$string;
        $newStringLength = (int)$newStringLength;
        $oldStringLength = strlen($string);
        $padding		 = (string)$padding;

        if ($newStringLength > 0) {
            if ($oldStringLength > $newStringLength ) {
                return $this->truncate($string, $newStringLength, '', true);
            } elseif ($oldStringLength == $newStringLength ) {
                return $string;
            } else {
                $newStringLength -= $oldStringLength;
                for ($i=1; $i <= $newStringLength; $i++) {
                    $string.= $padding;
                }
                return $string;
            }
        }
        return '';
    }
}