<?php
/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant;

use jrrdnx\searchassistant\elements\HistoryElement;
use jrrdnx\searchassistant\models\SettingsModel;
use jrrdnx\searchassistant\records\HistoryRecord;
use jrrdnx\searchassistant\services\HistoryService;
use jrrdnx\searchassistant\variables\CraftVariableBehavior;
use jrrdnx\searchassistant\widgets\PopularSearchesWidget;
use jrrdnx\searchassistant\widgets\RecentSearchesWidget;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\DefineBehaviorsEvent;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\events\SearchEvent;
use craft\helpers\FileHelper;
use craft\helpers\UrlHelper;
use craft\services\Dashboard;
use craft\services\Elements;
use craft\services\Gc;
use craft\services\Plugins;
use craft\services\Search;
use craft\services\UserPermissions;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use yii\base\Event;

/**
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 *
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class SearchAssistant extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var SearchAssistant
     */
    public static $plugin;

    public const EDITION_LITE = 'lite';
    public const EDITION_PRO = 'pro';

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    public bool $hasCpSection = true;
    public bool $hasCpSettings = true;

    // Public Methods
    // =========================================================================

    public static function editions(): array
    {
        return [
            self::EDITION_LITE,
            self::EDITION_PRO,
        ];
    }

    public function init()
    {
        if(Craft::$app->getRequest()->getIsConsoleRequest())
		{
			$this->controllerNamespace = 'jrrdnx\searchassistant\console\controllers';
		}

		parent::init();
        self::$plugin = $this;

        $this->setComponents([
			'history' => HistoryService::class,
		]);

        // Redirect to plugin settings after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    if(!Craft::$app->getRequest()->getIsConsoleRequest()) {
                        Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('settings/plugins/search-assistant'))->send();
                    }
                }
            }
        );

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                if($this->is(self::EDITION_PRO)) {
                    $event->rules['search-assistant'] = 'search-assistant/history/index';
                } else {
                    $event->rules['search-assistant'] = 'search-assistant/history/pro-version-required';
                }
            }
        );

        // Register our elements
        Event::on(
            Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = HistoryElement::class;
            }
        );

        // Opt-in to garbage collection
        Event::on(
            Gc::class,
            Gc::EVENT_RUN,
            function(Event $event) {
                // Delete `elements` table rows without peers in our custom table
                Craft::$app->getGc()->deletePartialElements(
                    HistoryElement::class,
                    HistoryRecord::tableName(),
                    'id',
                );
            }
        );

        // Register our widgets
        Event::on(
            Dashboard::class,
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function (RegisterComponentTypesEvent $event) {
                if($this->is(self::EDITION_PRO)) {
                    $event->types[] = PopularSearchesWidget::class;
                    $event->types[] = RecentSearchesWidget::class;
                }
            }
        );

        // Track searches
        Event::on(
            Search::class,
            Search::EVENT_AFTER_SEARCH,
            function(SearchEvent $event) {
                $this->history->track($event);
            }
        );

        // User permissions
        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions[] = [
                    'heading' => $this->getSettings()->getPluginName(),
                    'permissions' => [
                        'searchAssistant:viewFullHistory' => [
                            'label' => Craft::t('search-assistant', 'viewFullHistory'),
                            'nested' => [
                                'searchAssistant:canChangeStatus' => [
                                    'label' => Craft::t('search-assistant', 'canChangeStatus'),
                                ],
                                'searchAssistant:canDelete' => [
                                    'label' => Craft::t('search-assistant', 'canDelete'),
                                ],
                            ],
                        ],
                    ],
                ];
            }
        );

        // Add Craft variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_DEFINE_BEHAVIORS,
            function(DefineBehaviorsEvent $e) {
                $e->sender->attachBehaviors([
                    CraftVariableBehavior::class,
                ]);
            }
        );

		Craft::info(
            Craft::t(
                'search-assistant',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
	}

	/**
     * Build the sidebar nav
     *
     * @return \craft\base\Plugin|null
     */
	public function getCpNavItem(): ?array
	{
		$navItem = parent::getCpNavItem();

        if($this->is(self::EDITION_PRO) && $this->getSettings()->getEnabled()) {
            $navItem['label'] = $this->getSettings()->getPluginName();

            $navItem['icon'] = "@jrrdnx/searchassistant/icon-mask.svg";
        }

		return $navItem;
	}

    /**
     * Create and return the model used to store the plugin's settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel(): ?Model
    {
        return new SettingsModel();
    }

    /**
     * Return the rendered settings HTML
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'search-assistant/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

	/**
	 * Log plugin actions
	 *
	 * @return void
	 */
	public static function log($message): void
	{
        $today = new \DateTime();

		$file = Craft::getAlias('@storage/logs/search-assistant-' . $today->format('Y-m-d') . '.log');
		$log = $today->format('Y-m-d H:i:s').' '.$message."\n";

		FileHelper::writeToFile($file, $log, ['append' => true]);
	}
}
