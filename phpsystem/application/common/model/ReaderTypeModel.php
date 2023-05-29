<?php
namespace app\common\model;
use think\Model;

class ReaderTypeModel extends Model {
    /*关联表名*/
    protected $table  = 't_readerType';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    /*添加读者类型记录*/
    public function addReaderType($readerType) {
        $this->insert($readerType);
    }

    /*更新读者类型记录*/
    public function updateReaderType($readerType) {
        $this->update($readerType);
    }

    /*删除多条读者类型信息*/
    public function deleteReaderTypes($readerTypeIds){
        $readerTypeIdArray = explode(",",$readerTypeIds);
        foreach ($readerTypeIdArray as $readerTypeId) {
            $this->readerTypeId = $readerTypeId;
            $this->delete();
        }
        return count($readerTypeIdArray);
    }
    /*根据主键获取读者类型记录*/
    public function getReaderType($readerTypeId) {
        $readerType = ReaderTypeModel::where("readerTypeId",$readerTypeId)->find();
        return $readerType;
    }

    /*按照查询条件分页查询读者类型信息*/
    public function queryReaderType($currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        $readerTypeRs = ReaderTypeModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = ReaderTypeModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $readerTypeRs;
    }

    /*按照查询条件查询所有读者类型记录*/
  public function queryOutputReaderType() {
        $where = null;
        $readerTypeRs = ReaderTypeModel::where($where)->select();
        return $readerTypeRs;
    }

    /*查询所有读者类型记录*/
    public function queryAllReaderType(){
        $readerTypeRs = ReaderTypeModel::where(null)->select();
        return $readerTypeRs;

    }

}
