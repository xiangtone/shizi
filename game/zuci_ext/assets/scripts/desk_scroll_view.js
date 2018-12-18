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
        word_on_desk_prefab: {
            type: cc.Prefab,
            default: null,
        },
        //界面绑定
        ske_anim:{
            type:sp.Skeleton,
            default:null,
        },
    },

    // LIFE-CYCLE CALLBACKS:

    onLoad () {
        //cc.zc.INFO.length
        //获取一下spine动画组件
        this.ske_anim = cc.find("Canvas/bg/spine").getComponent(sp.Skeleton);
        this.ske_anim.getComponent(cc.Animation).play("walk_on");
        
    },
    btn_click_handler(e, custom) {
        console.log("btn_click_handler called", e, custom);

        if(typeof(cc.zc.INFO[cc.zc.lesson]) == "undefined"){
            return;
        }
        
        if(custom == cc.zc.INFO[cc.zc.lesson].new_word){
            console.log("回答正确");
            cc.zc.audio_mgr.playSFX("win.mp3");
            //播放回答正确动画骨骼
            this.ske_anim.clearTracks();//清理指定管道的索引
            this.ske_anim.addAnimation(1,"win",true )//播放一次
            
            //下一个练习
            cc.zc.lesson = cc.zc.lesson +1

            //
            if (cc.zc.lesson < cc.zc.INFO.length  ){
                //延时2秒重入场景
                this.scheduleOnce(function(){
                    cc.director.loadScene("game_scene");
                },2);
            }else{
                console.log("你已经完成这次练习");
                this.ske_anim.clearTracks();
                this.ske_anim.addAnimation(1,"win",true )//播放一次
                this.scheduleOnce(function(){
                    this.ske_anim.addAnimation(1,"walk on",true)//循环播放
                },2);  
            }
        }else{
            console.log("回答错误");
            cc.zc.audio_mgr.playSFX("lose.mp3");
            //播放回答错误动画骨骼
            this.ske_anim.clearTracks();//清理指定管道的索引         
            this.ske_anim.addAnimation(0,"sidle",false )//播放一次
            this.ske_anim.addAnimation(0,"walk on",true)//循环播放
        }
        /*
        console.log("btn_click_handler called", e, custom,cc.zc.INFO[cc.zc.lesson]);

        //var tmp = cc.find("Canvas/bg/img_game_blackboard/blackboard_scroll/view/content");
        
        //console.log("a->>",tmp,a);
        //防止溢出操作
        if(typeof(cc.zc.INFO[cc.zc.lesson]) == "undefined"){
            
            return;
        }

        //
        if (custom == (cc.zc.INFO[cc.zc.lesson].correct - 1)) {
            console.log("回答正确");
            cc.zc.audio_mgr.playSFX("win.mp3");
            //播放回答正确动画骨骼
            this.ske_anim.clearTracks();//清理指定管道的索引
            this.ske_anim.addAnimation(1,"win",true )//播放一次
            
            //下一个练习
            cc.zc.lesson = cc.zc.lesson +1

            //
            if (cc.zc.lesson < cc.zc.INFO.length  ){
                //延时2秒重入场景
                this.scheduleOnce(function(){
                    cc.director.loadScene("game_scene");
                },2);
            }else{
                console.log("你已经完成这次练习");
                this.ske_anim.clearTracks();
                this.ske_anim.addAnimation(1,"win",true )//播放一次
                this.scheduleOnce(function(){
                    this.ske_anim.addAnimation(1,"walk on",true)//循环播放
                },2);  
            }
            
            
        }else{
            console.log("回答错误");
            cc.zc.audio_mgr.playSFX("lose.mp3");
            //播放回答错误动画骨骼
            this.ske_anim.clearTracks();//清理指定管道的索引         
            this.ske_anim.addAnimation(0,"sidle",false )//播放一次
            this.ske_anim.addAnimation(0,"walk on",true)//循环播放
        }
        */
    },
    start () {

        for(var i = 0;i < cc.zc.INFO[cc.zc.lesson].rand_word.length;i++){
            var opt_item = cc.instantiate(this.word_on_desk_prefab);
            //根据联网数据展示文字
            opt_item.getChildByName("label").getComponent(cc.Label).string = cc.zc.INFO[cc.zc.lesson].rand_word[i];
             //获取按钮组件
             var item_btn           = opt_item.getComponent(cc.Button);
             //点击事件设置
            var click_event         = new cc.Component.EventHandler();
            click_event.target      = this.node;
            click_event.component   = "desk_scroll_view";
            click_event.handler     = "btn_click_handler";
            //数据传递
            click_event.customEventData = cc.zc.INFO[cc.zc.lesson].rand_word[i];
            //添加click事件处理
            item_btn.clickEvents.push(click_event);
            this.scrollview.content.addChild(opt_item);
        }
        /*
        //处理分词
        var word = cc.zc.INFO[cc.zc.lesson].choose.split(",");
        //console.log(word);
        for (var i = 0; i < word.length; i++) {
            var opt_item = cc.instantiate(this.word_on_desk_prefab);
            //根据联网数据展示文字
            //this.word_on_desk_prefab.label.string = "A";
            opt_item.getChildByName("label").getComponent(cc.Label).string = word[i];
            //opt_item.getComponent(cc.Button).node.on(cc.Node.EventType.TOUCH_START, this.btn_click_handler, this);
            //获取按钮组件
            var item_btn = opt_item.getComponent(cc.Button);
            //点击事件设置
            var click_event = new cc.Component.EventHandler();
            click_event.target = this.node;
            click_event.component = "desk_scroll_view";
            click_event.handler = "btn_click_handler";
            //数据传递
            click_event.customEventData = i;
            //添加click事件处理
            item_btn.clickEvents.push(click_event);
            //console.log(opt_item.getComponent(cc.Button));
            this.scrollview.content.addChild(opt_item);
        }
        */

    },

    // update (dt) {},
});
