<?php


namespace App\Services;

use App\Exceptions\ApiException;
use Hamcrest\Type\IsBoolean;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;

class LabelSetServices
{
    static function validateLabelSetFromRequest(Request $request)
    {
    }

    static function validateLabelSets($labelSets)
    {
        is_bool($labelSets);
    }
}
