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
        // foo: {
        //     // ATTRIBUTES:
        //     default: null,        // The default value will be used only when the component attaching
        //                           // to a node for the first time
        //     type: cc.SpriteFrame, // optional, default is typeof default
        //     serializable: true,   // optional, default is true
        // },
        // bar: {
        //     get () {
        //         return this._bar;
        //     },
        //     set (value) {
        //         this._bar = value;
        //     }
        // },
        scrollview: {
            type: cc.ScrollView,
            default: null,
        },
        anim:{
            type:cc.Animation,
            default:null,
        },
        new_word_prefab: {
            type: cc.Prefab,
            default: null,
        },
  
        popup:{
            type:cc.Node,
            default:null,
        },
        new_word_ask_idx:0,
        is_bz_anim_play:false,
        rand_word:[],
    },

    // LIFE-CYCLE CALLBACKS:
    
    onLoad () {
        //1--获取棒子动画组件
        this.anim_bz_beat = cc.find("Canvas/anim_bz_beat").getComponent(cc.Animation);
        //监听动画播放完成的事件处理
        this.anim_bz_beat.on('finished', this.on_anim_bz_beat_end_listener, this);

        //2--获取天兵下降动画组件
        this.anim_tb_fly = cc.find("Canvas/anim_tb_fly").getComponent(cc.Animation);
        //监听动画播放完成的事件处理
        this.anim_tb_fly.on('finished', this.on_anim_tb_fly_end_listener, this);

        //3--获取小汉哥的动画
        this.xhg_ske = cc.find("Canvas/xhg_skeleton");
        
        //4-处理生字 
        var temp = new Array();
        var j = 0;
        if(cc.zc.INFO[cc.zc.lesson].rand_word.length >4){ //生字大于4个的处理
            //先去掉关键字
            for(var i = 0 ;i < cc.zc.INFO[cc.zc.lesson].rand_word.length;i++){
                if(cc.zc.INFO[cc.zc.lesson].rand_word[i] != cc.zc.INFO[cc.zc.lesson].new_word){
                    temp[j] = cc.zc.INFO[cc.zc.lesson].rand_word[i];
                    j++;
                }
            }
            //随机获取一下关键字
            this.rand_word = this.getRandomArrayElements(temp,4);
            //获取随机下标
            var idx = Math.floor(4 * Math.random());
            //替换
            this.rand_word[idx] = cc.zc.INFO[cc.zc.lesson].new_word;
        }else{
            this.rand_word = cc.zc.INFO[cc.zc.lesson].rand_word;
        }
        
        
        /*
        //获取一下spine动画组件
        this.ske_tb_node = cc.find("Canvas/tb_skeleton");
        this.ske_tb_node.active=true;
        this.ske_tb_node.getComponent(sp.Skeleton).clearTracks();
        this.ske_tb_node.getComponent(sp.Skeleton).addAnimation(1,"changtai",true );
        */
        //this.ske_tb_anim.getComponent(cc.Animation).play("changtai");
        //this.ske_tb_anim.clearTracks();//清理指定管道的索引
        //this.ske_tb_anim.addAnimation(1,"changtai",true )//播放一次
    },
    /**
     * 小汉哥动画播放完毕事件处理
     *
     */
    on_anim_xhg_end_listener(){
        cc.log("on_anim_xhg_end_listener  called-->>");
        this.xhg_ske.active = false;  //小汉哥动画隐藏  
        this.anim_tb_fly.node.active=false;//设置天兵下降动画隐藏
        //展示生字和分词
        this.show_new_word_block();
        this.show_target_word_block();
        
        //循环播放 组词的声音
        this.play_word_voice();
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
     * 天兵动画播放完毕事件处理
     * 设置隐藏
     */
    on_anim_tb_fly_end_listener(){
        cc.log("on_anim_tb_fly_end_listener  called-->>");
        
        //播放小汉哥的skeleton动画
        this.xhg_ske.active = true;  //小汉哥可见
        this.xhg_ske.getComponent(sp.Skeleton).setCompleteListener(this.on_anim_xhg_end_listener.bind(this));
        
        
        
    },
    /**
     * 棒子敲打动画播放完毕事件处理
     * 设置隐藏
     */
    on_anim_bz_beat_end_listener(){
        cc.log("棒子动画播放结束",this.is_bz_anim_play);
        this.anim_bz_beat.node.active = false;//设置棒子敲打动画隐藏
        this.is_bz_anim_play = false;       //棒子动画播放结束标识
    },
    
    /**
     * 生字（备选字）显示
     *
     */
    show_new_word_block(){
        

        //处理生字显示,只是显示
        for(var i = 0;i < cc.zc.INFO[cc.zc.lesson].target_word.length;i++){
            //
            var opt_item = cc.instantiate(this.new_word_prefab);
            //修改名字,做一下区分方便以后操作
            opt_item.name = opt_item.name+i;
            cc.log("处理生字显示",opt_item);
            opt_item.getChildByName("label").getComponent(cc.Label).string = cc.zc.INFO[cc.zc.lesson].target_word.substr(i,1);
            // /cc.log(opt_item);
            //stringObject.substr(start,length) start 要抽取的子串的起始下标,length子串中的字符数。必须是数值
            if(cc.zc.INFO[cc.zc.lesson].target_word.substr(i,1) == cc.zc.INFO[cc.zc.lesson].new_word){
                opt_item.getChildByName("img_game_ask").active = true; //问号图片设置为可见
                opt_item.getChildByName("label").active  =false;        //文字显示设置为不可见
                this.new_word_ask_idx      = i; //保存生字显示的下标
            }
            this.scrollview.content.addChild(opt_item);
            //this.temp.content.addChild(opt_item);
        }
    },
    getRandomArrayElements(arr, count) {
        var shuffled = arr.slice(0),
            i = arr.length,
            min = i - count,
            temp, index;
        while (i-- > min) {
            index = Math.floor((i + 1) * Math.random());
            temp = shuffled[index];
            shuffled[index] = shuffled[i];
            shuffled[i] = temp;
        }
        return shuffled.slice(min);
    },

    /**
     * 天兵显示可以点击的字
     *
     */
    show_target_word_block(){
  
        
      
        
       
        cc.log("随机字符=",this.rand_word,cc.zc.INFO[cc.zc.lesson].rand_word);
        
        for(var i = 0;i < this.rand_word.length;i++){

            var idx = i+1;
            //cc.log("idx=",idx);
            if(i < 2){
                var path = "Canvas/bg/img_block_1"+"/tb_ske_"+idx;
                //cc.log(path);
            }else{
                var path = "Canvas/bg/img_block_2"+"/tb_ske_"+idx;
            }
            this.tb_ske = cc.find(path);
            cc.log(idx,this.tb_ske);
            
            this.tb_ske.active = true;  //天兵可见
            //设置按钮上的文字
            this.tb_ske.getChildByName("btn_word").getComponent(cc.Button).node.getChildByName("Label").getComponent(cc.Label).string = this.rand_word[i];
            //播放天兵的skeleton动画
            this.tb_ske.getComponent(sp.Skeleton).clearTrack(i);//清理指定管道的索引
            this.tb_ske.getComponent(sp.Skeleton).addAnimation(i,"changtai",true ); //播放一次 
            //cc.log("btn",this.tb.getChildByName("btn_word").getComponent(cc.Button).node.getChildByName("Label").getComponent(cc.Label)) ;
            
        }
        
        
    },
    /**
     * 播放棒子打天兵的动画
     *
     * @param {*} pos
     */
    play_bz_beat_anim(pos){
        cc.log("棒子动画播放开始",this.is_bz_anim_play);
        this.is_bz_anim_play = true;                                //棒子动画开始标识
        this.anim_bz_beat.node.active=true;                         //设置动画可见
        var node_pos = this.node.parent.convertToNodeSpaceAR(pos);  // 把世界坐标转到相对于它的父亲节点的坐标
        this.anim_bz_beat.node.setPosition(node_pos);               //设置节点坐标
        this.anim_bz_beat.play("bz_clip");                          //播放棒子敲打动画
    },
    
    /**
     * 处理点击备选字之后的操作
     *
     * @param {*} index
     */
    handle_target_word_click(index){
        var idx = parseInt(index)+1;
        if(idx < 3){
            var path = "Canvas/bg/img_block_1"+"/tb_ske_"+idx;
        }else{
            var path = "Canvas/bg/img_block_2"+"/tb_ske_"+idx;
        }
        var tb_ske      = cc.find(path);
        var tb_ske_comp = tb_ske.getComponent(sp.Skeleton);
        cc.log(idx,path,tb_ske);
        //tb.getChildByName("btn_word").getComponent(cc.Button).node.getChildByName("Label").getComponent(cc.Label).string = cc.zc.INFO[cc.zc.lesson].rand_word[i];
        tb_ske.getChildByName("btn_word").getComponent(cc.Button).interactable = false ;//设置字的按钮为不可用  
    },
    /**
     *点击被选自成功和失败的动画处理
     *
     * @param {*} index
     * @param {*} status
     */
    handle_target_word_check(index,status){
        cc.log("handle_target_word_check called");
        var idx = parseInt(index)+1;
        if(idx < 3){
            var path = "Canvas/bg/img_block_1"+"/tb_ske_"+idx;
        }else{
            var path = "Canvas/bg/img_block_2"+"/tb_ske_"+idx;
        }
        var tb_ske      = cc.find(path);
        var tb_ske_comp = tb_ske.getComponent(sp.Skeleton);

        if(status =='wrong'){
             //播放天兵被敲打动画
            tb_ske_comp.setAnimation(index,"cuo",false ); //播放一次 
            this.scheduleOnce(function(){
                tb_ske_comp.setAnimation(index,"changtai",true ); //循环播放
            },1); 
        }else if(status =='right'){
                //问好去掉
                //根据名字获取节点 scrollitem9名字取得节点
                var word_node = this.scrollview.content.getChildByName("new_word_prefab"+this.new_word_ask_idx);
                cc.log("获取--",index,word_node);
                word_node.getChildByName("label").active  =true;
                word_node.getChildByName("img_game_ask").active = false
                // this.scrollview.content.getChildByName("scrollitem9").getChildByName("nike_name").getComponent(cc.Label).string ="换字";
                // opt_item.getChildByName("img_game_ask").active = true;
                //播放天兵被敲打动画
                tb_ske_comp.setAnimation(index,"dui",false ); //播放一次 
             
        }
       
    },
    /**
     *
     * 天兵旁边的按钮点击事件
     * @param {*} e
     * @param {*} custom
     * @returns
     */
    on_target_word_btn_click(e, custom){
       

        var click_pos = e.getLocation() 
       
        //所有课程搞完
        if(typeof(cc.zc.INFO[cc.zc.lesson]) == "undefined"){
            return;
        }
        cc.log("棒子动画播放状态",this.is_bz_anim_play);
        if(this.is_bz_anim_play == true){
            return;
        }
        this.handle_target_word_click(custom);  //点击了哪个字,设置哪个字灰色
        this.play_bz_beat_anim(click_pos);      //播放棒子敲打动画,每次打都播放
        cc.zc.audio_mgr.playSFX("hit02.mp3");   //播放敲打的声音

        if(this.rand_word[custom] == cc.zc.INFO[cc.zc.lesson].new_word){
            
            this.handle_target_word_check(custom,'right');
            
            //延时播放胜利声音
            this.scheduleOnce(function(){ cc.zc.audio_mgr.playSFX("win.mp3");},1);
            //下一个练习
            cc.zc.lesson = cc.zc.lesson +1
            cc.log("回答正确",cc.zc.lesson);
            //
            if (cc.zc.lesson < cc.zc.INFO.length  ){
                
                
                //延时2秒重入场景
                this.scheduleOnce(function(){
                    cc.director.loadScene("game_scene");
                },2);
            }else{
                cc.log("你已经完成这次练习");
                this.unscheduleAllCallbacks(this);  //停止该组件所有的定时器
                this.http_game_sucess();            //把练习完成的数据上传到服务器

                this.scheduleOnce(function(){       //显示弹出框
                    this.popup.active = true;
                    
                },1);
            }
        }else{
            cc.log("回答错误");
            this.handle_target_word_check(custom,'wrong');
            this.scheduleOnce(function(){
                cc.zc.audio_mgr.playSFX("fail07.mp3");
            },1);
            
            // //播放回答错误动画骨骼
            // this.ske_anim.clearTracks();//清理指定管道的索引         
            // this.ske_anim.addAnimation(0,"sidle",false )//播放一次
            // this.ske_anim.addAnimation(0,"walk on",true)//循环播放
        }
    },
    /**
     * 把练习完成的数据上传到服务器
     */
    http_game_sucess(){
        var url = cc.zc.global.SUCCESS_URL+"&video_id="+cc.zc.http_args.video_id +"&user_id="+cc.zc.http_args.user_id+"&ex_type=1";
        cc.log("游戏成功URL=",url);
        //&video_id=9&user_id=16&ex_type=1
        cc.zc.http.getInstance().httpGets(url, function (err, data) {
            cc.log(err,data);
            if(err == false){
                cc.log("联网出错,请检查网络");
            }else{
                cc.log("联网成功,data=",data);
            }
        });
    },
    //
    start () {

        //播放动画
        this.anim_tb_fly.node.active=true;                         //设置天兵动画可见
        this.anim_bz_beat.play("tb_fly_clip");                          //播放动画
        cc.zc.audio_mgr.playSFX("fightbegin.mp3");
    },

    // update (dt) {},
});
