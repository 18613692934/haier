<?php
namespace Common\Model;
use Think\Model;
/**
 * 基础model
 */
class BaseModel extends Model{
 
    /**
     * 添加数据
     * @param    array    $data    数据 
     * @return   integer           新增数据的id 
     */
    public function addData($data){
        $id=$this->add($data);
        return $id;
    }
     
    /**
     * 修改数据
     * @param    array    $map    where语句数组形式 
     * @param    array    $data   修改的数据 
     * @return    boolean         操作是否成功
     */
    public function editData($map,$data){
        $result=$this->where($map)->save($data);
        return $result;
    }
     
    /**
     * 删除数据（物理删除）
     * @param    array    $map    where语句数组形式
     * @return   boolean          操作是否成功
     */
    public function deleteData($map){
        $result=$this->where($map)->delete();
        return $result;
    }
    /**
     * 假删除
     * @param array $map     需要删除的数据的id值，以数组的形式
     * @param array $status   需要修改的is_delete字段的值，以数组的形式
     * @return boolean        操作是否成功
     */
    function falseDelete($map,$status){
        $result=$this->where($map)->save($status);
        return $result;
    }

    
    /**
     * 根据条件查询全部数据
     * @param array $map     where语句数组形式
     * @return   array       查询出来的数据
     */
    public function selectData($map){
        $result = $this->where($map) -> select();
        return $result;
    }
}