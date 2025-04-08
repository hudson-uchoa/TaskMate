<?php

namespace App\Traits;

trait ArrayConvertible
{
    abstract protected function fields(): array;

    public function toArray(array $removeFields = []): array
    {
        $fields = $this->fields();
        $result = [];

        foreach ($fields as $field) {
            if(in_array(trim($field), array_map('trim', $removeFields), true)){
                continue;
            }

            $value = $this->$field ?? null;

            $result[$field] = $value;
            
        }

        return $result;
    }
    
}