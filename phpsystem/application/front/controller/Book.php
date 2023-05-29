<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\BookTypeModel;
use app\common\model\BookModel;

class Book extends Base {
    protected $bookTypeModel;
    protected $bookModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->bookTypeModel = new BookTypeModel();
        $this->bookModel = new BookModel();
    }

    /*添加图书信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $book = $this->getBookForm(true);
            $this->uploadPhoto($book,"bookPhoto","bookPhotoFile"); //处理图书图片上传
            $this->uploadFile($book,"bookFile","bookFileFile"); //处理图书文件上传
            try {
                $this->bookModel->addBook($book);
                $message = "图书添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('book/book_frontAdd');
        }
    }

    /*前台修改图书信息*/
    public function frontModify() {
        $this->assign("barcode",input("barcode"));
        return $this->fetch("book/book_frontModify");
    }

    /*前台按照查询条件分页查询图书信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $barcode = input("barcode")==null?"":input("barcode");
        $bookName = input("bookName")==null?"":input("bookName");
        $bookTypeObj["bookTypeId"] = input("bookTypeObj_bookTypeId")==null?0:input("bookTypeObj_bookTypeId");
        $publishDate = input("publishDate")==null?"":input("publishDate");
        $bookRs = $this->bookModel->queryBook($barcode, $bookName, $bookTypeObj, $publishDate, $this->currentPage);
        $this->assign("bookRs",$bookRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->bookModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->bookModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->bookModel->rows);
        $this->assign("barcode",$barcode);
        $this->assign("bookName",$bookName);
        $this->assign("bookTypeObj",$bookTypeObj);
        $this->assign("bookTypeList",$this->bookTypeModel->queryAllBookType());
        $this->assign("publishDate",$publishDate);
        return $this->fetch('book/book_frontlist');
    }

    /*ajax方式查询图书信息*/
    public function listAll() {
        $bookRs = $this->bookModel->queryAllBook();
        echo json_encode($bookRs);
    }
    /*前台查询根据主键查询一条图书信息*/
    public function frontshow() {
        $barcode = input("barcode");
        $book = $this->bookModel->getBook($barcode);
       $this->assign("book",$book);
        return $this->fetch("book/book_frontshow");
    }

    /*更新图书信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $book = $this->getBookForm(false);
            $this->uploadPhoto($book,"bookPhoto","bookPhotoFile"); //处理图书图片上传
            $this->uploadFile($book,"bookFile","bookFileFile"); //处理图书文件上传
            try {
                $this->bookModel->updateBook($book);
                $message = "图书更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取图书对象*/
            $barcode = input("barcode");
            $book = $this->bookModel->getBook($barcode);
            echo json_encode($book);
        }
    }

    /*删除多条图书记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $barcodes = input("barcodes");
        try {
            $count = $this->bookModel->deleteBooks($barcodes);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

