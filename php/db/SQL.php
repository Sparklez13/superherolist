<?php
namespace SuperHeroList\db;
/**
 * QueryBuilder для создания sql запросов
 */
class SQL
{
    public function __construct(private string $statement = '', private array $params = [])
    {
        
    }

    public function select (string|array $columns)
    {
        if (is_array($columns)) {
            $selects = implode(',', $columns);
        }
        $this->statement .= " SELECT $columns ";
        return $this;
    }

    public function from (string $table)
    {
        $this->statement .= " FROM $table ";
        return $this;
    }

    public function update(string $table, string|array $values)
    {
        $this->statement .= " UPDATE $table ";
        if (is_array($values)) {
            $valuesStr = implode(',', array_map(fn($k) => "$k=:{$k}", array_keys($values)));
        }
        $this->statement .= " SET $valuesStr ";
        $this->params  = array_merge($this->params, $values);
        return $this;
    }

    public function insert(string $table, array $values)
    {
        $columns = implode(',', array_keys($values));
        $bindings = implode(',', array_map(fn ($k) => ":{$k}", array_keys($values)));
        $this->statement .= " INSERT INTO $table ({$columns}) VALUES ({$bindings}) ";
        $this->params = array_merge($this->params, $values);
        return $this;
    }

    public function where(array $conditionals)
    {
        $condStr = implode(',', array_map(fn($k) => "$k=:{$k}", array_keys($conditionals)));

        $this->statement .= " WHERE $condStr";
        $this->params = array_merge($this->params, $conditionals);
        return $this;
    }

    public function delete()
    {
        $this->statement .= " DELETE ";
        return $this;
    }

    public function execute()
    {
        return Db::execute($this->statement, $this->params);
    }
}