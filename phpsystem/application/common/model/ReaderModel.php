<?php
namespace app\common\model;
use think\Model;

class ReaderModel extends Model {
    /*关联表名*/
    protected $table  = 't_reader';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //读者类型复合属性的获取: 多对一关系
    public function readerTypeObjF(){
        return $this->belongsTo('ReaderTypeModel','readerTypeObj');
    }

    /*添加读者记录*/
    public function addReader($reader) {
        $this->insert($reader);
    }

    /*更新读者记录*/
    public function updateReader($reader) {
        $this->update($reader);
    }

    /*删除多条读者信息*/
    public function deleteReaders($readerNos){
        $readerNoArray = explode(",",$readerNos);
        foreach ($readerNoArray as $readerNo) {
            $this->readerNo = $readerNo;
            $this->delete();
        }
        return count($readerNoArray);
    }
    /*根据主键获取读者记录*/
    public function getReader($readerNo) {
        $reader = ReaderModel::where("readerNo",$readerNo)->find();
        return $reader;
    }

    /*按照查询条件分页查询读者信息*/
    public function queryReader($readerNo, $readerTypeObj, $readerName, $birthday, $telephone, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($readerNo != "") $where['readerNo'] = array('like','%'.$readerNo.'%');
        if($readerTypeObj['readerTypeId'] != 0) $where['readerTypeObj'] = $readerTypeObj['readerTypeId'];
        if($readerName != "") $where['readerName'] = array('like','%'.$readerName.'%');
        if($birthday != "") $where['birthday'] = array('like','%'.$birthday.'%');
        if($telephone != "") $where['telephone'] = array('like','%'.$telephone.'%');
        $readerRs = ReaderModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = ReaderModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $readerRs;
    }

    /*按照查询条件查询所有读者记录*/
  public function queryOutputReader( $readerNo,  $readerTypeObj,  $readerName,  $birthday,  $telephone) {
        $where = null;
        if($readerNo != "") $where['readerNo'] = array('like','%'.$readerNo.'%');
        if($readerTypeObj['readerTypeId'] != 0) $where['readerTypeObj'] = $readerTypeObj['readerTypeId'];
        if($readerName != "") $where['readerName'] = array('like','%'.$readerName.'%');
        if($birthday != "") $where['birthday'] = array('like','%'.$birthday.'%');
        if($telephone != "") $where['telephone'] = array('like','%'.$telephone.'%');
        $readerRs = ReaderModel::where($where)->select();
        return $readerRs;
    }

    /*查询所有读者记录*/
    public function queryAllReader(){
        $readerRs = ReaderModel::where(null)->select();
        return $readerRs;

    }

}
