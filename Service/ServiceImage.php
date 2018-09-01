<?php
/*
 * (c) 2018: 975L <contact@975l.com>
 * (c) 2018: Laurent Marquet <laurent.marquet@laposte.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace c975L\ServicesBundle\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use c975L\EventsBundle\Entity\Event;
use c975L\ServicesBundle\Service\ServiceImageInterface;

/**
 * Services related to ServiceImageInterface
 * @author Laurent Marquet <laurent.marquet@laposte.net>
 * @copyright 2017 975L <contact@975l.com>
 */
class ServiceImage implements ServiceImageInterface
{
    /**
     * Stores Container
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $file)
    {
        $fs = new Filesystem();

        if ($fs->exists($file)) {
            $fs->remove($file);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFolder(string $folder)
    {
        $rootDir = $this->container->getParameter('kernel.root_dir');
        if (false === strrpos($folder, '/')) {
            $folder .= '/';
        }

        if (substr(Kernel::VERSION, 0, 1) == 4) {
            return $rootDir . '/../public/images/' . $folder;
        }

        return $rootDir . '/../web/images/' . $folder;
    }

    /**
     * {@inheritdoc}
     */
    public function resize($file, string $folder, string $filename, string $format = 'jpg', int $finalHeight = 400, int $compression = 75)
    {
        if (null !== $file) {
            //Defines data
            $extension = is_object($file) ? strtolower($file->guessExtension()) : substr($file, strrpos($file, '.') + 1, 3);

            //Checks if extension and format are supported
            if (in_array($format, array('jpg', 'png')) && in_array($extension, array('jpeg', 'jpg', 'png'))) {
                $fileData = getimagesize($file);
                //Also used to reduces poster issued from video
                $tempFilename = is_object($file) ? $file->getRealPath() : $file;
                //Use of of @ avoids errors of type IFD bad offset
                $exifData = @exif_read_data($tempFilename, 0, true);

                //Creates the final picture
                if (is_array($fileData)) {
                    //Defines data
                    $width = $fileData[0];
                    $height = $fileData[1];
                    $compression = 'png' == $format && $compression > 9 ? 6 : $compression;

                    //Resizes image
                    $newHeight = $finalHeight;
                    $newWidth = (int) round(($width * $newHeight) / $height);
                    $degree = 0;

                    //JPG format (input)
                    if (2 == $fileData[2]) {
                        $fileSource = imagecreatefromjpeg($tempFilename);
                        //Rotates (if needed)
                        if (isset($exifData['IFD0']['Orientation'])) {
                            switch ($exifData['IFD0']['Orientation']) {
                                case 1:
                                    $degree = 0;
                                    break;
                                case 3:
                                    $degree = 180;
                                    break;
                                case 6:
                                    $degree = 270;
                                    $newWidth = (int) round(($height * $newHeight) / $width);
                                    break;
                                case 8:
                                    $degree = 90;
                                    $newWidth = (int) round(($height * $newHeight) / $width);
                                    break;
                            }
                            $fileSource = imagerotate($fileSource, $degree, 0);
                        }
                    //PNG format (input)
                    } elseif (3 == $fileData[2]) {
                        $fileSource = imagecreatefrompng($tempFilename);
                    }

                    //Resizes
                    if (isset($fileSource)) {
                        $newPicture = imagecreatetruecolor($newWidth, $newHeight);
                        //JPG format (output) white background
                        if ('jpg' == $format) {
                            $whiteBackground = imagecolorallocate($newPicture, 255, 255, 255);
                            imagefill($newPicture, 0, 0, $whiteBackground);
                        }

                        //Rotates
                        if ($degree == 90 || $degree == 270) {
                            imagecopyresampled($newPicture, $fileSource, 0, 0, 0, 0, $newWidth, $newHeight, $height, $width);
                        } else {
                            imagecopyresampled($newPicture, $fileSource, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                        }

                        //Saves the picture JPG
                        if ('jpg' == $format) {
                            imagejpeg($newPicture, $tempFilename, $compression);
                        //Saves the picture PNG
                        } elseif ('png' == $format) {
                            imagepng($newPicture, $tempFilename, $compression);
                        }

                        //Saves the file in the right place
                        imagedestroy($newPicture);
                        $file->move($folder, str_replace(array('.jpg', '.jpeg', '.png'), '.' . $format, $filename));

                        return true;
                    }
                }
            }
        }

        return false;
    }
}
