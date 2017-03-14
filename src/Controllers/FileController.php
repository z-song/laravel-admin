<?php

namespace Encore\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Qiniu\Auth;

/**
 * Created by PhpStorm.
 * User: never615
 * Date: 13/03/2017
 * Time: 4:33 PM
 */
class FileController extends Controller
{

    private $bucket;
    private $auth;

    /**
     * QiniuController constructor.
     */
    public function __construct()
    {
        $this->bucket = config("filesystems.disks.qiniu.bucket");
        $this->auth = new Auth(config("filesystems.disks.qiniu.access_key"),
            config("filesystems.disks.qiniu.secret_key"));
    }


    /**
     * 获取七牛上传图片的token
     *
     * @return mixed
     */
    public function getUploadToken()
    {
        $token = $this->getUploadTokenInter();

        return response()->json([
            'uptoken' => $token,
        ]);
    }


    /**
     * 获取七牛图片上传token
     *
     * @return string
     */
    private function getUploadTokenInter()
    {
        $returnBody = [
            'key' => "$(key)",
        ];

        $policy = [
            'returnBody' => json_encode($returnBody, true),
            'saveKey'    => "editor/$(etag)",
        ];

        // 生成上传Token
        $token = $this->auth->uploadToken($this->bucket, null, 3600, $policy);

        return $token;
    }


    /**
     * 处理文件上传 给wangEditor使用
     *
     * @param Request $request
     * @return string
     */
    public function upload(Request $request)
    {

        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            $file = $request->file('file');

            //扩展名
            $extension = $file->extension();

            //允许的文件后缀
            $fileTypes = array('jpeg', 'png');

            //检查类型是否支持
            if (! in_array($extension, $fileTypes)) {
                return response("error|".trans("errors.upload_image_not_support"));
            }

            //检查文件大小是否超过php.ini的设置
            if ($file->getMaxFilesize() < $file->getClientSize()) {
                return response("error|".trans("errors.upload_size_too_large"));
            }


            $result = Storage::disk("admin")->putFile("editor", $file);


            if ($result == false) {
                return response("error|".trans("errors.upload_error"));
            }

            //直接返回对应文件的路径
            return response(Storage::disk("admin")->url($result));
        } else {
            return response("error|".trans("errors.upload_error").'请求错误');
        }
    }


}
