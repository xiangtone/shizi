// Learn cc.Class:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/class.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/class.html
// Learn Attribute:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/reference/attributes.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/reference/attributes.html
// Learn life-cycle callbacks:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/life-cycle-callbacks.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/life-cycle-callbacks.html

/*
http://www.dushujielong.com/ll/shizi/game/zuci/build/web-mobile/?video_id=1&user_id=2
http://127.0.0.1/ll/shizi/game/zuci/build/web-mobile/?video_id=1&user_id=2
http://localhost:7456/?video_id=1&user_id=2
*/
cc.Class({
    extends: cc.Component,

    properties: {

        label_tips: cc.Label,
        btn_enter:cc.Button,
        _state_str: '',
        _progress: 0.0,
        _splash: null,
        _is_loading: false,
    },

    // LIFE-CYCLE CALLBACKS:

    onLoad() {
        
        this.init_mgr();
    },


    start() {
        
    },

    update(dt) {
        if (this._state_str.length == 0) {
            return;
        }
        this.label_tips.string = this._state_str + ' ';
        if (this._is_loading) {
            this.label_tips.string += Math.floor(this._progress * 100) + "%";
        }
    },
    
    /**
     * 初始化加载本地、服务器资源
     */
    init_mgr() {
        cc.zc = {};
        //关闭cc.log输出信息-->>>>>
        //cc._initDebugSetting(cc.DebugMode.NONE);

        //加载全局配置
        cc.zc.global = require('global');
        cc.log("global=",cc.zc.global);
        //全局变量
        cc.zc.INFO = null;
        
        //加载http模块
        cc.zc.http = require("http_utils");
        
        //加载音效管理
        var audio_mgr = require("audio_mgr");
        cc.zc.audio_mgr = new audio_mgr();
        cc.zc.audio_mgr.init();
        //播放声音测试
        //cc.zc.audio_mgr.playBGM("bg.mp3");
        //加载本地游戏资源
        this.start_perloading();
        
        //获取webview传过来的参数 ?video_id=1&user_id=2
        cc.zc.http_args = this.urlParse();
        cc.log("传入游戏的HTTP参数--->",cc.zc.http_args);
        //cc.zc.http_args.video_id = 9;//lltest用的id     
        
         
    },
    /**
     * 解析获取http get 传过来的参数值
     */
    urlParse:function(){
        var params = {};
        if(window.location == null){
            return params;
        }
        var name,value; 
        var str=window.location.href; //取得整个地址栏
        var num=str.indexOf("?") 
        str=str.substr(num+1); //取得所有参数   stringvar.substr(start [, length ]
        
        var arr=str.split("&"); //各个参数放到数组里
        for(var i=0;i < arr.length;i++){ 
            num=arr[i].indexOf("="); 
            if(num>0){ 
                name=arr[i].substring(0,num);
                value=arr[i].substr(num+1);
                params[name]=value;
            } 
        }
        return params;
    },
    
    /**
     * 预加载网络数据
     */
    start_http_get(url){

        var self = this;
        this._state_str = "正在连接网络";
        
        cc.zc.http.getInstance().httpGets(url, function (err, data) {
            cc.log(err,data);
            
            if(err == false){
                
                self._state_str = "联网出错,请检查网络!";
                //cc.log("联网出错,请检查网络");
            }else{
                self._state_str = "联网成功";
                var jsonD = JSON.parse(data);
                cc.zc.INFO = jsonD;
                cc.log(cc.zc.INFO);
                cc.zc.sentence = new Array();
                var arr = [];
                //数据为空-->>提示错误
                if(cc.zc.INFO.length == 0){
                    self._state_str = "这个章节没有数据,请联系管理员";
                    //游戏资源为空,进入按钮设置不可用
                    self.btn_enter.interactable = false;
                    self.btn_enter.node.active = true;
                    return;
                }else{//有游戏数据-->整理游戏数据
                     //设置课程
                    cc.zc.lesson = 0;
                    cc.zc.total_lesson = cc.zc.INFO.length;                   
                    // for(var i =0 ; i<cc.zc.INFO.length;i++){
                    //     arr[0] = cc.zc.INFO[i].segment1;
                    //     arr[1] = cc.zc.INFO[i].segment2;
                    //     arr[2] = cc.zc.INFO[i].segment3;
                    //     arr[3] = cc.zc.INFO[i].segment4;
                        
                    //     //cc.log(arr);
                    //     var temp = {
                    //         sentence:cc.zc.INFO[i].segment1+cc.zc.INFO[i].segment2+cc.zc.INFO[i].segment3+cc.zc.INFO[i].segment4,
                    //         rand_segments:arr.concat(),
                    //         voice_url:cc.zc.INFO[i].voice_url
                    //     }
                    //     // var temp = {};
                    //     // temp.sentence = cc.zc.INFO[i].segment1+cc.zc.INFO[i].segment2+cc.zc.INFO[i].segment3+cc.zc.INFO[i].segment4;
                    //     // temp.rand_segments = arr.concat();
                    //     // temp.voice_url = cc.zc.INFO[i].voice_url;
                    //     cc.zc.sentence[i] =temp;
                    // }
                    // cc.log(cc.zc.sentence);
                }
               
                
                self.onload_complete();
            }
            
        });
    },
    /**
     * 预加载资源文件
     */
    start_perloading() {
        this._state_str = "正在加载资源，请稍候";
        this._is_loading = true;
        var self = this;

        //加载资源,系统会默认加resource,"/" 表示resource下的所有目录的资源加载
        cc.loader.loadResDir("/",
            function (completedCount, totalCount, item) {
                //cc.log("totalCount:" + totalCount + ",completedCount:" + completedCount);
                if (self._is_loading) {
                    self._progress = completedCount / totalCount;
                }
            },
            function (err, assets, item) {//资源加载完成
                cc.log("onComplete-->>", err, assets);
                
                self._is_loading = false;
                
                cc.zc.audio_mgr.playBGM("background.mp3");//播放背景音乐
                //加载网络数据 
                var url = cc.zc.global.URL+"&video_id="+cc.zc.http_args.video_id;
                cc.log("获取数据url=",url);
                self.start_http_get(url);
                
            });
    
       
    },
    /**
     * 点击进入游戏按钮响应事件
     */
    on_btn_enter_game_click(){
        cc.director.loadScene("game_scene");
        cc.loader.onComplete = null;
    },
    /**
     * 预加载资源完成--进入游戏场景
     */
    onload_complete() {
        this._is_loading = false;
        this._state_str = "加载资源完成";
        this.btn_enter.interactable = true;
        this.btn_enter.node.active = true;
        
        
        //
        
    },
});
