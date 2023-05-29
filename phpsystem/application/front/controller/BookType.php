<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\BookTypeModel;

class BookType extends Base {
    protected $bookTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->bookTypeModel = new BookTypeModel();
    }

    /*添加图书类型信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $bookType = $this->getBookTypeForm(true);
            try {
                $this->bookTypeModel->addBookType($bookType);
                $message = "图书类型添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书类型添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('bookType/bookType_frontAdd');
        }
    }

    /*前台修改图书类型信息*/
    public function frontModify() {
        $this->assign("bookTypeId",input("bookTypeId"));
        return $this->fetch("bookType/bookType_frontModify");
    }

    /*前台按照查询条件分页查询图书类型信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $bookTypeRs = $this->bookTypeModel->queryBookType($this->currentPage);
        $this->assign("bookTypeRs",$bookTypeRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->bookTypeModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->bookTypeModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->bookTypeModel->rows);
        return $this->fetch('bookType/bookType_frontlist');
    }

    /*ajax方式查询图书类型信息*/
    public function listAll() {
        $bookTypeRs = $this->bookTypeModel->queryAllBookType();
        echo json_encode($bookTypeRs);
    }
    /*前台查询根据主键查询一条图书类型信息*/
    public function frontshow() {
        $bookTypeId = input("bookTypeId");
        $bookType = $this->bookTypeModel->getBookType($bookTypeId);
       $this->assign("bookType",$bookType);
        return $this->fetch("bookType/bookType_frontshow");
    }

    /*更新图书类型信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $bookType = $this->getBookTypeForm(false);
            try {
                $this->bookTypeModel->updateBookType($bookType);
                $message = "图书类型更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "图书类型更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取图书类型对象*/
            $bookTypeId = input("bookTypeId");
            $bookType = $this->bookTypeModel->getBookType($bookTypeId);
            echo json_encode($bookType);
        }
    }

    /*删除多条图书类型记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $bookTypeIds = input("bookTypeIds");
        try {
            $count = $this->bookTypeModel->deleteBookTypes($bookTypeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

