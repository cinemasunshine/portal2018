<?php
/**
 * AzureStorageExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Psr\Container\ContainerInterface;

use Cinemasunshine\Portal\ORM\Entity\File;

/**
 * Azure Storage twig extension class
 */
class AzureStorageExtension extends \Twig_Extension
{
    /** @var ContainerInterface container */
    protected $container;
    
    /**
     * construct
     * 
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('blob_url', [$this, 'blobUrl']),
            new \Twig_Function('file_url', [$this, 'fileUrl']),
        ];
    }
    
    /**
     * Blob URL
     * 
     * Blobへのpublicアクセスを許可する必要があります。
     *
     * @param string $container Blob container name
     * @param string $blob      Blob name
     * @return string
     */
    public function blobUrl(string $container, string $blob)
    {
        $settings = $this->container->get('settings')['storage'];
        $protocol = $settings['secure'] ? 'https' : 'http';
        
        return sprintf(
            '%s://%s.blob.core.windows.net/%s/%s',
            $protocol,
            $settings['account']['name'],
            $container,
            $blob);
    }
    
    /**
     * return file URL
     *
     * @param File $file
     * @return string
     */
    public function fileUrl(File $file)
    {
        return $this->blobUrl(File::getBlobContainer(), $file->getName());
    }
}