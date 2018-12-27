// Learn cc.Class:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/class.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/class.html
// Learn Attribute:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/reference/attributes.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/reference/attributes.html
// Learn life-cycle callbacks:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/life-cycle-callbacks.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/life-cycle-callbacks.html



cc.Class({
    extends: cc.Component,
   
    properties: {

        cloud_up: [cc.Sprite],//上面的云朵
        cloud_down:[cc.Sprite],//下面的云朵
        xhg_ske:sp.Skeleton,
        popup:{
            type:cc.Node,
            default:null,
        },
    },

    // LIFE-CYCLE CALLBACKS:
    
    onLoad () {
        
    },
    /**
     * 把练习完成的数据上传到服务器
     */
    http_game_sucess(){
        var url = cc.zc.global.SUCCESS_URL+"&video_id="+cc.zc.http_args.video_id +"&user_id="+cc.zc.http_args.user_id+"&ex_type=2";
        cc.log("游戏成功URL=",url);
        //&video_id=9&user_id=16&ex_type=1
        cc.zc.http.getInstance().httpGets(url, function (err, data) {
            cc.log(err,data);
            if(err == false){
                //cc.log("联网出错,请检查网络");
            }else{
                cc.log("联网成功,data=",data);
            }
        });
    },
     /**
     * 循环播放 组词的声音
     *
     */
    play_word_voice(){
        //cc.log("声音url", cc.zc.INFO[cc.zc.lesson].voice_url, cc.zc.lesson, typeof (cc.zc.INFO[cc.zc.lesson].voice_url));
        this.unscheduleAllCallbacks(this); //停止组件的所有计时器
        //有音频Url就去播放声音
        if (cc.zc.INFO[cc.zc.lesson].voice_url != "") {
            //cc.log("play voice");
            cc.zc.audio_mgr.playNetSFX(cc.zc.INFO[cc.zc.lesson].voice_url);//先播一次
            
            this.schedule(function(){ //每隔5秒执行1次 
                if (cc.zc.INFO[cc.zc.lesson].voice_url != ""){
                    //播放音频
                    cc.zc.audio_mgr.playNetSFX(cc.zc.INFO[cc.zc.lesson].voice_url);
                } 
             },5);    
        }
    },
    /**
     * 小汉哥成功动画播放完毕事件处理
     *
     */
    on_xhg_ske_success_end_listener(){
        cc.log("小汉哥动画播放完毕  called-->>",this.xhg_ske);
        this.xhg_ske.node.active = false;  //小汉哥动画隐藏
        if (cc.zc.lesson < cc.zc.INFO.length  ){//判断是否完成练习
            this.scheduleOnce(function(){    cc.director.loadScene("game_scene");},1); //重来
        }else{
            cc.log("你已经完成这次练习");
            this.http_game_sucess();            //把练习完成的数据上传到服务器
            this.unscheduleAllCallbacks(this);  //停止该组件所有的定时器
            this.scheduleOnce(function(){this.popup.active = true;},1); //显示弹出框
        }
    },
    /**
     * 小汉哥失败动画播放完毕事件处理
     *
     */
    on_xhg_ske_fail_end_listener(){
        cc.log("小汉哥失败动画播放完毕  called-->>",this.xhg_ske);
        this.xhg_ske.node.active = false;  //小汉哥动画隐藏 
        this.scheduleOnce(function(){    cc.director.loadScene("game_scene");},1); //重来
    },
    /**
     * 云朵消失动画结束监听
     * @param {*} event 
     */
    on_anim_cloud_disp_end_listener(event){
        cc.log("xxxx-->>on_anim_cloud0_disp_end_listener called="+event.detail.name);

        for(var i = 0;i<4;i++){
            var clip_name = "cloud_disp_clip"+i;
            //cc.log("clip_name="+clip_name);
            if(clip_name == event.detail.name ){
                this.cloud_up[i].node.active =false;
                this.cloud_down[i].node.active =false;
                //cc.log("中了"+i);
            }
        }
    },
   
     /**
     * 下面的云朵消失
     */
    cloud_down_disp(){
        
        for(var i = 0;i<4;i++){
            
            this.cloud_up[i].node.getChildByName("anim_cloud_disp").active = true;
            this.cloud_up[i].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation).play("cloud_disp_clip"+i);
            this.cloud_up[i].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation).on('finished', this.on_anim_cloud_disp_end_listener, this);
        }
            
        // this.cloud_up[0].node.getChildByName("anim_cloud_disp").active = true;
        // this.cloud_up[0].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation).play("cloud_disp_clip0");
        // this.cloud_up[0].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation).on('finished', this.on_anim_cloud0_disp_end_listener, this);

        // this.cloud_up[1].node.getChildByName("anim_cloud_disp").active = true;
        // this.cloud_up[1].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation).play("cloud_disp_clip1");
        // this.cloud_up[1].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation).on('finished', this.on_anim_cloud1_disp_end_listener, this);
        
        
    },
    /**
     * 检查游戏是否完成
     */
    is_game_compelete(){
        //cc.log(this.cloud_up[0].node.getComponent('hit').is_collision);
        if (this.cloud_up[0].node.getComponent('hit').is_collision == true &&
            this.cloud_up[1].node.getComponent('hit').is_collision == true &&
            this.cloud_up[2].node.getComponent('hit').is_collision == true &&
            this.cloud_up[3].node.getComponent('hit').is_collision == true ){

            //根据碰撞顺序,获取字符串的连接  
            var answer_string = "";
            // for(var i = 0;i<cc.zc.INFO.answer.length;i++){
            //     //cc.log(this.node.getChildByName(cc.zc.INFO.answer[i]).getChildByName("label").getComponent(cc.Label).string);
            //     answer_string = answer_string + this.node.getChildByName(cc.zc.INFO.answer[i]).getChildByName("label").getComponent(cc.Label).string; 
            // }
            for(var k=0 ; k<4; k++){
                //cc.log(this.cloud_down[k].getComponent('cloud_down_extra').segment);
                answer_string = answer_string + this.cloud_down[k].getComponent('cloud_down_extra').segment;
            }
            cc.log("组句答案=",answer_string);
            //return;
            this.unRegisterEvent();//注销触摸事件的冒泡
            
            //检查练习是否已经完全做完 否-每次递增一课 是-弹出成功动画弹框
            if(cc.zc.INFO[cc.zc.lesson].sentence == answer_string){
                cc.log("回答正确",cc.zc.lesson);
                cc.zc.lesson = cc.zc.lesson +1;//下一个练习
                // ritht--播放小汉哥成功动画 
                this.xhg_ske.clearTracks();//清理指定管道的索引         
                this.xhg_ske.setAnimation(0,"chenggong",false)//播放一次
                cc.zc.audio_mgr.playSFX("win.mp3");   //播放失败的声音
                this.xhg_ske.getComponent(sp.Skeleton).setCompleteListener(this.on_xhg_ske_success_end_listener.bind(this));//监听小汉哥动画播放完毕事件
                
            }else{//回到错误
                cc.log("回答错误");
                cc.zc.audio_mgr.playSFX("fail.mp3");   //播放失败的声音
                //wrong--播放小汉哥失败动画 
                this.xhg_ske.clearTracks();//清理指定管道的索引         
                var e = this.xhg_ske.setAnimation(0,"shibai",false )//播放一次
                this.xhg_ske.getComponent(sp.Skeleton).setCompleteListener(this.on_xhg_ske_fail_end_listener.bind(this));//监听小汉哥动画播放完毕事件
                //this.xhg_ske.getComponent(sp.Skeleton).setTrackEndListener(e,this.on_xhg_ske_fail_end_listener.bind(this));
                this.cloud_down_disp(); //下面的云朵消失
               
                //cc.log(this.cloud_up[0].node.getChildByName("anim_cloud_disp").getComponent(cc.Animation));
                
             
            }
        }
    },
    /**
     * 显示云朵运动的文字
     */
    show_sentence() {

        for(var i = 0;i < 4 ; i++){
            //
            if(cc.zc.INFO[cc.zc.lesson].segment[i] == ""){
                this.cloud_up[i].node.setPosition(this.cloud_down[i].node.getPosition());
            }else{
                this.cloud_up[i].node.getChildByName("label").getComponent(cc.Label).string  = cc.zc.INFO[cc.zc.lesson].segment[i];
            }
            
            //cc.log(this.cloud_up[i].node.getChildByName("label").getComponent(cc.Label).string);
        }
    },
    
   
    //游戏开始
    start () {
        this.show_sentence();//展示文字
        this.xhg_ske.node.active = true;//展示小汉哥正常状态动画
        
      
        this.play_word_voice();//循环播放 句子的声音
        this.registerEvent();//用于监听子节点向上冒泡的触摸事件
        
        //cc.log(this.cloud_down[0].getComponent('cloud_down_extra').segment);
    },
    /**
     * 事件注册相关
     * @param {*} event 
     */
    touchCancel(event){
        //cc.log("冒泡传递--->TOUCH_CANCEL");
        this.is_game_compelete();
    },
    touchEnd(event) {
        //cc.log("冒泡传递--->TOUCH_END");
        this.is_game_compelete();
    },
    registerEvent() {
        
        //当手指在目标节点区域内离开屏幕时
        this.node.on(cc.Node.EventType.TOUCH_END, this.touchEnd, this);
        //当手指在目标节点区域外离开屏幕时
        this.node.on(cc.Node.EventType.TOUCH_CANCEL, this.touchCancel, this);
     },
     unRegisterEvent() {
         this.node.off(cc.Node.EventType.TOUCH_END, this.touchEnd, this);
         this.node.off(cc.Node.EventType.TOUCH_CANCEL, this.touchCancel, this);
     },
    

    // update (dt) {},
});
