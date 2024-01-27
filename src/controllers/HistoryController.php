<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\controllers;

use jrrdnx\searchassistant\SearchAssistant;

use craft\web\Controller;

/**
 * History Controller
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class HistoryController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|int|bool $allowAnonymous = false;

    // Public Methods
	// =========================================================================

	/**
     * Handle a GET request going to our plugin's index action URL,
     * e.g.: actions/search-assistant
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->renderTemplate(
            'search-assistant/index',
            [
                'pluginName' => SearchAssistant::$plugin->getSettings()->getPluginName()
            ]
        );
    }
    public function actionProVersionRequired()
    {
        return $this->renderTemplate(
            'search-assistant/proVersionRequired',
            [
                'pluginName' => SearchAssistant::$plugin->getSettings()->getPluginName()
            ]
        );
    }
}
