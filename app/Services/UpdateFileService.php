<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class UpdateFileService
{
    function updateFile($pathldImage,$oldLogo,$requestFileName)
    {
        if (request()->hasFile($requestFileName)) {
            $logo = getdate()['year'] . getdate()['yday'] . time() . '.' . request()->logo->extension();
            if(Storage::exists($pathldImage))
            {
                Storage::delete($pathldImage);
            }
        } else {
            $logo = $oldLogo;
        }
        return $logo;
    }

    function deleteFile($pathldImage)
    {
        if(Storage::exists($pathldImage))
        {
            Storage::delete($pathldImage);
        }
    }

}
