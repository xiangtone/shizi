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
        
    },

    // LIFE-CYCLE CALLBACKS:
    
    onLoad () {

    },
    /**
     * 检查游戏是否完成
     */
    is_game_compelete(){
        //cc.log(this.cloud_up[0].node.getComponent('hit').is_collision);
        if (this.cloud_up[0].node.getComponent('hit').is_collision == true &&
            this.cloud_up[1].node.getComponent('hit').is_collision == true&&
            this.cloud_up[2].node.getComponent('hit').is_collision == true &&
            this.cloud_up[3].node.getComponent('hit').is_collision == true ){
            cc.log("游戏完成");
            //1.检查练习是否已经完全做完 否-每次递增一课 是-弹出成功动画弹框

            //2.检查句子是否正确 ritht--播放小汉哥成功动画 wrong--播放小汉哥失败动画 下面的云朵消失
            //
            

        }
    },
    /**
     * 显示云朵运动的文字
     */
    show_sentence() {
        for(var i = 0;i<4 ; i++){
            //
            cc.log(this.cloud_up[i].node.getChildByName("label").getComponent(cc.Label).string);
        }
    },
    //
    start () {
        this.show_sentence();//展示文字
        
        //用于监听子节点向上冒泡的触摸事件
        this.node.on(cc.Node.EventType.TOUCH_END, function (event) {
            cc.log("冒泡传递--->MOUSE_DOWN");
            this.is_game_compelete();
        }, this);
        this.node.on(cc.Node.EventType.TOUCH_CANCEL, function (event) {
            cc.log("冒泡传递--->TOUCH_CANCEL");
            this.is_game_compelete();
        }, this);
        /*
        var self = this;
        for (var i = 0;i<4;i++){

                this.cloud_up[i].node.on(cc.Node.EventType.MOUSE_DOWN, function (event) {
                    cc.log("MOUSE_DOWN", this.name);
                    this.pos_ = this.getPosition();

                }, this.cloud_up[i].node);
                //用于手指移动目标的跟随
                this.cloud_up[i].node.on(cc.Node.EventType.TOUCH_MOVE, function (event) {
                    cc.log("TOUCH_MOVE", this.name);
                    var delta = event.touch.getDelta();
                    this.x += delta.x;
                    this.y += delta.y;

                }, this.cloud_up[i].node);
                //当鼠标从按下状态松开
                this.cloud_up[i].node.on(cc.Node.EventType.MOUSE_UP, function (event) {
                    cc.log("当鼠标从按下状态松开", self.touchingNumber, this.name, this.getPosition());
                    this.setPosition(this.pos_);
                    
                }, this.cloud_up[i].node);
                //当手指在目标节点区域外离开屏幕时
                this.cloud_up[i].node.on(cc.Node.EventType.TOUCH_CANCEL, function (event) {
                    cc.log("当手指在目标节点区域外离开屏幕时", self.touchingNumber, this.name, this.getPosition());
                    this.setPosition(this.pos_);
                    //this.setPosition(cc.zc.pos_collision);
                }, this.cloud_up[i].node);
               this.cloud_pos[i].name = this.cloud_up[i].node.name;
               this.cloud_pos[i].position = this.cloud_up[i].node.getPosition();
        }
        cc.log(this.cloud_pos);
        */
        
    },

    // update (dt) {},
});
