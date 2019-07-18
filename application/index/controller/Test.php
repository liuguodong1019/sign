<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/16
 * Time: 16:18
 */
namespace app\index\controller;
use think\Controller;
use think\facade\Cache;

class Test extends Controller
{
    /**
     * @return mixed
     * 学生端签到页面
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @return mixed
     * 教师端开启签到页面
     */
    public function demo()
    {
        return $this->fetch();
    }

    /**
     * 将签到的学生加入缓存
     */
    public function post()
    {
        if ($_POST) {
            $img = self::curlInfo();
            $_POST['head_pic'] = $img;
            $info = json_encode($_POST);
            Cache::set('user_info',$info);
        }
    }

    /**
     * 缓存取出用户
     */
    public function getUser ()
    {
        //取出并清除当前缓存
        $info = Cache::pull('user_info');
        //服务端事件标准规定（将MIME类型设为text/event-stream）
        header('Content-Type: text/event-stream');
        //告诉Web服务器关闭Web缓存
        header('Cache-Control: no-cache');
        echo "data:{$info}\n\n";
        //立即发送数据（而不是先缓冲起来，等到PHP代码执行完毕再发送）
        flush();
    }

    /**
     * 清除缓存
     */
    public function clearCache ()
    {
        Cache::clear();
    }

    /**
     * @return mixed
     * 获取用户随机头像
     */
    protected function curlInfo ()
    {
        $url = 'https://api.66mz8.com/api/rand.pic.php?type=%E4%B8%AA%E6%80%A7&return=json';
        $curl = curl_init();//初始化CURL句柄
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl,CURLOPT_HTTPGET,true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $info = json_decode($output,true);
        $img = $info['imgurl'];
        return $img;
    }
}