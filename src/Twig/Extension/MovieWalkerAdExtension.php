<?php
/**
 * MovieWalkerAdExtension.php
 * 
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Page;

/**
 * MovieWalker Ad twig extension class
 */
class MovieWalkerAdExtension extends \Twig_Extension
{
    /** @var array */
    protected $settings;
    
    /**
     * construct
     * 
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }
    
    /**
     * get functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('mw_ad_script', [$this, 'getAdScript'], [ 'is_safe' => ['html'] ]),
            new \Twig_Function('mw_ad', [$this, 'getAd'], [ 'is_safe' => ['html'] ]),
        ];
    }
    
    /**
     * return ad script
     *
     * @param Page $target
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAdScript($target)
    {
        if ($target instanceof Page) {
            /** @var Page $target */
            $adSlot = $this->getPageAdSlot($target);
            $adId = $this->getPageAdId($target);
        } else {
            throw new \InvalidArgumentException('invalid target.');
        }
        
        return $this->getAdScriptTag($adSlot, $adId);
    }
    
    /**
     * return ad script tag
     *
     * @param string $slot
     * @param string $id
     * @return string
     */
    protected function getAdScriptTag(string $slot, string $id)
    {
        $tag = <<<TAG
<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
<script>
  var googletag = googletag || {};
  googletag.cmd = googletag.cmd || [];
</script>

<script>
  googletag.cmd.push(function() {
    googletag.defineSlot('%s', [336, 280], '%s').addService(googletag.pubads());
    googletag.pubads().enableSingleRequest();
    googletag.enableServices();
  });
</script>
TAG;
        
        return sprintf($tag, $slot, $id);
    }
    
    /**
     * return ad
     *
     * @param Page $target
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAd($target)
    {
        if ($target instanceof Page) {
            /** @var Page $target */
            $adSlot = $this->getPageAdSlot($target);
            $adId = $this->getPageAdId($target);
        } else {
            throw new \InvalidArgumentException('invalid target.');
        }
        
        return $this->getAdTab($adSlot, $adId);
    }
    
    /**
     * return ad tag
     *
     * @param string $slot
     * @param string $id
     * @return string
     */
    protected function getAdTab(string $slot, string $id)
    {
        $tag = <<<TAG
<!-- %s -->
<div id='%s' style='height:280px; width:336px;'>
<script>
googletag.cmd.push(function() { googletag.display('%s'); });
</script>
</div>
TAG;
        return sprintf($tag, $slot, $id, $id);
    }
    
    /**
     * return ad slot for page
     * 
     * @param Page $page
     * @return string|null
     */
    protected function getPageAdSlot(Page $page)
    {
        $slots = $this->settings['page']['slots'];
        
        return $slots[$page->getId()] ?? null;
    }
    
    /**
     * return ad id for page
     * 
     * @param Page $page
     * @return string
     */
    protected function getPageAdId(Page $page)
    {
        $ids = $this->settings['page']['ids'];
        
        return $ids[$page->getId()] ?? null;
    }
}