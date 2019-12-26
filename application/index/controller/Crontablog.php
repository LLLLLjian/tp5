<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;

class Crontablog extends Common
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request            
     * @return \think\Response
     */
    public function save(Request $request)
    {
        var_dump($request->post());exit;
    }

    /**
     * 显示指定的资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function read($id)
    {
        echo $id;exit;
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param int $id            
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request            
     * @param int $id            
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        echo $id . "update";
        exit;
    }

    /**
     * 删除指定资源
     *
     * @param int $id            
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function list()
    {
        $where = array();
        $limit = input('get.limit');
        $skip = (input('get.page', 1) - 1) * $limit;

        $visit_time = input('get.create_time');
        if (!empty($visit_time)) {
            $tempTimeS = strtotime($visit_time);
            $tempTimeE = $tempTimeS + 86399;
            $where[] = ["create_time", ">=", "{$tempTimeS}"];
            $where[] = ["create_time", "<=", "{$tempTimeE}"];
        }

        $res = Db::name("crontab_logs")
            ->where($where)
            ->order(['id' => 'desc'])
            ->limit($skip, $limit)
            ->select();
        $countNum = Db::name("crontab_logs")
            ->where($where)
            ->count();
        $arr = array(
            'code' => 0,
            'msg' => '',
            'count' => $countNum,
            'data' => $res
        );

        echo json_encode($arr);
    }

    public function run()
    {
        /**
         * 格式化当前时间戳并转成 分 时 日 月 周 格式
         * 
         * i  有前导零的分钟数 00 到 59>
         * G  小时，24 小时格式，没有前导零 0 到 23
         * j  月份中的第几天，没有前导零 1 到 31
         * n  数字表示的月份，没有前导零 1 到 12
         * w  星期中的第几天，数字表示 0（表示星期天）到 6（表示星期六）
         */
        $now = explode(' ', date('i G j n w', time()));
        $raw = $this->parseCron(array('hello/index' => '* * * * *'));
        foreach ($raw as $command => $cron) {
            // 上面已经列出了所有的情况，所以当前时间循环时如果有一项不符合则不能向下执行
            foreach ($now as $k => $piece) {
                if (!in_array($piece, $raw[$command][$k])) {
                    continue 2;
                }
            }
            // 下面是调用系统函数执行shell命令
            $this->runCommandBackground($command);
        }
    }

    /**
     * 解析需要执行的命令
     * @param $cronJobs
     * @return array
     */
    public function parseCron($cronJobs)
    {
        // 解析后的数组
        $raw = [];
        foreach ($cronJobs as $command => $cron) {
            // $command -> hello/index  $cron -> */5 * * * *
            // 将命令用空格分割成数组
            $cronArr = explode(' ', $cron, 5); // ['*/5', '*', '*', '*', '*']
            // 针对每一个位置进行解析
            $dimensions = array(
                array(0, 59), //Minutes
                array(0, 23), //Hours
                array(1, 31), //Days
                array(1, 12), //Months
                array(0, 6),  //Weekdays
            );
            foreach ($cronArr as $key => $item) {
                // 标记是哪种命令格式，通过使用的crontab命令可以分为两大类
                // 1.每几分钟或每小时这样的 */10 * * * *
                // 2.几点几分这样的 10,20,30-50 * * * *
                list($repeat, $every) = explode('/', $item, 2) + [false, 1];
                if ($repeat === '*') {
                    $raw[$command][$key] = range($dimensions[$key][0], $dimensions[$key][1]);
                } else {
                    // 处理逗号拼接的命令
                    $tmpRaw = explode(',', $item);
                    foreach ($tmpRaw as $tmp) {
                        // 处理10-20这样范围的命令
                        $tmp = explode('-', $tmp, 2);
                        if (count($tmp) == 2) {
                            $raw[$command][$key] = array_merge($raw[$command][$key], range($tmp[0], $tmp[1]));
                        } else {
                            $raw[$command][$key][] = $tmp[0];
                        }
                    }
                }
                // 判断*/10 这种类型的
                if ($every > 1) {
                    foreach ($raw[$command][$key] as $k => $v) {
                        if ($v % $every != 0) {
                            unset($raw[$command][$key][$k]);
                        }
                    }
                }
            }
        }
        return $raw;
    }

    /**
     * 以守护进程模式执行命令
     * @param $command
     */
    public function runCommandBackground($command)
    {
        echo $command;exit;
    }
}

