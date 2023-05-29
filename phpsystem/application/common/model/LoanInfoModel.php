<?php
namespace app\common\model;
use think\Model;

class LoanInfoModel extends Model {
    /*关联表名*/
    protected $table  = 't_loanInfo';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //图书对象复合属性的获取: 多对一关系
    public function bookF(){
        return $this->belongsTo('BookModel','book');
    }

    //读者对象复合属性的获取: 多对一关系
    public function readerF(){
        return $this->belongsTo('ReaderModel','reader');
    }

    /*添加借阅信息记录*/
    public function addLoanInfo($loanInfo) {
        $this->insert($loanInfo);
    }

    /*更新借阅信息记录*/
    public function updateLoanInfo($loanInfo) {
        $this->update($loanInfo);
    }

    /*删除多条借阅信息信息*/
    public function deleteLoanInfos($loadIds){
        $loadIdArray = explode(",",$loadIds);
        foreach ($loadIdArray as $loadId) {
            $this->loadId = $loadId;
            $this->delete();
        }
        return count($loadIdArray);
    }
    /*根据主键获取借阅信息记录*/
    public function getLoanInfo($loadId) {
        $loanInfo = LoanInfoModel::where("loadId",$loadId)->find();
        return $loanInfo;
    }

    /*按照查询条件分页查询借阅信息信息*/
    public function queryLoanInfo($book, $reader, $borrowDate, $returnDate, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($book['barcode'] != 0) $where['book'] = $book['barcode'];
        if($reader['readerNo'] != 0) $where['reader'] = $reader['readerNo'];
        if($borrowDate != "") $where['borrowDate'] = array('like','%'.$borrowDate.'%');
        if($returnDate != "") $where['returnDate'] = array('like','%'.$returnDate.'%');
        $loanInfoRs = LoanInfoModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = LoanInfoModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $loanInfoRs;
    }

    /*按照查询条件查询所有借阅信息记录*/
  public function queryOutputLoanInfo( $book,  $reader,  $borrowDate,  $returnDate) {
        $where = null;
        if($book['barcode'] != 0) $where['book'] = $book['barcode'];
        if($reader['readerNo'] != 0) $where['reader'] = $reader['readerNo'];
        if($borrowDate != "") $where['borrowDate'] = array('like','%'.$borrowDate.'%');
        if($returnDate != "") $where['returnDate'] = array('like','%'.$returnDate.'%');
        $loanInfoRs = LoanInfoModel::where($where)->select();
        return $loanInfoRs;
    }

    /*查询所有借阅信息记录*/
    public function queryAllLoanInfo(){
        $loanInfoRs = LoanInfoModel::where(null)->select();
        return $loanInfoRs;

    }

}
