<?php

namespace common\behaviors;

use yii\base\InvalidConfigException;
use \yii\behaviors\AttributeBehavior;

/**
 * Class DateToTimeBehavior
 * @package common\behaviors
 */
class DateToTimeBehavior extends AttributeBehavior {

    public $timeAttribute;

    /**
     * @param \yii\base\Event $event
     * @return false|mixed|null|string
     * @throws InvalidConfigException
     */
    public function getValue($event) {

        if (empty($this->timeAttribute)) {
            throw new InvalidConfigException(
                'Can`t find "fromAttribute" property in ' . $this->owner->className()
            );
        }

        if (!empty($this->owner->{$this->attributes[$event->name]})) {
            $this->owner->{$this->timeAttribute} = strtotime(
                $this->owner->{$this->attributes[$event->name]}
            );

            return date('d.m.Y', $this->owner->{$this->timeAttribute});
        } else if (!empty($this->owner->{$this->timeAttribute})
            && is_numeric($this->owner->{$this->timeAttribute})
        ) {
            $this->owner->{$this->attributes[$event->name]} = date(
                'd.m.Y',
                $this->owner->{$this->timeAttribute}
            );

            return $this->owner->{$this->attributes[$event->name]};
        }

        return null;
    }
}