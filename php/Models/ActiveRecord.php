<?php
namespace SuperHeroList\Models;

use ReflectionClass;
use ReflectionProperty;
use SuperHeroList\db\Db;

abstract class ActiveRecord
{
    protected static $table;
    protected ?int $id = null;

    public function __construct($id = null)
    {
        if ($id && is_int($id)) $this->id = $id;
    }
    /**
     * Вернуть массив объектов из данных, полученных из бд по запросу
     *  SELECT * FROM TABLE
     */
    public static function all()
    {
        $properties = Db::sql()
        ->select('*')
        ->from(static::$table)
        ->execute();

        if ($properties === null) {
            // log error
            return null;
        }
        $objects = array_map(function($properties) {
            return static::load($properties);
        }, $properties);

        return $objects;
    }

    public static function find($id)
    {
        $properties = Db::sql()
        ->select('*')
        ->from(static::$table)
        ->where(['id' => $id])
        ->execute();

        return static::load($properties);
    }

    public function save()
    {
        if (is_int($this->id) && $this->id > 0) {
            return Db::sql()
            ->update(static::$table, $this->getPublic())
            ->where(['id' => $this->id])
            ->execute();
        } else {
            return Db::sql()
            ->insert(static::$table, $this->getPublic())
            ->execute();
        }
    }

    public function delete()
    {
        return Db::sql()
        ->delete()
        ->from(static::$table)
        ->where(['id' => $this->id])
        ->execute();
    }

    /**
     * Создать объект из массива со свойствами
     */
    public static function load(array $properties)
    {
        if (isset($properties['id']) && is_int($properties['id'])) {
            $object = new static($properties['id']);
        } else {
            $object = new static(null);
        }
        // var_dump($object);
        foreach ($properties as $property => $value) {
            if ($property === 'id') continue;
            if (property_exists(static::class, $property)) {
                $object->$property = $properties["$property"];
            }
        }
        return $object;
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * Получить массив со свойствами объекта
     */
    public function getPublic()
    {
        $ref = new ReflectionClass($this);
        $refProperties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $props = [];
        foreach ($refProperties as $rp) {
            $props[$rp->getName()] = $rp->getValue($this);
        }
        return $props;
    }
}