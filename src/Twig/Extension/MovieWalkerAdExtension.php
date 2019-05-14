<?php
/**
 * MovieWalkerAdExtension.php
 *
 * @author Atsushi Okui <okui@motionpicture.jp>
 */

namespace Cinemasunshine\Portal\Twig\Extension;

use Cinemasunshine\Portal\ORM\Entity\Page;
use Cinemasunshine\Portal\ORM\Entity\Theater;

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
            new \Twig_Function('is_mw_ad_support', [$this, 'isSupport']),
            new \Twig_Function('mw_ad_script', [$this, 'getAdScript'], [ 'is_safe' => ['html'] ]),
            new \Twig_Function('mw_ad', [$this, 'getAd'], [ 'is_safe' => ['html'] ]),
        ];
    }
    
    /**
     * is support
     *
     * @return boolean
     */
    public function isSupport()
    {
        return $this->settings['support'];
    }
    
    /**
     * return ad script
     *
     * @param Page $target
     * @return string
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function getAdScript($target)
    {
        if (!$this->isSupport()) {
            throw new \LogicException('disabled');
        };
        
        if ($target instanceof Page) {
            /** @var Page $target */
            $adName  = $this->getPageAdName($target);
            $adDivId = $this->getPageAdDivId($target);
        } elseif ($target instanceof Theater) {
            /** @var Theater $target */
            $adName  = $this->getTheaterAdName($target);
            $adDivId = $this->getTheaterAdDivId($target);
        } else {
            throw new \InvalidArgumentException('invalid target.');
        }
        
        return $this->getAdScriptTag($adName, $adDivId);
    }
    
    /**
     * return ad script tag
     *
     * @param string $name
     * @param string $divId
     * @return string
     */
    protected function getAdScriptTag(string $name, string $divId)
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
        
        return sprintf($tag, $name, $divId);
    }
    
    /**
     * return ad
     *
     * @param Page $target
     * @return string
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function getAd($target)
    {
        if (!$this->isSupport()) {
            throw new \LogicException('disabled');
        };
        
        if ($target instanceof Page) {
            /** @var Page $target */
            $adName  = $this->getPageAdName($target);
            $adDivId = $this->getPageAdDivId($target);
        } elseif ($target instanceof Theater) {
            /** @var Theater $target */
            $adName  = $this->getTheaterAdName($target);
            $adDivId = $this->getTheaterAdDivId($target);
        } else {
            throw new \InvalidArgumentException('invalid target.');
        }
        
        return $this->getAdTab($adName, $adDivId);
    }
    
    /**
     * return ad tag
     *
     * @param string $name
     * @param string $divId
     * @return string
     */
    protected function getAdTab(string $name, string $divId)
    {
        $tag = <<<TAG
<!-- %s -->
<div id='%s' style='height:280px; width:336px;'>
<script>
googletag.cmd.push(function() { googletag.display('%s'); });
</script>
</div>
TAG;
        return sprintf($tag, $name, $divId, $divId);
    }
    
    /**
     * return Ad name for page
     *
     * @param Page $page
     * @return string|null
     */
    protected function getPageAdName(Page $page)
    {
        $pages = $this->settings['page'];
        
        return $pages[$page->getId()]['name'] ?? null;
    }
    
    /**
     * return Ad div_id for page
     *
     * @param Page $page
     * @return string
     */
    protected function getPageAdDivId(Page $page)
    {
        $pages = $this->settings['page'];
        
        return $pages[$page->getId()]['div_id'] ?? null;
    }
    
    /**
     * return Ad name for theater
     *
     * @param Theater $theater
     * @return string|null
     */
    protected function getTheaterAdName(Theater $theater)
    {
        $theaters = $this->settings['theater'];
        
        return $theaters[$theater->getId()]['name'] ?? null;
    }
    
    /**
     * return Ad div_id for theater
     *
     * @param Theater $theater
     * @return string
     */
    protected function getTheaterAdDivId(Theater $theater)
    {
        $theaters = $this->settings['theater'];
        
        return $theaters[$theater->getId()]['div_id'] ?? null;
    }
}
