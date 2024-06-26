<?php
/*
 * Le Melinaidre Frédéric <lemelinaidre@gmail.com>
 * Class IdGenerator v2
 * Copyright (c) 2024.
 * TODO completer les tests
 *
 * exemple d'utilisation
 * IdGenerator::getId('int', [
            'length'    => 12,
            'separator' => '-',
            'suffix'    => [
                'value'     => ['bidule', 'truc'],
                'separator' => '-'
                'length'    => 3
            ]
        ]);
 *
 *
 * IdGenerator::getId('int', [
            'length'    => 12,
            'separator' => '-',
            'suffix'    => [
                'value'     => 'bidule',
                'length'    => 3
            ]
        ]);
 */

namespace Core\Util;

class IdGenerator
{

    /**
     * @param string $type
     * @param        $options
     *
     * @return string
     */
    public static function getId(string $type = 'int', $options =[])
    {
        $instance = new self();

        return $instance->generate($type, $options);
    }

    /**
     * @param $type
     * @param $options
     *
     * @return string
     */
    private function generate($type, $options)
    {
        $id = [];

        $length = $options['length'] ?? 10;

        if(isset($options['prefix'])) $id[] = $this->setPrefixSuffix($options['prefix']);

        $id[] = $this->create($type, $length);

        if(isset($options['suffix'])) $id[] = $this->setPrefixSuffix($options['suffix']);

        $separator = $options['separator'] ?? '';

        return implode($separator, $id) ;
    }

    /**
     * @param $type
     * @param $length
     *
     * @return string
     */
    private function create($type, $length)
    {
        if($type === 'string') {
            $id = sha1(uniqid(microtime(), true).$_SERVER['REMOTE_ADDR']);
        } else {
            $microtime = microtime(true);
            $id = str_replace('.', '', $microtime);
        }

        return substr($id, 0, $length);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function setPrefixSuffix(array $params): string
    {

        $length = (isset($params['length'])) ? $params['length'] : 0;

        if(!is_array($params['value'])) {
            $return = $this->createPrefixSuffix($params['value'], $length);
        } else {
            foreach($params['value'] as $value) {
                $values[] = $this->createPrefixSuffix($value, $length);
            }
            $separator = $params['separator'] ?? '';
            $return = implode($separator, $values);
        }
        return $return;
    }

    /**
     * @param string $value
     * @param int    $length
     *
     * @return string
     */
    private function createPrefixSuffix(string $value, int $length): string
    {
        return ($length === 0) ? $value : substr($value, 0, $length);
    }

}