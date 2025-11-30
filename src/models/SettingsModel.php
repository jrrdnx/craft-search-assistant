<?php

/**
 * Track and manage your users' search history to find popular searches, recent searches, and more in Craft
 *
 * @link      https://github.com/jrrdnx/craft-search-assistant
 * @copyright Copyright (c) 2024 Jarrod D Nix
 */

namespace jrrdnx\searchassistant\models;

use Craft;
use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\App;
use Dxw\CIDR\IPRange;

/**
 * SearchAssistant Settings Model
 *
 * @author    Jarrod D Nix
 * @package   SearchAssistant
 * @since     1.0.0
 */
class SettingsModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var bool
     */
    public bool $enabled = true;

    /**
     * @var bool
     */
    public bool $debugMode = false;

    /**
     * @var string
     */
    public string $pluginName = 'Search Assistant';

    /**
     *
     */
    public array $ipIgnore = [
        ['::1', 'IPv6 localhost'],
        ['127.0.0.1', 'IPv4 localhost']
    ];

    public bool $ignoreCpUsers = true;

    // Public Methods
    // =========================================================================

    public function getEnabled(): string
    {
        return App::parseEnv($this->enabled);
    }

    public function getDebugMode(): string
    {
        return App::parseEnv($this->debugMode);
    }

    public function getPluginName(): string
    {
        return App::parseEnv($this->pluginName);
    }

    public function getIgnoreCpUsers(): bool
    {
        return App::parseEnv($this->ignoreCpUsers);
    }

    protected function defineAttributes()
    {
        return [
            'enabled',
            'debugMode',
            'pluginName',
            'ipIgnore'
        ];
    }

    public function behaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => ['enabled', 'debugMode', 'pluginName'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        $rules[] = [['enabled'], 'boolean'];
        $rules[] = [['enabled'], 'default', 'value' => true];
        $rules[] = [['debugMode'], 'boolean'];
        $rules[] = [['debugMode'], 'default', 'value' => false];
        $rules[] = [['ipIgnore'], 'validateIpCidr'];
        $rules[] = [['ipIgnore'], 'default', 'value' => [
            ['::1', 'IPv6 localhost'],
            ['127.0.0.1', 'IPv4 localhost']
        ]];

        return $rules;
    }

    /**
     * Validate IP/CIDR on save
     */
    public function validateIpCidr($attribute)
    {
        foreach ($this->$attribute as &$row) {
            $result = IPRange::Make($row[0]);
            if ($result->isErr()) {
                $row[0] = ['value' => $row[0], 'hasErrors' => true];
                $this->addError($attribute, Craft::t('ip-restrictor', 'pleaseProvideValidIpCidr'));
            }
        }
    }
}
