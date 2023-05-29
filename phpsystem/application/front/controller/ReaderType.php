<?php
namespace app\front\controller;
use think\Request;
use think\Exception;
use app\common\model\ReaderTypeModel;

class ReaderType extends Base {
    protected $readerTypeModel;

    //控制层对象初始化：注入业务逻辑层对象等
    public function _initialize() {
        parent::_initialize();
        $this->request = Request::instance();
        $this->readerTypeModel = new ReaderTypeModel();
    }

    /*添加读者类型信息*/
    public function frontAdd(){
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $readerType = $this->getReaderTypeForm(true);
            try {
                $this->readerTypeModel->addReaderType($readerType);
                $message = "读者类型添加成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "读者类型添加失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            return $this->fetch('readerType/readerType_frontAdd');
        }
    }

    /*前台修改读者类型信息*/
    public function frontModify() {
        $this->assign("readerTypeId",input("readerTypeId"));
        return $this->fetch("readerType/readerType_frontModify");
    }

    /*前台按照查询条件分页查询读者类型信息*/
    public function frontlist() {
        if($this->request->param("currentPage") != null)
            $this->currentPage = $this->request->param("currentPage");
        $readerTypeRs = $this->readerTypeModel->queryReaderType($this->currentPage);
        $this->assign("readerTypeRs",$readerTypeRs);
        /*获取到总的页码数目*/
        $this->assign("totalPage",$this->readerTypeModel->totalPage);
        /*当前查询条件下总记录数*/
        $this->assign("recordNumber",$this->readerTypeModel->recordNumber);
        $this->assign("currentPage",$this->currentPage);
        $this->assign("rows",$this->readerTypeModel->rows);
        return $this->fetch('readerType/readerType_frontlist');
    }

    /*ajax方式查询读者类型信息*/
    public function listAll() {
        $readerTypeRs = $this->readerTypeModel->queryAllReaderType();
        echo json_encode($readerTypeRs);
    }
    /*前台查询根据主键查询一条读者类型信息*/
    public function frontshow() {
        $readerTypeId = input("readerTypeId");
        $readerType = $this->readerTypeModel->getReaderType($readerTypeId);
       $this->assign("readerType",$readerType);
        return $this->fetch("readerType/readerType_frontshow");
    }

    /*更新读者类型信息*/
    public function update() {
        $message = "";
        $success = false;
        if($this->request->isPost()) {
            $readerType = $this->getReaderTypeForm(false);
            try {
                $this->readerTypeModel->updateReaderType($readerType);
                $message = "读者类型更新成功!";
                $success = true;
                $this->writeJsonResponse($success, $message);
            } catch (Exception $ex) {
                $message = "读者类型更新失败!";
                $this->writeJsonResponse($success,$message);
            }
        } else {
            /*根据主键获取读者类型对象*/
            $readerTypeId = input("readerTypeId");
            $readerType = $this->readerTypeModel->getReaderType($readerTypeId);
            echo json_encode($readerType);
        }
    }

    /*删除多条读者类型记录*/
    public function deletes() {
        $message = "";
        $success = false;
        $readerTypeIds = input("readerTypeIds");
        try {
            $count = $this->readerTypeModel->deleteReaderTypes($readerTypeIds);
            $success = true;
            $message = $count."条记录删除成功";
            $this->writeJsonResponse($success, $message);
        } catch (Exception $ex) {
            $message = "有记录存在外键约束,删除失败";
            $this->writeJsonResponse($success, $message);
        }
    }

}

