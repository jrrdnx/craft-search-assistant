<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * SearchAssistantAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class SearchAssistantAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@jrrdnx/searchassistant/assetbundles/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/SearchAssistant.js',
        ];

        $this->css = [
            'css/SearchAssistant.css',
        ];

        parent::init();
    }
}
