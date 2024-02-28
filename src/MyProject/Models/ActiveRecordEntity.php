<?php

namespace MyProject\Models;
use MyProject\Services\Db;

abstract class ActiveRecordEntity implements \JsonSerializable
{
    

      protected $id;

      public static function getById(int $id): ?self
      {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `'. static::getTableName(). '` WHERE id=:id;',
            [':id'=>$id],
            static::class
        );
        return $entities ? $entities[0]:null;
      }
       
      public function getId(): int
      {
          return $this->id;
      }
  
      public function __set(string $name, $value)
      {
          $camelCaseName = $this->underscoreToCamelCase($name);
          $this->$camelCaseName = $value;
      }
  
      private function underscoreToCamelCase(string $source): string
      {
          return lcfirst(str_replace('_', '', ucwords($source, '_')));
      }
  
    
      public static function findAll(): array
      {
          $db = Db::getInstance();
          return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
      }
  
      abstract protected static function getTableName(): string;

      public function save(): void
      {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if($this->id !== null){
            $this->update($mappedProperties);
        }
       else {
        $this->insert($mappedProperties);
      }
    }

    private function update (array $mappedProperties): void
    {
        $columns2params = [];
        $columns2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value){
            $param = ':param'. $index; // :param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[$param] = $value; // [:param1 => value1] 
            $index++;
        }
        $sql = 'UPDATE '. static::getTableName() . ' SET '. implode(', ', $columns2params). ' WHERE id = '. $this->id;
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }    

    private function insert (array $mappedProperties): void
    {
        $filteredProperties = array_filter($mappedProperties);

        $columns = [];
        $params = [];
        $params2values = [];
        $index = 1;
        foreach ($filteredProperties as $column=>$value){
            $params [] = ':param' . $index; //:params
            $columns [] = $column; // column
            $params2values [':param' . $index] = $value; //[:param => value]
            $index++;
        }

        $sql = 'INSERT INTO ' . static::getTableName() . '(' . implode(', ', $columns) . ') VALUES (' . implode(', ', $params) . ')';

        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
        }

        private function refresh (): void
        {
            $objectFromDb = static::getById($this->id);
            $reflector = new \ReflectionObject($objectFromDb);
            $properties = $reflector->getProperties();
    
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $propertyName = $property->getName();
                $this->$propertyName = $property->getValue($objectFromDb);
            }
        }

        public function delete (): void 
        {
            $db = Db::getInstance();
            $db->query(
                'DELETE FROM `' . static::getTableName() . '` WHERE id = :id',
                [':id'=> $this->id]
            );
            $this->id = null;

        }

      private function mapPropertiesToDbFormat(): array
      {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }
        return $mappedProperties;
      }
      private function camelCaseToUnderscore(string $source): string
      {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
      }


      public static function findOneByColumn(string $columnName, $value): ?self
      {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` =:value LIMIT 1;',
            [':value' => $value],
            static::class
        );

        if ($result === []){
            return null;
        }
        return $result[0];
      }

    public function jsonSerialize()
    {
        return $this->mapPropertiesToDbFormat();
    }

    // public static function getPagesCount(int $itemsPerPage): int
    // {
    //     $db = Db::getInstance();
    //     $result = $db->query('SELECT COUNT(*) AS cnt FROM ' . static::getTableName() . ';');
    //     return ceil($result[0]->cnt / $itemsPerPage);
    // }

    public static function getPage(int $pageNum, int $itemsPerPage): array
    {
        $db = Db::getInstance();
        return $db->query(
            sprintf(
                'SELECT * FROM `%s` ORDER BY id DESC LIMIT %d OFFSET %d;',
                static::getTableName(),
                $itemsPerPage,
                ($pageNum - 1) * $itemsPerPage
            ),
            [],
            static::class
        );
    }

    }

 

?>