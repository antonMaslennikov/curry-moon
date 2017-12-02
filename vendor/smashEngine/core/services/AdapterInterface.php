<?php
namespace smashEngine\core\services;

use smashEngine\core\models\NSModel;

interface AdapterInterface
{

    /**
     * @param int $id
     *
     */
    public function getNode($id);

    /**
     * @param int $id
     * @return bool
     */
    public function deleteNode($id);

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function setData($id, array $data);

    /**
     * @param int $parentId
     * @param NSModel $child
     * @return bool
     */
    public function addChild($parentId, NSModel $child);

    /**
     * @param int $nodeId
     * @return []
     */
    public function getChildren($id);

    /**
     * @param int $nodeId
     * @return []
     */
    public function getAllChildren($id);


    /**
     * @param int $id
     * @param int $parent_id
     * @return bool
     */
    public function moveNode($id, $parent_id);


	public function getTree();
}
