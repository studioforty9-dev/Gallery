<?php
/**
 * Studioforty9 Gallery
 *
 * @category  Studioforty9
 * @package   Studioforty9_Gallery
 * @author    StudioForty9 <info@studioforty9.com>
 * @copyright 2015 StudioForty9 (http://www.studioforty9.com)
 * @license   https://github.com/studioforty9/gallery/blob/master/LICENCE BSD
 * @version   1.0.0
 * @link      https://github.com/studioforty9/gallery
 */

/**
 * Studioforty9_Gallery_Model_Relations
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage Model
 */
class Studioforty9_Gallery_Model_Relations
{
    /** @var string */
    protected $_table = 'studioforty9_gallery/gallery_media_album';

    /** @var string */
    protected $_entityField;

    /** @var string */
    protected $_entityValue;

    /** @var string */
    protected $_relationField;

    /** @var array */
    protected $_fields;

    /**
     * Initialize the model.
     *
     * @param string $entityField
     * @param string $entityValue
     * @param string $relationField
     * @param array  $fields
     */
    public function init($entityField, $entityValue, $relationField, $fields = array())
    {
        $this->_entityField = $entityField;
        $this->_entityValue = $entityValue;
        $this->_relationField = $relationField;
        $this->_fields = $fields;
        $this->_table = Mage::getSingleton('core/resource')->getTableName($this->_table);
    }

    /**
     * Synchronize the relationships.
     *
     * @param $entityIds
     * @return $this
     */
    public function sync($entityIds)
    {
        $old = $this->formatOld($this->getExistingRelations());

        $data = array(
            'old' => $old,
            'new' => $this->formatNew($old, $entityIds),
        );

        $sql = $this->getFormattedSql($data);

        foreach ($sql as $query) {
            $this->_getWriteConnection()->query($query);
        }

        return $this;
    }

    public function _commit($changes)
    {
        foreach ($changes as $type => $change) {
            foreach ($change as $id => $fields) {
                $this->_query($type, $id, $fields);
            }
        }
    }

    public function getChanges($entityIds)
    {
        $old = $this->formatOld($this->getExistingRelations());
        $new = $this->formatNew($old, $entityIds);
        $entities = array('old' => $old, 'new' => $new);
        $changes = array(
            'inserts' => array(), 'updates' => array(), 'deletes' => array()
        );

        foreach ($entities as $type => $data) {
            foreach ($data as $relationId => $relationFields) {
                $isOld = array_key_exists($relationId, $old);
                $isNew = array_key_exists($relationId, $new);

                if (!$isOld && $isNew) {
                    $changes['inserts'][$relationId] = $relationFields;
                }
                if ($isOld && $isNew) {
                    $changes['updates'][$relationId] = $relationFields;
                }
                if ($isOld && !$isNew) {
                    $changes['deletes'][$relationId] = null;
                }
            }
        }

        return $changes;
    }

    /**
     * Get existing rows.
     *
     * @return array
     */
    public function getExistingRelations()
    {
        $fields = array('media_id' , 'album_id');

        if (!empty($this->_fields)) {
            $fields = array_merge($fields, $this->_fields);
        }

        /** @var Varien_Db_Select $select */
        $sql = $this->_getReadConnection()->select()->distinct(true)
            ->from($this->_getTable(), $fields)
            ->where($this->_entityField.'=?', $this->_entityValue)
            ->__toString();

        return $this->_getReadConnection()->query($sql);
    }

    /**
     * Format existing data to convert sql.
     *
     * @param array $existing
     * @return array
     */
    public function formatOld($existing)
    {
        $data = array();
        foreach ($existing as $row) {
            foreach ($row as $key => $value) {
                if (!empty($this->_fields)) {
                    if ($key != $this->_entityField && $key != $this->_relationField) {
                        $data[$row[$this->_relationField]][$key] = $value;
                    }
                } else {
                    if ($key != $this->_entityField) {
                        $data[] = $row[$this->_relationField];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Format new data to convert sql.
     *
     * @param array $existing
     * @param array $entityIds
     * @return array
     */
    public function formatNew($existing, $entityIds)
    {
        $data = array();

        if (empty($entityIds)) {
            return $data;
        }

        foreach ($entityIds as $entityId => $entityFields) {
            if (array_key_exists($entityId, $existing) && is_array($entityFields)) {
                if (array_key_exists('position', $entityFields)) {
                    $data[$entityId]['position'] = $entityFields['position'];
                } else if (array_key_exists('grid_position', $entityFields)) {
                    $data[$entityId]['position'] = $entityFields['grid_position'];
                } else {
                    $fields = $existing[$entityId];
                    foreach ($fields as $key => $value) {
                        $data[$entityId][$key] = $value;
                    }
                }
            } else {
                foreach ($this->_fields as $field) {
                    $data[$entityId][$field] = null;
                }
            }
        }

        return $data;
    }

    /**
     * Get the database read connection.
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getReadConnection()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }

    /**
     * Get the database write connection.
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getWriteConnection()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    /**
     * Get the table.
     *
     * @return string
     */
    protected function _getTable()
    {
        return $this->_table;
    }

    /**
     * Get the formatted sql queries for syncing the relations.
     *
     * @param array $entities
     * @return array
     */
    public function getFormattedSql($entities)
    {
        $sql = array();
        foreach ($entities as $type => $data) {
            foreach ($data as $relationId => $relationFields) {
                $isOld = array_key_exists($relationId, $entities['old']);
                $isNew = array_key_exists($relationId, $entities['new']);

                if ($isOld && $isNew) {
                    $sql[$relationId] = $this->formatUpdate($relationId, $relationFields);
                }
                if ($isOld && !$isNew) {
                    $sql[$relationId] = $this->formatDelete($relationId);
                }
                if (!$isOld && $isNew) {
                    $sql[$relationId] = $this->formatInsert($relationId, $relationFields);
                }
            }
        }

        return $sql;
    }

    /**
     * Format an sql update query.
     *
     * @param string $relationId
     * @param array $relationFields
     * @return string
     */
    public function formatUpdate($relationId, $relationFields)
    {
        $fields = '';
        foreach ($relationFields as $key => $value) {
            $fields .= sprintf('%s=%s,', $key, is_null($value) ? 'NULL' : $value);
        }
        $fields = rtrim($fields, ',');

        return sprintf(
            'UPDATE %s SET %s WHERE `%s`=%s AND `%s`=%s',
            $this->_table,
            $fields,
            $this->_relationField,
            $relationId,
            $this->_entityField,
            $this->_entityValue
        );
    }

    /**
     * Format an sql delete query.
     *
     * @param int $relationId
     * @return string
     */
    public function formatDelete($relationId)
    {
        return sprintf(
            'DELETE FROM %s WHERE `%s`=%s AND `%s`=%s',
            $this->_table,
            $this->_relationField,
            $relationId,
            $this->_entityField,
            $this->_entityValue
        );
    }

    /**
     * Format an sql delete query.
     *
     * @param int   $relationId
     * @param array $relationFields
     *
     * @return string
     */
    public function formatInsert($relationId, $relationFields)
    {
        $fields = '';
        foreach ($relationFields as $key => $value) {
            $fields .= sprintf('%s=%s,', $key, is_null($value) ? 'NULL' : $value);
        }

        return sprintf(
            'INSERT INTO %s SET %s `%s`=%s, `%s`=%s',
            $this->_table,
            $fields,
            $this->_relationField,
            $relationId,
            $this->_entityField,
            $this->_entityValue
        );
    }
}
