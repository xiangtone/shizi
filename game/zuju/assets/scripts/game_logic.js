// Learn cc.Class:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/class.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/class.html
// Learn Attribute:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/reference/attributes.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/reference/attributes.html
// Learn life-cycle callbacks:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/life-cycle-callbacks.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/life-cycle-callbacks.html
var Cloudpos = cc.Class({
    name: 'Cloudpos',
    properties: {
        name: '',
        position: cc.v2,

    }
});


cc.Class({
    extends: cc.Component,
   
    properties: {
       
        cloud_up: [cc.Sprite],
        cloud_pos: {
            default: [],
            type: Cloudpos
        },
        
    },

    // LIFE-CYCLE CALLBACKS:
    
    onLoad () {
        
        cc.director.getCollisionManager().enabled = true;
        cc.director.getCollisionManager().enabledDebugDraw = true;
        cc.director.getCollisionManager().enabledDrawBoundingBox = true;
        this.touchingNumber = 0;
        
        
        // this.pos = this.node.getPosition();
        //this.pos_collision = this.cloud_up[0].node.getPosition();
       
    },
    
    //
    start () {
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
        /*
        //////11
        //用于手指移动目标的跟随
        this.cloud_up[1].node.on(cc.Node.EventType.TOUCH_MOVE, function (event) {
            cc.log("TOUCH_MOVE", this.name);

            var delta = event.touch.getDelta();
            this.x += delta.x;
            this.y += delta.y;
        }, this.cloud_up[1].node);
        //当鼠标从按下状态松开
        this.cloud_up[1].node.on(cc.Node.EventType.MOUSE_UP, function (event) {
            cc.log("当鼠标从按下状态松开", self.touchingNumber, this.name,this.getPosition(), cc.zc.pos_collision);
            // if(self.touchingNumber  == 0){
            //     var movex = cc.moveTo(0.2, self.pos);
            //     var seqx  = cc.sequence(movex,cc.callFunc(self.back_move_end2,self));

            //     self.node.runAction(seqx);
            // }else{
            //     self.node.setPosition(self.pos_collision);
            // }
            if (typeof (cc.zc.pos_collision) != "undefined" ||
                cc.zc.pos_collision != null) {
                this.setPosition(cc.zc.pos_collision);
            }

        }, this.cloud_up[1].node);
        //当手指在目标节点区域外离开屏幕时
        this.cloud_up[1].node.on(cc.Node.EventType.TOUCH_CANCEL, function (event) {
            cc.log("当手指在目标节点区域外离开屏幕时", self.touchingNumber, this.name, this.getPosition(), cc.zc.pos_collision);
            // if(self.touchingNumber  == 0){
            //     var movex = cc.moveTo(0.2, self.pos);
            //     var seqx  = cc.sequence(movex,cc.callFunc(self.back_move_end2,self));
            //     self.node.runAction(seqx);
            // }else{
            //     self.node.setPosition(self.pos_collision);
            // }

            this.setPosition(cc.zc.pos_collision);
        }, this.cloud_up[1].node);
        */
    },

    // update (dt) {},
});
