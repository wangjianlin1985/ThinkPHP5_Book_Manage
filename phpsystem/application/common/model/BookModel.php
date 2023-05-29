<?php
namespace app\common\model;
use think\Model;

class BookModel extends Model {
    /*关联表名*/
    protected $table  = 't_book';
    /*每页显示记录数目*/
    public $rows = 8;
    /*保存查询后总的页数*/
    public $totalPage;
    /*保存查询到的总记录数*/
    public $recordNumber;

    public function setRows($rows) {
        $this->rows = $rows;
    }

    //图书所在类别复合属性的获取: 多对一关系
    public function bookTypeObjF(){
        return $this->belongsTo('BookTypeModel','bookTypeObj');
    }

    /*添加图书记录*/
    public function addBook($book) {
        $this->insert($book);
    }

    /*更新图书记录*/
    public function updateBook($book) {
        $this->update($book);
    }

    /*删除多条图书信息*/
    public function deleteBooks($barcodes){
        $barcodeArray = explode(",",$barcodes);
        foreach ($barcodeArray as $barcode) {
            $this->barcode = $barcode;
            $this->delete();
        }
        return count($barcodeArray);
    }
    /*根据主键获取图书记录*/
    public function getBook($barcode) {
        $book = BookModel::where("barcode",$barcode)->find();
        return $book;
    }

    /*按照查询条件分页查询图书信息*/
    public function queryBook($barcode, $bookName, $bookTypeObj, $publishDate, $currentPage) {
        $startIndex = ($currentPage-1) * $this->rows;
        $where = null;
        if($barcode != "") $where['barcode'] = array('like','%'.$barcode.'%');
        if($bookName != "") $where['bookName'] = array('like','%'.$bookName.'%');
        if($bookTypeObj['bookTypeId'] != 0) $where['bookTypeObj'] = $bookTypeObj['bookTypeId'];
        if($publishDate != "") $where['publishDate'] = array('like','%'.$publishDate.'%');
        $bookRs = BookModel::where($where)->limit($startIndex,$this->rows)->select();
        /*计算总的页数和总的记录数*/
        $this->recordNumber = BookModel::where($where)->count();
        $mod = $this->recordNumber % $this->rows;
        $this->totalPage = (int)($this->recordNumber / $this->rows);
        if($mod != 0) $this->totalPage++;
        return $bookRs;
    }

    /*按照查询条件查询所有图书记录*/
  public function queryOutputBook( $barcode,  $bookName,  $bookTypeObj,  $publishDate) {
        $where = null;
        if($barcode != "") $where['barcode'] = array('like','%'.$barcode.'%');
        if($bookName != "") $where['bookName'] = array('like','%'.$bookName.'%');
        if($bookTypeObj['bookTypeId'] != 0) $where['bookTypeObj'] = $bookTypeObj['bookTypeId'];
        if($publishDate != "") $where['publishDate'] = array('like','%'.$publishDate.'%');
        $bookRs = BookModel::where($where)->select();
        return $bookRs;
    }

    /*查询所有图书记录*/
    public function queryAllBook(){
        $bookRs = BookModel::where(null)->select();
        return $bookRs;

    }

}
