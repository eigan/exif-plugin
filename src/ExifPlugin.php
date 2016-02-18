<?php

namespace Tagstore\Plugin;

use Tagstore\Domain\Exif;
use Tagstore\Domain\File;
use Tagstore\Domain\FileContent;
use Tagstore\Plugin\SyncPlugin;

class ExifPlugin extends SyncPlugin
{
    /**
     * Reads exif from files and store on the Exif table
     * @param File $file
     * @param FileContent $photo
     */
    public function onSync(File $file, FileContent $photo)
    {
        if (in_array($file->getExtension(), ["jpg", "JPG", "JPEG"]) == false) {
            return;
        }

        $exifData = exif_read_data($this->filesystem->absolutepath($file->getPath()));

        if (is_array($exifData) == false) {
            return;
        }

        $exif = $photo->getExif();

        if ($exif instanceof Exif == false) {
            $exif = new Exif($photo);
        }

        $exif->update($exifData);
        $photo->setExif($exif);
    }
}
